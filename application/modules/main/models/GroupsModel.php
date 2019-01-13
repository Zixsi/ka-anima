<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsModel extends APP_Model
{
	const TABLE_HOMEWORK = 'lectures_homework';
	const TABLE_FILES = 'files';
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getImageFiles($id)
	{
		try
		{
			$sql = 'SELECT 
						f.id, f.full_path as src 
					FROM 
						'.self::TABLE_HOMEWORK.' as hw 
					LEFT JOIN 
						'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
					WHERE 
						hw.group_id = ? AND 
						f.is_image = 1';

			if($res = $this->db->query($sql, [$id])->result_array())
			{
				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}
}