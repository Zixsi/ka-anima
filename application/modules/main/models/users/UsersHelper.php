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
		try
		{	
			$this->db->trans_begin();

			$this->form_validation->reset_validation();
			$this->form_validation->set_data($data);

			// проверка полей
			if($this->form_validation->run('user_add') == FALSE)
				// $this->form_validation->error_string()
				throw new Exception(current($this->form_validation->error_array()), -32602);

			$data['active'] = (int) ($data['active'] ?? 1);
			$data['password'] = $this->UserModel->pwdHash($data['password']);
			$data['hash'] = sha1(time());
			unset($data['re_password']);

			if(($id = $this->UserModel->add($data)) === false)
				throw new Exception('Ошибка создания', -32603);

			if($this->db->trans_status() === FALSE)
				throw new Exception('Ошибка создания', -32603);

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

	public function edit($id, $data = [])
	{
		try
		{	
			$this->db->trans_begin();

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
			
			if($this->db->trans_status() === FALSE)
				throw new Exception('Ошибка редактирования', 1);

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

	public function remove($id)
	{
		try
		{	
			$this->db->trans_begin();

			if(($item = $this->UserModel->getByID($id)) === false)
				throw new Exception('Пользователь не найден', -32602);

			if($item['role'] == '5')
				throw new Exception('Действие запрещено', -32603);

			$this->UserModel->update($id, ['deleted' => 1]);

			if($this->db->trans_status() === FALSE)
				throw new Exception('Ошибка удаления', -32603);

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

	public function block($id)
	{
		try
		{	
			$this->db->trans_begin();

			if(($item = $this->UserModel->getByID($id)) === false)
				throw new Exception('Пользователь не найден', -32602);

			if($item['role'] == '5')
				throw new Exception('Действие запрещено', -32603);

			$this->UserModel->update($id, ['blocked' => 1]);

			if($this->db->trans_status() === FALSE)
				throw new Exception('Ошибка блокировки', -32603);

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

	public function unblock($id)
	{
		try
		{	
			$this->db->trans_begin();

			if(($item = $this->UserModel->getByID($id)) === false)
				throw new Exception('Пользователь не найден', -32602);

			$this->UserModel->update($id, ['blocked' => 0]);

			if($this->db->trans_status() === FALSE)
				throw new Exception('Ошибка разблокировки', -32603);

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
}