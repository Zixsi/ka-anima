<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingGroups extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel', 'main/LecturesModel']);
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->CoursesGroupsModel->getTeacherGroups($this->Auth->userID());

		$this->load->lview('teachingGroups/index', $data);
	}

	public function group($id = 0)
	{
		$data = [];
		$data['items'] = $this->LecturesModel->getAvailableForGroup($id);

		$this->load->lview('teachingGroups/group', $data);
	}

	public function lecture($group_id = 0, $id = 0)
	{
		$data = [];
		$data['homework'] = $this->LecturesModel->getTeacherHomeWork($group_id, $id);

		$this->load->lview('teachingGroups/lecture', $data);
	}
}
