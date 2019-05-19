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
			'main/users/UsersHelper',
			'main/lectures/LecturesHelper',
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
			case 'lecture.remove':
				$this->lectureRemove();
			break;
			case 'user.add':
				$this->userAdd();
			break;
			case 'user.edit':
				$this->userEdit();
			break;
			case 'user.remove':
				$this->userRemove();
			break;
			case 'user.block':
				$this->userBlock();
			break;
			case 'user.unblock':
				$this->userUnBlock();
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

	// удаление лекции
	private function lectureRemove()
	{
		try
		{
			if($this->LecturesHelper->remove((int) ($this->request['params'] ?? 0)) === false)
				throw new Exception($this->LecturesHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// создание пользователя
	private function userAdd()
	{
		try
		{
			if($this->UsersHelper->add(($this->request['params'] ?? [])) === false)
				throw new Exception($this->UsersHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// редактирование пользователя
	private function userEdit()
	{
		try
		{
			if($this->UsersHelper->edit(($this->request['params']['id'] ?? 0), ($this->request['params'] ?? [])) === false)
				throw new Exception($this->UsersHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// удаление пользователя
	private function userRemove()
	{
		try
		{
			if($this->UsersHelper->remove(($this->request['params'] ?? 0)) === false)
				throw new Exception($this->UsersHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// заблокировать пользователя
	private function userBlock()
	{
		try
		{
			if($this->UsersHelper->block(($this->request['params'] ?? 0)) === false)
				throw new Exception($this->UsersHelper->getLastError());

			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// разблокировать пользователя
	private function userUnBlock()
	{
		try
		{
			if($this->UsersHelper->unblock(($this->request['params'] ?? 0)) === false)
				throw new Exception($this->UsersHelper->getLastError());

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
