<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->layout = 'empty';

		if(!$this->Auth->isActive())
			die();
	}
	
	public function index($code = null)
	{
		$data = [];
		$data['user'] = $this->Auth->user();
		$data['code'] = md5($data['user']['id']);
		
		$data['video'] = '0000000000000000000000000';
		$data['video_img'] = 'https://school.cgaim.ru/'.TEMPLATE_DIR.'/assets/player-preview.jpg';

		$data['mark'] = $code;
		$video = $this->VideoHelper->getDetailInfo($code);
		$data['video'] = $video['video_url'];

		if($video['source_type'] === 'review')
		{
			$data['mark'] = '';
			if($item = $this->ReviewModel->getByID($video['source_id']);)
			{
				if((int) $data['user']['id'] !== (int) $item['user'])
					$this->load->lview('video/index_404');
			}
		}
		else
		{
			$data['mark'] = $code;
			if(isset($video['course']['code']))
				$data['mark'] = $video['course']['code'].'#'.$data['user']['id'];

			$courseId = (int) ($video['course']['id'] ?? 0);
			$lectureId = (int) ($video['source']['id'] ?? 0);

			// Проверка юзера на доступ к видео
			// Если что то пошло не так отображать заглушку
			if($this->VideoHelper->checkVideoAccess($data['user']['id'], $courseId, $lectureId) === false)
				$this->load->lview('video/index_404');
		}

		$this->load->lview('video/index', $data);
	}
}
