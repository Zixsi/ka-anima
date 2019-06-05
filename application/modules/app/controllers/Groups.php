<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = $this->Auth->user();
	}
	
	public function index()
	{
		$data = [];
		$data['items'] = $this->GroupsModel->getTeacherGroups($this->user['id'], false);
		$this->GroupsHelper->prepareListForTeacher($data['items']);
		// debug($data); die();
		$this->load->lview('groups/index', $data);
	}

	public function item($code)
	{
		$data = [];
		if(($data['item'] = $this->GroupsModel->getByCode($code)) === false)
			show_404();

		$params = $this->input->get(null, true);

		// ученики группы
		$type = ($data['item']['type'] === 'standart')?'advanced':$data['item']['type'];
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['item']['id'], $type);
		$this->GroupsHelper->setUsersHomeworkStatus($data['item']['id'], $data['users']);

		// дз ученика
		$data['user'] = null;
		$data['homeworks'] = [];
		if(isset($params['user']) && array_key_exists($params['user'], $data['users']))
		{
			$data['user'] = $data['users'][$params['user']];
			// получить лекции группы
			// с меткой о загруженных файлах
			// с меткой о загруженных ревью
			$data['homeworks'] = $this->LecturesGroupModel->listForUser($data['item']['id'], $data['user']['id']);
			$this->listenUserActions($data['item'], $params, $data);
		}

		$data['wall'] = $this->WallModel->list($data['item']['id']);

		// debug($data); die();
		$this->load->lview('groups/item', $data);
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
					action(UserActionsModel::ACTION_HOMEWORK_FILE_DOWNLOAD, [
						'group_code' => $group['code'], 
						'lecture_name' => $lecture['name'], 
						'user_id' => $data['user']['id'],
						'user_name' => $data['user']['full_name']
					]);
					
					$this->HomeworkHelper->download($group['id'], $params['lecture'], $params['user']);
				}
			break;
			default:
				// empty 
			break;
		}
	}
}
