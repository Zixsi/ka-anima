<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller
{
	public function __construct()
	{
		// Проверка Ajax запроса
		if(!$this->input->is_ajax_request())
		{
			$this->jsonrpc->error(-32600);
		}

		// Проверка авторизации
		if(intval($this->Auth->userID() ?? 0) === 0)
		{
			$this->jsonrpc->error(-32000);
		}

		$this->load->model([
			'main/groups/GroupsHelper',
		]);
	}

	public function index()
	{
		// разбираем запрос
		$data = file_get_contents("php://input");
		if(empty($data) && !empty($_POST['query']))
		{
			$data = $_POST['query'];
		}

		$this->request = $this->jsonrpc->parse($data);
		$this->user = $this->Auth->user();

		switch(($this->request['method'] ?? ''))
		{
			case 'group.create':
				$this->groupCreate();
			break;
			case 'group.remove':
				$this->groupRemove();
			break;
		}
	}

	// создание группы
	private function groupCreate()
	{
		try
		{
			if($this->GroupsHelper->add(($this->request['params'] ?? [])) === false)
				throw new Exception($this->GroupsHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// удаление группы
	private function groupRemove()
	{
		try
		{
			if($this->GroupsHelper->remove((int) ($this->request['params']['id'] ?? 0)) === false)
				throw new Exception($this->GroupsHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}
}
