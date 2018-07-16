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
		$this->load->lview('courses/index');
	}

	public function enroll()
	{
		$data = [];
		$data['items'] = $this->CoursesModel->List(['active' => true]);

		$this->load->lview('courses/enroll', $data);
	}
}
