<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesModel extends APP_Model
{
	private const TABLE = 'lectures';
	private const TABLE_FIELDS = ['active', 'name', 'description', 'course', 'video', 'modify', 'sort'];

	public function __construct()
	{
		parent::__construct();
	}

	public function Add($data = [])
	{
		if($this->CheckFields($data) == false)
		{
			return false;
		}

		if($this->db->insert(self::TABLE, $data))
		{
			return $this->db->insert_id();
		}

		return false;
	}

	public function Edit($id, $data = [])
	{
		if($this->CheckFields($data) == false)
		{
			return false;
		}

		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
		{
			return true;
		}

		return false;
	}

	public function Delete($id)
	{
		return false;
	}

	public function GetByID($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function List($filter = [], $order = [], $select = [])
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

		return false;
	}

	private function CheckFields(&$data = [])
	{
		try
		{
			$this->form_validation->set_data($data);

			if($this->form_validation->run('lectures_add') == FALSE)
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
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}
}