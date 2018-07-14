<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function Login()
	{
		try
		{
			$data = $this->input->post(null, true);
			$this->form_validation->set_data($data);

			if($this->form_validation->run('signin') == FALSE)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			if(($res = $this->UserModel->GetByEmail($data['email'])) == FALSE)
			{
				throw new Exception("User not found", 1);
			}

			if($this->UserModel->PwdHash($data['password']) !== $res['password'])
			{
				throw new Exception("Invalid email or password", 1);
			}

			unset($res['password']);
			if(isset($data['remember']))
			{
				$time = 3600 * 24 *30;
				set_cookie('remember', '1', $time);
				set_cookie('email', $data['email'], $time);
			}
			else
			{
				delete_cookie('remember');
				delete_cookie('email');
			}

			$this->SetUser($res);
			
			return true;
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
			//var_dump($e->getMessage());
		}

		return false;
	}

	public function Register()
	{
		try
		{
			$data = $this->input->post(null, true);
			$this->form_validation->set_data($data);

			if($this->form_validation->run('signup') == FALSE)
			{
				throw new Exception($this->form_validation->error_string(), 1);
			}

			$user_fields = [
				'email' => $data['email'],
				'password' => $this->UserModel->PwdHash($data['password']),
				'active' => 1,
				'hash' => sha1(time())
			];
			if($user_id = $this->UserModel->Add($user_fields))
			{
				$user = $this->UserModel->GetByID($user_id);
				$this->SetUser($user);
			}
			
			return true;
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
			//var_dump($e->getMessage());
		}

		return false;
	}

	public function GetLoginRemember()
	{
		return [
			'email' => get_cookie('email', true),
			'remember' => (bool) get_cookie('remember', true)
		];
	}

	public function Logout()
	{
		$this->session->sess_destroy();
		return true;
	}

	public function SetUser($user = [])
	{
		$this->session->set_userdata('USER', $user);
	}

	public function User()
	{
		return $this->session->userdata('USER');
	}

	public function UserID()
	{
		return $this->User()['id'];
	}

	public function Check()
	{
		if(($user = $this->User()) == false )
		{
			return false;
		}

		if(isset($user['id']) == false || $user['id'] < 1 || intval($user['active']) === 0)
		{
			return false;
		}

		return true;
	}
}