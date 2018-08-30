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
			//$subscr = $this->SubscriptionModel->byUserService($user, $data['courses'][0]['course_group'], 0);
			$subscr_list = [];
			if($res = $this->SubscriptionModel->byUser($user))
			{
				foreach($res as $val)
				{
					$subscr_list[$val['service'].'_'.$val['type']] = $val;
				}
				unset($res);
			}

			$i = 0;
			foreach($data['courses'] as &$val)
			{
				$val['status'] = (isset($subscr_list[$val['course_group'].'_0']) && $subscr_list[$val['course_group'].'_0']['active'] == true)?true:false;

				if($i == 0 && $val['status'])
				{
					if($data['course_lectures'] = $this->LecturesModel->getAvailableForGroup($val['course_group']))
					{
						if(isset($subscr_list[$val['course_group'].'_0']))
						{
							$end = strtotime($subscr_list[$val['course_group'].'_0']['ts_end']);
							foreach($data['course_lectures'] as $k => &$v)
							{
								if(strtotime($v['ts']) <= $end)
								{
									if($val['status'])
									{
										$video = $this->LecturesModel->lectureOrignVideo($v['id']);
										$v['video'] = isset($video['mp4'])?$video['mp4']:'';
									}
									else
									{
										$v['video'] = '';
									}
								}
								else
								{
									unset($data['course_lectures'][$k]);
								}
							}
						}
					}
				}

				$i++;
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
