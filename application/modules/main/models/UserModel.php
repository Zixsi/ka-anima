<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends APP_Model
{
	private const TABLE = 'users';
	private const ROLES = [
		0, // юзер / ученик
		1, // преподаватель
		2, // резерв
		3, // резерв
		4, // резерв
		5 // админ
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
		{
			return $this->db->insert_id();
		}

		return false;
	}

	public function update($id, $data = [])
	{
		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$id = intval($id);
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function getByEmail($email)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE email = ?', [$email]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function list()
	{
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