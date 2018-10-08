<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingGroups extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel', 'main/LecturesModel', 'main/LecturesGroupModel']);
	}

	public function index()
	{
		//$this->Auth->checkAccess()
		$data = [];
		$data['items'] = $this->CoursesGroupsModel->getTeacherGroups($this->Auth->userID());

		$this->load->lview('teachingGroups/index', $data);
	}

	public function group($id = 0)
	{
		$data = [];
		$data['group'] = $this->CoursesGroupsModel->getByID($id);
		$data['items'] = $this->LecturesGroupModel->listForGroup($id);
		$data['users'] = $this->SubscriptionModel->getGroupUsers($id);

		$this->load->lview('teachingGroups/group', $data);
	}

	public function lecture($group_id = 0, $id = 0)
	{
		$data = [];

		if(cr_valid_key())
		{
			$url = $this->input->post('url', true);
			if(!empty($url))
			{
				if($this->LecturesModel->addReview($group_id, $id, $this->Auth->userID(), $url))
				{
					SetFlashMessage('success', 'Success');
					header('Location: ./');
				}
			}

			if($this->LecturesModel->LAST_ERROR)
			{
				SetFlashMessage('error', $this->LecturesModel->LAST_ERROR);
			}
		}
		$data['csrf'] = cr_get_key();

		$data['group'] = $this->CoursesGroupsModel->getByID($group_id);
		$data['item'] = $this->LecturesModel->getByID($id);
		$data['homework'] = $this->LecturesModel->getTeacherHomeWork($group_id, $id);

		$this->load->lview('teachingGroups/lecture', $data);
	}
}
