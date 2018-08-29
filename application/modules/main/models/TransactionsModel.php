<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsModel extends APP_Model
{
	private const TABLE = 'transactions';
	private const TABLE_FIELDS = ['user', 'type', 'amount', 'description', 'service', 'service_id'];
	private const TYPES = [
		0, // IN
		1 // OUT
	];

	public function Add($data = [])
	{
		try
		{
			$this->_CheckFields($data);

			if($this->db->insert(self::TABLE, $data))
			{
				return $this->db->insert_id();
			}
		}
		catch(Exception $e)
		{
			var_dump($e->getMessage().'###'); die();
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function Update($id, $data = [])
	{
		try
		{
			$this->_CheckFields($data);

			$this->db->where('id', $id);
			if($this->db->update(self::TABLE, $data))
			{
				return true;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function GetByID($id)
	{
		$id = intval($id);
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function List($filter = [], $order = [], $select = [])
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

	public function BalanceUser($id)
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

	private function _CheckFields($data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('transaction') == FALSE)
		{
			throw new Exception($this->form_validation->error_string(), 1);
		}

		foreach($data as $key => $val)
		{
			if(in_array($key, self::TABLE_FIELDS) == false)
			{
				unset($data[$key]);
			}
		}
		
		return true;
	}
}