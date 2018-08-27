<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesModel', 'main/CoursesGroupsModel', 'main/LecturesModel']);
	}
	
	public function index()
	{
		$data = [];

		$user = $this->Auth->userID();
		$data['courses'] = $this->SubscriptionModel->coursesList($user);
		$data['course_lectures'] = [];
		if($data['courses'])
		{
			if($data['course_lectures'] = $this->LecturesModel->getAvailableForGroup($data['courses'][0]['course_group']))
			{
				foreach($data['course_lectures'] as &$val)
				{
					$video = $this->LecturesModel->lectureOrignVideo($val['id']);
					$val['video'] = isset($video['mp4'])?$video['mp4']:'';
				}
			}
		}

		$this->load->lview('courses/index', $data);
	}

	public function enroll()
	{
		$data = [];

		$user = $this->Auth->userID();
		$data['error'] = null;
		$data['items'] = $this->CoursesGroupsModel->listSubscribe($user);
		$data['course_types'] = $this->CoursesModel::TYPES;

		if(CrValidKey())
		{
			$subscr_data = [
				'user' => $user,
				'group' => $this->input->post('group', true),
				'price_period' => $this->input->post('price', true)
			];

			if($this->SubscriptionModel->group($subscr_data['user'], $subscr_data['group'], $subscr_data['price_period']))
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
