<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsHelper extends APP_Model
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

			// $this->form_validation->reset_validation();
			// $this->form_validation->set_data($data);

			// // проверка полей
			// if($this->form_validation->run('user_add') == FALSE)
			// 	// $this->form_validation->error_string()
			// 	throw new Exception(current($this->form_validation->error_array()), -32602);

			// $data['active'] = (int) ($data['active'] ?? 1);
			// $data['password'] = $this->UserModel->pwdHash($data['password']);
			// $data['hash'] = sha1(time());
			// unset($data['re_password']);

			// if(($id = $this->UserModel->add($data)) === false)
			// 	throw new Exception('Ошибка создания', -32603);

			// if($this->db->trans_status() === FALSE)
			// 	throw new Exception('Ошибка создания', -32603);

			// $this->UserActionsModel->add();

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