<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FaqModel extends APP_Model
{
	const TABLE = 'faq';
	const TABLE_SECTIONS = 'faq_sections';

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

	public function list()
	{
		$sql = 'SELECT 
					f.* , s.name as section_name 
				FROM 
					'.self::TABLE.' as f  
				LEFT JOIN 
					'.self::TABLE_SECTIONS.' as s ON(s.id = f.section) 
				ORDER BY id';
		if($res = $this->db->query($sql)->result_array())
			return $res;

		return false;
	}

	public function getSectionById($id)
	{
		$sql = 'SELECT * FROM '.self::TABLE_SECTIONS.' WHERE id = ?';
		if($row = $this->db->query($sql, [(int) $id])->row_array())
			return $row;

		return false;
	}

	public function addSection($data = [])
	{
		if($this->db->insert(self::TABLE_SECTIONS, $data))
			return $this->db->insert_id();

		return false;
	}

	public function updateSection(int $id, array $data = [])
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE_SECTIONS, $data))
			return true;

		return false;
	}

	public function deleteSection(int $id)
	{
		return $this->db->delete(self::TABLE_SECTIONS, ['id' => $id]);
	}

	public function listSections()
	{
		$sql = 'SELECT * FROM '.self::TABLE_SECTIONS;
		if($res = $this->db->query($sql)->result_array())
		{
			$this->prepareListSections($res);
			return $res;
		}

		return [];
	}

	private function prepareListSections(&$data)
	{
		$result = [];
		if(is_array($data))
		{
			foreach($data as $val)
				$result[$val['id']] = $val;
		}

		$data = $result;
	}
}