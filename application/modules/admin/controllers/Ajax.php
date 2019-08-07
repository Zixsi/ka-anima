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
			$data = $_POST['query'];

		$this->request = $this->jsonrpc->parse($data);
		$this->user = $this->Auth->user();

		try
		{
			switch(($this->request['method'] ?? ''))
			{
				case 'group.create':
					$this->groupCreate();
				break;
				case 'group.remove':
					$this->groupRemove();
				break;
				case 'group.user.add':
					$this->groupUserAdd();
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
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	// создание группы
	private function groupCreate()
	{
		$this->GroupsHelper->add(($this->request['params'] ?? []));
		$this->jsonrpc->result('Успешно');
	}

	// удаление группы
	private function groupRemove()
	{
		$this->GroupsHelper->remove((int) ($this->request['params']['id'] ?? 0));
		$this->jsonrpc->result('Успешно');
	}

	private function groupUserAdd()
	{
		$this->GroupsHelper->userAdd(($this->request['params'] ?? []));
		$this->jsonrpc->result('Успешно');
	}

	// удаление лекции
	private function lectureRemove()
	{
		$this->LecturesHelper->remove((int) ($this->request['params'] ?? 0));
		$this->jsonrpc->result('Успешно');
	}

	// создание пользователя
	private function userAdd()
	{
		$this->UsersHelper->add(($this->request['params'] ?? []));
		$this->jsonrpc->result('Успешно');
	}

	// редактирование пользователя
	private function userEdit()
	{
		$this->UsersHelper->edit(($this->request['params']['id'] ?? 0), ($this->request['params'] ?? []));
		$this->jsonrpc->result('Успешно');
	}

	// удаление пользователя
	private function userRemove()
	{
		$this->UsersHelper->remove(($this->request['params'] ?? 0));
		$this->jsonrpc->result('Успешно');
	}

	// заблокировать пользователя
	private function userBlock()
	{
		$this->UsersHelper->block(($this->request['params'] ?? 0));
		$this->jsonrpc->result('Успешно');
	}

	// разблокировать пользователя
	private function userUnBlock()
	{
		$this->UsersHelper->unblock(($this->request['params'] ?? 0));
		$this->jsonrpc->result('Успешно');
	}

	// добавить сообщение на стену 
	private function wallMessage()
	{
		$params = ($this->request['params'] ?? []);
		$params['user'] = $this->user['id'];

		if($this->WallHelper->add($params) === false)
			throw new Exception($this->WallHelper->getLastError(), -32099);
		
		$this->jsonrpc->result(true);
	}

	// список сообщений стены
	private function wallMessageList()
	{
		$params = ($this->request['params'] ?? []);
		if(($res = $this->WallHelper->list($params)) === false)
			throw new Exception($this->WallHelper->getLastError(), -32099);
		
		$this->jsonrpc->result($res);
	}

	// список дочерних сообщений
	private function wallMessageChild()
	{
		$params = ($this->request['params'] ?? []);
		if(($res = $this->WallHelper->child($params)) === false)
			throw new Exception($this->WallHelper->getLastError(), -32099);
		
		$this->jsonrpc->result($res);
	}
}
