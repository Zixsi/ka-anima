<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsModel extends APP_Model
{
	const TABLE = 'user_actions';

	const ACTION_SUBSCR_COURSE = 'SUBSCR_COURSE';
	const ACTION_ADD_HOMEWORK = 'ADD_HOMEWORK';
	const ACTION_ADD_REVIEW = 'ADD_REVIEW';
	const ACTION_DEL_REVIEW = 'DEL_REVIEW';

	const ACTIONS = [
		self::ACTION_ADD_HOMEWORK => ['description' => 'Добавил ДЗ'],
		self::ACTION_ADD_REVIEW => ['description' => 'Добавил ревью'],
		self::ACTION_DEL_REVIEW => ['description' => 'Удалил ревью'],
	];

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
		// $this->db->where('id', $id);
		// if($this->db->update(self::TABLE, $data))
		// 	return true;

		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByHash($hash)
	{
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE hash = ?';
		if($res = $this->db->query($sql, [$id]))
			return $res->row_array();

		return false;
	}

	public function list($filter = [], $order = 'DESC')
	{
		$bind = [];
		$sql_where = [];
		$sql = 'SELECT * FROM '.self::TABLE.' '.$sql_where.' ORDER BY date '.$order;

		if(isset($filter['user']))
		{
			$sql_where[] = 'user = ?';
			$bind[] = (int) $filter['user'];
		}

		if(count($sql_where))
			$sql_where = 'WHERE '.implode(' AND ', $sql_where);

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

	private function hash($user, $action)
	{
		return md5($user, $action, time());
	}
}