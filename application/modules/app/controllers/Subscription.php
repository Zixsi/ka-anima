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
		$data['error'] = null;

		$user_id = $this->Auth->userID();
		
		if(cr_valid_key())
		{
			$data_post = $this->input->post(null, true);
			if($this->SubscriptionModel->renewItem($data_post['id']) == false)
			{
				$data['error'] = $this->SubscriptionModel->LAST_ERROR;
			}
		}
		$data['csrf'] = cr_get_key();

		$data['items'] = $this->SubscriptionModel->byUser($user_id);
		$this->prepareSubscription($data['items']);
		

		$this->load->lview('subscription/index', $data);
	}

	private function prepareSubscription(&$items)
	{
		$user_id = $this->Auth->userID();
		$this->load->model(['main/TransactionsModel', 'main/LecturesModel']);
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
