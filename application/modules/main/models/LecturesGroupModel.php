<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesGroupModel extends APP_Model
{
	private const TABLE = 'lectures';
	private const TABLE_LECTURES_GROUPS = 'lectures_groups';
	private const TABLE_COURSES_GROUPS = 'courses_groups';

	public function __construct()
	{
		parent::__construct();
	}

	// Список всех лекций группы
	public function listForGroup($id)
	{
		$sql = 'SELECT 
					l.id, l.name, IF(lg.ts < ?, 1, 0) as active, lg.ts, l.type 
				FROM 
					'.self::TABLE_COURSES_GROUPS.' as cg 
				LEFT JOIN 
					'.self::TABLE_LECTURES_GROUPS.' as lg ON(lg.group_id = cg.id) 
				LEFT JOIN 
					'.self::TABLE.' as l ON(l.id = lg.lecture_id) 
				WHERE 
					cg.id = ?
				ORDER BY
					l.type DESC, 
					l.sort ASC, 
					l.id ASC';
					
		$res = $this->db->query($sql, [date('Y-m-d H:i:s'), $id]);
		if($res = $res->result_array())
		{
			return $res;
		}

		return false;
	}
}