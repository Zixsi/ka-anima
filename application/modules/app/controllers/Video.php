<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/VideoModel']);

		$this->load->layout = 'empty';
	}
	
	public function index($code)
	{
		$data = [];
		$data['user'] = $this->Auth->user();

		// TODO
		// Проверка юзера на доступ к видео
		// Если что то пошло не так отображать заглушку
		
		$data['video'] = false;
		$data['video_img'] = 'https://school.cgaim.ru/'.TEMPLATE_DIR.'/assets/player-preview.png';

		if($video = $this->VideoModel->byVideoCode($code))
		{
			$data['video'] = $video['video_url'];
		}

		$this->load->lview('video/index', $data);
	}
}
