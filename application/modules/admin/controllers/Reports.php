<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends APP_Controller
{

	public function pay()
	{
		$data = [];
		$data['date'] = ($this->input->get('date') ?? date(DATE_FORMAT_DB_SHORT));

		$filter = [
			'type' => 'IN',
			'status' => 'SUCCESS',
			'ts >=' => $data['date'].' 00:00:00',
			'ts <=' => $data['date'].' 23:59:59',
		];
		$data['items'] = $this->TransactionsModel->list($filter);
		$data['users'] = [];
		if(count($data['items']))
		{
			$usersId = extractValues($data['items'], 'user');
			$data['users'] = $this->UserModel->list(['id' => array_unique($usersId)]);
			$data['users'] = groupByField($data['users'], 'id', true);
		}
		// debug($data['users']); die();

		$this->load->lview('reports/pay', $data);
	}

}
