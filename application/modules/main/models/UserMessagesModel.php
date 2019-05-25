<?php

class UserMessagesModel extends APP_Model
{
	const TABLE = 'user_messages';
	const TABLE_BLACK_LIST = 'user_messages_black_list';
	const TABLE_USERS = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	public function add(array $data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function listChats(int $user)
	{
		$bind = [$user, $user];

		$sql = 'SELECT 
					m.user, m.target, CONCAT_WS(\' \', u1.name, u1.lastname) as user_full_name, u1.email as user_email, u1.role as user_role,  
					CONCAT_WS(\' \', u2.name, u2.lastname) as target_full_name, u2.email as target_email, u2.role as target_role 
				FROM 
					'.self::TABLE.' as m 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u1 ON(u1.id = m.user) 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u2 ON(u2.id = m.target) 
				WHERE 
					m.user = ? OR m.target = ? 
				GROUP BY
					m.user, m.target';

		$result = [];
		if($res = $this->db->query($sql, $bind)->result_array())
		{
			foreach($res as $val)
			{
				if(intval($val['user']) === $user)
				{
					$val['name'] = (!empty($val['target_full_name']))?$val['target_full_name']:$val['target_email'];
					$val['role'] = $val['target_role'];
				}
				else
				{
					$val['name'] = (!empty($val['user_full_name']))?$val['user_full_name']:$val['user_email'];
					$val['target'] = $val['user'];
					$val['role'] = $val['user_role'];
				}

				if(!array_key_exists($val['target'], $result))
				{
					$result[$val['target']] = [
						'id' => $val['target'],
						'name'=> $val['name'],
						'role'=> $val['role'],
						'role_name'=> UserModel::ROLES_NAME[$val['role']],
						'img' => $this->imggen->createIconSrc(['seed' => md5('user'.$val['target'])])
					];
				}
			}
		}

		return $result;
	}

	public function listForChat(int $user, int $target)
	{
		$bind = [$user, $target, $target, $user];

		$sql = 'SELECT 
					m.*, CONCAT_WS(\' \', u.name, u.lastname) as user_full_name, u.email as user_email, u.role as role  
				FROM 
					'.self::TABLE.' as m  
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = m.user) 
				WHERE 
					(m.user = ? AND m.target = ?) OR 
					(m.user = ? AND m.target = ?) 
				ORDER BY 
					id ASC';

		$result = [];
		if($res = $this->db->query($sql, $bind)->result_array())
		{
			foreach($res as $val)
			{
				$val['name'] = (!empty($val['user_full_name']))?$val['user_full_name']:$val['user_email'];
				$val['role_name'] = UserModel::ROLES_NAME[$val['role']];
				$result[] = $val;
			}
		}

		return $result;
	}

	public function addToBlackList(int $user, int $id)
	{
		$data = [
			'user' => intval($user), 
			'id' => intval($id)
		];
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function deleteFromBlackList(int $user, int $id)
	{
		$data = [
			'user' => intval($user), 
			'id' => intval($id)
		];
		return $this->db->delete(self::TABLE, $data);
	}

	public function clearBlackList(int $user)
	{
		$data = [
			'user' => intval($user)
		];
		return $this->db->delete(self::TABLE, $data);
	}

	public function blackList(int $user)
	{
		$sql = 'SELECT 
					u.id, CONCAT_WS(\' \', u.name, u.lastname) as full_name 
				FROM 
					'.self::TABLE_BLACK_LIST.' as umbl 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = umbl.id) 
				WHERE 
					uf.user = ?';
		if($res = $this->db->query($sql, [intval($user)])->result_array())
			return $res;

		return false;
	}
}