<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = $this->Auth->user();
	}
	
	public function index()
	{
		$data = [];
		$data['items'] = $this->GroupsModel->getTeacherGroups($this->user['id']);

		// debug($data); die();
		$this->load->lview('groups/index', $data);
	}

	public function item($code)
	{
		$data = [];
		if(($data['item'] = $this->GroupsModel->getByCode($code)) === false)
			show_404();

		$type = ($data['item']['type'] === 'standart')?'advanced':$data['item']['type'];
		$data['users'] = $this->SubscriptionModel->getGroupUsers($data['item']['id'], $type);

		// debug($data); die();
		$this->load->lview('groups/item', $data);
	}
	
}
