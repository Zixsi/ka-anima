<?php

class Ulogin
{
	private $c;
	private $providers = ['vkontakte', 'odnoklassniki', 'facebook'];

	public function __construct()
	{
		$this->c = &get_instance();
	}

	public function getUser()
	{
		$result = null;
		$data = file_get_contents('http://ulogin.ru/token.php?token=' . ($_POST['token'] ?? '') . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($data, true);
		if (is_array($user) && isset($user['uid'])) {
			$result = new UloginUser($user);
		}

		return $result;
	}

	public function getCode($ignore_providers = [])
	{
		$html = null;
		$url = urlencode($this->c->config->item('base_url') . 'auth/soc/');
		$providers = $this->getProviders();
		$providers = array_diff($providers, $ignore_providers);

		if (count($providers)) {
			$html = '<script src="//ulogin.ru/js/ulogin.js"></script><div id="uLogin" data-ulogin="display=panel;theme=flat;fields=first_name,last_name,email;providers='.implode(',', $providers).';hidden=;redirect_uri=' . $url . ';mobilebuttons=0;"></div>';
		}
		
		return $html;
	}

	private function getProviders()
	{
		return $this->providers;
	}
}

/**
 *  
 */
class UloginUser
{
	private $first_name;
	private $last_name;
	private $email;
	private $network;
	private $uid;

	public function __construct(array $data)
	{
		$this->parse($data);
	}

	private function parse(array $data)
	{
		$class = new ReflectionClass($this);
		foreach ($data as $key => $value) {
			$method = 'set' . str_replace('_', '', ucwords($key));
			if ($class->hasMethod($method)) {
				$this->$method($value);
			}
		}
	}

	private function setFirstName($value)
	{
		$this->first_name = $value;
	}

	public function getFirstName()
	{
		return $this->first_name;
	}

	private function setLastName($value)
	{
		$this->last_name = $value;
	}

	public function getLastName()
	{
		return $this->last_name;
	}

	private function setEmail($value)
	{
		$this->email = $value;
	}

	public function getEmail()
	{
		return $this->email;
	}

	private function setNetwork($value)
	{
		$this->network = $value;
	}

	public function getNetwork()
	{
		return $this->network;
	}

	private function setUid($value)
	{
		$this->uid = $value;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function makeEmail()
	{
		return $this->getUid() . '@soc-'. $this->getNetwork() .'.com';
	}
}