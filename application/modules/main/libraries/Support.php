<?php

class Support
{
	private $c;

	public function __construct()
	{
		$this->c = &get_instance();
	}

	public function getStatusList()
	{
		return [
			['code' => 'PENDING', 'text' => 'В процессе'],
			['code' => 'DECLINED', 'text' => 'Отклонено'],
			['code' => 'COMPLETED', 'text' => 'Завершено']
		];
	}

	public function getStatusInfo($value)
	{
		$result = null;
		switch($value)
		{
			case 'PENDING':
				$result = [
					'text' => 'В процессе',
					'class' => 'warning'
				];
				break;
			case 'DECLINED':
				$result = [
					'text' => 'Отклонено',
					'class' => 'danger'
				];
				break;
			case 'COMPLETED':
				$result = [
					'text' => 'Завершено',
					'class' => 'success'
				];
				break;
			default:
				// empty
				break;
		}

		return $result;
	}

	/**
	* Добавить тикет
	**/
	public function addTicket($data = [])
	{
		$userId = $this->c->Auth->getUserId();
		$params = [
			'user' => $userId,
			'text' => ($data['text'] ?? ''),
			'code' => md5($userId.microtime(true))
		];

		$this->c->form_validation->reset_validation();
		$this->c->form_validation->set_data($params);

		if($this->c->form_validation->run('support_add_ticket') == FALSE)
			throw new Exception(current($this->c->form_validation->error_array()), 1);

		return $this->c->SupportModel->add($params);
	}

	/**
	* Список тикетов пользователя
	**/
	public function getUserTickets()
	{
		$params = [
			'user' => $this->c->Auth->getUserId()
		];
		if($result = $this->c->SupportModel->getList($params))
			$this->prepareTickets($result);

		return $result;
	}

	public function getTickets()
	{
		$params = [
			'status' => [SupportModel::PENDING]
		];
		if($result = $this->c->SupportModel->getList($params))
			$this->prepareTickets($result);

		return $result;
	}

	public function prepareTickets(&$data)
	{
		foreach($data as &$row)
		{
			$this->prepareTicket($row);
		}
	}

	public function prepareTicket(&$data)
	{
		$data['status_info'] = $this->getStatusInfo($data['status']);
	}

	public function prepareAdminTickets(&$data)
	{
		foreach($data as &$row)
		{
			$row['user_info'] = $this->c->UserModel->getByID($row['user']);
		}
	}

	/**
	* Добавить ответ на тикет
	**/
	public function addTicketMessage($data = [])
	{
		$userId = ($this->c->Auth->isAdmin())?0:$this->c->Auth->getUserId();
		$params = [
			'user' => $userId,
			'target' => ($data['target'] ?? 0),
			'text' => ($data['text'] ?? '')
		];

		$this->c->form_validation->reset_validation();
		$this->c->form_validation->set_data($params);

		if($this->c->form_validation->run('support_add_ticket_message') == FALSE)
			throw new Exception(current($this->c->form_validation->error_array()), 1);

		return $this->c->SupportModel->addMessage($params);
	}
	
	/**
	* Список сообщений тикета
	**/
	public function getTicketMessage($id)
	{
		$params = [
			'target' => $id
		];

		return $this->c->SupportModel->getListMessage($params);
	}
}