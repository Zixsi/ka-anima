<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesModel extends APP_Model
{
	private const TABLE = 'lectures';

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

	public function List()
	{
		return false;
	}
}