<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->layout = 'auth';
	}

	public function index()
	{
		$this->load->lview('auth/login');
	}

	public function logout()
	{
		echo 'logout';
	}
}
