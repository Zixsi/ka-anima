<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data = [];
		$data['items'] = $this->TransactionsHelper->listByUser($this->Auth->userID());

		$this->load->lview('transactions/index', $data);
	}
}
