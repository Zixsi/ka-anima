<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilesModel extends APP_Model
{
	private const TABLE = 'files';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			$data['file_path'] = get_rel_path($data['file_path']);
			$data['full_path'] = get_rel_path($data['full_path']);

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

	public function saveFileArray($data)
	{
		if(!empty($data))
		{
			if(isset($data['image_size_str']))
			{
				unset($data['image_size_str']);
			}

			$data['ts'] = date('Y-m-d 00:00:00');

			return $this->add($data); 
		}

		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE id = ?';
		if($row = $this->db->query($sql, [$id])->row_array())
		{
			return $row;
		}

		return false;
	}

	public function list($filter = [], $order = [], $select = [])
	{
		$select = count($select)?implode(', ', $select):'*';
		$this->db->select($select);
	
		count($filter)?$this->db->where($filter):$this->db->where('id >', 0);
		foreach($order as $key => $val)
		{
			$this->db->order_by($key, $val);
		}

		if($res = $this->db->get(self::TABLE))
		{
			return $res->result_array();
		}

		return false;
	}
}