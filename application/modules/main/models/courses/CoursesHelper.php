<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoursesHelper extends APP_Model
{
	const PRICE_STRUCTURE = [
		'standart' => ['month' => 0, 'full' => 0], // стандартный
		'advanced' => ['month' => 0, 'full' => 0], // расширенный
		'vip' => ['month' => 0, 'full' => 0], // VIP
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		try
		{
			$this->db->trans_begin();

			$price = ($data['price'] ?? []) + self::PRICE_STRUCTURE;
			$this->CoursesModel->preparePrice($price);
			$item = [
				'name' => ($data['name'] ?? ''),
				'code' => ($data['code'] ?? ''),
				'description' => ($data['description'] ?? ''),
				'teacher' => intval($data['teacher'] ?? 0),
				'price' => json_encode($price),
				'only_standart' => intval($data['only_standart'] ?? 0),
				'trailer_url' => ($data['trailer_url'] ?? ''),
				'examples_url' => ($data['examples_url'] ?? ''),
				'text_app_main' => ($data['text_app_main'] ?? ''),
				'text_app_other' => ($data['text_app_other'] ?? ''),
				'preview_text' => ($data['preview_text'] ?? ''),
				'meta_keyword' => ($data['meta_keyword'] ?? ''),
				'meta_description' => ($data['meta_description'] ?? '')
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($data);
			if($this->form_validation->run('course') == false)
				throw new Exception($this->form_validation->error_string(), 1);

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

			$price = ($data['price'] ?? []) + self::PRICE_STRUCTURE;
			$this->CoursesModel->preparePrice($price);
			$item = [
				'active' => ($data['active'] ?? $course_item['active']),
				'name' => ($data['name'] ?? $course_item['name']),
				// 'code' => ($data['code'] ?? $course_item['code']),
				'description' => ($data['description'] ?? $course_item['description']),
				'teacher' => intval($data['teacher'] ?? $course_item['teacher']),
				'price' => json_encode($price),
				'only_standart' => intval($data['only_standart'] ?? $course_item['only_standart']),
				'img' => $course_item['img'],
				'trailer_url' => ($data['trailer_url'] ?? $course_item['trailer_url']),
				'examples_url' => ($data['examples_url'] ?? $course_item['examples_url']),
				'text_app_main' => ($data['text_app_main'] ?? $course_item['text_app_main']),
				'text_app_other' => ($data['text_app_other'] ?? $course_item['text_app_other']),
				'preview_text' => ($data['preview_text'] ?? $course_item['preview_text']),
				'meta_keyword' => ($data['meta_keyword'] ?? $course_item['meta_keyword']),
				'meta_description' => ($data['meta_description'] ?? $course_item['meta_description'])
			];

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($data);
			if($this->form_validation->run('course_edit') == false)
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

	// подготовить курс
	public function prepareItem(&$data)
	{
		if(!is_array($data))
			return;


	}

	// подготовить предложения
	public function prepareOffers(&$data)
	{
		if(!is_array($data))
			return;

		foreach($data as &$val)
		{
			$val['only_standart'] = (int) ($val['only_standart'] ?? 0);
			$val['min_price'] = 0;

			if(isset($val['price']))
			{
				$val['price'] = json_decode($val['price'], true);

				$prices = [];
				foreach($val['price'] as $v)
				{
					$prices[] = (float) $v['full'];
				}

				if(count($prices))
					$val['min_price'] = min($prices);
			}

			$val['min_price_f'] = number_format($val['min_price'], 2, '.', ' ');

			if(array_key_exists('img', $val))
			{
				if((int) $val['img'] > 0 && ($img_item = $this->FilesModel->getByID($val['img'])))
					$val['img'] = '/'.$img_item['full_path'];
				else
					$val['img'] = IMG_DEFAULT_16_9;
			}

			$val['description'] = character_limiter($val['description'], 200);
			unset($val['price']);
		}
	}
}