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
		if($id > 0)
		{
			$this->load->model(['main/LecturesModel']);
			if($res = $this->LecturesModel->GetByCourse($id))
			{
				foreach($res as $val)
				{
					$data['items'][] = [
						'id' => $val['id'],
						'name' => $val['name'],
						'video' => isset($val['video'])?$val['video'].'?rel=0&amp;showinfo=0':''
					];
				}
			}
		}

		echo json_encode($data);
	}
}
