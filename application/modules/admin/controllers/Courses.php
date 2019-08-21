<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];

		$data['items'] = $this->CoursesModel->list();

		$groups = $this->GroupsModel->getActiveGroups();
		$data['roadmap'] = $this->GroupsModel->makeRoadmap($groups);
		// die();
		unset($groups);

		$this->load->lview('courses/calendar', $data);
	}

	public function add()
	{
		$data = [];
		$data['structure']['price'] = CoursesHelper::PRICE_STRUCTURE;
		$data['teachers'] = $this->UserModel->listTeachers();
		
		if(cr_valid_key())
		{
			if($this->CoursesHelper->add($this->input->post(null, true)))
				header('Location: ../');
		}

		$data['csrf'] = cr_get_key();
		$data['error'] = $this->CoursesHelper->getLastError();

		$this->load->lview('courses/add', $data);
	}

	public function edit($id = 0)
	{
		$data = [];

		if(($data['item'] = $this->CoursesModel->getByID(intval($id))) == false)
			header('Location: ../');

		//debug($data['item']); die();
		$data['structure']['price'] = CoursesHelper::PRICE_STRUCTURE;
		$data['teachers'] = $this->UserModel->listTeachers();
		
		if(cr_valid_key())
		{
			if($this->CoursesHelper->update($id, $this->input->post(null, true)))
			{
				set_flash_message('success', 'Успешно');
				redirect('/admin/courses/edit/'.$id.'/', 'refresh');
			}
		}

		$data['csrf'] = cr_get_key();
		$data['error'] = $this->CoursesHelper->getLastError();

		$this->load->lview('courses/edit', $data);
	}
}
