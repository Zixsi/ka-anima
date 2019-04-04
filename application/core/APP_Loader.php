<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require APPPATH."third_party/MX/Loader.php";

class APP_Loader extends MX_Loader
{
	public $layout = 'index';
	private $content_view = null;

	public function lview($view, $vars = [], $return = FALSE)
	{
		$this->content_view = $view;
		return $this->view($this->layout, $vars, $return);
	}

	public function content()
	{
		$this->view($this->content_view);
	}
}

