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
			throw new Exception(current($this->form_validation->error_array()), -32602);

		$data['active'] = (int) ($data['active'] ?? 1);
		$data['password'] = $this->UserModel->pwdHash($data['password']);
		$data['hash'] = sha1(time());
		unset($data['re_password']);

		if(($id = $this->UserModel->add($data)) === false)
			throw new Exception('Ошибка создания', -32603);

		return true;
	}

	public function edit($id, $data = [])
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', -32602);

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
			throw new Exception(current($this->form_validation->error_array()), -32602);


		if(isset($data['password']) && empty($data['password']))
			unset($data['password']);
		elseif(isset($data['password']) && !empty($data['password']))
		{
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('password', 'Пароль', 'trim|min_length[6]|max_length[64]');
			$this->form_validation->set_rules('re_password', 'Повтор пароля', 'trim|matches[password]');
			$this->form_validation->set_data($data);
			if($this->form_validation->run() == FALSE)
				throw new Exception(current($this->form_validation->error_array()), -32602);

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
			throw new Exception('Пользователь не найден', -32602);

		if($item['role'] == '5')
			throw new Exception('Действие запрещено', -32603);

		$this->UserModel->update($id, ['deleted' => 1]);

		return true;
	}

	public function block($id)
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', -32602);

		if($item['role'] == '5')
			throw new Exception('Действие запрещено', -32603);

		$this->UserModel->update($id, ['blocked' => 1]);

		return true;
	}

	public function unblock($id)
	{
		if(($item = $this->UserModel->getByID($id)) === false)
			throw new Exception('Пользователь не найден', -32602);

		$this->UserModel->update($id, ['blocked' => 0]);

		return true;
	}

	// подготовка изображения профиля перед установкой
	public function prepareProfileImg($name)
	{
		if(empty(($_FILES[$name] ?? null)))
			return false;

		$this->load->config('upload');
		$this->upload_config = $this->config->item('upload_user_profile');
		$this->load->library('upload', $this->upload_config);

		if($this->upload->do_upload($name) == false)
			throw new Exception($this->upload->display_errors(), 1);


		$img = $this->upload->data();
		var_dump($img);

		// get_rel_path($data['full_path'])

		// $img['image_width']
		// $img['image_height']
		// $img['file_name']

		// $config = [
		// 	'image_library' => 'imagemagick',
		// 	'source_image' => $img['full_path'],
		// 	'x_axis' => 0,
		// 	'y_axis' => 0
		// ];

		// $this->image_lib->initialize($config);
		// if(!$this->image_lib->crop())
		// 	throw new Exception($this->image_lib->display_errors(), 1);

		
		die();
	}
}