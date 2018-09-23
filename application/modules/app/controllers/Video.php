<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/LecturesModel']);

		$this->load->layout = 'empty';
	}
	
	public function index($id = null, $type = 1)
	{
		$data = [];
		$data['user'] = $this->Auth->user();
		$data['video'] = $this->LecturesModel->getLectureVideo($id);

		$this->load->lview('video/index', $data);
	}
}
