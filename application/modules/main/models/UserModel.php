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
		try
		{
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

	public function updateProfile($id, $data = [])
	{
		$data = [
			'name' => $data['name'] ?? '',
			'lastname' => $data['lastname'] ?? '',
			'birthday' => date('Y-m-d', strtotime($data['birthday'] ?? '')),
			'phone' => $data['phone'] ?? ''
		];

		return $this->update($id, $data);
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$id = intval($id);
		$sql = 'SELECT *, CONCAT_WS(\' \', name, lastname) as full_name FROM '.self::TABLE.' WHERE id = ?';
		$res = $this->db->query($sql, [$id]);
		if($row = $res->row_array())
		{
			if(empty($row['img']))
			{
				$row['full_name'] = (!empty($row['full_name']))?$row['full_name']:$row['email'];
				$row['img'] = $this->imggen->createIconSrc(['seed' => md5('user'.$row['id'])]);
			}

			return $row;
		}

		return false;
	}

	public function getByEmail($email)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE email = ?', [$email]);
		if($row = $res->row_array())
		{
			if(empty($row['img']))
			{
				$row['img'] = $this->imggen->createIconSrc(['seed' => md5('user'.$row['id'])]);
			}
			
			return $row;
		}

		return false;
	}

	public function list()
	{
		return false;
	}

	public function listTeachers()
	{
		$sql = 'SELECT *, CONCAT_WS(\' \', name, lastname) as full_name FROM '.self::TABLE.' WHERE role = 1 ORDER BY id ASC';
		if($res = $this->db->query($sql, []))
		{
			return $res->result_array();
		}

		return [];
	}

	public function listAllForUser($id)
	{
		try
		{
			$id = intval($id);
			$bind = [$id];

			$sql = 'SELECT 
						u.id, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.email, uf.user as is_friend    
					FROM 
						'.self::TABLE.' as u 
					LEFT JOIN 
						'.self::TABLE_USER_FRIENDS.' as uf ON(uf.id = u.id AND uf.user = ?) 
					WHERE 
						u.role != 5 
					ORDER BY 
						u.id ASC';

			$result = [];
			if($res = $this->db->query($sql, $bind)->result_array())
			{
				foreach($res as $val)
				{
					$val['img'] = $this->imggen->createIconSrc(['seed' => md5('user'.$val['id'])]);
					$val['full_name'] = (!empty($val['full_name']))?$val['full_name']:$val['email'];
					$val['is_friend'] = ($val['is_friend'] || intval($val['id']) === $id)?true:false;
					$result[] = $val;
				}
			}

			return $result;
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function pwdHash($password, $salt = false)
	{
		return ($salt !== false)?sha1($password.$salt):sha1($password);
	}

	public function pwdSalt()
	{
		return sha1(microtime(true));
	}
}