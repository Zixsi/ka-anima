<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends APP_Controller
{
	public function __construct()
	{
		$this->load->library(['ydvideo']);
	}

	// php index.php cli test index 1 qwerty
	public function index($id, $password)
	{
		var_dump('TEST');
		
		if(($user = $this->UserModel->getById($id)) === false)
			throw new AppBadLogicExtension('Неверный код');

		$params = [
			'password' => $this->UserModel->pwdHash($password),
			'hash' => sha1($user['email'].time())
		];
		$this->UserModel->update($user['id'], $params);
		var_dump('OK');
	}
}
