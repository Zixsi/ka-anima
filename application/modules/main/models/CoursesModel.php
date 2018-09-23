<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesModel extends APP_Model
{
	private const TABLE = 'courses';
	private const TABLE_LECTURES = 'lectures';
	private const TABLE_FIELDS = ['name', 'description', 'type', 'period', 'price_month', 'price_full', 'author', 'ts', 'active'];
	public const TYPES = [
		0 => 'Самостоятельное обучение',
		1 => 'Обучение с инструктором'
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
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
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function update($id, $data = [])
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

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$sql = 'SELECT 
					c.*, l_all.cnt as cnt_all, l_main.cnt as cnt_main, (l_all.cnt - l_main.cnt) as cnt_other  
				FROM 
					'.self::TABLE.' as c  
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l_all ON(l_all.course_id = c.id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = c.id) 
				WHERE id = ?';

		if($row = $this->db->query($sql, [$id])->row_array())
		{
			return $row;
		}

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

		return false;
	}

	private function _CheckFields(&$data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('course') == FALSE)
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