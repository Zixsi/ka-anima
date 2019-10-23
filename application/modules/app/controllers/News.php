<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends APP_Controller
{
	public function index()
	{
		show_404();
		// empty
	}

	public function item($id = null)
	{ 
		$data = [];
		if(($data['item'] = $this->NewsModel->getById($id)) === false)
			show_404();

		// $data['item']['text'] = url2link(htmlspecialchars_decode($data['item']['text']));

		$this->load->lview('news/item', $data);
	}
}
