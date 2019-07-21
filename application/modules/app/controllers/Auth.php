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
		$this->load->lview('auth/register', []);
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
		$data = ['success' => false];
		$data['success'] = $this->Auth->confirm(($_GET['code'] ?? ''));
		
		$this->load->lview('auth/confirmation', $data);
	}

	
}
