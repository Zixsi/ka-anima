<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VideoModel extends APP_Model
{
	const TABLE = 'video';
	const VIDEO_TYPES = [
		'lecture', // лекция
		'review', // ревью 
	];

	public function __construct()
	{
		parent::__construct();
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
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE source_id = ? AND source_type = ?';
		if($res = $this->db->query($sql, $bind)->row_array())
			return $res;

		return false;
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

			log_message('error', $url);
			$this->load->library(['ydvideo']);
			if($video = $this->ydvideo->getVideo($url))
			{
				log_message('error', json_encode($video));
				$this->set($source_id, $url, $video['video'], $type, 'mp4');
			}
			else
				throw new Exception('empty video answer', 1);

			return false;
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
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

				log_message('error', json_encode($data));

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
			log_message('error', $e->getMessage());
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
}