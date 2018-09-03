<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	private $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesModel', 'main/CoursesGroupsModel', 'main/LecturesModel', 'main/FilesModel']);

		$this->user_id = $this->Auth->userID();
	}
	
	public function index($group = 0, $lecture = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['group_id'] = intval($group);
		$data['lecture_id'] = intval($lecture);

		$data['courses'] = $this->SubscriptionModel->byUserType($this->user_id, 0);
		$this->prepareCourses($data['courses']);
		$data['lectures'] = [];
		$data['lecture'] = [];

		if($data['group_id'] > 0)
		{
			if(array_key_exists($data['group_id'], $data['courses']) == false)
			{
				header('Location: /courses/');
			}

			$data['lectures'] = $this->LecturesModel->getAvailableForGroup($data['group_id']);
			$this->prepareLectures($data['lectures'], $data['courses'][$data['group_id']]);

			if($data['lecture_id'] > 0)
			{
				if(array_key_exists($data['lecture_id'], $data['lectures']) == false)
				{
					header('Location: /courses/'.$data['group_id'].'/');
				}

				$data['lecture'] = $data['lectures'][$data['lecture_id']];
				$video = $this->LecturesModel->lectureOrignVideo($data['lecture']['id']);
				$data['lecture']['video'] = isset($video['mp4'])?$video['mp4']:'';

				if(CrValidKey())
				{
					$this->uploadHomeWork($data);
				}
				$data['csrf'] = CrGetKey();
			}
		}

		$data['homework'] = $this->LecturesModel->getUserHomeWork($data['group_id'], $data['lecture_id'], $this->user_id);


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

	private function prepareCourses(&$data)
	{
		$tmp_data = $data;
		$data = [];

		foreach($tmp_data as $val)
		{
			$data[$val['id']] = [
				'id' => $val['id'],
				'service' => $val['service'],
				'name' => $val['description'],
				'active' => $val['active'],
				'ts_end' => $val['ts_end'],
				'ts_end_mark' => $val['ts_end_mark']
			];
		}

		unset($tmp_data);
	}

	private function prepareLectures(&$data, $group)
	{
		if($data)
		{
			$tmp_data = $data;
			$data = [];
			
			foreach($tmp_data as $val)
			{
				if(strtotime($val['ts']) <= $group['ts_end_mark'])
				{
					$data[$val['id']] = $val;
				}
			}
		
			unset($tmp_data);
		}
	}


	private function uploadHomeWork(&$data)
	{
		$comment = $this->input->post('text', true);
		$this->load->config('upload');
		$upload_config = $this->config->item('upload_homework');
		$this->load->library('upload', $upload_config);

		if($this->upload->do_upload('file') == false)
		{
			$data['error'] = $this->upload->display_errors();
		}
		else
		{
			if($file_id = $this->FilesModel->saveFileArray($this->upload->data()))
			{
				$this->LecturesModel->addHomeWork($data['group_id'], $data['lecture_id'], $this->user_id, $file_id, $comment);
			}

			$data['error'] = $this->FilesModel->LAST_ERROR;
		}
	}
}
