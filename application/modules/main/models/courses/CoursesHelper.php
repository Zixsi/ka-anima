<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'main/CoursesModel',
			'main/LecturesModel',
		]);
	}

	public function add($data)
	{
		try
		{
			$this->db->trans_begin();

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($data);
			if($this->form_validation->run('course') == false)
				throw new Exception($this->form_validation->error_string(), 1);

			$item = [
				'type' => ($data['type'] ?? ''),
				'name' => ($data['name'] ?? ''),
				'description' => ($data['description'] ?? ''),
				'teacher' => intval($data['teacher'] ?? 0),
				'price_month' => floatval($data['price_month'] ?? 0),
				'price_full' => floatval($data['price_full'] ?? 0)
			];

			if($img_id = $this->uploadImg('img', 'upload_course'))
				$item['img'] = $img_id;

			if($this->CoursesModel->add($item) === false)
				throw new Exception('Ошибка добавления', 1);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка добавления');
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}

	public function update($id, $data)
	{
		try
		{
			$this->db->trans_begin();

			if(($course_item = $this->CoursesModel->getByID(intval($id))) == false)
				throw new Exception('Курс не найден', 1);

			$item = [
				'active' => ($data['active'] ?? $course_item['active']),
				'type' => ($data['type'] ?? $course_item['type']),
				'name' => ($data['name'] ?? $course_item['name']),
				'description' => ($data['description'] ?? $course_item['description']),
				'teacher' => intval($data['teacher'] ?? $course_item['teacher']),
				'price_month' => number_format(floatval($data['price_month'] ?? $course_item['price_month']), 2, '.', ''),
				'price_full' => number_format(floatval($data['price_full'] ?? $course_item['price_full']), 2, '.', ''),
				'img' => $course_item['img']
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($data);
			if($this->form_validation->run('course') == false)
				throw new Exception($this->form_validation->error_string(), 1);

			if($img_id = $this->uploadImg('img', 'upload_course'))
				$item['img'] = $img_id;

			if($this->CoursesModel->update($id, $item) === false)
				throw new Exception('Ошибка обновления', 1);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка обновления');
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}

	private function uploadImg($name, $config)
	{
		if(isset($_FILES[$name]) && !empty($_FILES[$name]['name']))
		{
			$this->load->model(['main/FilesModel']);
			$this->load->config('upload');
			$this->upload_config = $this->config->item($config);
			$this->load->library('upload', $this->upload_config);

			if($this->upload->do_upload($name) == false)
				throw new Exception($this->upload->display_errors(), 1);

	
			if(($id = $this->FilesModel->saveFileArray($this->upload->data())) === false)
				throw new Exception($this->FilesModel->getLastError(), 1);
			
			return $id;
		}

		return false;
	}
}