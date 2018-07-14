<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesModel extends APP_Model
{
	private const TABLE = 'courses';

	public function __construct()
	{
		parent::__construct();
	}

	public function Add($data = [])
	{
		$this->CheckFields($data);

		if($this->db->insert(self::TABLE, $data))
		{
			return $this->db->insert_id();
		}

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

	public function List()
	{
		return false;
	}

	private function CheckFields($data = [])
	{
		
		
		return true;
	}
}