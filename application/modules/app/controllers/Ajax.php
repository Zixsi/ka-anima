<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// 
	}

	public function courseLectures()
	{
		$data = [];
		$data['items'] =  [];

		$id = intval($this->input->post('id', true));
		//$id = intval($this->input->get('id', true));
		if($id > 0)
		{
			$this->load->model(['main/LecturesModel']);
			if($res = $this->LecturesModel->getAvailableForGroup($id))
			{
				//debug($res);
				foreach($res as $val)
				{
					$video = $this->LecturesModel->lectureOrignVideo($val['id']);
					$data['items'][] = [
						'id' => $val['id'],
						'name' => $val['name'],
						'video' => isset($video['mp4'])?$video['mp4']:'',
						'task' => $val['task']
					];
				}
			}
		}

		echo json_encode($data);
	}
}
