<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeachingStreams extends APP_Controller
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = $this->Auth->user();
	}

	public function index()
	{
		$data = [];
		$data['items'] = $this->StreamsModel->list($this->Auth->userID(), ($_GET['filter'] ?? []));
		// debug($data['items']); die();

		$this->load->lview('teaching_streams/index', $data);
	}

	public function add()
	{
		$data = [];

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			$form_data['author'] = $this->user['id'];
			if($id = $this->StreamsModel->add($form_data))
			{
				$actionParams = [
					'item_id' => $id,  
					'item_name' => $form_data['name'],
				];
				Action::send(Action::STREAM_ADD, [$actionParams]);

				header('Location: ../');
			}
		}

		$data['error'] = $this->StreamsModel->getLastError();
		$filter = [
			'with_subscribed' => true, // с подписанными пользователями
		];
		$data['groups'] = $this->GroupsModel->getTeacherGroups($this->user['id'], true, $filter);

		$data['csrf'] = cr_get_key();

		$this->load->lview('teaching_streams/add', $data);
	}

	public function edit($id)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
			header('Location: ../../');

		if(cr_valid_key())
		{
			$form_data = $this->input->post(null, true);
			if($this->StreamsModel->update($id, $form_data))
			{
				$data['item'] = $this->StreamsModel->getByID($id);

				$actionParams = [
					'item_id' => $data['item']['id'],  
					'item_name' => $data['item']['name'], 
				];
				Action::send(Action::STREAM_UPDATE, [$actionParams]);

				header('Location: ../../');
			}
		}

		$data['error'] = $this->StreamsModel->getLastError();
		$filter = [
			'with_subscribed' => true, // с подписанными пользователями
		];
		$data['groups'] = $this->GroupsModel->getTeacherGroups($this->user['id'], true, $filter);
		$data['csrf'] = cr_get_key();

		$this->load->lview('teaching_streams/edit', $data);
	}

	public function item($id)
	{
		$data = [];
		if(($data['item'] = $this->StreamsModel->getByID($id)) == false)
			header('Location: ../');

		$this->load->library(['youtube']);

		$data['item']['video_code'] = $this->youtube->extractVideoId($data['item']['url']);
		$data['item']['chat'] = $this->youtube->getLiveChatUrl($data['item']['video_code']);
		//debug($data['item']);

		$this->load->lview('teaching_streams/item', $data);
	}
}
