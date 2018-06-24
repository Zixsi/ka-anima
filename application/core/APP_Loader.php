<?php

class APP_Loader extends CI_Loader
{
	public $layout = 'index';
	private $_content_view = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function lview($view, $vars = array(), $return = FALSE)
	{
		$this->_content_view = $view;
		return $this->view($this->layout, $vars, $return);
	}

	public function content()
	{
		$this->view($this->_content_view);
	}
}
