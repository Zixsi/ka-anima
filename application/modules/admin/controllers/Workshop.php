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
		$data['items'] = $this->WorkshopModel->getList();
		$data['subscribeCount'] = $this->SubscriptionModel->getSubscribeCount(null, 'workshop');
		$data['subscribeCount'] = extractValues($data['subscribeCount'], 'count', 'id');

		$this->load->lview('workshop/index', $data);
	}

	public function item($id = 0)
	{
		$data = [];
		if(($item = $this->WorkshopModel->getItem($id)) === null)
			show_404();

		$data['error'] = null;
		$item = new WorkshopEntity($item);
		$params = $this->input->post(null, true);

		// debug($item); die();

		if(cr_valid_key())
		{
			try
			{	
				$item->parseParams($params);
				$this->WorkshopModel->validate($item->toDbArray());
				if($img = uploadFile('img', 'upload_workshop'))
					$item->setImg(get_rel_path($img['full_path']));

				if($img = uploadFile('img_land_bg', 'upload_workshop'))
					$item->setImgLandBg(get_rel_path($img['full_path']));
				
				$this->WorkshopModel->update($item->getId(), $item->toDbArray());
				redirect('/admin/workshop/item/'.$item->getId().'/');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['csrf'] = cr_get_key();
		$data['item'] = $item->toArray();
		$data['teachers'] = $this->UserModel->listTeachers();
		$data['videos'] = $this->VideoModel->getList(['source_id' => $data['item']['id'], 'source_type' => 'workshop']);
		// debug($data['videos']); die();

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

				if($img = uploadFile('img_land_bg', 'upload_workshop'))
					$item->setImgLandBg(get_rel_path($img['full_path']));
				
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
		$data['teachers'] = $this->UserModel->listTeachers();
		$data['csrf'] = cr_get_key();
		$this->load->lview('workshop/add', $data);
	}

	public function view($id = 0)
	{
		$data = [];
		if(($item = $this->WorkshopModel->getItem($id)) === null)
			show_404();

		$data['item'] = $item;
		$data['subscriptionUsers'] = $this->SubscriptionModel->getGroupUsers($id, null, 'workshop');

		// debug($data['subscriptionUsers']); die();
		$this->load->lview('workshop/view', $data);
	}

}
