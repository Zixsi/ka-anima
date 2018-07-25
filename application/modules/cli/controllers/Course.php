<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends APP_Controller
{
	public function __construct()
	{
		$this->load->model(['main/CoursesModel', 'main/CoursesGroupsModel']);
	}

	public function index()
	{
		// 
	}

	// php www/index.php cli course createGroups
	public function createGroups()
	{
		//$this->CoursesGroupsModel->GetListNeedCreate();
		//var_dump('Create Groups');
	}
}
