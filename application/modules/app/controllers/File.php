<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// 
	}

	public function download($id = 0)
	{
		if($file = $this->FilesModel->getByID($id))
		{
			$this->load->helper('download');
			if(file_exists($file['full_path']))
			{
				force_download($file['full_path'], NULL);
			}
		}

		show_404();
	}
}
