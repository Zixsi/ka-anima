<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workshop extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];

		$this->load->lview('workshop/index', $data);
	}
}
