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
		if($data['amount'] === 0.0)
			$data['status'] = TransactionsModel::STATUS_SUCCESS;
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
		}
	}
}