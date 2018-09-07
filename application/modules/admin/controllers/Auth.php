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

		if($this->Auth->login()) redirect('/admin/');
		
		$data['form'] = $this->Auth->getLoginRemember();
		$data['error'] = $this->Auth->LAST_ERROR;

		$this->load->lview('auth/login', $data);
	}

	public function logout()
	{
		$this->Auth->logout();
		redirect('/admin/');
	}
}
