<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesGroupsModel extends APP_Model
{
	private const TABLE = 'courses_groups';
	private const TABLE_COURSES = 'courses';
	private const TABLE_FIELDS = ['code', 'course', 'ts'];

	public function __construct()
	{
		parent::__construct();
	}

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

	public function GetByCode($code)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE code = ?', [$code]);
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

	public function ListSubscribe()
	{
		$ts = time() - (3600 * 24 * 30);
		$sql = 'SELECT c.id, c.name, c.description, c.price_month, c.price_full, g.id as group_id, g.code, g.ts FROM courses as c LEFT JOIN courses_groups as g ON(c.id = g.course) WHERE c.active = 1 AND g.id IS NOT NULL AND g.ts >= \''.date('Y-m-d 00:00:00', $ts).'\' ORDER BY c.id ASC, g.ts ASC';
		$res = $this->db->query($sql);
		if($res = $res->result_array())
		{
			$result = [];

			foreach($res as $val)
			{
				if(array_key_exists($val['id'], $result) === false)
				{
					$result[$val['id']] = [
						'id' => $val['id'],
						'name' => $val['name'],
						'description' => $val['description'],
						'price' => [
							'month' => $val['price_month'],
							'full' => $val['price_full']
						],
						'groups' => []
					];
				}

				$result[$val['id']]['groups'][] = [
					'id' => $val['group_id'],
					'code' => $val['code'],
					'ts' => $val['ts']
				];
			}

			return $result;
		}

		return false;
	}

	// Выбрать курсы группы для которых еще не созданы
	public function GetListNeedCreate()
	{
		//$sql = 'SELECT c.id, g.id as group_id, g.code, g.ts  FROM courses as c LEFT JOIN (SELECT g1.* FROM courses_groups as g1 LEFT JOIN courses_groups AS g2 ON g1.course = g2.course AND g1.ts < g2.ts WHERE g2.course IS NULL) as g ON(c.id = g.course) WHERE c.active = 1 AND (g.id IS NULL OR g.ts < ???) ORDER BY c.id ASC';
		//print_r($sql);
		/*$res = $this->db->query($sql);
		if($res = $res->result_array())
		{
			return $res;
		}*/

		return false;
	}

	private function _CheckFields(&$data = [])
	{
		$this->form_validation->set_data($data);
		if($this->form_validation->run('courses_groups') == FALSE)
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