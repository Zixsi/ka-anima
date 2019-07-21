<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends APP_Controller
{
	public $user_id = null;

	public function __construct()
	{
		parent::__construct();
		$this->user_id = intval($this->Auth->userID());
	}

	public function index()
	{
		$data = [];
		$data['message'] = null;

		if(($id = (int) $this->input->post('id', true)) > 0)
		{
			if($this->UserFriendsModel->add($this->user_id, intval($id)) !== false)
			{
				$data['message']['type'] = true;
				$data['message']['text'] = 'Success';
			}
		}

		$data['users'] = $this->UserModel->listAllForUser($this->user_id);

		$this->load->lview('users/index', $data);
	}
}
