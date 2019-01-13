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
		$this->load->model(['main/VideoModel']);
	}

	public function add($data = [])
	{
		try
		{
			$params = [
				'group_id' => $data['group_id'] ?? 0,
				'lecture_id' => $data['lecture_id'] ?? 0,
				'user' => $data['user'] ?? 0,
				'video_url' => $data['video_url'] ?? '',
				'text' => $data['text'] ?? ''
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($params);
			if($this->form_validation->run('review_add') == false)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			if($this->db->insert(self::TABLE, $params))
			{
				$id = $this->db->insert_id();
				$this->VideoModel->prepareAndSet($id, 'review', $params['video_url']);

				return $id;
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
			$params = [
				'group_id' => $data['group_id'] ?? 0,
				'lecture_id' => $data['lecture_id'] ?? 0,
				'user' => $data['user'] ?? 0,
				'video_url' => $data['video_url'] ?? '',
				'text' => $data['text'] ?? ''
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($params);
			if($this->form_validation->run('review_add') == false)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			
			$this->db->where('id', $id);
			if($this->db->update(self::TABLE, $params))
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

	public function getByID($id)
	{
		$sql = 'SELECT r.*, l.name as lecture_name FROM '.self::TABLE.' as r LEFT JOIN '.self::TABLE_LECTURES.' as l ON(r.lecture_id = l.id) WHERE r.id = ?';
		if($res = $this->db->query($sql, [$id])->row_array())
		{
			return $res;
		}

		return false;
	}

	// По лекции группы
	public function getByLecture($group_id, $id)
	{
		$sql = 'SELECT 
					r.*, u.email as user_name 
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
					r.id, r.group_id, r.lecture_id, r.user, r.text, r.score, r.ts, rv.video_url as src, u.email as user_name, l.name    
				FROM 
					'.self::TABLE.' as r  
				LEFT JOIN 
					'.self::TABLE_VIDEO.' as rv ON(r.id = rv.source_id AND rv.source_type = \'review\' AND rv.type = \'img\') 
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
				$bind[] = intval($filter['lecture']);
			}

			if(($filter['user'] ?? 0) > 0)
			{
				$sql .= ' AND r.user = ? ';
				$bind[] = intval($filter['user']);
			}
		}

		$sql .= 'ORDER BY r.id DESC';

		if($res = $this->db->query($sql, $bind)->result_array())
		{
			foreach($res as &$val)
			{
				$val['url'] = '/video/review/'.$val['id'];
			}

			return $res;
		}

		return false;
	}
}