<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesGroupsModel extends APP_Model
{
	private const TABLE = 'courses_groups';
	private const TABLE_COURSES = 'courses';
	private const TABLE_LECTURES = 'lectures';
	private const TABLE_LECTURES_GROUPS = 'lectures_groups';
	private const TABLE_SUBSCRIPTION = 'subscription';
	private const TABLE_FILES = 'files';
	private const TABLE_FIELDS = ['code', 'course_id', 'ts', 'ts_end'];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			$this->_checkFields($data);

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
			$this->_checkFields($data);

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
					g.*, c.name, c.price_month, c.price_full, c.author, 
					l_all.cnt as cnt_all, l_main.cnt as cnt_main, (l_all.cnt - l_main.cnt) as cnt_other, f.full_path as img_src  
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l_all ON(l_all.course_id = g.course_id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = g.course_id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img) 
				WHERE g.id = ?';
		if($row = $this->db->query($sql, [$id])->row_array())
		{
			return $row;
		}

		return false;
	}

	public function getByCode($code)
	{
		$res = $this->db->query('SELECT g.*, c.price_month, c.price_full FROM '.self::TABLE.' as g LEFT JOIN '.self::TABLE_COURSES.' as c ON(c.id = g.course_id) WHERE g.code = ?', [$id]);
		if($row = $res->row_array())
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

	// Получить группы в которые не cтартовали и доступны еще не все лекции 
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
		$bind = [
			intval($user), 
			date('Y-m-d 00:00:00', time())
		];

		$sql = 'SELECT 
					c.id, c.name, c.type, c.description, c.price_month, 
					c.price_full, g.id as group_id, g.code, g.ts, f.full_path as img_src, s.user  
				FROM 
					'.self::TABLE_COURSES.' as c 
				LEFT JOIN 
					'.self::TABLE.' as g ON(c.id = g.course_id) 
				LEFT JOIN 
					'.self::TABLE_SUBSCRIPTION.' as s ON(g.id = s.service AND s.type = 0 AND s.user = ?) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img) 
				WHERE 
					c.active = 1 AND 
					g.id IS NOT NULL AND 
					g.ts >= ? 
				ORDER BY 
					c.id ASC, g.ts ASC';

		if($res = $this->db->query($sql, $bind)->result_array())
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
						'img' => $val['img_src'],
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
					'ts' => $val['ts'],
					'subscription' => intval($val['user'])?1:0
				];
			}

			return $result;
		}

		return false;
	}

	// Выбрать курсы, группы для которых еще не созданы
	public function getListNeedCreate()
	{
		$bind = [
			date('Y-m-01 00:00:00') // 
		];

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

		if($res = $this->db->query($sql, $bind)->result_array())
		{
			return $res;
		}

		return false;
	}

	// Получить список групп юзера
	public function getUserGroups($id)
	{
		$sql = 'SELECT 
					service as id, description as name 
				FROM 
					'.self::TABLE_SUBSCRIPTION.' 
				WHERE 
					user = ? AND type = 0  
				ORDER BY 
					id DESC';
		if($res = $this->db->query($sql, [$id])->result_array())
		{
			return $res;
		}

		return false;
	}

	// Проверка наличия юзера в группе
	public function userInGroup($id, $user)
	{
		$sql = 'SELECT id FROM '.self::TABLE_SUBSCRIPTION.' WHERE user = ? AND service = ? AND type = 0';
		if($this->db->query($sql, [$user, $id])->row_array())
		{
			return true;
		}

		return false;
	}

	// Получить список групп преподавателя
	public function getTeacherGroups($id)
	{
		$sql = 'SELECT 
					c.id, c.name, c.author, g.id as group_id, g.ts, g.ts_end, w.cnt as weeks_cnt, wc.cnt as weeks_current, u.cnt as user_cnt   
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON (c.id = g.course_id) 
				LEFT JOIN 
					(SELECT count(id) as cnt, course_id FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as w ON(w.course_id = g.course_id) 
				LEFT JOIN 
					(SELECT count(*) as cnt, group_id FROM '.self::TABLE_LECTURES_GROUPS.' WHERE ts < ? GROUP BY group_id) as wc ON(wc.group_id = g.id)
				LEFT JOIN 
					(SELECT count(DISTINCT(user)) as cnt, service FROM '.self::TABLE_SUBSCRIPTION.' WHERE type = 0 GROUP BY service) as u ON(u.service = g.id) 
				WHERE 
					c.author = ? AND g.ts_end > ? 
				ORDER BY 
					g.ts_end ASC';

		$bind = [date('Y-m-d H:i:s'), $id, date('Y-m-d H:i:s')];

		if($res = $this->db->query($sql, $bind)->result_array())
		{
			return $res;
		}

		return false;
	}

	// Информация по группе
	public function getGroupInfo($id)
	{
		if($res = $this->getByID($id))
		{
			
			debug($res); die();
		}

		return false;
	}

	private function _checkFields(&$data = [])
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