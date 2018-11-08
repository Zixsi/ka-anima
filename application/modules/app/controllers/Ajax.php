<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$content = ['files' => []];
		
		$this->load->config('upload');
		$upload_config = $this->config->item('upload_lectures');
		$this->load->library('upload', $upload_config);

		$this->prepareFiles('files');
		if($this->upload->do_upload('files') == false)
		{
			$content['error'] = $this->upload->display_errors();
		}
		else
		{
			if(($file_id = $this->FilesModel->saveFileArray($this->upload->data())) == false)
			{
				$content['error'] = $this->FilesModel->LAST_ERROR;
			}
			
			$_FILES['files']['id'] = $file_id;
			$content['files'][] = $_FILES['files'];
		}

		$json = json_encode($content);

		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-type: application/json');

		echo $json;
	}

	private function prepareFiles($name)
	{
		if(isset($_FILES[$name]) && is_array($_FILES[$name]))
		{
			$files = [];
			foreach($_FILES[$name] as $key => $val)
			{
				$files[$key] = $val[0];
			}

			$_FILES[$name] = $files;
		}
	}
}
