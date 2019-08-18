<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->Auth->isActive())
			header('Location: /');
	}

	public function index()
	{
		$data = [];
		$data['message'] = null;
		$user_id = (int) $this->Auth->userID();

		if(($id = (int) $this->input->post('id', true)) > 0)
		{
			if($this->UserFriendsModel->add($user_id, intval($id)) !== false)
			{
				$data['message']['type'] = true;
				$data['message']['text'] = 'Success';
			}
		}

		$data['users'] = $this->UserModel->listAllForUser($user_id);

		$this->load->lview('users/index', $data);
	}
}
