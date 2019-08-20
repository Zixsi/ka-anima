<?php

class APP_Form_validation extends CI_Form_validation
{
	public function __construct($rules = array())
	{
		parent::__construct($rules);
	}

	public function alpha_numeric_spaces_ru($str)
	{
		return (bool) preg_match('/^[A-ZА-ЯЁёЙй0-9 ]+$/ui', $str);
	}
}
