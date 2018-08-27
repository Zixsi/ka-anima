<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/SubscriptionModel', 'main/CoursesModel', 'main/CoursesGroupsModel']);
	}
	
	public function index()
	{
		$data = [];
		$data['items'] = $this->SubscriptionModel->byUser($this->Auth->userID());
		//debug($data['items']); die();

		$this->load->lview('subscription/index', $data);
	}
}
