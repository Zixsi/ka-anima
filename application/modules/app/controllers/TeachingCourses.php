<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingCourses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel']);
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->CoursesModel->list(['author' => $this->Auth->userID()]);
		//Debug($data); die();

		$this->load->lview('teachingCourses/index', $data);
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

		$this->load->lview('teachingCourses/add', $data);
	}

	public function edit($id = 0)
	{
		$data = [];
		$data['course_types'] = $this->CoursesModel::TYPES;
		
		$id = intval($id);
		if(($data['item'] = $this->CoursesModel->getByID($id)) == false)
		{
			header('Location: ../');
		}

		if($data['item']['author'] !== $this->Auth->userID())
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

		$this->load->lview('teachingCourses/edit', $data);
	}
}
