<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel']);
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->CoursesModel->list();
		$data['roadmap_months'] = roadmap_months('now'); 
		
		//debug($data); die();
		$this->load->lview('courses/calendar', $data);
	}

	public function add()
	{
		$data = [];
		$data['course_types'] = $this->CoursesModel::TYPES;
		
		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			$form_data['author'] = $this->Auth->userID();

			if($id = $this->CoursesModel->add($form_data))
			{
				header('Location: ../');
			}
		}

		$data['csrf'] = cr_get_key();
		$data['error'] = $this->CoursesModel->LAST_ERROR;

		$this->load->lview('courses/add', $data);
	}

	public function edit($id = 0)
	{
		$data = [];
		$data['course_types'] = $this->CoursesModel::TYPES;
		
		if(($data['item'] = $this->CoursesModel->getByID(intval($id))) == false)
		{
			header('Location: ../');
		}

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);

			if($id = $this->CoursesModel->update($id, $form_data))
			{
				$data['item'] = $this->CoursesModel->getByID($id);
				SetFlashMessage('success', 'Success');
			}
		}

		$data['csrf'] = cr_get_key();
		$data['error'] = $this->CoursesModel->LAST_ERROR;

		$this->load->lview('courses/edit', $data);
	}
}
