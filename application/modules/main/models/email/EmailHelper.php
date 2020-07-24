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
                    'url' => $this->site['url'].'auth/confirmation/?code='.($data['code'] ?? $data['hash'] ?? ''),
                    'originPassword' => ($data['origin_password'] ?? null)
		];
		$html = $this->load->viewl('email', 'email/registration', $params, true, 'app');
		$to = ($data['email'] ?? null);
		if($this->checkEmailMx($to) === false)
			return false;

		$this->email->to($to);
		$this->email->subject('Регистрация');
		$this->email->message($html);

		if(($res = $this->email->send()) === false)
		{
			log_message('error', $this->email->print_debugger(array('headers')));
		}

		return $res;
	}

	public function forgot($data = [])
	{

		$this->init();
		
		$params = [
			'site' => $this->site,
			'url' => $this->site['url'].'auth/recovery/?code='.($data['code'] ?? $data['hash'] ?? '')
		];
		$html = $this->load->viewl('email', 'email/forgot', $params, true, 'app');

		$to = ($data['email'] ?? null);
		if($this->checkEmailMx($to) === false)
			return false;

		$this->email->to($to);
		$this->email->subject('Восстановление пароля');
		$this->email->message($html);

		if(($res = $this->email->send()) === false)
		{
			log_message('error', $this->email->print_debugger(array('headers')));
		}

		return $res;
	}

	public function mailing($data = [])
	{
		$result = null;
		$params = json_decode($data['data'], true);

		switch($data['event'])
		{
			case Action::MAILING:
				$result = $this->sendMailingEvent($params);
				break;
			case Action::REGISTRATION:
				$result = $this->registration($params);
				break;
			case Action::FORGOT:
				$result = $this->forgot($params);
				break;
			default:
				// empty
				break;
		}
		
		return $result;
	}

	private function sendMailingEvent($params)
	{
		$this->init();
		$params['site'] = $this->site;
		$html = $this->load->viewl('email', 'email/mailing_'.$params['type'], $params, true, 'app');

		$to = ($params['email'] ?? null);
		if($this->checkEmailMx($to) === false)
			return false;

		$this->email->to($to);
		$this->email->subject('Информация');
		$this->email->message($html);

		if(($res = $this->email->send()) === false)
		{
			log_message('error', $this->email->print_debugger(array('headers')));
		}

		return $res;
	}

	private  function checkEmailMx($to)
	{
		$emailArr = explode('@', $to);
		$emailHost = end($emailArr);
		getmxrr($emailHost, $mx);
		
		return (!$mx || count($mx) == 0)?false:true;
	}
	
}