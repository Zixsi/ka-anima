<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsModel extends APP_Model
{
	const TABLE = 'news';

	public function __construct()
	{
		parent::__construct();
	}

	public function getById($id)
	{
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE id = ?';
		if($row = $this->db->query($sql, [intval($id)])->row_array())
		{
			$this->prepareItem($row);
			return $row;
		}

		return false;
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function update(int $id, array $data = [])
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			return true;

		return false;
	}

	public function delete(int $id)
	{
		return $this->db->delete(self::TABLE, ['id' => $id]);
	}

	public function list($sort = 'asc')
	{
		$sql = 'SELECT * FROM '.self::TABLE.' ORDER BY id '.$sort;
		if($res = $this->db->query($sql)->result_array())
		{
			$this->prepareList($res);
			return $res;
		}

		return false;
	}

	public function prepareList(&$data)
	{
		if(is_array($data) && count($data))
		{
			foreach($data as &$item)
				$this->prepareItem($item);
		}
	}

	public function prepareItem(&$data)
	{
		$data['ts_timestamp'] = strtotime($data['ts'] ?? 0);
		$data['ts_formated'] = date(DATE_FORMAT_SHORT, $data['ts_timestamp']);
		if(empty($data['img']))
			$data['img'] = IMG_DEFAULT_300_200;
	}
}