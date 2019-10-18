<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller
{
	public $user;

	public function __construct()
	{
		parent::__construct();
		if(!$this->Auth->isActive())
			header('Location: /');
		
		$this->user = $this->Auth->user();
	}

	public function index()
	{
		if($this->Auth->isTeacher())
			$this->indexTeacher();
		else
			$this->indexUser();
	}

	// список групп для преподователя
	private function indexTeacher()
	{
		$data = [];
		$filter = [
			'with_subscribed' => true, // с подписанными пользователями
		];
		$data['items'] = $this->GroupsModel->getTeacherGroups($this->user['id'], false, $filter);
		$this->GroupsHelper->prepareListForTeacher($data['items'], $filter);

		$this->load->lview('groups/index_teacher', $data);
	}

	// список групп пользователя
	private function indexUser()
	{
		$data = [];
		$data['items'] = $this->SubscriptionModel->groupsList($this->user['id']);
		
		$this->load->lview('groups/index_user', $data);
	}

	public function item($code, $lecture = 0)
	{
		if(($item = $this->GroupsModel->getByCode($code)) === false)
			show_404();

		if($this->Auth->isTeacher())
			$this->itemTeacher($item);
		else
			$this->itemUser($item, $lecture);
	}

	private function itemTeacher($item)
	{
		$data = [];
		$data['item'] = $item;
		$params = $this->input->get(null, true);

		// ученики группы
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['item']['id'], $data['item']['type']);
		$this->GroupsHelper->setUsersHomeworkStatus($data['item']['id'], $data['users']);

		// лекции группы
		$lectures = $this->LecturesGroupModel->listForGroup($data['item']['id']);

		// дз ученика
		$data['user'] = null;
		$data['homeworks'] = [];
		if(isset($params['user']) && array_key_exists($params['user'], $data['users']))
		{
			$data['user'] = $data['users'][$params['user']];

			// список файлов пользователя
			$user_homeworks = $this->LecturesHomeworkModel->userFilesForGroup($data['user']['id'], $data['item']['id']);
			// список ревью для пользователя
			$user_reviews = $this->ReviewModel->getByGroup($data['item']['id'], ['user' => $data['user']['id']]);

			$data['homeworks'] = $this->GroupsHelper->buildHomeworkInfo($lectures, $user_homeworks, $user_reviews);
			unset($lectures, $user_homeworks, $user_reviews);

			$this->listenUserActions($data['item'], $params, $data);
		}

		$data['wall'] = $this->WallModel->list($data['item']['id']);

		// debug($data); die();
		$this->load->lview('groups/item_teacher', $data);
	}

	// главная страница группы для ученика
	private function itemUser($item, $lecture = 0)
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'index';
		$data['group'] = $item;
		$group_id = $data['group']['id'];

		$data['subscr'] = $this->checkSubscr($group_id);
		$data['subscr_is_active'] = ($data['subscr'])?true:false;

		$data['lectures'] = $this->LecturesGroupModel->listForGroup($group_id);
		$data['lecture_id'] = ((int) $lecture === 0)?current($data['lectures'])['id']:$lecture;
		$last_active_lecture = $this->prepareLectures($data['lectures']);
		$data['lectures_is_active'] = ($last_active_lecture == 0)?false:true;

		if($data['lectures_is_active'] && $data['subscr_is_active'])
		{
			if(empty($data['lectures'][$data['lecture_id']]) OR $data['lectures'][$data['lecture_id']]['active'] == 0)
				header('Location: /groups/'.$data['group']['code'].'/lecture/'.$last_active_lecture);

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

		$this->setHomeworkStatus($group_id, $this->user['id'], $data['lectures']);

		$this->load->lview('groups/item_user', $data);
	}

	public function group($group = '')
	{
		$data = [];
		$data['error'] = null;
		$data['section'] = 'group';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);

		if(($data['subscr'] = $this->checkSubscr($group_id)) === false)
			header('Location: /groups/'.$data['group']['code'].'/');
			
		$data['lectures'] = $this->LecturesGroupModel->listForGroup($group_id);
		$data['group']['current_week'] = $this->currentGroupWeek($data['lectures']);
		$data['teacher'] = $this->UserModel->getById($data['group']['teacher']);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($group_id);
		$data['images'] = $this->GroupsModel->getImageFiles($group_id);
		$data['videos'] = $this->GroupsModel->getVideoFiles($group_id);

		$data['wall'] = $this->WallModel->list($group_id);
		// debug($data['wall']); die();

		$this->load->lview('groups/group', $data);
	}

	public function review($group = '', $review = 0)
	{
		$data = [];
		$data['section'] = 'review';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);

		if(($data['subscr'] = $this->checkSubscr($group_id)) === false)
			header('Location: /groups/'.$data['group']['code'].'/');

		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /groups/'.$data['group']['code'].'/');

		$data['review_item'] = false; 
		if($review > 0)
		{
			$review_item = $this->ReviewModel->getByID($review);
			// Если юзер не просматривал новое
			if(intval($review_item['item_is_viewed'] ?? 1) === 0 && intval($review_item['user'] ?? 1) === (int) $this->user['id'])
				$this->ReviewModel->setViewedStatus($review_item['id'], true);
		
			if($user = $this->UserModel->getByID($review_item['user']))
				$review_item['user_name'] = $user['full_name'];

			$data['review_item'] = $review_item;
		}

		$filter = $this->input->get('filter', true);
		$data['filter_url'] = http_build_query(['filter' => $filter]);
		$data['items'] = $this->ReviewModel->getByGroup($group_id, $filter);
		$data['lectures'] = [];
		$lectures = $this->LecturesGroupModel->listForGroup($group_id);
		foreach($lectures as $key => $row)
		{
			$row['index'] = $key;
			$data['lectures'][$row['id']] = $row;
		}

		$data['users'] = $this->SubscriptionModel->getGroupUsers($group_id);
		$data['not_viewed'] = $this->ReviewModel->notViewedItems((int) $this->user['id'], $group_id);

		// debug($data['review_item']); die();
		$this->load->lview('groups/reviews', $data);
	}

	public function stream($group = '', $item = 0)
	{
		$data = [];
		$data['section'] = 'stream';
		$data['group'] = $this->CoursesGroupsModel->getByCode($group);
		$group_id = (int) ($data['group']['id']);

		if(($data['subscr'] = $this->checkSubscr($group_id)) === false)
			header('Location: /groups/'.$data['group']['code'].'/');

		$data['group'] = $this->CoursesGroupsModel->getByID($group_id);
		if(($data['subscr']['type'] ?? '') === 'standart')
			header('Location: /groups/'.$data['group']['code'].'/');

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
				header('Location: /groups/'.$data['group']['code'].'/stream/');

			$this->load->library(['youtube']);
			$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
			$data['item']['started'] = boolval(time() >= strtotime($data['item']['ts']));

			$data['item']['chat'] = $this->youtube->getLiveChatUrl($data['item']['video_code']);
		}

		$this->load->lview('groups/stream', $data);
	}

	// проверка подписки
	private function checkSubscr($id)
	{
		if(($subscr = $this->SubscriptionModel->get($this->user['id'], $id, 'course')) == false)
			return false;

		if(strtotime($subscr['ts_end']) < time())
			return false;

		return $subscr;
	}

	// подготовка лекций
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
					$last_active_id = $val['id'];
			}
		
			unset($tmp_data);
		}

		return $last_active_id;
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
			$data['error'] = $this->upload->display_errors();
		else
		{
			$file_data = $this->upload->data();
			if($file_id = $this->FilesModel->saveFileArray($file_data))
			{
				if($this->LecturesHomeworkModel->add($data['group']['id'], $data['lecture_id'], $this->user['id'], $file_id, $comment))
				{
					$this->FilesModel->createThumb($file_data);

					$actionParams = [
						'group_code' => $data['group']['code'], 
						'lecture_name' => $data['lecture']['name'], 
						'file_name' => $file_data['orig_name']
					];

					Action::send(Action::HOMEWORK_UPLOAD, [$actionParams]);
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
				$result[] = $val['id'];
		}

		return $result;
	}
	
	private function listenUserActions($group, $params, $data = [])
	{
		if(!isset($params['action']) || empty($params['action']))
			return;

		switch($params['action'])
		{
			case 'download':
				if(isset($params['target']) && $params['target'] == 'homework')
				{
					$lecture = $this->LecturesModel->getByID($params['lecture']);

					$actionParams =[
						'group_code' => $group['code'], 
						'lecture_name' => $lecture['name'], 
						'user_id' => $data['user']['id'],
						'user_name' => $data['user']['full_name']
					];
					
					Action::send(Action::HOMEWORK_DOWNLOAD, [$actionParams]);
					
					$this->HomeworkHelper->download($group['id'], $params['lecture'], $params['user']);
				}
			break;
			default:
				// empty 
			break;
		}
	}
}
