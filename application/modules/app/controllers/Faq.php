<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends APP_Controller
{
	public function index()
	{
		$this->load->model(['main/FaqModel']);
		$data = [];
		$data['items'] = $this->FaqModel->list();

		$this->load->lview('faq/index', $data);
	}
}
