<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends APP_Controller
{
	public function __construct()
	{
		$this->load->library(['ydvideo']);
	}

	// php index.php cli test index 1 qwerty
	public function index()
	{
		var_dump('TEST');

		// $params = [
		// 	'list_ids' => '19169561',
		// 	'double_optin' => 3,
		// 	'fields' => [
		// 		'email' => 'test@mail.ru',
		// 		'name' => 'test user'
		// 	]
		// ];
		// $res = $this->unisender->subscribe($params);
		// var_dump($res);
		
		// if(($user = $this->UserModel->getById($id)) === false)
		// 	throw new AppBadLogicExtension('Неверный код');

		// $params = [
		// 	'password' => $this->UserModel->pwdHash($password),
		// 	'hash' => sha1($user['email'].time())
		// ];
		// $this->UserModel->update($user['id'], $params);
		// var_dump('OK');

		// $this->load->library(['ydvideo']);
		// $res = $this->ydvideo->getVideo('https://yadi.sk/i/NQ6Dce7dem1n-Q');
		// debug($res);
	}
}
