<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	private $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesModel', 'main/CoursesGroupsModel', 'main/LecturesModel', 'main/FilesModel', 'main/LecturesGroupModel', 'main/LecturesHomeworkModel']);

		$this->user_id = $this->Auth->userID();
	}
	
	public function index($group = 0, $lecture = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['group_id'] = intval($group);
		$data['lecture_id'] = intval($lecture);
		$data['subscr_is_active'] = $this->checkSubscribeGroup($data['group_id']);

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($data['group_id']);
		$last_active_lecture = $this->prepareLectures($data['lectures']);
		$data['lectures_is_active'] = ($last_active_lecture == 0)?false:true;

		if($data['lectures_is_active'] && $data['subscr_is_active'])
		{
			if(empty($data['lectures'][$data['lecture_id']]) OR $data['lectures'][$data['lecture_id']]['active'] == 0)
			{
				header('Location: /courses/'.$data['group_id'].'/lecture/'.$last_active_lecture);
				die();
			}

			$data['lecture'] = $this->LecturesModel->getByID($data['lecture_id']);
			if(cr_valid_key())
			{
				$this->uploadHomeWork($data);
			}
			$data['csrf'] = cr_get_key();
			$data['lecture_homework'] = $this->LecturesHomeworkModel->getListForUsers($data['group_id'], $data['lecture_id']);
		}

		$this->load->lview('courses/index', $data);
	}

	public function group($group = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['group_id'] = intval($group);
		
		if($this->checkSubscribeGroup($data['group_id']) == false)
		{
			header('Location: /courses/'.$data['group_id'].'/');
		}

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($data['group_id']);
		$data['group']['current_week'] = $this->currentGroupWeek($data['lectures']);
		$data['teacher'] = $this->UserModel->getById($data['group']['author']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['group_id']);

		$this->load->lview('courses/group', $data);
	}

	public function enroll()
	{
		$data = [];

		$user = $this->Auth->userID();
		$data['error'] = null;
		$data['items'] = $this->CoursesGroupsModel->listSubscribe($user);
		$data['course_types'] = $this->CoursesModel::TYPES;
		$data['balance'] = $this->Auth->balance();

		if(cr_valid_key())
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

		$data['csrf'] = cr_get_key();

		$this->load->lview('courses/enroll', $data);
	}

	private function checkSubscribeGroup($id)
	{
		if(($subscr = $this->SubscriptionModel->byUserService($this->user_id, $id)) == false)
		{
			header('Location: /');
			die();
		}

		if(strtotime($subscr['ts_end']) > time())
		{
			return true;
		}


		return false;
	}

	private function currentGroupWeek($list)
	{
		$cnt = 0;

		foreach($list as $val)
		{
			$cnt += ($val['active'] == 1)?1:0;
		}

		return $cnt;
	}

	private function prepareLectures(&$data)
	{
		$last_active_id = 0;

		if($data)
		{
			$tmp_data = $data;
			$data = [];
			
			foreach($tmp_data as $val)
			{
				$data[$val['id']] = $val;
				if($val['active'] == 1)
				{
					$last_active_id = $val['id'];
				}
			}
		
			unset($tmp_data);
		}

		return $last_active_id;
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
				$this->LecturesHomeworkModel->add($data['group_id'], $data['lecture_id'], $this->user_id, $file_id, $comment);
			}

			$data['error'] = $this->FilesModel->LAST_ERROR;
		}
	}
}
