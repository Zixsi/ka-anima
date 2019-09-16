<?php

class ActionHook
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	* Слушаем события
	**/ 
	public function listenActions()
	{
		Action::listen(Action::LOGIN,  [$this, 'executeLoginAction']);
		Action::listen(Action::REGISTRATION,  [$this, 'executeRegistrationAction']);
		Action::listen(Action::REVIEW_ADD,  [$this, 'executeReviewAddAction']);
		Action::listen(Action::REVIEW_DELETE,  [$this, 'executeReviewDeleteAction']);
		Action::listen(Action::SUBSCRIPTION,  [$this, 'executeSubscriptionAction']);
		Action::listen(Action::STREAM_ADD,  [$this, 'executeStreamAddAction']);
		Action::listen(Action::STREAM_UPDATE,  [$this, 'executeStreamUpdateAction']);
		Action::listen(Action::HOMEWORK_UPLOAD,  [$this, 'executeHomeworkUploadAction']);
		Action::listen(Action::HOMEWORK_DOWNLOAD,  [$this, 'executeHomeworkDownloadAction']);
	}

	/**
	* Событие авторизации
	**/
	public function executeLoginAction($user)
	{
		$this->CI->UserActionsHelper->loadHttpData();
		$this->CI->UserActionsHelper->add($user['id'], Action::LOGIN);
	}

	/**
	* Событие регистрации
	**/
	public function executeRegistrationAction($user)
	{
		$this->CI->UserActionsHelper->loadHttpData();
		$this->CI->UserActionsHelper->add($user['id'], Action::REGISTRATION);

		// создание задания на отправку письма о регистрации
		$params = [
			'email' => $user['email'],
			'hash' => $user['hash'],
			'name' => $user['name'],
			'lastname' => $user['lastname']
		];

		$this->CI->TasksHelper->add(TasksModel::TYPE_EMAIL, Action::REGISTRATION, $params, 5, $user['email']);
	}

	/**
	* Событие создания обзора
	**/
	public function executeReviewAddAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::REVIEW_ADD, $params);
	}

	/**
	* Событие удаления обзора
	**/
	public function executeReviewDeleteAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::REVIEW_DELETE, $params);
	}

	/**
	* Событие подписки
	**/
	public function executeSubscriptionAction($params, $userId = 0)
	{
		if((int) $userId === 0)
			$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::SUBSCRIPTION, $params);
	}

	/**
	* Событие создания трансляции
	**/
	public function executeStreamAddAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::STREAM_ADD, $params);
	}

	/**
	* Событие изменения трансляции
	**/
	public function executeStreamUpdateAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::STREAM_UPDATE, $params);
	}

	/**
	* Событие загрузки домашнего задания
	**/
	public function executeHomeworkUploadAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::HOMEWORK_UPLOAD, $params);
	}
	
	/**
	* Событие скачивания домашнего задания
	**/
	public function executeHomeworkDownloadAction($params)
	{
		$userId = $this->CI->Auth->userID();
		$params = ['data' => $params];
		$this->CI->UserActionsHelper->add($userId, Action::HOMEWORK_DOWNLOAD, $params);
	}
}