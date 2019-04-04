<?php

class APP_Model extends CI_Model
{
	public $LAST_ERROR = null;
	private $last_error_code = 0;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function setLastError($text, $code = 0)
	{
		$this->LAST_ERROR = strval($text);
		$this->last_error_code = intval($code);
	}

	public function getLastError()
	{
		return $this->LAST_ERROR;
	}

	public function getLastErrorCode()
	{
		return $this->last_error_code;
	}
}
