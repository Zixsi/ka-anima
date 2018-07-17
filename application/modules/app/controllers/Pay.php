<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/TransactionsModel']);
	}
	
	public function index()
	{
		$data = [];
		$user_id = $this->Auth->UserID();

		if(CrValidKey())
		{
			$fields = [
				'user' => $user_id,
				'type' => 'IN',
				'amount' => $this->input->post('amount', true),
				'description' => 'add balance'
			];

			if($this->TransactionsModel->Add($fields))
			{
				 $this->Auth->UpdateBalance();
			}
		}
		$data['csrf'] = CrGetKey();

		$data['items'] = $this->TransactionsModel->List(['user' => $user_id], ['ts' => 'DESC']);

		$this->load->lview('pay/index', $data);
	}
}
