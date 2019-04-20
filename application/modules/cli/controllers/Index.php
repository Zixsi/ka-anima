<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		var_dump('INDEX CLI');
	}

	public function test()
	{
		var_dump('TEST CLI');
	}
}
