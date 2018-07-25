<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel', 'main/CoursesSubscriptionModel', 'main/CoursesGroupsModel']);
	}
	
	public function index()
	{
		$this->load->lview('courses/index');
	}

	public function enroll()
	{
		$data = [];

		if(CrValidKey())
		{
			$subscr_data = [
				'user' => $this->Auth->UserID(),
				'group' => $this->input->post('group', true),
				'price_month' => 1,
				'price_full' => 1
			];

			if($id = $this->CoursesSubscriptionModel->Add($subscr_data))
			{
				header('Location: ../');
			}
		}

		$data['csrf'] = CrGetKey();
		$data['error'] = $this->CoursesSubscriptionModel->LAST_ERROR;

		$data['items'] = $this->CoursesGroupsModel->ListSubscribe();

		$this->load->lview('courses/enroll', $data);
	}
}
