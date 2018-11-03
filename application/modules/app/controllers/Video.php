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
	
	public function index($type, $id)
	{
		$data = [];
		$data['user'] = $this->Auth->user();
		
		$data['video'] = false;
		$data['video_img'] = TEMPLATE_DIR.'/admin_1/assets/img/video-preview-bg.jpg';

		$video = $this->VideoModel->bySource($id, $type);
		foreach($video as $val)
		{
			if($val['type'] == 'img')
			{
				//$data['video_img'] = $val['video_url'];
			}
			else
			{
				$data['video'][] = $val;
			}
		}
		unset($video);
		//debug($data); die();

		$this->load->lview('video/index', $data);
	}
}
