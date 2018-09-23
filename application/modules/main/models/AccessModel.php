<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessModel extends APP_Model
{
	private $access = [];

	public function __construct()
	{
		parent::__construct();
		$this->access = $this->config->load('access');
	}

	public function check($role, $dir, $mod)
	{
		if(array_key_exists($role, $this->access) == false)
		{
			return false;
		}

		if(empty($dir) || array_key_exists($dir, $this->access[$role]) == false)
		{
			return false;
		}

		if(empty($mod) || array_key_exists($mod, $this->access[$role][$dir]) == false)
		{
			return false;
		}

		return ($this->access[$role][$dir][$mod] === 1)?true:false;
	}
}