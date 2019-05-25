<?php

class APP_Model extends CI_Model
{
	public $last_error = null;
	private $last_error_code = 0;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function setLastException($e)
	{
		$this->last_error = (string) $e->getMessage();
		$this->last_error_code = (int) $e->getCode();
	}

	public function setLastError($text, $code = 0)
	{
		$this->last_error = (string) $text;
		$this->last_error_code = (int) $code;
	}

	public function getLastError()
	{
		return $this->last_error;
	}

	public function getLastErrorCode()
	{
		return $this->last_error_code;
	}
}
