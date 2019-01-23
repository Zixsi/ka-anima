<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FaqModel extends APP_Model
{
	const TABLE = 'faq';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			if($this->db->insert(self::TABLE, $data))
			{
				return $this->db->insert_id();
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function update($id, $data = [])
	{
		try
		{
			$this->db->where('id', $id);
			if($this->db->update(self::TABLE, $data))
			{
				return true;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function list()
	{
		$sql = 'SELECT * FROM '.self::TABLE;
		if($res = $this->db->query($sql)->result_array())
		{
			return $res;
		}

		return false;
	}
}