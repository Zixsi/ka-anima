<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];

		$this->load->lview('groups/index', $data);
	}

	public function item($code = null)
	{
//            $this->GroupsHelper->moveToGroup(7, 96, 99); 
//            die();
            
		$data = [];
		if(($data['item'] = $this->GroupsModel->getByCode($code)) === false)
			show_404();

		$params = $this->input->get(null, true);
		$type = $data['item']['type'];

		$data['teacherList'] = $this->UserModel->listTeachers();
		$data['teacherList'] = setArrayKeys($data['teacherList'], 'id');
                $data['groupMonth'] = $this->LecturesGroupModel->getGroupMonthMap($data['item']['id']);
		$data['groups'] = $this->GroupsModel->getActiveForCourse($data['item']['course_id']);
                
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['item']['id'], $type);
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

			$this->listenUserActions($data['item'], $params);
		}

		$data['streams'] = $this->StreamsModel->byGroupList($data['item']['id']);

		$stat = $this->TransactionsModel->getGroupStatByMonths($data['item']['id']);
		$data['stat'] = $this->StatsHelper->prepareChart($stat);
		$data['statTotal'] = $this->TransactionsModel->getGroupTotalAmount($data['item']['id']);

		$this->load->lview('groups/item', $data);
	}

	private function listenUserActions($group, $params)
	{
		if(!isset($params['action']) || empty($params['action']))
			return;

		switch($params['action'])
		{
			case 'download':
				if(isset($params['target']) && $params['target'] == 'homework')
					$this->HomeworkHelper->download($group['id'], $params['lecture'], $params['user']);
			break;
			default:
				// empty 
			break;
		}
	}
}
