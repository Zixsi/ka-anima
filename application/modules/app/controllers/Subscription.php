<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data = [];
		$data['error'] = null;

		$user_id = $this->Auth->userID();
		
		if(cr_valid_key())
		{
			$this->renewItem($data);
			$this->pay();
		}
		$data['csrf'] = cr_get_key();

		$data['balance'] = $this->Auth->balance();
		$data['items'] = $this->SubscriptionModel->byUser($user_id);
		$data['transactions']['in'] = $this->TransactionsModel->listUserTxByType($user_id, 0);
		$data['transactions']['out'] = $this->TransactionsModel->listUserTxByType($user_id, 1);

		$this->prepareSubscription($data['items']);
		

		$this->load->lview('subscription/index', $data);
	}

	private function renewItem(&$data)
	{
		if($this->input->post('request_type', true) == 'renew')
		{
			$data_post = $this->input->post(null, true);
			if($this->SubscriptionModel->renewItem($data_post['id']) == false)
			{
				$data['error'] = $this->SubscriptionModel->LAST_ERROR;
			}
		}
	}

	private function pay()
	{
		if($this->input->post('request_type', true) == 'pay')
		{
			$fields = [
				'user' => $this->Auth->userID(),
				'type' => '0',
				'amount' => abs($this->input->post('amount', true)),
				'description' => 'PaySystem'
			];

			if($this->TransactionsModel->add($fields))
			{
				 $this->Auth->updateBalance();
			}
		}
	}

	private function prepareSubscription(&$items)
	{
		$user_id = $this->Auth->userID();
		//debug($items); die();
		//debug($user); die();

		if($items)
		{
			foreach($items as &$item)
			{
				$item['renew'] = null;

				switch($item['type'])
				{
					// Если подписка на курс
					case 0:

						// Если не все оплачено по месяцам
						if($item['amount'] > 0)
						{
							$item['renew'] = 'month';
						}
						// Если оплатили все м кончилась подписка
						elseif(strtotime($item['ts_end']) < time())
						{
							$item['renew'] = 'year';
						}

					break;

					default:
						//
					break;
				}
			}
		}
	}
}
