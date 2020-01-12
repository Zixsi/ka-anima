<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionModel extends APP_Model
{
	const TABLE = 'subscription';
	const TABLE_COURSES = 'courses';
	const TABLE_COURSES_GROUPS = 'courses_groups';
	const TABLE_USERS = 'users';
	const TABLE_FILES = 'files';

	// Тип подписки
	const TYPES_SUBSCR = [
		0, // полная (подписка произведена на весь срок)
		1, // частичная (подписка на месяц / несколько месяцев)
		2 // продление
	];

	const PERIOD_SUBSCR = [
		'full', // полная (подписка произведена на весь срок)
		'month', // подписка на месяц / несколько месяцев
	];

	// тип объекта подписки
	const TYPES_TARGET = [
		'course', // курс
	];

	// вариант подписки 
	const TYPES = [
		'standart' => ['title' => 'Стандартная'],
		'advanced' => ['title' => 'Расширенная'],
		'vip' => ['title' => 'VIP'],
		'private' => ['title' => 'Закрытая']
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		$data['hash'] = $this->makeHash();
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

	public function remove($id)
	{
		$this->db->delete(self::TABLE, ['id' => (int) $id]);
		return true;
	}

	public function removeSubscription($user, $target, $targetType = 'course')
	{
		$this->db->delete(self::TABLE, [
			'user' => (int) $user,
			'target' => (int) $target,
			'target_type' => $targetType
		]);
		return true;
	}

	public function getByID($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function getByHash($hash)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE hash = ?', [$hash]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	
	public function сheck($user, $target, $target_type = 'course')
	{
		$bind = [$user, $target, $target_type];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND target = ? AND target_type = ?';
		$res = $this->db->query($sql, $bind);
		if($res->row())
			return true;
		
		return false;
	}

	public function get($user, $target, $target_type = 'course')
	{
		$bind = [$user, $target, $target_type];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND target = ? AND target_type = ?';
		$res = $this->db->query($sql, $bind);
		if($item = $res->row_array())
		{
			$item['active'] = (strtotime($item['ts_end']) > time())?true:false;
			return $item;
		}
		
		return false;
	}

	public function getList($user, $targetType = null)
	{
		$result = [];
		$binds = [
			':user' => (int) $user
		];

		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = :user';

		if(empty($targetType) === false)
		{
			$binds[':targetType'] = $targetType;
			$sql .= ' AND target_type = :targetType';
		}

		$res = $this->query($sql, $binds);
		if($res = $res->result_array())
			$result = $res;
		
		return $result;
	}

	// public function getAllList($filter = [])
	// {
	// 	$result = [];
	// 	$filterParams = $this->parseListFilter($filter);
	// 	$binds = $filterParams['binds'];
	// 	$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = :user';
	// 	if(count($filterParams['where']))
	// 		$sql .= ' WHERE ' . implode(' AND ', $filterParams['where']);

	// 	$res = $this->query($sql, $binds);
	// 	if($res = $res->result_array())
	// 		$result = $res;
		
	// 	return $result;
	// }

	// курсы на которые подписан пользователь
	public function coursesList($user)
	{
		if( (int) $user === 0)
			return [];

		$bind = [(int) $user, date('Y-m-d H:i:s')];
		$sql = 'SELECT 
					c.id, c.name, cg.ts, cg.ts_end, cg.id as course_group, cg.code as code 
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_COURSES_GROUPS.' as cg ON(cg.id = s.target AND s.target_type = \'course\') 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = cg.course_id) 
				WHERE 
					s.user = ? AND cg.ts_end > ? 
				ORDER BY 
					cg.id ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			foreach($res as $key => &$val)
			{
				$val['ts_f'] = date('F Y', strtotime($val['ts']));
			}

			return $res;
		}

		return [];
	}

	// список идентификаторов курсов на которые подписан пользователь
	public function listCoursesId($user)
	{
		$result = [];
		if($res = $this->coursesList($user))
		{
			foreach($res as $val)
			{
				$result[] = $val['id'];
			}
		}

		return $result;
	}


	// группы на которые подписан пользователь
	public function groupsList($user)
	{
		if( (int) $user === 0)
			return [];

		$bind = [(int) $user, date('Y-m-d H:i:s')];
		$sql = 'SELECT 
					c.id, c.code as course_code, c.name, c.description, c.preview_text, cg.ts, cg.ts_end, cg.id as course_group, cg.code as code, f.full_path as img, 
					s.ts_end as subscr_ts_end, s.type as subscr_type, s.subscr_type as subscr_pay_type, s.hash   
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_COURSES_GROUPS.' as cg ON(cg.id = s.target AND s.target_type = \'course\') 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = cg.course_id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img) 
				WHERE 
					s.user = ? AND s.ts_end >= ? 
				ORDER BY 
					cg.id ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			foreach($res as $key => &$val)
			{
				$val['ts_timestamp'] = strtotime($val['ts']);
				$val['ts_end_timestamp'] = strtotime($val['ts_end']);
				$val['ts_f'] = date(DATE_FORMAT_SHORT, $val['ts_timestamp']);
				$val['ts_end_f'] = date(DATE_FORMAT_SHORT, $val['ts_end_timestamp']);

				$val['subscr_ts_end'] = strtotime($val['subscr_ts_end']);
				$val['subscr_active'] = ($val['subscr_ts_end'] > time());

				if(array_key_exists('img', $val))
					$val['img'] = empty($val['img'])?IMG_DEFAULT_16_9:'/'.$val['img'];
			}

			return $res;
		}

		return [];
	}
	
	// Подписки пользователя
	public function byUser($user)
	{
		$sql = 'SELECT 
					s.*, cg.code  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_COURSES_GROUPS.' as cg ON(cg.id = s.target AND s.target_type = \'course\') 
				WHERE 
					user = ? 
				ORDER BY 
					id DESC';
		$res = $this->db->query($sql, [intval($user)]);
		if($res = $res->result_array())
		{
			foreach($res as &$val)
				$val['active'] = (strtotime($val['ts_end']) > time())?true:false;

			return $res;
		}

		return false;
	}

	public function getByUserList($id)
	{
		$result = [];
		$binds = [':id' => (int) $id];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = :id ORDER BY id DESC';
		$res = $this->query($sql, $binds);
		if($res = $res->result_array())
		{
			foreach($res as &$val)
				$val['active'] = (strtotime($val['ts_end']) > time())?true:false;

			$result = $res;
		}

		return $result;
	}

	// пользователи группы
	public function getGroupUsers($group,  $type = null, $target_type = 'course')
	{
		if($group > 0)
		{
			$bind = [(int) $group, $target_type];
			$sql_where = '';

			if($type && array_key_exists($type, self::TYPES))
			{
				$bind[] = $type;
				$sql_where .= ' AND s.type = ? ';
			}

			$sql = 'SELECT 
						u.id, u.email, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.img, s.ts_end, s.ts_start, s.id as sid   
					FROM 
						'.self::TABLE.' as s 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(s.user = u.id) 
					WHERE 
						s.target = ? AND s.target_type = ? '.$sql_where.' 
					ORDER BY 
						s.user ASC';

			if($res = $this->db->query($sql, $bind))
			{
				// debug($res); die();
				$result = [];
				$res = $res->result_array();
				foreach($res as $val)
				{
					$this->UserModel->prepareUser($val);
					$val['ts_end_timestamp'] = strtotime($val['ts_end']);
					$result[$val['id']] = $val;
				}

				unset($res);
				return $result;
			}
		}

		return false;
	}

	public function statForIds($ids = [])
	{
		$bind = [];
		$sql = 'SELECT 
					target as id, count(*) as cnt 
				FROM 
					'.self::TABLE.' 
				WHERE 
					target IN('.implode(',', $ids).') AND 
					target_type = \'course\' AND 
					type != \'standart\' 
				GROUP BY 
					target';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			$result = [];
			foreach($res as $val)
			{
				$result[$val['id']] = $val;
			}
			
			unset($res);
			return $result;
		}

		return [];
	}

	/**
	* Получить общее кол-во подписок для типа, по элементам
	**/
	public function getSubscribeCount($id, $type)
	{
		$result = [];
		$binds = [
			':type' => $type
		];

		$sql = 'SELECT 
					target as id, count(*) as count 
				FROM 
					'.self::TABLE.' 
				WHERE 
					target_type = :type';

		if(empty($id) === false)
		{
			if(is_array($id))
				$sql .= ' AND target IN(' . implode(',', $id) . ')';
			else
			{
				$binds[':id'] = $id;
				$sql .= ' AND target = :id';
			}
		}

		$sql .= ' GROUP BY 
					target 
				ORDER BY 
					target ASC';
		if($res = $this->query($sql, $binds))
		{
			$result = $res->result_array();
		}

		return $result;
	}

	public function parseListFilter($params = [])
	{
		$result = [
			'binds' => [],
			'where' => [],
			'offset' => 0,
			'limit' => 9999
		];

		if(isset($params['id']) && empty($params['id']) === false)
		{
			if(is_array($params['id']))
				$result['where'][] = 'id IN('.implode(',', $params['id']).')';
			else
			{
				$result['binds'][':id'] = $params['id'];
				$result['where'][] = 'id = :id';
			}
		}

		return $result;
	}

	public function makeHash()
	{
		return md5(microtime(true));
	}

	public function getStatCountByDays($from, $to, $type = null)
	{
		$result = [];
		$binds = [
			':from' => $from,
			':to' => $to
		];
		$sql = 'SELECT 
					COUNT(*) as value, ((UNIX_TIMESTAMP(ts_start) DIV 86400) * 86400) as ts 
				FROM 
					'.self::TABLE.' 
				WHERE 
					ts_start >= :from AND 
					ts_start < :to ';

		if($type)
		{
			$binds[':type'] = $type;
			$sql .= ' AND target_type = :type ';
		}

		$sql .= ' GROUP BY 
					ts
				ORDER BY 
					ts ASC';
		$res = $this->query($sql, $binds);
		if($rows = $res->result_array())
		{
			foreach($rows as &$row)
			{
				$row['date'] = date(DATE_FORMAT_SHORT, $row['ts']);
			}

			$result = $rows;
		}

		return $result;
	}

	public function getStatCountByMonths($from, $to, $type = null)
	{
		$result = [];
		$binds = [
			':from' => $from,
			':to' => $to
		];
		$sql = 'SELECT 
					COUNT(*) as value, DATE_FORMAT(ts_start, \'%Y-%m-01\') as ts_group  
				FROM 
					'.self::TABLE.' 
				WHERE 
					ts_start >= :from AND 
					ts_start < :to ';
		if($type)
		{
			$binds[':type'] = $type;
			$sql .= ' AND target_type = :type ';
		}

		$sql .= ' GROUP BY 
					ts_group
				ORDER BY 
					ts_group ASC';
					
		$res = $this->query($sql, $binds);
		if($rows = $res->result_array())
		{
			foreach($rows as &$row)
			{
				$row['ts'] = strtotime($row['ts_group']);
				$row['date'] = date(DATE_FORMAT_SHORT, $row['ts']);
				unset($row['ts_group']);
			}

			$result = $rows;
		}

		return $result;
	}
}