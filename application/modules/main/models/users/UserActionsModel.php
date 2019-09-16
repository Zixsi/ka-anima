<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsModel extends APP_Model
{
	const TABLE = 'user_actions';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return true;

		return false;
	}

	public function update($hash, $data = [])
	{
		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function list($filter = [], $order = 'DESC')
	{
		$bind = [];
		$sql_where = [];

		if(isset($filter['user']))
		{
			$sql_where[] = 'user = ?';
			$bind[] = (int) $filter['user'];
		}

		if(count($sql_where))
			$sql_where = 'WHERE '.implode(' AND ', $sql_where);

		$sql = 'SELECT * FROM '.self::TABLE.' '.$sql_where.' ORDER BY date '.$order;
		if($res = $this->db->query($sql, $bind))
		{
			return $res->result_array();
		}

		return [];
	}

	public function listByUser($user, $order = 'DESC')
	{
		return $this->list(['user' => $user], $order);
	}

	public function hash($user, $action)
	{
		return md5($user.$action.time());
	}
}