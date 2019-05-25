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
			return $row;

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
			return $res;

		return false;
	}
}