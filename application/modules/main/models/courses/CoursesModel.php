<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesModel extends APP_Model
{
	private const TABLE = 'courses';
	private const TABLE_LECTURES = 'lectures';
	private const TABLE_FILES = 'files';

	private $upload_config = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();
	
		return false;
	}

	public function update($id, $data = [])
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			return true;
			
		return false;
	}

	public function delete($id)
	{
		return false;
	}

	public function getByID($id)
	{
		$sql = 'SELECT 
					c.*, l_all.cnt as cnt_all, l_main.cnt as cnt_main, (l_all.cnt - l_main.cnt) as cnt_other, f.full_path as img_src   
				FROM 
					'.self::TABLE.' as c  
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l_all ON(l_all.course_id = c.id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = c.id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img)  
				WHERE c.id = ?';

		if($row = $this->db->query($sql, [$id])->row_array())
		{
			$this->prepareItem($row);
			return $row;
		}

		return false;
	}

	public function getByCode($code)
	{
		$sql = 'SELECT 
					c.*, l_all.cnt as cnt_all, l_main.cnt as cnt_main, (l_all.cnt - l_main.cnt) as cnt_other, f.full_path as img_src    
				FROM 
					'.self::TABLE.' as c  
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' GROUP BY course_id) as l_all ON(l_all.course_id = c.id) 
				LEFT JOIN 
					(SELECT course_id, count(id) as cnt FROM '.self::TABLE_LECTURES.' WHERE type = 0 GROUP BY course_id) as l_main ON(l_main.course_id = c.id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img)  
				WHERE c.code = ?';

		if($row = $this->db->query($sql, [$code])->row_array())
		{
			$this->prepareItem($row);
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
			return $res->result_array();

		return [];
	}

	// список автивных курсов
	public function listActive()
	{
		return $this->list(['active' => 1], ['id' => 'DESC']);
	}

	public function prepareItem(&$data)
	{
		if(is_array($data))
		{
			$data['free'] = false;
			$data['only_standart'] = (int) ($data['only_standart'] ?? 0);

			if(array_key_exists('img_src', $data))
				$data['img_src'] = empty($data['img_src'])?IMG_DEFAULT_16_9:'/'.$data['img_src'];

			if(array_key_exists('price', $data))
			{
				$data['price'] = json_decode($data['price'], true);
				$this->preparePrice($data['price']);

				if($data['only_standart'])
					$data['free'] = ((int) $data['price']['standart']['month'] === 0);
				else
				{
					foreach($data['price'] as $v)
					{
						if(((int) $v['month'] === 0))
						{
							$data['free'] = true;
							break;
						}
					}
				}
			}
		}
	}

	public function preparePrice(&$data)
	{
		if(is_array($data))
		{
			foreach($data as &$val)
			{
				foreach($val as $k => &$v)
				{
					if(in_array($k, ['month', 'full']))
						$v = number_format((float) $v, 2, '.', '');
				}
			}
		}
	}
}