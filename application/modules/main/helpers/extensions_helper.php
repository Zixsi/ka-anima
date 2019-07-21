<?php

class AppBadLogicExtension extends Exception
{
	private $string_code = null;

	public function __construct($message = null, $code = null, Exception $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->string_code = $code;
	}

	final public function getCodeString()
	{
		return $this->string_code;
	}
}