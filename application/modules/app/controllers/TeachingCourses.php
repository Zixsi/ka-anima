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
		$data['items'] = $this->CoursesModel->List(['author' => $this->Auth->UserID()]);
		//Debug($data); die();

		$this->load->lview('teachingCourses/index', $data);
	}

	public function add()
	{
		$data = [];
		
		if(CrValidKey())
		{
			$form_data = $this->input->post(null, true);
			$form_data['author'] = $this->Auth->UserID();

			if($id = $this->CoursesModel->Add($form_data))
			{
				header('Location: ../');
			}
		}

		$data['csrf'] = CrGetKey();
		$data['error'] = $this->CoursesModel->LAST_ERROR;

		$this->load->lview('teachingCourses/add', $data);
	}

	public function edit($id = 0)
	{
		$data = [];
		
		$id = intval($id);
		if(($data['item'] = $this->CoursesModel->GetByID($id)) == false)
		{
			header('Location: ../');
		}

		if($data['item']['author'] !== $this->Auth->UserID())
		{
			header('Location: ../');
		}

		if(CrValidKey())
		{
			$form_data = $this->input->post(null, true);

			if($id = $this->CoursesModel->Update($id, $form_data))
			{
				$data['item'] += $form_data;
				SetFlashMessage('success', 'Success');
			}
		}

		$data['csrf'] = CrGetKey();
		$data['error'] = $this->CoursesModel->LAST_ERROR;

		$this->load->lview('teachingCourses/edit', $data);
	}
}
