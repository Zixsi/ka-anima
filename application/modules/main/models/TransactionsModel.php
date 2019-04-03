<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsModel extends APP_Model
{
	private const TABLE = 'transactions';
	private const TYPES = [
		0, // IN
		1 // OUT
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
		{
			return $row;
		}

		return false;
	}

	public function list($filter = [], $order = [], $select = [])
	{
		try
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
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function listUserTxByType($user, $type = 0)
	{
		try
		{
			$bind = [
				intval($user), // юзер
				intval($type) // тип
			];
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND type = ? ORDER BY id DESC LIMIT 20';
			if($rows = $this->db->query($sql, $bind)->result_array())
			{
				return $rows;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function balanceUser($id)
	{
		try
		{
			$res = $this->db->query('SELECT SUM(amount) as amount, type FROM '.self::TABLE.' WHERE user = ? GROUP BY type', [intval($id)]);
			if($rows = $res->result_array())
			{
				$data = ['0' => 0, '1' => 0];
				foreach($rows as $val)
				{
					$data[$val['type']] = $val['amount'];
				}

				return ($data['0'] - $data['1']);
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return 0;
	}

	// Сумма оплат сервиса
	public function getPayAmountService($id, $user, $service = 'group')
	{
		try
		{
			$sql = 'SELECT SUM(amount) as amount FROM '.self::TABLE.' WHERE user = ? AND service_id = ? AND service = ? GROUP BY service_id';
			if($res = $this->db->query($sql, [intval($user), intval($id) , $service])->row_array())
			{
				return floatval($res['amount']);
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return 0;
	}
}