<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingCourses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel', 'app/TeachingCoursesManager']);
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->CoursesModel->List(['author' => $this->Auth->UserID()]);

		$this->load->lview('teachingCourses/index', $data);
	}

	public function add()
	{
		$data = [];
		
		if($id = $this->TeachingCoursesManager->Add())
		{
			header('Location: ../');
		}
		$data['error'] = $this->TeachingCoursesManager->LAST_ERROR;

		$this->load->lview('teachingCourses/add', $data);
	}
}
