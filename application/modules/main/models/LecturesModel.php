<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesModel extends APP_Model
{
	private const TABLE = 'lectures';
	private const TABLE_LECTURES_GROUPS = 'lectures_groups';
	private const TABLE_LECTURES_VIDEO = 'video';
	private const TABLE_LECTURES_HOMEWORK = 'lectures_homework';
	private const TABLE_COURSES_GROUPS = 'courses_groups';
	private const TABLE_USERS = 'users';
	private const TABLE_FILES = 'files';
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

	// Список всех лекций курса
	public function listForCourse($id)
	{
		$sql = 'SELECT 
					id, name 
				FROM 
					'.self::TABLE.' 
				WHERE 
					course_id = ? AND 
					active = 1 
				ORDER BY 
					sort ASC, 
					id ASC';
		$res = $this->db->query($sql, [$id]);
		if($res = $res->result_array())
		{
			return $res;
		}

		return false;
	}

	// Список всех лекций группы
	public function listForGroup($id)
	{
		$sql = 'SELECT 
					l.id, l.name, 
				FROM 
					'.self::TABLE.' as l
				LEFT JOIN 
					'.self::TABLE_LECTURES_GROUPS.' as lg ON()
				WHERE 
					course_id = ? AND 
					active = 1 
				ORDER BY 
					sort ASC, 
					id ASC';
		$res = $this->db->query($sql, [$id]);
		if($res = $res->result_array())
		{
			return $res;
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
					l.* , lg.ts 
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

	// Получить кол-во лекций для курса
	public function getCntByCourse($id)
	{
		$sql = 'SELECT 
					l.course_id, count(l.id) as cnt_all, l_main.cnt as cnt_main, (count(l.id) - l_main.cnt) as cnt_other   
				FROM 
					'.self::TABLE.' as l
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = l.course_id) 
				WHERE 
					l.course_id = ?  
				GROUP BY l.course_id';

		if($res = $this->db->query($sql, [$id])->row_array())
		{
			return $res;
		}

		return false;
	}

	// добавить лекцию к группе
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

	public function addLectureToGroupTs($group_id, $lecture_id, $date)
	{
		try
		{
			$group_id = intval($group_id);
			$lecture_id = intval($lecture_id);

			$data = [
				'group_id' => $group_id,
				'lecture_id' => $lecture_id,
				'ts' => $date 
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

	// добавить файл д/з к лекции
	public function addHomeWork($group_id, $lecture_id, $user_id, $file_id, $comment = '')
	{
		try
		{
			$data = [
				'user' => $user_id,
				'group_id' => $group_id,
				'lecture_id' => $lecture_id,
				'file' => $file_id,
				'type' => 0, // д/з
				'comment' => $comment
			];

			if($this->db->insert(self::TABLE_LECTURES_HOMEWORK, $data))
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

	// Список загруженных юзером файлов в лекции
	public function getUserHomeWork($group_id, $lecture_id, $user)
	{
		try
		{
			$sql = 'SELECT 
						hw.*, f.orig_name as name
					FROM 
						'.self::TABLE_LECTURES_HOMEWORK.' as hw 
					LEFT JOIN 
						'.self::TABLE_FILES.' as f  ON(f.id = hw.file)  
					WHERE 
						hw.user = ? AND hw.group_id = ? AND hw.lecture_id = ? AND hw.type = 0
					ORDER BY 
						hw.id DESC';

			if($res = $this->db->query($sql, [$user, $group_id, $lecture_id])->result_array())
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

	// Список загруженных юзером файлов в лекции для преподователя
	public function getTeacherHomeWork($group_id, $lecture_id)
	{
		try
		{
			$sql = 'SELECT 
						hw.*, f.orig_name as name, u.email as user_name 
					FROM 
						'.self::TABLE_LECTURES_HOMEWORK.' as hw 
					LEFT JOIN 
						'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(u.id = hw.user)  
					WHERE 
						hw.group_id = ? AND hw.lecture_id = ? 
					ORDER BY 
						hw.id DESC';

			if($res = $this->db->query($sql, [$group_id, $lecture_id])->result_array())
			{
				foreach($res as &$val)
				{
					if($val['type'] == 1)
					{
						$val['name'] = 'Review '.date('Y-m-d', strtotime($val['ts']));
					}
				}

				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	// добавить ревью к лекции
	public function addReview($group_id, $lecture_id, $user_id, $url)
	{
		try
		{
			$data = [
				'user' => $user_id,
				'group_id' => $group_id,
				'lecture_id' => $lecture_id,
				'video_url' => $url,
				'type' => 1 // ревью
			];

			if($this->db->insert(self::TABLE_LECTURES_HOMEWORK, $data))
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