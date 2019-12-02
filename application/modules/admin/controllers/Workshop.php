<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(CLASSES_DIR  . 'WorkshopEntity.php');

class Workshop extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];

		$this->load->lview('workshop/index', $data);
	}

	public function item($id = 0)
	{
		$data = [];

		$this->load->lview('workshop/item', $data);
	}

	public function add()
	{
		$data = [];
		$data['error'] = null;

		$params = $this->input->post(null, true);
		$item = new WorkshopEntity($params);

		if(cr_valid_key())
		{
			try
			{	
				$this->WorkshopModel->validate($item->toDbArray());
				if($img = uploadFile('img', 'upload_workshop'))
					$item->setImg(get_rel_path($img['full_path']));
				$item->makeCode();
				$this->WorkshopModel->add($item->toDbArray());
				redirect('/admin/workshop/');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['item'] = $item->toArray();
		$data['csrf'] = cr_get_key();
		$this->load->lview('workshop/add', $data);
	}
}
