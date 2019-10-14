<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupportModel extends APP_Model
{
	const TABLE_TICKET = 'support_ticket';
	const TABLE_MESSAGE = 'support_message';

	const PENDING = 'PENDING';
	const DECLINED = 'DECLINED';
	const COMPLETED = 'COMPLETED';

	const LIST = [
		self::PENDING,
		self::DECLINED,
		self::COMPLETED
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add(array $params)
	{
		$this->db->insert(self::TABLE_TICKET, $params);
		return $this->db->insert_id();
	}

	public function update(int $id, array $params)
	{
		$this->db->where('id', $id);
		$this->db->update(self::TABLE_TICKET, $params);
		return true;
	}

	public function getByCode($code)
	{
		$result = null;
		$binds = [$code];

		$sql = 'SELECT * FROM '.self::TABLE_TICKET.' WHERE code = ?';
		$res = $this->db->query($sql, $binds);
		if($row = $res->row_array())
		{
			$this->prerpareItem($row);
			$result = $row;
		}	

		return $result;
	}

	public function getList(array $filter = [])
	{
		$result = [];
		$binds = [];
		$sqlWhere = [];

		$sql = 'SELECT 
					t.*, m.count as message_count 
				FROM 
					'.self::TABLE_TICKET.' as t 
				LEFT JOIN 
					(SELECT target, count(*) as count FROM '.self::TABLE_MESSAGE.' GROUP BY target) as m ON(m.target = t.id) 
				';

		if(isset($filter['user']))
		{
			$sqlWhere[] .= 't.user = '.$filter['user'];
		}

		if(isset($filter['status']))
		{
			if(is_array($filter['status']))
				$sqlWhere[] .= 't.status IN(\''.implode('\',\'', $filter['status']).'\')';
			else
				$sqlWhere[] .= 't.status = \''.$filter['status'].'\'';
		}

		if(count($sqlWhere) > 0)
			$sql .= ' WHERE '.implode(' AND ', $sqlWhere);

		$sql .= ' ORDER BY t.id DESC';

		if(isset($filter['limit']))
		{
			$sql .= ' LIMIT '.((int) $filter['limit']);
			if(isset($filter['offset']))
				$sql .= ' OFFSET '.((int) $filter['offset']);	
		}

		$res = $this->db->query($sql, $binds);
		if($rows = $res->result_array())
		{
			$result = $rows;
		}

		return $result;
	}

	private function prerpareItem(&$data)
	{
		$data['date_timestamp']  = strtotime($data['date']);
		$data['date_f']  = date(DATE_FORMAT_FULL, $data['date_timestamp']);
	}

	// =============================================================== //

	public function addMessage(array $params)
	{
		$this->db->insert(self::TABLE_MESSAGE, $params);
		return $this->db->insert_id();
	}

	public function getListMessage(array $filter = [])
	{
		$result = [];
		$binds = [];
		$sqlWhere = [];

		$sql = 'SELECT * FROM '.self::TABLE_MESSAGE.' ';

		if(isset($filter['target']))
		{
			$sqlWhere[] .= 'target = '.$filter['target'];
		}

		if(count($sqlWhere) > 0)
			$sql .= ' WHERE '.implode(' AND ', $sqlWhere);

		$sql .= ' ORDER BY id ASC';

		if(isset($filter['limit']))
		{
			$sql .= ' LIMIT '.((int) $filter['limit']);
			if(isset($filter['offset']))
				$sql .= ' OFFSET '.((int) $filter['offset']);	
		}

		$res = $this->db->query($sql, $binds);
		if($rows = $res->result_array())
		{
			$result = $rows;
			foreach($result as &$row)
			{
				$this->prerpareMessageItem($row);
			}
		}

		return $result;
	}

	private function prerpareMessageItem(&$data)
	{
		$data['date_timestamp']  = strtotime($data['date']);
		$data['date_f']  = date(DATE_FORMAT_FULL, $data['date_timestamp']);
	}
}