<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PayHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// разбираем запрос на оплату
	public function parse($data)
	{
		$result = false;

		// продление подписки
		if(isset($data['hash']))
			$result = $this->parseByHash($data['hash']);
		// новая подписка
		elseif(isset($data['course']) && isset($data['group']) && isset($data['type']))
			$result = $this->parseGroup($data['course'], $data['group'], $data['type'], ($data['period'] ?? ''));

		return $result;
	}

	// производим оплату
	public function pay($data)
	{
		// создаем транзакцию оплаты
		$tx = [
			'user' => $data['user'],
			'type' => TransactionsModel::TYPE_IN,
			'amount' => $data['price'],
			'description' => 'PaySystem',
			'data' => json_encode($data)
		];

		if(($id = $this->TransactionsHelper->add($tx)) === false)
			throw new Exception('ошибка создания транзакции', 1);
		
		$tx_item = $this->TransactionsModel->getByID($id);
		// если транзакция уже прошла проводим 
		if($tx_item['status'] === TransactionsModel::STATUS_SUCCESS)
			return $this->subscr($data);

		return $tx_item['hash'];
	}

	// создаем / обновляем подписку
	public function subscr($item = [])
	{
		switch(($item['pay'] ?? null))
		{
			// оплата курса
			case 'course':
				// обновление подписки
				if($item['period'] === 'renew')
				{
					$params = [
						'ts_end' => $item['ts_end'],
						'amount' =>  $item['price']
					];

					if($this->SubscriptionModel->renew($item['object']['id'], $params) === false)
						throw new Exception('ошибка обновления подписки', 1);
				}
				// новая подписка
				else
				{
					$params = [
						'user' => $item['user'],
						'type' => $item['type'],
						'course' => $item['object']['course'],
						'group' => $item['object']['group'],
						'period' => $item['period'],
					];

					if($this->SubscriptionHelper->group($params) === false)
						throw new Exception($this->SubscriptionHelper->getLastError(), 1);
				}

				$tx = [
					'user' => $item['user'],
					'type' => TransactionsModel::TYPE_OUT,
					'amount' => $item['price'],
					'description' => $item['name'],
					'data' => json_encode($item)
				];

				if(($id = $this->TransactionsHelper->add($tx)) === false)
					throw new Exception('ошибка создания транзакции', 1);

				return true;
			break;
			default:
				// empty
			break;
		}

		return false;
	}

	// оплата для существующей подписки
	private function parseByHash($hash)
	{
		// подписка
		if(($item = $this->SubscriptionModel->getByHash($hash)) === false)
			throw new Exception('неверные параметры', 1);
		$item['data'] = json_decode($item['data'], true);

		$item['ts_start_timestamp'] = strtotime($item['ts_start']);
		$item['ts_end_timestamp'] = strtotime($item['ts_end']);

		// группа
		if(($group = $this->GroupsModel->getById($item['target'])) === false)
			throw new Exception('ошибка обработки данных', 1);

		$group['ts_timestamp'] = strtotime($group['ts']);
		$group['ts_end_timestamp'] = strtotime($group['ts_end']);

		$pay = $this->getPayObject();
		$pay['name'] = $item['description'];
		$pay['type'] = $item['type'];
		$pay['object']['id'] = $item['id'];
		$pay['object']['course'] = $group['course_id'];
		$pay['object']['group'] = $group['id'];
		$pay['period'] = 'renew'; // продление подписки

		$price = (float) ($item['data']['price'] ?? 0); // стоимость за месяц
		$item['amount'] = (float) $item['amount']; // осталось оплатить

		// если остаточная сумма больше 0 - продление на месяц / несколько месяцев
		if((float) $item['amount'] > 0)
		{
			$pay['price'] = 0;

			$today = new DateTime();
			$date = new DateTime($item['ts_end']);
			$date->setTime(0, 0, 0);
			// рассчитываем сумму и дату окончания подписки
			while($date->getTimestamp() < $today->getTimestamp())
			{
				$date->modify('+4 weeks');
				if($pay['price'] < $item['amount'])
					$pay['price'] += $price;
				else
					$pay['price'] = $item['amount'];
			}
			if($date->getTimestamp() > $group['ts_end_timestamp'])
				$date = new DateTime($group['ts_end']);

			$pay['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);
			$pay['name'] = $item['description'].' '.date(DATE_FORMAT_SHORT, $item['ts_end_timestamp']).' - '.$date->format(DATE_FORMAT_SHORT);

			// если дата окончания меньше текущей даты то требуется еще продление на год
			if($date->getTimestamp() < time())
			{
				$date = new DateTime();
				$date->modify('next year');
				$date->setTime(0, 0, 0);
				$pay['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);
				$pay['name'] = $item['description'].' '.date(DATE_FORMAT_SHORT).' - '.$date->format(DATE_FORMAT_SHORT);
				$pay['price'] += $this->OptionsModel->getPriceRenewYear();
			}
			
		}
		// если закончился период подписки - продление на год
		elseif($item['ts_end_timestamp'] < time())
		{
			$date = new DateTime();
			$date->modify('next year');
			$date->setTime(0, 0, 0);
			$pay['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);
			$pay['name'] .= ' '.date(DATE_FORMAT_SHORT).' - '.$date->format(DATE_FORMAT_SHORT);
			$pay['price'] = $this->OptionsModel->getPriceRenewYear();
		}
		// хз что это но это оплатить нельзя
		else
			$pay = false;

		return $pay;
	}

	// оплата для новой подписки на курс
	private function parseGroup($course, $group, $type, $period)
	{
		// если группа курса
		if(isset($course) && isset($group) && isset($type))
		{
			if(($course_item = $this->CoursesModel->getByCode($course)) === false)
				throw new Exception('неверный код курса', 1);
			
			if(($group_item = $this->GroupsModel->getByCode($group)) === false)
				throw new Exception('неверный код группы', 1);

			if(!array_key_exists($type, $this->SubscriptionModel::TYPES))
				throw new Exception('неверный тип подписки', 1);

			if(!in_array($period, $this->SubscriptionModel::PERIOD_SUBSCR))
				throw new Exception('неверный период подписки', 1);

			$pay = $this->getPayObject();
			$pay['name'] = $course_item['name'];
			$pay['price'] = (float) $course_item['price'][$type][$period];
			$pay['quantity'] = 1;
			$pay['type'] = $type;
			$pay['object'] = [
				'course' => $course_item['id'],
				'group' => $group_item['id']
			];
			$pay['period'] = $period;

			return $pay;
		}

		return false;
	}

	// объект оплаты
	private function getPayObject()
	{
		return [
			'pay' => 'course', // что оплачиваем
			'name' => null, // описание оплаты
			'ts_end' => 0, // дата окончания подписки
			'price' => 0, // сумма
			'quantity' => 1,
			'type' => null, // тип подписки
			'object' => [ // описание объекта оплаты
				'id' => 0,
				'course' => 0,
				'group' => 0,
			],
			'period' => null // период оплаты
		];
	}
}