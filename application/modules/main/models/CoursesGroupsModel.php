<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesGroupsModel extends APP_Model
{
	private const TABLE = 'courses_groups';
	private const TABLE_COURSES = 'courses';
	private const TABLE_LECTURES = 'lectures';
	private const TABLE_LECTURES_GROUPS = 'lectures_groups';
	private const TABLE_SUBSCRIPTION = 'subscription';
	private const TABLE_FIELDS = ['code', 'course_id', 'ts', 'ts_end'];

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

	public function getByID($id)
	{
		$sql = 'SELECT 
					g.*, c.name, c.price_month, c.price_full, 
					l_all.cnt as cnt_all, l_main.cnt as cnt_main, (l_all.cnt - l_main.cnt) as cnt_other 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l_all ON(l_all.course_id = g.course_id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = g.course_id) 
				WHERE g.id = ?';
		if($row = $this->db->query($sql, [$id])->row_array())
		{
			return $row;
		}

		return false;
	}

	public function GetByCode($code)
	{
		$res = $this->db->query('SELECT g.*, c.price_month, c.price_full FROM '.self::TABLE.' as g LEFT JOIN '.self::TABLE_COURSES.' as c ON(c.id = g.course_id) WHERE g.code = ?', [$id]);
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

	// Получить группы в которые не мтартовали и доступны еще не все лекции 
	public function getActiveGroups()
	{
		$sql = 'SELECT 
					g.id, g.course_id, l1.cnt as cnt_all, l2.cnt as cnt_available 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l1 ON(l1.course_id = g.course_id) 
				LEFT JOIN 
					(SELECT group_id, count(lecture_id) as cnt FROM '.self::TABLE_LECTURES_GROUPS.' GROUP BY group_id) as l2 ON(l2.group_id = g.id)
				WHERE 
					g.ts < ?';

		$res = $this->db->query($sql, [date('Y-m-d 00:00:00')]);
		if($res = $res->result_array())
		{
			$result = [];
			foreach($res as $val) 
			{
				$val = array_map(function($v){return intval($v);}, $val);
				if($val['cnt_available'] < $val['cnt_all'])
				{
					$result[] = $val;
				}					
			}

			return  $result;
		}

		return false;
	}

	public function listSubscribe($user)
	{
		//$ts = time() - (3600 * 24 * 30);
		$ts = time();
		$sql = 'SELECT 
					c.id, c.name, c.type, c.description, c.price_month, 
					c.price_full, g.id as group_id, g.code, g.ts 
				FROM 
					'.self::TABLE_COURSES.' as c 
				LEFT JOIN 
					'.self::TABLE.' as g ON(c.id = g.course_id) 
				LEFT JOIN 
					'.self::TABLE_SUBSCRIPTION.' as s ON(g.id = s.service AND s.type = 0 AND s.user = ?) 
				WHERE 
					c.active = 1 AND 
					g.id IS NOT NULL AND 
					g.ts >= ? AND 
					s.user IS NULL 
				ORDER BY 
					c.id ASC, g.ts ASC';

		$res = $this->db->query($sql, [intval($user), date('Y-m-d 00:00:00', $ts)]);

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
						'type' => $val['type'],
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

	// Выбрать курсы, группы для которых еще не созданы
	public function GetListNeedCreate()
	{
		$sql = 'SELECT 
					c.id, c.active, g.id as gid, g.ts  
				FROM 
					'.self::TABLE_COURSES.' as c 
				LEFT JOIN 
					'.self::TABLE.' as g ON(c.id = g.course_id AND g.ts > ?) 
				WHERE 
					c.active = 1 AND 
					g.id IS NULL 
				ORDER BY 
					c.id ASC';

		$res = $this->db->query($sql, [date('Y-m-01 00:00:00')]);
		if($result = $res->result_array())
		{
			return $result;
		}

		return false;
	}

	private function _CheckFields(&$data = [])
	{
		$this->form_validation->reset_validation();
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