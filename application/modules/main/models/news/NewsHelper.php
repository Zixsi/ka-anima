<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// подготовка изображения 
	public function prepareImg($name)
	{
		if(empty(($_FILES[$name] ?? null)) || (int) $_FILES[$name]['size'] === 0)
			return false;

		$this->load->config('upload');
		$this->upload_config = $this->config->item('upload_news');
		$this->load->library('upload', $this->upload_config);

		if($this->upload->do_upload($name) == false)
			throw new Exception($this->upload->display_errors(), 1);

		$img = $this->upload->data();
		return get_rel_path($img['full_path']);
	}
}