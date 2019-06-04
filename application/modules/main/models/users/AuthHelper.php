<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function login($data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);

		if($this->form_validation->run('auth_login') === false)
			throw new AppBadLogicExtension($this->form_validation->error_string());

		if(($res = $this->UserModel->getByEmail($data['email'])) === false)
			throw new AppBadLogicExtension('Пользователь не найден');

		if($this->UserModel->pwdHash($data['password']) !== $res['password'])
			throw new AppBadLogicExtension('Неверный логин или пароль');

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

		$res['balance'] = $this->TransactionsModel->balanceUser($res['id']);
		$res['last_update'] = time();

		$this->setUser($res);
		action(UserActionsModel::ACTION_LOGIN);
		
		return true;
	}

	public function register($data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);

		if($this->form_validation->run('auth_register') === false)
			throw new AppBadLogicExtension($this->form_validation->error_string());

		if((bool) ($data['agree'] ?? false) === false)
			throw new AppBadLogicExtension('Необходимо принять условия соглашения');

		$user_fields = [
			'email' => $data['email'],
			'name' => $data['name'],
			'lastname' => $data['lastname'],
			'password' => $this->UserModel->pwdHash($data['password']),
			'active' => 0,
			'hash' => sha1($data['email'].time())
		];

		if($user_id = $this->UserModel->add($user_fields))
		{
			// отправляем письмо
			$email_params = [
				'email' => $user_fields['email'],
				'code' => $user_fields['hash']
			];
			if($this->EmailHelper->registration($email_params) === false)
				throw new AppBadLogicExtension('Произошла ошибка при отправке письма');

			// авторизуем
			$user = $this->UserModel->getByID($user_id);
			$this->setUser($user);
			action(UserActionsModel::ACTION_LOGIN);
		}
			
		return true;
	}

	public function confirm($code)
	{
		try
		{
			if(($user = $this->UserModel->getByHash($code)) === false)
				throw new AppBadLogicExtension('Неверный код подтверждения');

			if((int) $user['active'] === 1)
				return true;

			return $this->UserModel->setActive($user['id'], true);
		}
		catch(Exception $e)
		{
			// 
		}

		return false;
	}

	public function forgot($email)
	{
		if(($user = $this->UserModel->getByEmail($email)) === false)
			throw new AppBadLogicExtension('Неверный адрес электронной почты');

		$email_params = [
			'email' => $user['email'],
			'code' => $user['hash']
		];
		if($this->EmailHelper->forgot($email_params) === false)
			throw new AppBadLogicExtension('Произошла ошибка при отправке письма');

		return true;
	}

	public function recovery($data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);

		if($this->form_validation->run('auth_recovery') === false)
			throw new AppBadLogicExtension($this->form_validation->error_string());

		if(($user = $this->UserModel->getByHash(($data['code'] ?? ''))) === false)
			throw new AppBadLogicExtension('Неверный код');

		$params = [
			'password' => $this->UserModel->pwdHash($data['password']),
			'hash' => sha1($user['email'].time())
		];
		$this->UserModel->update($user['id'], $params);
			
		return true;
	}

	public function getLoginRemember()
	{
		return [
			'email' => get_cookie('email', true),
			'remember' => (bool) get_cookie('remember', true)
		];
	}

	public function logout()
	{
		$this->session->sess_destroy();
	}

	public function setUser($user = [])
	{
		$this->load->library('session');
		$this->session->set_userdata('USER', $user);
	}

	public function user()
	{
		if(!isset($_SESSION))
			return false;

		return $this->session->userdata('USER');
	}

	public function userID()
	{
		return (int) ($this->user()['id'] ?? 0);
	}

	public function userRole()
	{
		return (int) $this->user()['role'];
	}

	public function isUser()
	{
		return ($this->userRole() === 0)?true:false;
	}

	public function isTeacher()
	{
		return ($this->userRole() === 1)?true:false;
	}

	public function isAdmin()
	{
		return ($this->userRole() === 5)?true:false;
	}

	public function check()
	{
		return (($user = $this->user()) == false || isset($user['id']) == false
		 || $user['id'] < 1 || (int) $user['deleted'] === 1 || (int) $user['blocked'] === 1)?false:true;
	}

	// Проверка доступа
	public function checkAccess($params = [])
	{
		if($user = $this->user())
		{
			foreach($params as $val)
			{
				if($this->AccessModel->check($user['role'], $val[0], $val[1]) === true)
				{
					return true;
				}
			}
		}

		return false;
	}

	public function balance()
	{
		$user = $this->user();
		return isset($user['balance'])?$user['balance']:0;
	}

	public function updateBalance()
	{
		$user = $this->user();
		$user['balance'] = $this->TransactionsModel->balanceUser($user['id']);
		$this->session->set_userdata('USER', $user);
	}
}