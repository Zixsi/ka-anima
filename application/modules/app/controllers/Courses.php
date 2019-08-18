<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	// список доступных курсов
	public function index()
	{
		$data = [];
		$data['items'] = $this->CoursesModel->listActive();
		$this->CoursesHelper->prepareOffers($data['items']);

		$this->load->lview('courses/index', $data);
	}

	// подробная информация по курсу
	public function item($code = null)
	{
		$data = [];

		// курс
		if(($data['item'] = $this->CoursesModel->getByCode($code)) === false)
			header('Location: ../');

		$this->CoursesHelper->prepareItem($data['item']);

		// список предложений
		$data['offers'] = $this->GroupsModel->listOffersForCourse($data['item']['id']);
		if(count($data['offers']) && empty($this->input->get('date')))
		{
			$offer = current($data['offers']);
			header('Location: ./?date='.$offer['ts_formated']);
		}

		// список лекций
		$data['lectures'] = $this->LecturesModel->getByCourse($data['item']['id']);
		// преподаватель
		$data['teacher'] = $this->UserModel->getById($data['item']['teacher']);

		$offer_date = $this->input->get('date');
		$data['selected_offer_index'] = $this->GroupsHelper->selectOfferByDate($offer_date, $data['offers']);

		// поиск подходящей вип группы, если её нет - создаем
		$data['vip_offer'] = null;
		if(!$data['item']['only_standart'] && ($vip_group_id = $this->GroupsHelper->makeGroup($data['item'], GroupsModel::TYPE_VIP, time())))
			$data['vip_offer'] = $this->GroupsModel->getByID($vip_group_id);

		$this->load->lview('courses/item', $data);
	}
}
