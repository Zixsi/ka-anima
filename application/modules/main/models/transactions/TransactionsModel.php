<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsModel extends APP_Model
{
	const TABLE = 'transactions';
	const TYPE_IN = 'IN';
	const TYPE_OUT = 'OUT';
	const TYPE = [
		self::TYPE_IN,
		self::TYPE_OUT
	];

	const STATUS_SUCCESS = 'SUCCESS';
	const STATUS_PENDING = 'PENDING';
	const STATUS_ERROR = 'ERROR';
	const STATUS = [
		self::STATUS_SUCCESS,
		self::STATUS_PENDING,
		self::STATUS_ERROR
	];

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function update($id, $data = [])
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			return true;

		return false;
	}

	public function getByID($id)
	{
		$id = intval($id);
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
			return $row;

		return false;
	}

	public function list($filter = [], $order = [], $select = [])
	{
		$select = count($select)?implode(', ', $select):'*';
		$this->db->select($select);
	
		count($filter)?$this->db->where($filter):$this->db->where('id >', 0);
		foreach($order as $key => $val)
		{
			$this->db->order_by($key, $val);
		}

		if($res = $this->db->get(self::TABLE))
		{
			return $res->result_array();
		}

		return [];
	}
}