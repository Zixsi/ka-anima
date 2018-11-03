<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VideoModel extends APP_Model
{
	private const TABLE = 'video';
	private const VIDEO_TYPES = [
		'lecture', // лекция
		'review', // ревью 
	];

	public function __construct()
	{
		parent::__construct();
	}

	// список видео по ресурсу
	public function bySource($id, $type = 'lecture', $format = null)
	{
		try
		{
			$bind = [$id, $type];
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE source_id = ? AND source_type = ?';
			
			if($format)
			{
				$bind[] = $format;
				$sql .= ' AND type = ?';
			}

			if($res = $this->db->query($sql, $bind)->result_array())
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

	public function prepareAndSet($source_id, $type = 'lecture', $url = '')
	{
		try
		{
			if(in_array(strtolower($type), self::VIDEO_TYPES) == false)
			{
				throw new Exception('Invalid video type', 1);
			}

			$this->load->library(['youtube']);

			$code  = $this->youtube->extractVideoId($url);
			$video_data = $this->youtube->prepareData($code);
			$video_array = $this->youtube->getVideoFromData($video_data);
			foreach($video_array as $format => $val)
			{
				$video = current($val);
				$this->set($source_id, $code, $video, $type, $format);
			}

			if($img = $this->youtube->getImgFromData($video_data))
			{
				$this->set($source_id, $code, $img, $type, 'img');
			}

			return true;
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
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
				{
					return true;
				}
			}
			else
			{
				$data = [
					'source_id' => $source_id,
					'source_type' => $type,
					'code' => $code,
					'video_url' => $url,
					'type' => $format,
					'ts' => date('Y-m-d H:i:s')
				];

				if($this->db->insert(self::TABLE, $data))
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


	// список видео для обновления
	public function forUpdate($time = 300)
	{
		try
		{
			$expire = date('Y-m-d H:i:s', time() - $time);
			$sql = 'SELECT source_id, source_type, code, type FROM '.self::TABLE.' WHERE ts IS NULL OR ts < ?';
			if($res = $this->db->query($sql, [$expire])->result_array())
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
}