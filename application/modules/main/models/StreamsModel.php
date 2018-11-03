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
		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts, c.author   
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

	public function list(int $author = 0)
	{
		$bind = [date('Y-m-d H:i:s', time() - (3600 * 24 * 7))];

		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					s.ts >= ? ';

		if($author > 0)
		{
			$bind[] = $author;
			$sql .= ' AND c.author = ? ';
		}

		$sql .= ' ORDER BY 
					s.ts ASC';

		if($res = $this->db->query($sql, $bind)->result_array())
		{

			$ts = time() + (3600 * 12);
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
			}

			return $res;
		}

		return false;
	}

	public function byGroup(int $id)
	{
		$bind = [$id, date('Y-m-d H:i:s', time() - (3600 * 4))];

		$sql = 'SELECT 
					s.*, c.name as course_name, g.ts as course_ts, c.author   
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

	public function getNextForAuthor(int $author)
	{
		$bind = [$author, date('Y-m-d H:i:s')];

		$sql = 'SELECT 
					s.id, s.ts  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_GROUPS.' as g ON(s.group_id = g.id) 
				LEFT JOIN 
					'.self::TABLE_COURSE.' as c ON(g.course_id = c.id) 
				WHERE 
					c.author = ? AND s.ts > ? 
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