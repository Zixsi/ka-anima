<?php

class APP_Model extends CI_Model
{
	// protected $table = '';
	// protected $primaryKey = 'id';
	// protected $allowedFields = [];

	public $last_error = null;
	private $last_error_code = 0;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function setLastException($e)
	{
		$this->last_error = (string) $e->getMessage();
		$this->last_error_code = (int) $e->getCode();
	}

	public function setLastError($text, $code = 0)
	{
		$this->last_error = (string) $text;
		$this->last_error_code = (int) $code;
	}

	public function getLastError()
	{
		return $this->last_error;
	}

	public function getLastErrorCode()
	{
		return $this->last_error_code;
	}

	// public function add($data = [])
	// {
	// 	$result = false;
	// 	if($this->db->insert($this->table, $data))
	// 		$result = $this->db->insert_id();

	// 	return $result;
	// }

	// public function update($id, $data = [])
	// {
	// 	$result = false;
	// 	$this->db->where($this->primaryKey, $id);
	// 	if($this->db->update($this->table, $data))
	// 		$result = true;

	// 	return $result;
	// }

	// public function delete($id)
	// {
	// 	$this->db->delete($this->table, [$this->primaryKey => $id]);
	// 	return true;
	// }

	// protected function getByField($key, $value)
	// {
	// 	return $this->db
	// 	->from($this->table)
	// 	->where($key, $value)
	// 	->get()->row_array();
	// }

	protected function query($sql, $binds = [])
	{
		if(isset($this->db_tools) === false)
			$this->load->library('main/db_tools');

		$this->db_tools->prepareBinds($sql, $binds);
		return $this->db->query($sql, $binds);
	}
}
