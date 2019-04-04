<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingStreams extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel', 'main/VideoModel', 'main/StreamsModel']);
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->StreamsModel->list($this->Auth->userID(), ($_GET['filter'] ?? []));

		$this->load->lview('teaching_streams/index', $data);
	}

	public function add()
	{
		$data = [];

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			if($id = $this->StreamsModel->add($form_data))
			{
				header('Location: ../');
			}
		}

		$data['error'] = $this->StreamsModel->LAST_ERROR;
		$data['groups'] = $this->CoursesGroupsModel->getTeacherGroups($this->Auth->userID());
		$data['csrf'] = cr_get_key();

		$this->load->lview('teaching_streams/add', $data);
	}

	public function edit($id)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
		{
			header('Location: ../../');
		}

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			if($id = $this->StreamsModel->update($id, $form_data))
			{
				header('Location: ../../');
			}
		}

		$data['error'] = $this->StreamsModel->LAST_ERROR;
		$data['groups'] = $this->CoursesGroupsModel->getTeacherGroups($this->Auth->userID());
		$data['csrf'] = cr_get_key();

		$this->load->lview('teaching_streams/edit', $data);
	}

	public function item($id)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
		{
			header('Location: ../');
		}

		$this->load->library(['youtube']);

		$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
		//debug($data['item']);

		$this->load->lview('teaching_streams/item', $data);
	}
}
