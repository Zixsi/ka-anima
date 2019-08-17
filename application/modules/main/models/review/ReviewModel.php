<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewModel extends APP_Model
{
	private const TABLE = 'review';
	private const TABLE_VIDEO = 'video';
	private const TABLE_USERS = 'users';
	private const TABLE_LECTURES = 'lectures';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		$params = [
			'group_id' => ($data['group_id'] ?? 0),
			'lecture_id' => ($data['lecture_id'] ?? 0),
			'user' => ($data['user'] ?? 0),
			'video_url' => ($data['video_url'] ?? ''),
			'file_url' => ($data['file_url'] ?? ''),
			'text' => ($data['text'] ?? '')
		];

		if($this->db->insert(self::TABLE, $params))
			return $this->db->insert_id();

		return false;
	}

	public function delete($id)
	{
		return $this->db->delete(self::TABLE, ['id' => $id]);
	}

	public function getByID($id)
	{
		$sql = 'SELECT 
					r.*, l.name as lecture_name, rv.video_code as video_code 
				FROM 
					'.self::TABLE.' as r 
				LEFT JOIN 
					'.self::TABLE_LECTURES.' as l ON(r.lecture_id = l.id) 
				LEFT JOIN 
					'.self::TABLE_VIDEO.' as rv ON(r.id = rv.source_id AND rv.source_type = \'review\') 
				WHERE 
					r.id = ?';
		if($res = $this->db->query($sql, [$id]))
		{
			if($res = $res->row_array())
				return $res;
		}

		return false;
	}

	// По лекции группы
	public function getByLecture($group_id, $id)
	{
		$sql = 'SELECT 
					r.*, CONCAT_WS(\' \', u.name, u.lastname) as user_name
				FROM 
					'.self::TABLE.' as r 
				LEFT JOIN  
					'.self::TABLE_USERS.' as u ON(r.user = u.id)   
				WHERE 
					r.group_id = ? AND 
					r.lecture_id = ? 
				ORDER BY 
					r.id ASC';

		if($res = $this->db->query($sql, [$group_id, $id])->result_array())
		{
			return $res;
		}

		return false;
	}

	// Ревью группы
	public function getByGroup($group_id, $filter = false)
	{
		$bind = [$group_id];
		$sql = 'SELECT 
					r.id, r.group_id, r.lecture_id, r.user, r.text, r.score, r.ts, r.video_url, r.file_url, rv.video_code as video_code, CONCAT_WS(\' \', u.name, u.lastname) as user_name, l.name    
				FROM 
					'.self::TABLE.' as r  
				LEFT JOIN 
					'.self::TABLE_VIDEO.' as rv ON(r.id = rv.source_id AND rv.source_type = \'review\') 
				LEFT JOIN
					'.self::TABLE_USERS.' as u ON(r.user = u.id) 
				LEFT JOIN
					'.self::TABLE_LECTURES.' as l ON(r.lecture_id = l.id) 
				WHERE 
					r.group_id = ? ';

		if(is_array($filter))
		{
			if(($filter['lecture'] ?? 0) > 0)
			{
				$sql .= ' AND r.lecture_id = ? ';
				$bind[] = (int) $filter['lecture'];
			}

			if(($filter['user'] ?? 0) > 0)
			{
				$sql .= ' AND r.user = ? ';
				$bind[] = (int) $filter['user'];
			}
		}

		$sql .= 'ORDER BY r.id DESC';

		if($res = $this->db->query($sql, $bind)->result_array())
		{
			foreach($res as &$val)
			{
				$val['url'] = '/video/'.$val['video_code'].'/';
			}

			return $res;
		}

		return [];
	}

	public function notViewedItems(int $user, int $group)
	{	
		$result = [];

		try
		{
			$sql = 'SELECT id FROM '.self::TABLE.' WHERE group_id = ? AND user = ? AND item_is_viewed = 0';
			if($res = $this->db->query($sql, [intval($group), intval($user)])->result_array())
			{
				foreach($res as $val)
				{
					$result[] = $val['id'];
				}
			}
		}
		catch(Exception $e)
		{
			// 
		}

		return $result;
	}

	public function setViewedStatus(int $id, $status)
	{
		$params = [
			'item_is_viewed	' => ($status)?1:0
		];
		
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $params))
			return true;

		return false;
	}
}