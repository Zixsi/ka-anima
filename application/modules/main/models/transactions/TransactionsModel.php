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
			return $res->result_array();

		return [];
	}

	// стата движения бабок по дням
	public function getStatByDays($from, $to)
	{
		$binds = [$from, $to];
		$sql = 'SELECT 
					SUM(amount) as value, ((UNIX_TIMESTAMP(ts) DIV 86400) * 86400) as ts 
				FROM 
					'.self::TABLE.' 
				WHERE 
					type = \''.self::TYPE_IN.'\' AND 
					status = \''.self::STATUS_SUCCESS.'\' AND 
					ts >= ? AND 
					ts < ?
				GROUP BY 
					ts
				ORDER BY 
					ts ASC';
		$res = $this->db->query($sql, $binds);
		if($res = $res->result_array())
		{
			return $res;
		}

		return [];
	}

	// стата движения бабок по месяцам
	public function getStatByMonths($from, $to)
	{
		$binds = [$from, $to];
		$sql = 'SELECT 
					SUM(amount) as value, DATE_FORMAT(ts, \'%Y-%m-01\') as ts_group 
				FROM 
					'.self::TABLE.' 
				WHERE 
					type = \''.self::TYPE_IN.'\' AND 
					status = \''.self::STATUS_SUCCESS.'\' AND 
					ts >= ? AND 
					ts < ?
				GROUP BY 
					ts_group 
				ORDER BY 
					ts_group ASC';

		$res = $this->db->query($sql, $binds);
		if($result = $res->result_array())
		{
			foreach($result as &$value)
			{
				$value['ts'] = $value['ts_group'];
				unset($value['ts_group']);
			}

			return $result;
		}

		return [];
	}

	// общая сумма дохода
	public function getTotalAmount()
	{
		$binds = [];
		$sql = 'SELECT 
					SUM(amount) as value  
				FROM 
					'.self::TABLE.' 
				WHERE 
					type = \''.self::TYPE_IN.'\' AND 
					status = \''.self::STATUS_SUCCESS.'\' ';
		$res = $this->db->query($sql, $binds);
		if($res = $res->row_array())
			return(float) $res['value'];

		return (float) 0;
	}
}