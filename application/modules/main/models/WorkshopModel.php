<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorkshopModel extends APP_Model
{
	const TABLE = 'workshop';

	// collection
	// webinar

	public function add($data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();
	
		return false;
	}
	
	public function validate($params)
	{
		$this->load->library('form_validation');

		$this->form_validation->reset_validation();
		$this->form_validation->set_data($params);
		
		$this->form_validation->set_rules('title', 'Название', 'required|min_length[3]');

		switch(($params['type'] ?? null))
		{
			case 'webinar':
				$this->validateWebinar();
				break;
			
			case 'collection':
			default:
				$this->validateCollection();
				break;
		}

		if($this->form_validation->run() === false)
			throw new Exception($this->form_validation->error_string(), 1);
	}

	private function validateWebinar()
	{
		$this->form_validation->set_rules('video', 'Ссылка на трейлер / вебинар', ['required', ['video', function($value){
			if(isValidYoutubeVideoUrl($value) === false)
				$this->form_validation->set_message('video', 'Неверный источник видео');

			return true;
		}]]);
		$this->form_validation->set_rules('date', 'Дата начала', 'required');
	}

	private function validateCollection()
	{
		$this->form_validation->set_rules('video', 'Ссылка на трейлер / вебинар', ['required', ['video', function($value){
			if(isValidYandexVideoUrl($value) === false)
				$this->form_validation->set_message('video', 'Неверный источник видео');

			return true;
		}]]);

		$this->form_validation->set_rules('video_list', 'Видео', [['video_list', function($value){
			$value = json_decode($value, true);
			try
			{
				if(empty($value))
					throw new Exception('Список видео пуст', 1);
				
				foreach($value as $key => $row)
				{
					if(empty($row['name']))
						throw new Exception('пустое название на строке ' . ($key + 1), 1);
					elseif(empty($row['url']))
						throw new Exception('пустая ссылка на строке ' . ($key + 1), 1);

					if(isValidYandexVideoUrl($row['url']) === false)
						throw new Exception('неверный источник видео на строке' . ($key + 1), 1);
				}
			}
			catch(Exception $e)
			{
				$this->form_validation->set_message('video_list', 'Поле Видео '.$e->getMessage());
				return false;
			}

			return true;
		}]]);
	}
}