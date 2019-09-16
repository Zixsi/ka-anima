<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TasksModel extends APP_Model
{
	const TABLE = 'tasks';
	const TYPE_EMAIL = 'EMAIL';

	const TYPES = [
		self::TYPE_EMAIL
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add(array $params)
	{
		$this->db->insert(self::TABLE, $params);
		return $this->db->insert_id();
	}

	public function update(int $id, array $params)
	{
		$this->db->where('id', $id);
		$this->db->update(self::TABLE, $params);
		return true;
	}

	public function list(array $filter = [])
	{
		$binds = [];
		$sql_where = [];

		$sql = 'SELECT * FROM '.self::TABLE.' ';

		if(isset($filter['type']))
		{
			$sql_where[] .= 'type = ?';
			$binds[] = $filter['type'];
		}

		if(isset($filter['status']))
		{
			$sql_where[] .= 'status = '.(( (int) $filter['status'] > 0)?1:0);
		}

		if(count($sql_where) > 0)
		{
			$sql .= ' WHERE '.implode(' AND ', $sql_where);
		}

		$sql .= ' ORDER BY 
					priority DESC, date ASC';

		if(isset($filter['limit']))
		{
			$sql .= ' LIMIT '.((int) $filter['limit']);
			if(isset($filter['offset']))
				$sql .= ' OFFSET '.((int) $filter['offset']);	
		}

		$res = $this->db->query($sql, $binds);
		if($rows = $res->result_array())
		{
			return $rows;
		}

		return [];
	}

	public function setStatus(int $id, int $value)
	{
		$params = [
			'status' => ($value > 0)?1:0
		];
		
		return $this->update($id, $params);
	}
}