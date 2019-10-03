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
					s.user = ? AND cg.ts_end > ? 
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

	// пользователи группы
	public function getGroupUsers($group,  $type = null)
	{
		if($group > 0)
		{
			$bind = [(int) $group];
			$sql_where = '';

			if($type && array_key_exists($type, self::TYPES))
			{
				$bind[] = $type;
				$sql_where .= ' AND s.type = ? ';
			}

			$sql = 'SELECT 
						u.id, u.email, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.img 
					FROM 
						'.self::TABLE.' as s 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(s.user = u.id) 
					WHERE 
						s.target = ? AND s.target_type = \'course\' '.$sql_where.' 
					GROUP BY 
						s.user 
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

	public function makeHash()
	{
		return md5(microtime(true));
	}
}