<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends APP_Controller
{
	public function index()
	{
		$this->load->model(['main/NewsModel']);

		$data = [];
		$data['news'] = $this->NewsModel->list('desc');

		$this->load->lview('main/index', $data);
	}
}
