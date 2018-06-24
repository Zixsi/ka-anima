<?php

class APP_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$GLOBALS['EXT']->call_hook('pre_controller_constructor');
	}
}
