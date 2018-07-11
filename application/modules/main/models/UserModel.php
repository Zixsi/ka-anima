<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends APP_Model
{
	private const TABLE = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	public function Add($data = [])
	{
		return false;
	}

	public function Update($id, $data = [])
	{
		return false;
	}

	public function Delete($id)
	{
		return false;
	}

	public function GetByID($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $query->row_array())
		{
			return $row;
		}

		return false;
	}

	public function GetByEmail($email)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE email = ?', [$email]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function List()
	{
		return false;
	}

	public function PwdHash($password, $salt = false)
	{
		return ($salt !== false)?sha1($password.$salt):sha1($password);
	}

	public function PwdSalt()
	{
		return sha1(microtime(true));
	}
}