<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	private $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesModel', 'main/CoursesGroupsModel', 'main/LecturesModel', 'main/FilesModel', 'main/LecturesGroupModel', 'main/LecturesHomeworkModel', 'main/ReviewModel', 'main/VideoModel', 'main/GroupsModel']);

		$this->user_id = $this->Auth->userID();
	}
	
	public function index($group = 0, $lecture = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'index';
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
			$data['lecture']['files'] = $this->FilesModel->listLinkFiles($data['lecture_id'], 'lecture');
		}

		$this->setHomeworkStatus($data['group_id'], $this->user_id, $data['lectures']);

		//debug($data['lectures']); die();

		$this->load->lview('courses/index', $data);
	}

	public function group($group = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'group';
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
		$data['images'] = $this->GroupsModel->getImageFiles($data['group_id']);

		$this->load->lview('courses/group', $data);
	}

	public function review($group = 0, $review = 0)
	{
		$data = [];
		$data['section'] = 'review';
		$data['group_id'] = intval($group);
		
		if($this->checkSubscribeGroup($data['group_id']) == false)
		{
			header('Location: /courses/'.$data['group_id'].'/');
		}

		$data['review_item'] = false; 
		if($review > 0)
		{
			$data['review_item'] = $this->ReviewModel->getByID($review);
			$data['review_item']['files'] = $this->FilesModel->listLinkFiles($data['review_item']['id'], 'review');
			if($user = $this->UserModel->getByID($data['review_item']['user']))
			{
				$data['review_item']['user_name'] = $user['full_name'];
			}
			//debug($data['review_item']); die();
		}

		$filter = $this->input->get('filter', true);
		$data['filter_url'] = http_build_query(['filter' => $filter]);
		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		$data['items'] = $this->ReviewModel->getByGroup($data['group_id'], $filter);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($data['group_id']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['group_id']);

		$this->load->lview('courses/reviews', $data);
	}

	public function stream($group = 0, $item = 0)
	{
		$data = [];
		$data['section'] = 'stream';
		$data['group_id'] = intval($group);
		
		if($this->checkSubscribeGroup($data['group_id']) == false)
		{
			header('Location: /courses/'.$data['group_id'].'/');
		}

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		$data['list'] = $this->StreamsModel->byGroupList($data['group_id']);
		$data['item'] = false;
		$streams_id = $this->getStreamsIds($data['list']);


		if(count($streams_id))
		{
			if($item > 0)
			{
				$data['item'] = $this->StreamsModel->getByID($item);
			}
			else
			{
				$data['item'] = $this->StreamsModel->byGroup($data['group_id']);
			}

			if(!in_array($data['item']['id'], $streams_id))
			{
				header('Location: /courses/'.$data['group_id'].'/stream/');
			}

			if($data['item'])
			{
				$this->load->library(['youtube']);
				$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
				$data['item']['started'] = boolval(time() >= strtotime($data['item']['ts']));
			}
			//debug($data['item']);
		}

		$this->load->lview('courses/stream', $data);
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

	private function setHomeworkStatus($group_id, $user_id, &$data)
	{
		if(($list = $this->LecturesHomeworkModel->listLecturesIdWithHomework($group_id, $user_id)) !== false)
		{
			foreach($data as &$val)
			{
				$val['homework_fail'] = false;
				if($val['active'] == 1 && $val['type'] == 0 && !in_array($val['id'], $list))
				{
					$val['homework_fail'] = true;
				}
			}
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
				$this->LecturesHomeworkModel->add($data['group_id'], $data['lecture_id'], $this->user_id, $file_id, $comment);
			}

			$data['error'] = $this->FilesModel->LAST_ERROR;
		}
	}

	private function getStreamsIds($data = [])
	{
		$result = [];
		if(is_array($data))
		{
			foreach($data as $val)
			{
				$result[] = $val['id'];
			}
		}

		return $result;
	}
}
