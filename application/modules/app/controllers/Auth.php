<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->layout = 'auth';
	}

	public function index()
	{
		$data = [];
		$data['remembered'] = $this->Auth->getLoginRemember();

		// $this->EmailHelper->registration(['email' => 'zixxsi@gmail.com', 'code' => '123456']);
		// die();

		$this->load->lview('auth/login', $data);
	}

	public function logout()
	{
		$this->Auth->logout();
		redirect('/');
	}

	public function register()
	{
		$data = [];
		$data['landUrl'] = $this->config->item('land_url');

		$this->load->lview('auth/register', $data);
	}

	public function forgot()
	{
		$data = [];

		$this->load->lview('auth/forgot', $data);
	}

	public function recovery()
	{
		$data = [];
		$data['code'] = ($_GET['code'] ?? '');

		$this->load->lview('auth/recovery', $data);
	}
	
	public function confirmation()
	{	
		$data['message'] = [
			'status' => false, 
			'text' => null
		];

		try
		{
			$this->Auth->confirm(($_GET['code'] ?? ''));
			$data['message'] = [
				'status' => true, 
				'text' => 'Регистрация успешно подтверждена. Пожалуйста, авторизуйтесь, перейдя по ссылке'
			];
		}
		catch(Exception $e)
		{
			$data['message']['text'] = $e->getMessage();
		}
		
		$this->load->lview('auth/confirmation', $data);
	}

	public function soc()
	{
		$user_soc = $this->ulogin->getUser();
		if(empty($user_soc) || empty($user_soc->getUid()))
			redirect('/');

		$login = $user_soc->makeEmail();
		$user = $this->UserModel->getByLogin($login);

		if ($this->Auth->check()) {

			// предупреждение о том что будет произведена привязка / уже есть привязка
			
			// if ($user) {
			// 	// если пользователь авторизован и есть в системе - редирект на главную
			// 	$this->UserModel->setParent($user['id'], $this->Auth->getUserId());
			// }
			// else {
			// 	// если пользователь авторизован и нет привязки к соц. сети - спрашиваем и привязываем если согласился
			// 	$this->AuthSoc->socRegister($user_soc, $this->Auth->getUserId());
			// }
		}
		else {
			if ($user) {
				// если пользователь неавторизован и есть в системе - авторизуем (через аккаунт соц. сети или связаный с ним обычный)
				$id = (((int) $user['parent'] > 0)?$user['parent']:$user['id']);
				$this->AuthSoc->socAuth($id);
			}
			else {
				// если пользователь неавторизован и нет в системе - регистрируем
				if ($id = $this->AuthSoc->socRegister($user_soc)) {
					$this->AuthSoc->socAuth($id);
				}
			}
		}
		
		redirect('/');
	}
}
