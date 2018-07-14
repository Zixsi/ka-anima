<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function index()
	{
		$this->load->lview('main/index');
	}
}
