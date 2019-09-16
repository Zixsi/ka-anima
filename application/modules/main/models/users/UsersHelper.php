<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);

		// проверка полей
		if($this->form_validation->run('user_add') == FALSE)
			throw new Exception(current($this->form_validation->error_array()), 1);

		$data['active'] = (int) ($data['active'] ?? 1);
		$data['password'] = $this->UserModel->pwdHash($data['password']);
		$data['hash'] = sha1(time());
		unset($data['re_password']);

		if(($id = $this->UserModel->add($data)) === false)
			throw new Exception('Ошибка создания', 1);

		return true;
	}

	public function edit($id, $data = [])
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', 1);

		$this->form_validation->reset_validation();

		if(isset($data['role']))
			$this->form_validation->set_rules('role', 'Роль', 'required|in_list[0,1]');

		if(isset($data['email']) && $data['email'] !== $item['email'])
			$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[users.email]');

		if(isset($data['name']))
			$this->form_validation->set_rules('name', 'Имя', 'required|min_length[2]');

		if(isset($data['lastname']))
			$this->form_validation->set_rules('lastname', 'Фамилия', 'required|min_length[2]');

		$this->form_validation->set_data($data);
		if($this->form_validation->run() == FALSE)
			throw new Exception(current($this->form_validation->error_array()), 1);

		if(isset($data['birthday']))
			$data['birthday'] = date(DATE_FORMAT_DB_SHORT, strtotime($data['birthday']));

		if(isset($data['password']) && empty($data['password']))
			unset($data['password']);
		elseif(isset($data['password']) && !empty($data['password']))
		{
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('password', 'Пароль', 'trim|min_length[6]|max_length[64]');
			$this->form_validation->set_rules('re_password', 'Повтор пароля', 'trim|matches[password]');
			$this->form_validation->set_data($data);
			if($this->form_validation->run() == FALSE)
				throw new Exception(current($this->form_validation->error_array()), 1);

			$data['password'] = $this->UserModel->pwdHash($data['password']);
		}
		unset($data['re_password']);

		if($this->UserModel->update($id, $data) === false)
			throw new Exception('Ошибка редактирования', 1);

		return true;
	}

	public function remove($id)
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', 1);

		if($item['role'] == '5')
			throw new Exception('Действие запрещено', 1);

		$this->UserModel->update($id, ['deleted' => 1]);

		return true;
	}

	public function block($id)
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', 1);

		if($item['role'] == '5')
			throw new Exception('Действие запрещено', 1);

		$this->UserModel->update($id, ['blocked' => 1]);

		return true;
	}

	public function unblock($id)
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', 1);

		$this->UserModel->update($id, ['blocked' => 0]);

		return true;
	}

	// подготовка изображения профиля перед установкой
	public function prepareProfileImg($name)
	{
		if(empty(($_FILES[$name] ?? null)) || (int) $_FILES[$name]['size'] === 0)
			return false;

		$this->load->config('upload');
		$this->upload_config = $this->config->item('upload_user_profile');
		$this->load->library('upload', $this->upload_config);

		if($this->upload->do_upload($name) == false)
			throw new Exception($this->upload->display_errors(), 1);


		$img = $this->upload->data();
		$pos = calc_crop_rect($img['image_width'], $img['image_height']);

		chmod($img['full_path'], FILE_WRITE_MODE);

		$config_crop = [
			'source_image' => $img['full_path'],
			'maintain_ratio' => false,
			'width' => $pos['width'],
			'height' => $pos['height'],
			'x_axis' => $pos['x'],
			'y_axis' => $pos['y']
		];

		$this->image_lib->clear();
		$this->image_lib->initialize($config_crop);
		if(!$this->image_lib->crop())
		{
			unlink($img['full_path']);
			throw new Exception($this->image_lib->display_errors(), 1);
		}

		return get_rel_path($img['full_path']);
	}

	public function setLastActive($id)
	{
		$this->UserModel->update($id, ['ts_last_active' => date(DATE_FORMAT_DB_FULL)]);
	}

	public function getStatRegistration($period)
	{
		$result = [];
		$to = new DateTime('now');
		$to->setTime(23, 59, 59);
		$from = clone $to;

		switch($period)
		{
			case 'year':
				$from->modify('-1 year');
				break;
			case 'month':
			default:
				$from->modify('-1 month');
				break;
		}

		$from->setTime(0, 0, 0);

		$date_from = $from->format(DATE_FORMAT_DB_FULL);
		$date_to = $to->format(DATE_FORMAT_DB_FULL);

		switch($period)
		{
			case 'year':
				$result = $this->UserModel->getRegistrationStatByMonths($date_from, $date_to);
				break;
			case 'month':
			default:
				$result = $this->UserModel->getRegistrationStatByDays($date_from, $date_to);
				break;
		}

		return $result;
	}
}