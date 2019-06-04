<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends APP_Controller
{
	public function __construct()
	{

	}

	public function index()
	{
		// 
	}

	// Добавляем курс в список доступных для подписки
	// php index.php cli course createGroups
	public function createGroups()
	{
		// empty
	}

	// Обновление ссылок на видео
	// php index.php cli course updateVideoUrl
	public function updateVideoUrl()
	{
		echo 'Update Video Url => Start'.PHP_EOL;
		$this->load->library(['ydvideo']);

		if($items = $this->VideoModel->forUpdate(3600))
		{
			echo 'Count video => '.count($items).PHP_EOL;
			foreach($items as $item)
			{
				$video = $this->ydvideo->getVideo($item['code']);
				$this->VideoModel->set($item['source_id'], $item['code'], $video['video'], $item['source_type'], $item['type']);
			}
		}
		echo 'Update Video Url => End'.PHP_EOL;
	}

	// php index.php cli course addLectureVideo
	public function addLectureVideo()
	{
		// empty
	}
}
