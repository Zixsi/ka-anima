<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailHelper extends APP_Model
{
	private $site = [];
	private $conf = [];

	public function __construct()
	{
		$this->load->library('email');
		$this->config->load('email', true);
		$this->conf = $this->config->config['email'];

		$this->site = [
			'name' => $this->config->item('project_name'),
			'url' => $this->config->item('base_url')
		];

		$this->email->initialize($this->conf);
	}

	private function init()
	{
		$this->email->from($this->conf['email_from'], $this->conf['email_from_name']);
		$this->email->bcc($this->conf['email_from']);
	}

	public function registration($data = [])
	{
		$this->init();
		
		$params = [
			'site' => $this->site,
			'url' => $this->site['url'].'/auth/confirmation/?code='.($data['code'] ?? '')
		];
		$html = $this->load->viewl('email', 'email/registration', $params, true);

		$this->email->to(($data['email'] ?? null));
		$this->email->subject('Регистрация');
		$this->email->message($html);

		$res = $this->email->send();
		// if(!$res)
		// 	echo $this->email->print_debugger(array('headers'));

		return $res;
	}

	public function forgot()
	{

		$this->init();
		
		$params = [
			'site' => $this->site,
			'url' => $this->site['url'].'/auth/recovery/?code='.($data['code'] ?? '')
		];
		$html = $this->load->viewl('email', 'email/forgot', $params, true);

		$this->email->to(($data['email'] ?? null));
		$this->email->subject('Восстановление пароля');
		$this->email->message($html);

		$res = $this->email->send();
		// if(!$res)
		// 	echo $this->email->print_debugger(array('headers'));

		return $res;
	}
}