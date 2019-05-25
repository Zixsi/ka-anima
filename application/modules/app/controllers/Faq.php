<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends APP_Controller
{
	public function index()
	{
		$data = [];
		$data['items'] = $this->FaqModel->list();

		$this->load->lview('faq/index', $data);
	}
}
