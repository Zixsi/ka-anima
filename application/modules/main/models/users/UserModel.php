<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends APP_Model
{
	const TABLE = 'users';
	const TABLE_USER_FRIENDS = 'user_friends';
	const ROLES = [
		0, // юзер / ученик
		1, // преподаватель
		2, // резерв
		3, // резерв
		4, // резерв
		5 // админ
	];

	const ROLES_NAME = [
		0 => 'ученик',
		1 => 'преподаватель',
		5 => 'админ'
	];

	public function __construct()
	{
		parent::__construct();
	}

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

	public function updateProfile($id, $params = [])
	{
		$data = [
			'name' => $params['name'] ?? '',
			'lastname' => $params['lastname'] ?? '',
			'birthday' => date('Y-m-d', strtotime($params['birthday'] ?? '')),
			'phone' => $params['phone'] ?? '',
			'img' => ($params['img'] ?? ''),
			'soc' => ($params['soc'] ?? ''),
			'title' => ($params['title'] ?? ''),
			'discord' => ($params['discord'] ?? ''),
		];

		if (isset($params['email'])) {
			$data['email'] = $params['email'];
		}

		return $this->update($id, $data);
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		return $this->getByField('id', (int) $id);
	}

	public function getByLogin($login)
	{
		return $this->getByField('login', $login);
	}

	public function getByEmail($email)
	{
		return $this->getByField('email', $email);
	}

	public function getByField($field, $value)
	{
		$sql = 'SELECT *, CONCAT_WS(\' \', name, lastname) as full_name FROM '.self::TABLE.' WHERE '.$field.' = ?';
		$res = $this->db->query($sql, [$value]);
		if($row = $res->row_array())
		{
			$this->prepareUser($row);
			return $row;
		}

		return false;
	}

	public function prepareUser(&$data)
	{
		if(isset($data['role']))
			$data['role_name'] = self::ROLES_NAME[$data['role']];

		if(empty($data['img']))
			$data['img'] = TEMPLATE_DIR.'/assets/profile_icon_male2.png';
	}

	public function getByHash($code)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE hash = ?', [$code]);
		if($row = $res->row_array())
			return $row;

		return false;
	}

	public function list($filter = [], $order = ['id' => 'desc'])
	{
		$bind = [];

		$sql = 'SELECT 
					* , CONCAT_WS(\' \', name, lastname) as full_name 
				FROM 
					'.self::TABLE.' 
				WHERE 
					id IS NOT NULL';

		// роль
		if(isset($filter['role']) && $filter['role'] !== 'all' && $filter['role'] !== '')
		{
			$sql .= ' AND role = '.((int) $filter['role']).' ';
		}

		if(isset($filter['id']) && empty($filter['id']) === false)
		{
			if(is_array($filter['id']))
				$sql .= ' AND id IN('.implode(',', $filter['id']).') ';
			else
				$sql .= ' AND id = '.((int) $filter['id']).' ';
		}

		// группа
		if(isset($filter['group']) && $filter['group'] !== 'all' && $filter['group'] !== '')
		{
			switch($filter['group'])
			{
				case 'active':
					$sql .= ' AND deleted = 0 AND blocked = 0 ';
				break;
				case 'blocked':
					$sql .= ' AND blocked = 1 ';
				break;
				case 'deactivated':
					$sql .= ' AND active = 0 ';
				break;
				case 'deleted':
					$sql .= ' AND deleted = 1 ';
				break;
			}
		}
		else
		{
			// отображать только не удаленных
			$sql .= ' AND deleted = 0 ';
		}

		if(isset($filter['search']) && mb_strlen($filter['search']) >= 3)
		{
			$sql .= ' AND (email LIKE ? OR name LIKE ? OR lastname LIKE ?) ';
			$bind[] = '%'.$filter['search'].'%';
			$bind[] = '%'.$filter['search'].'%';
			$bind[] = '%'.$filter['search'].'%';
		}

		if(count($order))
		{
			$sql_order = [];
			foreach($order as $key => $val)
			{
				$sql_order[] = $key.' '.$val;
			}

			if(count($sql_order))
				$sql .= ' ORDER BY '.implode(', ', $sql_order);

			unset($sql_order);
		}

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			foreach($res as &$val)
			{
				$this->prepareUser($val);
			}
			return $res;
		}

		return [];
	}

	public function listTeachers()
	{
		$sql = 'SELECT *, CONCAT_WS(\' \', name, lastname) as full_name FROM '.self::TABLE.' WHERE role = 1 AND deleted = 0 AND blocked = 0 ORDER BY id ASC';
		if($res = $this->db->query($sql, []))
		{
			return $res->result_array();
		}

		return [];
	}

	public function listForSelect($filter = [])
	{
		$bind = [];
		$where = '';
		if(isset($filter['role']))
		{
			$bind[] = $filter['role'];
			$where .= ' AND role = ? ';
		}

		if(isset($filter['search']) && mb_strlen($filter['search']) >= 3)
		{
			$bind[] = '%'.$filter['search'].'%';
			$bind[] = '%'.$filter['search'].'%';
			$bind[] = '%'.$filter['search'].'%';

			$where .= ' AND (name LIKE ? OR lastname LIKE ? OR email LIKE ?) ';
		}

		$sql = 'SELECT id, CONCAT_WS(\' \', name, lastname) as full_name, email FROM '.self::TABLE.' WHERE id IS NOT NULL AND deleted = 0 AND blocked = 0 '.$where.' ORDER BY id ASC';
		if($res = $this->db->query($sql, $bind))
		{
			$result = [];
			$res = $res->result_array();
			foreach($res as $val)
			{
				$result[] = [
					'id' => $val['id'],
					'text' => $val['email'].' ('.$val['full_name'].')'
				]; 
			}

			return $result;
		}

		return [];
	}

	public function listAllForUser($id)
	{
		$id = intval($id);
		$bind = [$id];

		$sql = 'SELECT 
					u.id, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.email, u.role, u.img, uf.user as is_friend    
				FROM 
					'.self::TABLE.' as u 
				LEFT JOIN 
					'.self::TABLE_USER_FRIENDS.' as uf ON(uf.id = u.id AND uf.user = ?) 
				WHERE 
					u.role != 5 AND 
					u.deleted = 0 AND 
					u.blocked = 0
				ORDER BY 
					u.id ASC';

		$result = [];
		if($res = $this->db->query($sql, $bind)->result_array())
		{
			foreach($res as $val)
			{
				$this->prepareUser($val);
				$val['full_name'] = (!empty($val['full_name']))?$val['full_name']:$val['email'];
				$val['is_friend'] = ($val['is_friend'] || intval($val['id']) === $id)?true:false;
				$result[] = $val;
			}
		}

		return $result;
	}

	// кол-во юзеров по ролям
	public function cntRoles()
	{
		$result = [];
		$result['all'] = 0;
		foreach(self::ROLES as $val)
		{
			$result[$val] = 0;
		}

		$sql = 'SELECT count(*) as cnt, role FROM '.self::TABLE.' GROUP BY role';
		if($res = $this->db->query($sql, []))
		{
			$res = $res->result_array();
			foreach($res as $val)
			{
				$result[$val['role']] = (int) $val['cnt'];
				$result['all'] += (int) $val['cnt'];
			}
		}

		return $result;
	}

	public function setActive($id, $flag = true)
	{
		return $this->db->update(self::TABLE, ['active' => (($flag)?1:0)], ['id' => $id]);
	}

	public function setParent(int $id, int $parent = 0)
	{
		return $this->db->update(self::TABLE, ['parent' => $parent], ['id' => $id]);
	}

	public function pwdHash($password, $salt = false)
	{
		return ($salt !== false)?sha1($password.$salt):sha1($password);
	}

	public function pwdSalt()
	{
		return sha1(microtime(true));
	}

	// стата движения бабок по дням
	public function getRegistrationStatByDays($from, $to)
	{
		$binds = [$from, $to];
		$sql = 'SELECT 
					COUNT(*) as value, ((UNIX_TIMESTAMP(ts_created) DIV 86400) * 86400) as ts 
				FROM 
					'.self::TABLE.' 
				WHERE 
					ts_created >= ? AND 
					ts_created < ? 
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
	public function getRegistrationStatByMonths($from, $to)
	{
		$binds = [$from, $to];
		$sql = 'SELECT 
					COUNT(*) as value, DATE_FORMAT(ts_created, \'%Y-%m-01\') as ts_group 
				FROM 
					'.self::TABLE.' 
				WHERE 
					ts_created >= ? AND 
					ts_created < ? 
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

	public function getCountTotal()
	{
		$sql = 'SELECT COUNT(*) as value FROM '.self::TABLE.' ';
		$res = $this->db->query($sql, []);
		if($res = $res->row_array())
			return $res['value'];

		return 0;
	}

	public function getCountActive()
	{
		$sql = 'SELECT COUNT(*) as value FROM '.self::TABLE.' WHERE active = 1';
		$res = $this->db->query($sql, []);
		if($res = $res->row_array())
			return $res['value'];

		return 0;
	}

	public function getCountBlocked()
	{
		$sql = 'SELECT COUNT(*) as value FROM '.self::TABLE.' WHERE blocked = 1';
		$res = $this->db->query($sql, []);
		if($res = $res->row_array())
			return $res['value'];

		return 0;
	}
}