<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StreamsModel extends APP_Model
{
	private const TABLE = 'streams';
	private const TABLE_GROUPS = 'courses_groups';
	private const TABLE_COURSE = 'courses';
	private const TABLE_VIDEO = 'video';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			$params = [
				'group_id' => $data['group_id'] ?? 0,
				'url' => $data['url'] ?? '',
				'name' => $data['name'] ?? '',
				'description' => $data['description'] ?? '',
				'ts' => $data['ts'] ?? ''
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($params);
			if($this->form_validation->run('stream_add') == false)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			$params['ts'] = date('Y-m-d H:i:s', strtotime($params['ts']));
			if($this->db->insert(self::TABLE, $params))
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
			if(($item = $this->getByID($id)) == false)
			{
				throw new Exception("item not found", 1);
			}
			
			$params = [
				'group_id' => $data['group_id'] ?? $item['group_id'],
				'url' => $data['url'] ?? $item['url'],
				'name' => $data['name'] ?? $item['name'],
				'description' => $data['description'] ?? $item['description'],
				'ts' => $data['ts'] ?? $item['ts']
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($params);
			if($this->form_validation->run('stream_add') == false)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			$params['ts'] = date('Y-m-d H:i:s', strtotime($params['ts']));
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
		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts, g.teacher   
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					s.id = ?
				ORDER BY 
					s.ts ASC';
		if($res = $this->db->query($sql, [$id])->row_array())
		{
			return $res;
		}

		return false;
	}

	public function list(int $teacher = 0, array $filter = [])
	{
		$bind = [];

		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts, g.ts_end as course_ts_end  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					1 ';

		if(isset($filter['active']) && intval($filter['active']) !== 0)
		{
			$bind[] = date('Y-m-d H:i:s');
			$sql .= ($filter['active'] > 0)?' AND s.ts > ? ':' AND s.ts <= ? ';
		}

		if($teacher > 0)
		{
			$bind[] = $teacher;
			$sql .= ' AND g.teacher = ? ';
		}

		$sql .= ' ORDER BY 
					s.ts DESC';

		if($res = $this->db->query($sql, $bind)->result_array())
		{

			$ts = time();
			foreach($res as &$val)
			{
				$val['status'] = 1;
				$ts_start = strtotime($val['ts']);
				$ts_end = $ts_start + (3600 * 4);

				if($ts > $ts_end)
				{
					$val['status'] = -1;
				}
				elseif($ts >= $ts_start && $ts < $ts_end)
				{
					$val['status'] = 0;
				}
				elseif(date('Y-m-d') === date('Y-m-d', $ts_start))
				{
					$val['status'] = 2;
				}
			}
			//debug($res); die();

			return $res;
		}

		return false;
	}

	public function byGroup(int $id)
	{
		$bind = [$id, date('Y-m-d H:i:s', time() - (3600 * 4))];

		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts, g.teacher   
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					s.group_id = ? AND s.ts > ? 
				ORDER BY 
					s.ts ASC';

		if($res = $this->db->query($sql, $bind)->row_array())
		{
			return $res;
		}

		return false;
	}

	public function byGroupList(int $id)
	{
		$bind = [$id];

		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					s.group_id = ? 
				ORDER BY 
					s.ts ASC';

		if($res = $this->db->query($sql, $bind)->result_array())
		{

			$ts = time();
			foreach($res as &$val)
			{
				$val['status'] = 1;
				$ts_start = strtotime($val['ts']);
				$ts_end = $ts_start + (3600 * 4);

				if($ts > $ts_end)
				{
					$val['status'] = -1;
				}
				elseif($ts >= $ts_start && $ts < $ts_end)
				{
					$val['status'] = 0;
				}
				elseif(date('Y-m-d') === date('Y-m-d', $ts_start))
				{
					$val['status'] = 2;
				}
			}

			return $res;
		}

		return false;
	}

	public function getNextForAuthor(int $teacher)
	{
		$bind = [$teacher, date('Y-m-d H:i:s')];

		$sql = 'SELECT 
					s.id, s.ts  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					g.teacher = ? AND s.ts > ? 
				ORDER BY 
					s.ts ASC';

		if($res = $this->db->query($sql, $bind)->row_array())
		{
			$res['ts_timestamp'] = strtotime($res['ts']);
			return $res;
		}

		return false;
	}
}