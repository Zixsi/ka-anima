<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends APP_Controller
{
	public function index()
	{
		$data = [];
		$to = new DateTime('now');
		$to->setTime(23, 59, 59);
		$from = clone $to;
		$from->modify('-1 month');
		$from->setTime(0, 0, 0);

		$data['stat'] = $this->TransactionsModel->getStatByDays($from->format(DATE_FORMAT_DB_FULL), $to->format(DATE_FORMAT_DB_FULL));
		$this->TransactionsHelper->prepareStat($data['stat']);
		$data['stat_chart'] = $this->TransactionsHelper->prepareStatChart($data['stat']);

		$this->load->lview('main/index', $data);
	}
}
