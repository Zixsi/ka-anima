<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingLectures extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesModel', 'main/LecturesModel']);
	}

	public function index($course = 0)
	{
		$data = [];
		$this->checkAccess($course);

		$data['items'] = $this->LecturesModel->getByCourse($course);
		
		$this->load->lview('teachingLectures/index', $data);
	}

	public function add($course = 0)
	{
		$data = [];
		$this->checkAccess($course);

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			$form_data['course_id'] = $course;
			if($id = $this->LecturesModel->add($form_data))
			{
				$this->FilesModel->filesUpload('files', $id, 'lecture', 'upload_lectures');
				header('Location: ../');
			}
		}

		$data['csrf'] = cr_get_key();
		$data['error'] = $this->LecturesModel->LAST_ERROR;

		$this->load->lview('teachingLectures/add', $data);
	}

	public function edit($id = 0, $course = 0)
	{
		$data = [];
		$this->checkAccess($course);
		
		$id = intval($id);

		if(($data['item'] = $this->LecturesModel->getByID($id)) == false)
		{
			header('Location: ../');
		}

		if($data['item']['course_id'] !== $course)
		{
			header('Location: ../');
		}

		$data['error'] = null;

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			$form_data['course_id'] = $course;
			if($this->LecturesModel->update($id, $form_data))
			{
				$data['item'] += $form_data;

				$this->FilesModel->deleteLinkFile(($form_data['del_files'] ?? []), $id, 'lecture');
				if($this->FilesModel->filesUpload('files', $id, 'lecture', 'upload_lectures'))
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

		$data['item']['files'] = $this->FilesModel->listLinkFiles($id, 'lecture');
		$data['csrf'] = cr_get_key();
		

		$this->load->lview('teachingLectures/edit', $data);
	}

	private function checkAccess($id = 0)
	{
		try
		{
			if($id == 0)
				throw new Exception("Empty course id", 1);

			if(($item = $this->CoursesModel->getByID($id)) == false)
				throw new Exception("Course not found", 1);

			if($item['author'] !== $this->Auth->userID())
				throw new Exception("Access denied", 1);
		}
		catch(Exception $e)
		{
			header('Location: /teachingcourses/');
		}

		return true;
	}
}
