<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsHelper extends APP_Model
{
	private $ip;
	private $userAgent;

	public function __construct()
	{
		parent::__construct();
	}

	public function loadHttpData()
	{
		$this->ip = getIp();
		$this->userAgent = getUserAgent();
	}

	public function add(int $userId, string $action, array $params = [])
	{
		$ts = time();
		$data = [
			'user' => $userId,
			'date' => date(DATE_FORMAT_DB_FULL, $ts),
			'date_ts' => $ts,
			'action' => $action,
			'ip' => ($this->ip ?? null),
			'ua' => ($this->userAgent ?? null),
		];


		if(isset($params['data']) && is_string($params['data']) === false)
			$params['data'] = json_encode($params['data']);

		$data = array_merge($data, $params);

		return $this->UserActionsModel->add($data);
	}

	public function prepareList(&$list = [])
	{
		foreach($list as &$item)
		{
			$data = json_decode($item['data'], true);
			$item['description'] = Action::getDescription($item['action'], ($data ?? []), $item);
		}
	}
}