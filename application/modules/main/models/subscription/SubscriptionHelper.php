<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// TODO поправить цену
	// подготовка подписки на группу
	public function prepareGroup($data)
	{
		if((int) $data['user'] === 0)
			throw new Exception('неуказан пользователь', 1);

		if(empty(($data['type'] ?? '')) || !array_key_exists($data['type'], SubscriptionModel::TYPES))
			throw new Exception('неверное значение параметра тип подписки', 1);

		$group_id = ($data['group'] ?? 0);
		$course_id = ($data['course'] ?? 0);

		if(($course_item = $this->CoursesModel->getByID($course_id)) === false)
			throw new Exception('курс не найден', 1);

		// если неверный тип подписки 
		if((int) $course_item['only_standart'] === 1 && $data['type'] !== 'standart')
			throw new Exception('неверное значение параметра тип подписки', 1);

		// если VIP
		if($data['type'] == 'vip')
		{
			// проверяем есть ли подходящая группа
			if($res = $this->GroupsModel->getNearVip($course_id))
				$group_id = $res['id'];
			else
			{
				// создаем отдельную группу
				$date = new DateTime('now');
				if(intval($date->format('N')) !== 1)
					$date->modify('next monday');
				if(($group_id = $this->GroupsHelper->addSimple($course_id, 'vip',  $date)) === false)
					throw new Exception('ошибка создания группы', 1);
			}
		}

		if(($item = $this->GroupsModel->getByIdDetail($group_id)) === false)
			throw new Exception('группа не найдена', 1);

		// if(($_GET['debug'] ?? false))
		// {
		// 	debug($item); 
		// 	debug(json_decode($item['price'], true)); 
		// 	debug($data);
		// 	die();
		// }

		$item['price'] = json_decode($item['price'], true);

		if(!array_key_exists($data['type'], $item['price']) && !array_key_exists($data['period'], $item['price'][$data['type']]))
			throw new Exception('неверные параметры', 1);

		$price = (float) $item['price'][$data['type']][$data['period']];

		// проверяем подписал юзер на эту группу или нет
		if($this->SubscriptionModel->сheck($data['user'], $item['group_id']))
			throw new Exception('уже подписан', 1);

		////////////////////////////////////////////////////////////////////////

		// если бесплатный курс, то подписка полностью
		if($price === 0)
			$data['period'] = 'full';

		$ts_end = $item['ts_end'];
		$subscr_type = 0; // полная подписка
		$amount = $price; // остаток для оплаты

		// если оплата за месяц
		if($data['period'] == 'month')
		{
			$subscr_type = 1;
			$ts_curr = new DateTime($item['ts']);
			if(intval($ts_curr->format('N')) !== 1)
				$ts_curr->modify('next monday');
			$ts_month = clone $ts_curr;
			$ts_month->modify('+4 weeks'); // 4 недели 

			$ts_end_obj = new DateTime($ts_end);
			$diff = $ts_end_obj->diff($ts_month);
			$diff_total = $ts_end_obj->diff($ts_curr);

			// рассчитываем остаток оплаты 
			// $amount = (ceil($diff_total->days / 28) - 1) * $price;
			$amount = (floor($diff_total->days / 28) - 1) * $price;
			
			// Если разница между датой окончания и месяцем оплаты меньше или равно 1 недели 
			// то устанавливаем дату окончания курса
			if($ts_month < $ts_end_obj && (int) $diff->days > 7)
			{
				$ts_end = $ts_month->format('Y-m-d 00:00:00');
			}
		}

		$result = [
			'user' => $data['user'],
			'type' => $data['type'],
			'target' => $item['group_id'],
			'target_type' => 'course',
			'description' => $item['name'],
			'ts_start' => $item['ts'],
			'ts_end' => $ts_end,
			'subscr_type' => $subscr_type,
			'amount' => (float) $amount,
			'data' => json_encode(['price' => $price])
		];

		return $result;
	}

	// подготовка обновления подписки
	public function prepareSubscr($item)
	{
		$result = [
			'ts_start' => $item['ts_start'],
			'ts_end' => null,
			'items' => [],
			'amount' => 0,
			'params' => []
		];

		$item['data'] = json_decode($item['data'], true);

		$item['ts_start_timestamp'] = strtotime($item['ts_start']);
		$item['ts_end_timestamp'] = strtotime($item['ts_end']);

		// группа
		if(($group = $this->GroupsModel->getById($item['target'])) === false)
			throw new Exception('ошибка обработки данных', 1);

		$group['ts_timestamp'] = strtotime($group['ts']);
		$group['ts_end_timestamp'] = strtotime($group['ts_end']);

		$price = (float) ($item['data']['price'] ?? 0); // стоимость за месяц
		$item['amount'] = (float) $item['amount']; // осталось оплатить

		// если остаточная сумма больше 0 - продление на месяц / несколько месяцев
		if((float) $item['amount'] > 0)
		{
			$price_item = 0;

			$today = new DateTime();
			$date = new DateTime($item['ts_end']);
			$date->setTime(0, 0, 0);
			// рассчитываем сумму и дату окончания подписки
			while($date->getTimestamp() < $today->getTimestamp())
			{
				$date->modify('+4 weeks');
				if($price_item < $item['amount'])
					$price_item += $price;
				else
					$price_item = $item['amount'];
			}
			if($date->getTimestamp() > $group['ts_end_timestamp'])
				$date = new DateTime($group['ts_end']);

			$result['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);

			$result['items'][] = [
				'description' => $item['description'].' '.date(DATE_FORMAT_SHORT, $item['ts_end_timestamp']).' - '.$date->format(DATE_FORMAT_SHORT).' - возобновление подписки',
				'price' => $price_item
			];

			$result['amount'] = ($item['amount'] - $price_item);

			// если дата окончания меньше текущей даты то требуется еще продление на год
			if($date->getTimestamp() < time())
			{
				$date = new DateTime();
				$date->modify('next year');
				$date->setTime(0, 0, 0);
				$result['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);

				$result['items'][] = [
					'description' => $item['description'].' '.date(DATE_FORMAT_SHORT).' - '.$date->format(DATE_FORMAT_SHORT).' - доступ к лекциям на год',
					'price' => $this->OptionsModel->getPriceRenewYear()
				];
			}
			
		}
		// если закончился период подписки - продление на год
		elseif($item['ts_end_timestamp'] < time())
		{
			$date = new DateTime();
			$date->modify('next year');
			$date->setTime(0, 0, 0);
			$result['ts_end'] = $date->format(DATE_FORMAT_DB_FULL);

			$result['items'][] = [
				'description' => $item['description'].' '.date(DATE_FORMAT_SHORT).' - '.$date->format(DATE_FORMAT_SHORT).' - доступ к лекциям на год',
				'price' => $this->OptionsModel->getPriceRenewYear()
			];
		}
		// хз что это но это оплатить нельзя
		else
			throw new Exception('непредвиденное исключение', 1);

		return $result;
	}

	// обработка данных транзакции для курса
	public function processingCourse($data)
	{
		// если новый добавляем
		$params = [
			'user' => $data['user'],
			'type' => $data['type'],
			'target' => $data['object']['id'],
			'target_type' => 'course',
			'description' => $data['name'],
			'ts_start' => $data['ts_start'],
			'ts_end' => $data['ts_end'],
			'subscr_type' => $data['params']['subscr_type'],
			'amount' => $data['params']['amount'],
			'data' => json_encode(['price' => $data['params']['price']])
		];

		return ($this->SubscriptionModel->add($params))?true:false;
	}

	// 
	public function processingSubscription($data)
	{
		$params = [
			'ts_end' => $data['ts_end'],
			'amount' => $data['params']['amount'],
		];

		return ($this->SubscriptionModel->update($data['object']['id'], $params))?true:false;
	}
}