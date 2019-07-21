<?php

class UserFriendsModel extends APP_Model
{
	const TABLE = 'user_friends';
	const TABLE_USERS = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	public function add(int $user, int $id)
	{
		try
		{
			$data = [
				'user' => intval($user), 
				'id' => intval($id)
			];
			if($this->db->insert(self::TABLE, $data))
			{
				return $this->db->insert_id();
			}
		}
		catch(Exception $e)
		{
			// 
		}

		return false;
	}

	public function delete(int $user, int $id)
	{
		$data = [
			'user' => intval($user), 
			'id' => intval($id)
		];
		return $this->db->delete(self::TABLE, $data);
	}

	public function clear(int $user)
	{
		$data = [
			'user' => intval($user)
		];
		return $this->db->delete(self::TABLE, $data);
	}

	public function list(int $user)
	{
		try
		{
			$sql = 'SELECT 
						u.id, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.email, u.role   
					FROM 
						'.self::TABLE.' as uf 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(u.id = uf.id) 
					WHERE 
						uf.user = ?';

			$result = [];
			if($res = $this->db->query($sql, [intval($user)])->result_array())
			{
				foreach($res as $val)
				{
					$this->UserModel->prepareUser($val);

					$val['full_name'] = (!empty($val['full_name']))?$val['full_name']:$val['email'];
					$result[] = $val;
				}
			}

			return $result;
		}
		catch(Exception $e)
		{
			// 
		}

		return false;
	}

	public function cnt(int $user)
	{
		try
		{
			$sql = 'SELECT count(*) as cnt FROM '.self::TABLE.' WHERE user = ?';
			if($res = $this->db->query($sql, [intval($user)])->row_array())
			{
				return intval($res['cnt']);
			}

			return 0;
		}
		catch(Exception $e)
		{
			// 
		}

		return 0;
	}

	public function isFriends(int $user_a, int $user_b)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE user = ? AND id = ?', [$user_a, $user_b]);
		if($row = $res->row_array())
		{
			return true;
		}

		return false;
	}
}