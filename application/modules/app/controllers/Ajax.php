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
			case 'subscr.group':
				$this->subscrGroup();
			break;
			case 'review.add':
				$this->reviewAdd();
			break;
			case 'review.delete':
				$this->reviewDelete();
			break;
			case 'wall.message':
				$this->wallMessage();
			break;
			case 'wall.message.list':
				$this->wallMessageList();
			break;
			case 'wall.message.child':
				$this->wallMessageChild();
			break;
			default:
				$this->jsonrpc->error(-32600);
			break;
		}
	}

	// подписка на группу
	private function subscrGroup()
	{
		try
		{
			$params = ($this->request['params'] ?? []);
			$params['user'] = $this->user['id'];

			if($this->SubscriptionHelper->group($params) === false)
				throw new Exception($this->SubscriptionHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// добавление ревью
	private function reviewAdd()
	{
		try
		{
			$params = ($this->request['params'] ?? []);
			if($this->ReviewHelper->add($params) === false)
				throw new Exception($this->ReviewHelper->getLastError());
			
			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// добавление ревью
	private function reviewDelete()
	{
		try
		{
			if($this->ReviewHelper->delete(($this->request['params'] ?? 0)) === false)
				throw new Exception($this->ReviewHelper->getLastError());
			
			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// добавить сообщение на стену 
	private function wallMessage()
	{
		try
		{
			$params = ($this->request['params'] ?? []);
			$params['user'] = $this->user['id'];

			if($this->WallHelper->add($params) === false)
				throw new Exception($this->WallHelper->getLastError());
			
			$this->jsonrpc->result(true);
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// список сообщений стены
	private function wallMessageList()
	{
		try
		{
			$params = ($this->request['params'] ?? []);

			if(($res = $this->WallHelper->list($params)) === false)
				throw new Exception($this->WallHelper->getLastError());
			
			$this->jsonrpc->result($res);
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// список дочерних сообщений
	private function wallMessageChild()
	{
		try
		{
			$params = ($this->request['params'] ?? []);

			if(($res = $this->WallHelper->child($params)) === false)
				throw new Exception($this->WallHelper->getLastError());
			
			$this->jsonrpc->result($res);
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}
	
}
