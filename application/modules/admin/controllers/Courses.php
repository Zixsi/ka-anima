<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel', 'main/GroupsModel', 'main/courses/CoursesHelper']);
	}

	public function index()
	{
		$data = [];

		$data['items'] = $this->CoursesModel->list();

		$last_date = 'now';
		$groups = $this->GroupsModel->getActiveGroups();
		$data['roadmap'] = $this->GroupsModel->makeRoadmap($groups);

		//debug($data); die();

		$this->load->lview('courses/calendar', $data);
	}

	public function add()
	{
		$data = [];
		$data['course_types'] = $this->CoursesModel::TYPES;
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
		$data['course_types'] = $this->CoursesModel::TYPES;
		$data['teachers'] = $this->UserModel->listTeachers();
		
		if(($data['item'] = $this->CoursesModel->getByID(intval($id))) == false)
			header('Location: ../');

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
