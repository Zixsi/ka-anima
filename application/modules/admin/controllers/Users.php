<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/UserModel']);
	}

	public function index()
	{
		$data = [];
		$data['filter'] = $this->input->get();
		$data['items'] = $this->UserModel->list($data['filter'], ['id' => 'desc']);
		$data['roles'] = UserModel::ROLES_NAME;

		$this->load->lview('users/index', $data);
	}

	public function user($id = null)
	{
		$data = [];
		if(($data['item'] = $this->UserModel->getByID($id)) === false)
			header('Location: ../');
		$data['roles'] = UserModel::ROLES_NAME;

		$this->load->lview('users/item', $data);
	}

	public function listForSelect()
	{
		$filter = [
			'role' => 0,
			'search' => ($_GET['q'] ?? '')
		];
		$result = $this->UserModel->listForSelect($filter);
		echo json_encode($result); die();
	}
}
