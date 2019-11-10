<?php

require_once APPPATH.'third_party/unisender/UnisenderApi.php';
use Unisender\ApiWrapper\UnisenderApi;

class Unisender
{
	private $key = '6ch1whawgtrwbsu98uoiwuzwqz6mzqfgyu61i9co';
	private $instance;

	public function __construct()
	{
		$this->instance = new UnisenderApi($this->key);
	}

	public function __call($method, $args)
    {
    	return call_user_func_array(array($this->instance, $method), $args);
    }
}