<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PayHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// разбираем входные данные
	public function parse($data)
	{
		$result = null;

		switch(($data['action'] ?? ''))
		{
			case 'new': // новая подписка
				$result = $this->parseNew($data);
			break;
			case 'renewal': // обновления подписки
				$result = $this->parseRenewal($data);
			break;
			default:
				throw new Exception('неопределенное действие', 1);
			break;
		}

		return $result;
	}

	// производим оплату
	public function pay($data)
	{
		// создаем транзакцию оплаты
		// $tx = [
		// 	'user' => $data['user'],
		// 	'type' => TransactionsModel::TYPE_IN,
		// 	'amount' => $data['price'],
		// 	'description' => 'PaySystem',
		// 	'data' => json_encode($data)
		// ];

		// if(($id = $this->TransactionsHelper->add($tx)) === false)
		// 	throw new Exception('ошибка создания транзакции', 1);
		
		// $tx_item = $this->TransactionsModel->getByID($id);
		// // если транзакция уже прошла проводим 
		// if($tx_item['status'] === TransactionsModel::STATUS_SUCCESS)
		// 	return $this->subscr($data);

		// return $tx_item['hash'];

		throw new Exception('ошибка создания транзакции', 1);
	}

	// разобрать параметры обновления
	private function parseRenewal($data)
	{
		if(($item = $this->SubscriptionModel->getByHash(($data['hash'] ?? ''))) === false)
			throw new Exception('неверный идентификатор подписки', 1);

		$res = $this->SubscriptionHelper->prepareSubscr($item);
		$payData = new PayData(PayData::OBJ_TYPE_SUBSCR, $item['id'], $item['type']);
		foreach($res['items'] as $val)
		{
			$payData->addRow($val['description'], $val['price']);
		}
		$payData->setPeriod($res['ts_start'], $res['ts_end']);
		$payData->calcPrice();

		return $payData->toArray();
	}

	// разобрать параметры новой подписки
	private function parseNew($data)
	{
		if(($group_item = $this->GroupsModel->getByCode($data['group'])) === false)
			throw new Exception('неверный код группы', 1);

		if(!array_key_exists($data['type'], $this->SubscriptionModel::TYPES))
			throw new Exception('неверный тип подписки', 1);

		if(!in_array($data['period'], $this->SubscriptionModel::PERIOD_SUBSCR))
			throw new Exception('неверный период подписки', 1);

		// debug($group_item); die();

		$data['course'] = $group_item['course_id'];
		$group = $this->SubscriptionHelper->prepareGroup($data);
		$group['data'] = json_decode($group['data'], true);
		// debug($group); die();

		$payData = new PayData(PayData::OBJ_TYPE_COURSE, $group_item['id'], $data['type']);
		$payData->setName($group['description']);
		$payData->addRow($group['description'].' '.date(DATE_FORMAT_SHORT, strtotime($group['ts_start'])).' - '.date(DATE_FORMAT_SHORT, strtotime($group['ts_end'])), $group['data']['price']);
		$payData->setPeriod($group['ts_start'], $group['ts_end']);
		$payData->setParams([
			'amount' => $group['amount'],
			'price' => $group['price'],
			'subscr_type' => $group['subscr_type']
		]);
		$payData->setNew(true);
		$payData->calcPrice();

		return $payData->toArray();
	}
}