<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesModel extends APP_Model
{
	private const TABLE = 'lectures';
	private const TABLE_LECTURES_GROUPS = 'lectures_groups';
	private const TABLE_LECTURES_VIDEO = 'lectures_video';
	private const TABLE_FIELDS = ['active', 'name', 'description', 'task', 'type', 'course_id', 'video', 'modify', 'sort'];
	private const LECTURE_VIDEO_TYPES = [
		'lecture', // лекция
		'review', // ревью 
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			$this->_CheckFields($data);

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
			$this->_CheckFields($data);

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

	public function getByID($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	public function getByCourse($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE course_id = ? ORDER BY sort ASC, id ASC', [$id]);
		if($res = $res->result_array())
		{
			return $res;
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

	// Список доcтупных для добавления в группу
	public function listAvailableForAddToGroup($course_id, $offset = 0)
	{
		$sql = 'SELECT 
					id, type 
				FROM 
					'.self::TABLE.' 
				WHERE 
					course_id = ? AND 
					active = 1 
				ORDER BY 
					sort ASC, 
					id ASC 
				LIMIT ?, 5';
		$res = $this->db->query($sql, [$course_id, $offset]);
		if($res = $res->result_array())
		{
			$result = [];

			foreach($res as $val)
			{
				$val['id'] = intval($val['id']);
				$val['type'] = intval($val['type']);
				$result[] = $val;
			}

			return $result;
		}

		return false;
	}

	// Список доcтупных для группы
	public function getAvailableForGroup($group_id)
	{
		$sql = 'SELECT 
					l.* 
				FROM 
					'.self::TABLE_LECTURES_GROUPS.' as lg 
				LEFT JOIN
					'.self::TABLE.' as l ON(l.id = lg.lecture_id) 
				WHERE 
					lg.group_id = ?  
				ORDER BY 
					l.sort ASC, 
					l.id ASC';
		$res = $this->db->query($sql, [$group_id]);
		if($res = $res->result_array())
		{
			return $res;
		}

		return false;
	}

	public function addLectureToGroup($group_id, $lecture_id)
	{
		try
		{
			$group_id = intval($group_id);
			$lecture_id = intval($lecture_id);

			$data = [
				'group_id' => $group_id,
				'lecture_id' => $lecture_id,
				'ts' => date('Y-m-d 00:00:00') 
			];

			if($this->db->insert(self::TABLE_LECTURES_GROUPS, $data))
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

	public function getLectureVideo($id, $type = 'lecture')
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE_LECTURES_VIDEO.' WHERE source_id = ? AND source_type = 1 ORDER BY source_id ASC';
			$res = $this->db->query($sql, [$id, $type]);
			if($res = $res->result_array())
			{
				$result = [];

				return $result;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function updateLectureVideo($id, $url, $format = '', $type = 'lecture')
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE_LECTURES_VIDEO.' WHERE source_id = ? AND source_type = ? AND type = ? ORDER BY source_id ASC';
			if($res = $this->db->query($sql, [$id, $type, $format])->row_array())
			{
				$this->db->where(['source_id' => $id, 'source_type' => $type, 'type' => $format]);
				
				$data = [
					'video_url' => $url,
					'ts' => date('Y-m-d H:i:s')
				];

				if($this->db->update(self::TABLE_LECTURES_VIDEO, $data))
				{
					return true;
				}
			}
			else
			{
				$data = [
					'source_id' => $id,
					'source_type' => $type,
					'video_url' => $url,
					'type' => $format,
					'ts' => date('Y-m-d H:i:s')
				];

				if($this->db->insert(self::TABLE_LECTURES_VIDEO, $data))
				{
					return true;
				}
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function lectureOrignVideo($id, $type = 'lecture')
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE_LECTURES_VIDEO.' WHERE source_id = ? AND source_type = ?';
			if($res = $this->db->query($sql, [$id, $type])->result_array())
			{
				$result = [];
				foreach ($res as $val)
				{
					$result[$val['type']] = $val['video_url'];
				}
				
				return $result;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function listLecturesVideoForUpdate($time = 300)
	{
		try
		{
			$expire = date('Y-m-d H:i:s', time() - $time);
			$sql = 'SELECT 
						l.id, l.video, lv.type, lv.ts 
					FROM 
						'.self::TABLE.' as l 
					LEFT JOIN 
						'.self::TABLE_LECTURES_VIDEO.' as lv  ON(lv.source_id = l.id AND lv.source_type = \'lecture\')  
					WHERE 
						lv.ts IS NULL OR lv.ts < ? 
					ORDER BY 
						l.id ASC';

			$res = $this->db->query($sql, [$expire]);
			if($res = $res->result_array())
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

	private function _CheckFields(&$data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('lectures') == FALSE)
		{
			throw new Exception($this->form_validation->error_string(), 1);
		}

		foreach($data as $key => $val)
		{
			if(in_array($key, self::TABLE_FIELDS) == false)
			{
				unset($data[$key]);
			}
		}
		
		return true;
	}
}