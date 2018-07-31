<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesGroupsModel', 'main/LecturesModel']);
	}
	
	public function index()
	{
		$data = [];

		$user = $this->Auth->UserID();
		$data['courses'] = $this->SubscriptionModel->CoursesList($user);
		$data['course_lectures'] = [];
		if(count($data['courses']))
		{
			$data['course_lectures'] = $this->LecturesModel->GetByCourse($data['courses'][0]['id']);
		}
		

		$this->load->lview('courses/index', $data);
	}

	public function enroll()
	{
		$data = [];

		$user = $this->Auth->UserID();
		$data['error'] = null;
		$data['items'] = $this->CoursesGroupsModel->ListSubscribe($user);

		if(CrValidKey())
		{
			$subscr_data = [
				'user' => $user,
				'group' => $this->input->post('group', true),
				'price_period' => $this->input->post('price', true)
			];

			if($this->SubscriptionModel->Group($subscr_data['user'], $subscr_data['group'], $subscr_data['price_period']))
			{
				header('Location: ./');
			}

			$data['error'] = $this->SubscriptionModel->LAST_ERROR;
		}

		$data['csrf'] = CrGetKey();
		//debug($data['items']); die();

		$this->load->lview('courses/enroll', $data);
	}
}
