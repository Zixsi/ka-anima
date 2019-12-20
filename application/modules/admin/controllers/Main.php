<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends APP_Controller
{
	public function index()
	{
		$data = [];
		$data['period'] = ($this->input->get('period', true) ?? 'mounth');

		$data['stat']['transactions']['items'] = $this->TransactionsHelper->getStat($data['period']);
		$this->StatsHelper->prepare($data['stat']['transactions']['items']);
		$data['stat']['transactions']['chart'] = $this->StatsHelper->prepareChart($data['stat']['transactions']['items']);
		$data['stat']['transactions']['info'] = [
			'total_amount' => $this->TransactionsModel->getTotalAmount()
		];

		$data['stat']['users']['items'] = $this->UsersHelper->getStatRegistration($data['period']);
		$this->StatsHelper->prepare($data['stat']['users']['items']);
		$data['stat']['users']['chart'] = $this->StatsHelper->prepareChart($data['stat']['users']['items']);
		$data['stat']['users']['info'] = [
			'total' => $this->UserModel->getCountTotal(),
			'active' => $this->UserModel->getCountActive(),
			'blocked' => $this->UserModel->getCountBlocked()
		];

		$data['stat']['courses']['summary']['items'] = $this->TransactionsModel->getCourseSummaryStat();
		$data['stat']['courses']['summary']['chart'] = $this->StatsHelper->prepareChartCoursesPie($data['stat']['courses']['summary']['items']);
		$data['stat']['courses']['months']['items'] = $this->TransactionsHelper->getCourseStatByMonths();
		$data['stat']['courses']['months']['chart'] = $this->StatsHelper->prepareChartCoursesMonth($data['stat']['courses']['months']['items']);


		$workshopItems = $this->SubscriptionHelper->getStatCount($data['period'], 'workshop');
		$data['stat']['workshop']['chart'] = $this->StatsHelper->prepareChart($workshopItems);

		$this->load->lview('main/index', $data);
	}
}
