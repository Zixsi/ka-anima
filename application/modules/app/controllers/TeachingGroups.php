<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingGroups extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel', 'main/LecturesModel', 'main/LecturesGroupModel', 'main/LecturesHomeworkModel', 'main/ReviewModel']);
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
			$params = $this->input->post(null, true);
			$params['group_id'] = $group_id;
			$params['lecture_id'] = $id;
			if(($review_id = $this->ReviewModel->add($params)) == false)
			{
				SetFlashMessage('danger', $this->ReviewModel->LAST_ERROR);
			}
			else
			{
				$this->FilesModel->filesUpload('files', $review_id, 'review', 'upload_lectures');
				header('Location: ./');
			}
		}
		$data['csrf'] = cr_get_key();

		$data['group'] = $this->CoursesGroupsModel->getByID($group_id);
		$data['item'] = $this->LecturesModel->getByID($id);
		$data['homework'] = $this->LecturesModel->getTeacherHomeWork($group_id, $id);
		$data['homework_users'] = $this->LecturesHomeworkModel->listUsersForLecture($group_id, $id);
		$data['reviews'] = $this->ReviewModel->getByLecture($group_id, $id);

		$this->load->lview('teachingGroups/lecture', $data);
	}

	public function review($group_id = 0, $lecture_id = 0, $id = 0)
	{
		$data = [];
		$id = intval($id);

		if(($data['item'] = $this->ReviewModel->getByID($id)) == false)
		{
			header('Location: ../');
		}

		$data['group'] = $this->CoursesGroupsModel->getByID($group_id);
		$data['lecture'] = $this->LecturesModel->getByID($lecture_id);
		$data['error'] = null;

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			$data['item']['video_url'] = $form_data['video_url'] ?? $data['item']['video_url'];
			$data['item']['text'] = $form_data['text'] ?? $data['item']['text'];

			if($this->ReviewModel->update($id, $data['item']))
			{
				$this->FilesModel->deleteLinkFile(($form_data['del_files'] ?? []), $id, 'review');
				if($this->FilesModel->filesUpload('files', $id, 'review', 'upload_lectures'))
				{
					$data['error'] = $this->FilesModel->LAST_ERROR;
				}

				SetFlashMessage('success', 'Success');
			}
			else
			{
				$data['error'] = $this->LecturesModel->LAST_ERROR;
			}
		}
		$data['item']['files'] = $this->FilesModel->listLinkFiles($id, 'review');
		$data['item']['user'] = $this->UserModel->getByID($data['item']['user']);
		//debug($data['item']); die();	
		$data['csrf'] = cr_get_key();

		$this->load->lview('teachingGroups/review', $data);
	}
}
