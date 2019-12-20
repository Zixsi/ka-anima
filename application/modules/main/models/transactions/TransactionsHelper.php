<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsHelper extends APP_Model
{
	public function add($data)
	{
		if(!isset($data['user']) || (int) ($data['user'] ?? 0) === 0)
			throw new Exception('не указан пользователь', 1);

		if(!isset($data['type']) || !in_array($data['type'], TransactionsModel::TYPE))
			throw new Exception('неверный тип', 1);

		if(!array_key_exists('amount', $data))
			throw new Exception('неуказана сумма', 1);

		if(!isset($data['description']) || empty($data['description']))
			throw new Exception('пустое описание', 1);

		$data['amount'] = (float) $data['amount'];
		$data['status'] = TransactionsModel::STATUS_PENDING;
		$data['data'] = ($data['data'] ?? '');

		$data['hash'] = md5($data['user'].$data['type'].$data['data'].time());

		return $this->TransactionsModel->add($data);
	}

	// список транзакций пользователя
	public function listByUser(int $user)
	{
		$res = $this->TransactionsModel->list(['user' => $user], ['ts' => 'desc']);
		$this->prepare($res);

		return $res;
	}

	// подготовить список
	public function prepare(&$data)
	{
		foreach($data as &$val)
		{
			$val['ts'] = strtotime($val['ts']);
			$val['ts_f'] = date(DATE_FORMAT_FULL, $val['ts']);
			$val['amount_f'] = number_format($val['amount'], 2, '.', ' ');
			$sign = ($val['type'] === $this->TransactionsModel::TYPE_OUT && $val['amount'] > 0)?'-':'';
			$val['amount_f'] = $sign.$val['amount_f'];
			$val['status_info'] = $this->statusInfo($val['status']);
		}
	}

	// обработка данных транзакции
	public function processingData($data)
	{
		if(!is_array($data))
			$data = json_decode($data, true);

		if(empty($data) || !is_array($data))
			return false;

		switch(($data['object']['type'] ?? ''))
		{
			case PayData::OBJ_TYPE_COURSE:
				return  $this->SubscriptionHelper->processingCourse($data);
			break;
			case  PayData::OBJ_TYPE_SUBSCR:
				return  $this->SubscriptionHelper->processingSubscription($data);
			break;
			case  PayData::OBJ_TYPE_WORKSHOP:
				return  $this->SubscriptionHelper->processingWorkshop($data);
			break;
			default:
				// empty 
			break;
		}

		return false;
	}

	public function statusInfo($status)
	{
		$info = [
			TransactionsModel::STATUS_SUCCESS => ['name' => 'Успешно', 'class' => 'success'],
			TransactionsModel::STATUS_PENDING => ['name' => 'В процессе', 'class' => 'warning'],
			TransactionsModel::STATUS_ERROR => ['name' => 'Ошибка', 'class' => 'danger'],
		];

		return $info[$status] ?? null;
	}

	public function getStat($period)
	{
		$result = [];
		$to = new DateTime('now');
		$to->setTime(23, 59, 59);
		$from = clone $to;

		switch($period)
		{
			case 'year':
				$from->modify('-1 year');
				break;
			case 'month':
			default:
				$from->modify('-1 month');
				break;
		}

		$from->setTime(0, 0, 0);

		$date_from = $from->format(DATE_FORMAT_DB_FULL);
		$date_to = $to->format(DATE_FORMAT_DB_FULL);

		switch($period)
		{
			case 'year':
				$result = $this->TransactionsModel->getStatByMonths($date_from, $date_to);
				break;
			case 'month':
			default:
				$result = $this->TransactionsModel->getStatByDays($date_from, $date_to);
				break;
		}

		return $result;
	}

	public function getCourseStatByMonths()
	{
		$result = [];
		$to = new DateTime();
		$from = clone $to;
		$from->modify('-1 year');
		$from->setDate($from->format('Y'), $from->format('n'), 1);

		$initMonth = [];
		$fromIterator = clone $from;
		for($i = 0; $i < 13; $i++)
		{
			$initMonth[$fromIterator->format(DATE_FORMAT_DB_SHORT)] = 0;
			$fromIterator->modify('+1 month');
		}

		$items = $this->TransactionsModel->getCourseStatByMonths($from->format(DATE_FORMAT_DB_FULL), $to->format(DATE_FORMAT_DB_FULL));
		if(count($items))
		{
			foreach($items as $item)
			{
				$key = $item['course_id'];
				if(array_key_exists($key, $result) === false)
				{
					$result[$key] = [
						'name' => $item['name'],
						'values' => $initMonth
					];
				}

				$result[$key]['values'][$item['ts']] = $item['value'];
			}
		}

		return $result;
	}
}