<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Streams extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->StreamsModel->list();

		$this->load->lview('streams/index', $data);
	}

	public function item($id = 0)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
			header('Location: ../');

		$this->load->library(['youtube']);
		$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);

		$this->load->lview('streams/item', $data);
	}

	public function edit($id = 0)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
			header('Location: ../../');

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			if($id = $this->StreamsModel->update($id, $form_data))
				header('Location: ../../');
		}

		$data['error'] = $this->StreamsModel->LAST_ERROR;
		// $data['groups'] = $this->GroupsModel->getTeacherGroups($data['item']['teacher']);
		$data['csrf'] = cr_get_key();

		$this->load->lview('streams/edit', $data);
	}
}
