<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends APP_Controller
{
	public function index()
	{
		//echo 'main index';
		$this->load->lview('main/index');
	}
}
