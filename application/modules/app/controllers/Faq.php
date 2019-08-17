<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends APP_Controller
{
	public function index()
	{
		$data = [];
		$items = $this->FaqModel->list();
		$sections = $this->FaqModel->listSections();
		$this->FaqHelper->splitBySections($items, $sections);

		$data['chunks'] = array_chunk($items, 2);

		$this->load->lview('faq/index', $data);
	}
}
