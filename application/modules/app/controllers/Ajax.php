<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller
{
	private $request = null;
	private $params = [];
	private $user = null;
	private $binds = [];

	public function __construct()
	{
		// проверка Ajax запроса
		if(!$this->input->is_ajax_request())
			$this->jsonajax->error(Jsonajax::CODE_ACCESS_DENIED);
	}

	public function index()
	{
		$this->bind('auth.login', $this->Auth, 'login');
		$this->bind('auth.register', $this->Auth, 'register');
		$this->bind('auth.forgot', $this->Auth, 'forgot');
		$this->bind('auth.recovery', $this->Auth, 'recovery');
		
		$this->bind('wall.message', $this, 'wallMessage');
		$this->bind('wall.message.list', $this, 'wallMessageList');
		$this->bind('wall.message.child', $this, 'wallMessageChild');

		$this->bind('review.add', $this, 'reviewAdd');
		$this->bind('review.delete', $this, 'reviewDelete');
		$this->bind('review.edit', $this, 'reviewEdit');
		$this->bind('review.getItem', $this, 'reviewGetItem');

		$this->run();
	}

	// проверка авторизации
	private function checkAuth()
	{
		if(empty($this->user) || (int) ($this->user['id'] ?? 0) === 0)
			throw new AppBadLogicExtension(null, Jsonajax::CODE_NOT_AUTHORIZED);
	}

	// закрепляем метод за конкретной функцией
	private function bind($method, $object, $function, $check_auth = false)
	{
		if(empty($method))
			return;

		$this->binds[$method] = [$object, $function, $check_auth];
	}

	private function run()
	{
		try
		{
			$this->parse();

			// $method = ($_GET['method'] ?? '');
			$method = ($this->request['method'] ?? '');
			if(array_key_exists($method, $this->binds))
			{
				$item = $this->binds[$method];

				if($item[2])
					$this->checkAuth();

				$obj = ($item[0] === null)?$item[1]:[$item[0], $item[1]];
				if(is_callable($obj))
				{
					$res = call_user_func_array($obj, [$this->params]);
					$this->jsonajax->result($res);
				}
			}

			throw new AppBadLogicExtension('invalid request', Jsonajax::CODE_INTERNAL_ERROR);
		}
		catch(AppBadLogicExtension $e)
		{
			$code = (array_key_exists($e->getCodeString(), Jsonajax::ERRORS))?$e->getCodeString():null;
			$this->jsonajax->error($code, $e->getMessage());
		}
	}

	private function parse()
	{
		// разбираем запрос
		$data = file_get_contents("php://input");
		if(empty($data) && !empty($_POST['query']))
			$data = $_POST['query'];

		$this->request = json_decode($data, true);
		$this->params = (array) ($this->request['params'] ?? []);
		$this->user = $this->Auth->user();
	}

	//===============================================================================================================//

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

	private function reviewEdit()
	{
		try
		{
			$params = $this->request['params'];
			unset($params['id']); 
			if($this->ReviewHelper->edit($this->request['params']['id'], $this->request['params']) === false)
				throw new Exception($this->ReviewHelper->getLastError());
			
			$this->jsonrpc->result('Успешно');
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result(false);
	}

	private function reviewGetItem()
	{
		$result = [];
		try
		{
			$result = $this->ReviewModel->getByID(($this->request['params']['id'] ?? 0));
		}
		catch(Exception $e)
		{
			$this->jsonrpc->error(-32099, $e->getMessage());
		}

		$this->jsonrpc->result($result);
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
