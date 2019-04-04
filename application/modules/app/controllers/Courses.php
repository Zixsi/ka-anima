<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public $user_id = null;

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
		$data['subscr'] = $this->subscrGroup($data['group_id']);
		$data['subscr_is_active'] = ($data['subscr'])?true:false;

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
			$data['lecture']['video_code'] = '';
			if($res = $this->VideoModel->bySource($data['lecture']['id']))
			{
				$data['lecture']['video_code'] = $res['video_code'];
			}

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
		$data['subscr'] = $this->subscrGroup($data['group_id']);
		
		if($data['subscr'] === false)
		{
			header('Location: /courses/'.$data['group_id'].'/');
		}

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($data['group_id']);
		$data['group']['current_week'] = $this->currentGroupWeek($data['lectures']);
		$data['teacher'] = $this->UserModel->getById($data['group']['teacher']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['group_id']);
		$data['images'] = $this->GroupsModel->getImageFiles($data['group_id']);

		$this->load->lview('courses/group', $data);
	}

	public function review($group = 0, $review = 0)
	{
		$data = [];
		$data['section'] = 'review';
		$data['group_id'] = intval($group);
		$data['subscr'] = $this->subscrGroup($data['group_id']);
		
		if($data['subscr'] == false)
			header('Location: /courses/'.$data['group_id'].'/');

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /courses/'.$data['group_id'].'/');

		$data['review_item'] = false; 
		if($review > 0)
		{
			$review_item = $this->ReviewModel->getByID($review);
			// Если юзер не просматривал новое
			if(intval($review_item['item_is_viewed'] ?? 1) === 0 && intval($review_item['user'] ?? 1) === intval($this->user_id))
			{
				$this->ReviewModel->setViewedStatus($review_item['id'], true);
			}
		
			$review_item['files'] = $this->FilesModel->listLinkFiles($review_item['id'], 'review');
			if($user = $this->UserModel->getByID($review_item['user']))
			{
				$review_item['user_name'] = $user['full_name'];
			}

			$data['review_item'] = $review_item;
		}

		$filter = $this->input->get('filter', true);
		$data['filter_url'] = http_build_query(['filter' => $filter]);
		$data['items'] = $this->ReviewModel->getByGroup($data['group_id'], $filter);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($data['group_id']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['group_id']);
		$data['not_viewed'] = $this->ReviewModel->notViewedItems($this->user_id, $data['group_id']);

		$this->load->lview('courses/reviews', $data);
	}

	public function stream($group = 0, $item = 0)
	{
		$data = [];
		$data['section'] = 'stream';
		$data['group_id'] = intval($group);
		$data['subscr'] = $this->subscrGroup($data['group_id']);
		
		if($data['subscr'] == false)
			header('Location: /courses/'.$data['group_id'].'/');

		$data['group'] = $this->CoursesGroupsModel->getByID($data['group_id']);
		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /courses/'.$data['group_id'].'/');

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

			if(empty($data['item']))
			{
				$data['item'] = current($data['list']);
			}
			
			if(!in_array($data['item']['id'], $streams_id))
			{
				header('Location: /courses/'.$data['group_id'].'/stream/');
			}

			$this->load->library(['youtube']);
			$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
			$data['item']['started'] = boolval(time() >= strtotime($data['item']['ts']));
			//debug($data['item']);
		}

		$this->load->lview('courses/stream', $data);
	}

	// запись на курсы
	public function enroll()
	{
		$data = [];
		$data['balance'] = $this->Auth->balance();
		$data['items'] = $this->GroupsModel->listOffers($this->user_id);

		if(count($data['items']))
		{
			$subscr_courses = $this->SubscriptionModel->listCoursesId($this->user_id);
			foreach($data['items'] as &$val)
			{
				$val['subscription'] = in_array($val['id'], $subscr_courses);
			}
		}
		//debug($data['items']); die();
		
		$this->load->lview('courses/enroll', $data);
	}

	private function subscrGroup($id)
	{
		if(($subscr = $this->SubscriptionModel->get($this->user_id, $id, 'course')) == false)
		{
			header('Location: /'); die();
		}

		if(strtotime($subscr['ts_end']) > time())
		{
			return $subscr;
		}


		return false;
	}

	private function currentGroupWeek($list)
	{
		$cnt = 0;

		foreach($list as $val)
		{
			$cnt += ($val['active'] == 1 && (int) $val['type'] === 0)?1:0;
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
