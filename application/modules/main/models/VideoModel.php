<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VideoModel extends APP_Model
{
	const TABLE = 'video';
	const VIDEO_TYPES = [
		'lecture', // лекция
		'review', // ревью 
		'workshop', // мастерская 
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();
	
		return false;
	}

	public function update($id, $data = [])
	{
		$result = false;
		unset($data['id']);
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			$result = true;

		return $result;
	}

	public function getItem($id)
	{
		return $this->getByField('id', $id);
	}

	public function getByField($key, $value)
	{
		return $this->db
		->from(self::TABLE)
		->where($key, $value)
		->get()->row_array();
	}

	public function delete(int $id)
	{
		$this->db->where('id', $id);
		$this->db->delete(self::TABLE);
			
		return true;
	}

	// удаление 
	public function remove($target, $type = 'lecture')
	{
		$this->db->where('source_id', $target);
		$this->db->where('source_type', $type);
		$this->db->delete(self::TABLE);
			
		return true;
	}

	// список видео по ресурсу
	public function bySource($id, $type = 'lecture')
	{
		$bind = [$id, $type];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE source_id = ? AND source_type = ? ORDER BY sort DESC, id ASC';
		if($res = $this->db->query($sql, $bind)->row_array())
			return $res;

		return false;
	}

	public function getList(array $params = [])
	{
		$result = [];
		$filter = $this->prepareFilter($params);
		$binds = $filter['binds'];

		$sql = 'SELECT * FROM '.self::TABLE;
		if(count($filter['where']))
			$sql .= ' WHERE '.implode(' AND ', $filter['where']);

		$sql .= ' ORDER BY sort ASC, id ASC';

		if($res = $this->query($sql, $binds)->result_array())
		{
			$result = $res;
		}

		return $result;
	}

	public function byVideoCode($code)
	{
		$bind = [$code];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE video_code = ?';
		if($res = $this->db->query($sql, $bind)->row_array())
			return $res;

		return false;
	}


	public function prepareAndSet($source_id, $type = 'lecture', $url = '')
	{
		try
		{
			if(in_array(strtolower($type), self::VIDEO_TYPES) == false)
				throw new Exception('Invalid video type', 1);

			$this->load->library(['ydvideo']);
			if($video = $this->ydvideo->getVideo($url))
				$this->set($source_id, $url, $video['video'], $type, 'mp4');
			else
				$this->set($source_id, $url, '', $type, 'mp4');

			return false;
		}
		catch(Exception $e)
		{
			// log_message('error', $e->getMessage());
		}

		return false;
	}

	// обновить / добавить ссылки на видео
	public function set($source_id, $code, $url, $type = 'lecture', $format = 'mp4')
	{
		try
		{
			if($this->bySource($source_id, $type, $format))
			{
				$this->db->where(['source_id' => $source_id, 'source_type' => $type, 'type' => $format]);
				
				$data = [
					'code' => $code,
					'video_url' => $url,
					'ts' => date('Y-m-d H:i:s')
				];

				if($this->db->update(self::TABLE, $data))
					return true;
			}
			else
			{
				$data = [
					'source_id' => $source_id,
					'source_type' => $type,
					'code' => $code,
					'video_code' => random_string('alnum', 25),
					'video_url' => $url,
					'type' => $format,
					'ts' => date('Y-m-d H:i:s')
				];

				if($this->db->insert(self::TABLE, $data))
					return true;
			}
		}
		catch(Exception $e)
		{
			// log_message('error', $e->getMessage());
		}

		return false;
	}


	// список видео для обновления
	public function forUpdate($time = 300)
	{
		$expire = date('Y-m-d H:i:s', time() - $time);
		$sql = 'SELECT source_id, source_type, code, type FROM '.self::TABLE.' WHERE ts IS NULL OR ts < ?';
		if($res = $this->db->query($sql, [$expire])->result_array())
			return $res;

		return false;
	}

	public function makeCode()
	{
		return md5(random_string('alnum', 8) . microtime(true));
	}

	private function prepareFilter(array $params)
	{
		$result = [
			'where' => [],
			'binds' => [],
			'limit' => 0,
			'offset' => 0
		];

		if(isset($params['source_id']) && (int) $params['source_id'] > 0)
		{
			$result['binds'][':source_id'] = (int) $params['source_id'];
			$result['where'][] = 'source_id = :source_id';
		}

		if(isset($params['source_type']) && empty($params['source_type']) === false)
		{
			$result['binds'][':source_type'] = $params['source_type'];
			$result['where'][] = 'source_type = :source_type';
		}

		return $result;
	}
}