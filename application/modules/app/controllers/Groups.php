<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/LecturesModel', 'main/FilesModel']);
	}
	
	public function index($id = 0)
	{
		/*$user_id = $this->Auth->userID();
		$data = [];
		$data['error'] = null;

		if($id == 0 || $this->CoursesGroupsModel->userInGroup($id, $user_id) == false)
		{
			header('Location: /');
		}

		if(($data['group'] = $this->CoursesGroupsModel->getByID($id)) == false)
		{
			header('Location: /');
		}
		
		if(CrValidKey())
		{
			$comment = $this->input->post('text', true);
			$this->load->config('upload');
			$upload_config = $this->config->item('upload_homework');
			$this->load->library('upload', $upload_config);

			if(!$this->upload->do_upload('file'))
			{
				$data['error'] = $this->upload->display_errors();
			}
			else
			{
				if($file_id = $this->FilesModel->saveFileArray($this->upload->data()))
				{
					$this->LecturesModel->addHomeWork(0, $user_id, $file_id, $comment);
				}
			}

			$data['error'] = $this->FilesModel->LAST_ERROR;
		}

		$data['csrf'] = CrGetKey();

		$this->load->lview('groups/index', $data);*/
	}
}
