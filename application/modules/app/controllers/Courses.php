<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->user_id = $this->Auth->userID();
	}

	// список доступных курсов
	public function index()
	{
		$data = [];
		// список предложений курсов
		$data['items'] = $this->GroupsModel->listOffers();

		$this->load->lview('courses/index', $data);
	}

	// подробная информация по курсу
	public function item($code = null)
	{
		$data = [];

		// курс
		if(($data['item'] = $this->CoursesModel->getByCode($code)) === false)
			header('Location: ../');

		// список лекций
		$data['lectures'] = $this->LecturesModel->getByCourse($data['item']['id']);
		// список групп доступных для подписки
		$data['offers'] = $this->GroupsModel->listOffersForCourse($data['item']['id']);
		// преподаватель
		$data['teacher'] = $this->UserModel->getById($data['item']['teacher']);

		$this->load->lview('courses/item', $data);
	}
	
	/*public function index($group = '', $lecture = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'index';

		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);
		$data['subscr'] = $this->subscrGroup($group_id);

		$data['lecture_id'] = (int) $lecture;
		$data['subscr_is_active'] = ($data['subscr'])?true:false;

		$data['lectures'] = $this->LecturesGroupModel->listForGroup($group_id);
		$last_active_lecture = $this->prepareLectures($data['lectures']);
		$data['lectures_is_active'] = ($last_active_lecture == 0)?false:true;

		if($data['lectures_is_active'] && $data['subscr_is_active'])
		{
			if(empty($data['lectures'][$data['lecture_id']]) OR $data['lectures'][$data['lecture_id']]['active'] == 0)
				header('Location: /courses/'.$group.'/lecture/'.$last_active_lecture);

			$data['lecture'] = $this->LecturesModel->getByID($data['lecture_id']);
			$data['lecture']['ts'] = $data['lectures'][$data['lecture_id']]['ts'];
			$data['lecture']['can_upload_files'] = true;
			// если прошла 3 недели после запуска
			// запрещаем закружать в эту лекцию файлы
			// $ts_now = new DateTime('now');
			// $ts_lecture_start = new DateTime($data['lecture']['ts']);
			// $diff = $ts_now->diff($ts_lecture_start);
			// if((int) $diff->days > 21)
			// 	$data['lecture']['can_upload_files'] = false;
			// unset($ts_now, $ts_lecture_start, $diff);

			$data['lecture']['video_code'] = '';
			if($res = $this->VideoModel->bySource($data['lecture']['id']))
				$data['lecture']['video_code'] = $res['video_code'];

			if(cr_valid_key())
				$this->uploadHomeWork($data);
			
			$data['csrf'] = cr_get_key();
			$data['lecture_homework'] = $this->LecturesHomeworkModel->getListForUsers($group_id, $data['lecture_id']);
			$data['lecture']['files'] = $this->FilesModel->listLinkFiles($data['lecture_id'], 'lecture');
		}

		$this->setHomeworkStatus($group_id, $this->user_id, $data['lectures']);

		// debug($data['group']); die();
		$this->load->lview('courses/index', $data);
	}*/

	/*
	public function group($group = '')
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'group';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);
		$data['subscr'] = $this->subscrGroup($group_id);

		if($data['subscr'] === false)
			header('Location: /courses/'.$data['group']['code'].'/');

		$data['lectures'] = $this->LecturesGroupModel->listForGroup($group_id);
		$data['group']['current_week'] = $this->currentGroupWeek($data['lectures']);
		$data['teacher'] = $this->UserModel->getById($data['group']['teacher']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($group_id);
		$data['images'] = $this->GroupsModel->getImageFiles($group_id);

		$data['wall'] = $this->WallModel->list($group_id);
		// debug($data['wall']); die();

		$this->load->lview('courses/group', $data);
	}

	public function review($group = '', $review = 0)
	{
		$data = [];
		$data['section'] = 'review';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);
		$data['subscr'] = $this->subscrGroup($group_id);

		if($data['subscr'] == false)
			header('Location: /courses/'.$data['group']['code'].'/');

		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /courses/'.$data['group']['code'].'/');

		$data['review_item'] = false; 
		if($review > 0)
		{
			$review_item = $this->ReviewModel->getByID($review);
			// Если юзер не просматривал новое
			if(intval($review_item['item_is_viewed'] ?? 1) === 0 && intval($review_item['user'] ?? 1) === intval($this->user_id))
				$this->ReviewModel->setViewedStatus($review_item['id'], true);
		
			if($user = $this->UserModel->getByID($review_item['user']))
				$review_item['user_name'] = $user['full_name'];

			$data['review_item'] = $review_item;
		}

		$filter = $this->input->get('filter', true);
		$data['filter_url'] = http_build_query(['filter' => $filter]);
		$data['items'] = $this->ReviewModel->getByGroup($group_id, $filter);
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($group_id);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($group_id);
		$data['not_viewed'] = $this->ReviewModel->notViewedItems($this->user_id, $group_id);

		// debug($data); die();
		$this->load->lview('courses/reviews', $data);
	}

	public function stream($group = '', $item = 0)
	{
		$data = [];
		$data['section'] = 'stream';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);
		$data['subscr'] = $this->subscrGroup($group_id);
		
		if($data['subscr'] == false)
			header('Location: /courses/'.$data['group']['code'].'/');

		$data['group'] = $this->CoursesGroupsModel->getByID($group_id);
		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /courses/'.$data['group']['code'].'/');

		$data['list'] = $this->StreamsModel->byGroupList($group_id);
		$data['item'] = false;
		$streams_id = $this->getStreamsIds($data['list']);

		if(count($streams_id))
		{
			if($item > 0)
				$data['item'] = $this->StreamsModel->getByID($item);
			else
				$data['item'] = $this->StreamsModel->byGroup($group_id);

			if(empty($data['item']))
				$data['item'] = current($data['list']);
			
			if(!in_array($data['item']['id'], $streams_id))
				header('Location: /courses/'.$data['group']['code'].'/stream/');

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
		$data['items'] = $this->GroupsModel->listOffers($this->user_id);

		if(count($data['items']))
		{
			$subscr_courses = $this->SubscriptionModel->listCoursesId($this->user_id);
			foreach($data['items'] as &$val)
			{
				$val['subscription'] = in_array($val['id'], $subscr_courses);
			}
		}
		// debug($data); die();
		
		$this->load->lview('courses/enroll', $data);
	}

	private function subscrGroup($id)
	{
		if(($subscr = $this->SubscriptionModel->get($this->user_id, $id, 'course')) == false)
			header('Location: /');

		if(strtotime($subscr['ts_end']) > time())
			return $subscr;

		return false;
	}

	private function currentGroupWeek($list)
	{
		$cnt = 0;
		if($list && is_array($list))
		{
			foreach($list as $val)
			{
				$cnt += ($val['active'] == 1 && (int) $val['type'] === 0)?1:0;
			}
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
			if($data && is_array($data))
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
	}
	

	private function uploadHomeWork(&$data)
	{
		$comment = $this->input->post('text', true);
		$this->load->config('upload');
		$upload_config = $this->config->item('upload_homework');
		$this->load->library('upload', $upload_config);

		if($this->upload->do_upload('file') == false)
			$data['error'] = $this->upload->display_errors();
		else
		{
			$file_data = $this->upload->data();
			if($file_id = $this->FilesModel->saveFileArray($file_data))
			{
				if($this->LecturesHomeworkModel->add($data['group']['id'], $data['lecture_id'], $this->user_id, $file_id, $comment))
				{
					action(UserActionsModel::ACTION_HOMEWORK_FILE_ADD, [
						'group_code' => $data['group']['code'], 
						'lecture_name' => $data['lecture']['name'], 
						'file_name' => $file_data['orig_name']
					]);
				}
			}

			$data['error'] = $this->FilesModel->getLastError();
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

	*/
}
