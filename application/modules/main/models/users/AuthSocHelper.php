<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthSocHelper extends AuthHelper
{
	public function socRegister(UloginUser $user, int $parent = 0)
	{
		$login = $user->makeEmail();
		$user_data = [
			'login' => $login,
			'email' => $user->getEmail(),
			'name' => $user->getFirstName(),
			'lastname' => $user->getLastName(),
			'password' => $this->UserModel->pwdHash($login),
			'active' => 1,
			'hash' => sha1($login . time()),
			'network' => $user->getNetwork(),
			'parent' => $parent
		];

		return $this->UserModel->add($user_data);
	}

	public function socAuth(int $user_id)
	{
		if ($user_id > 0) {
			$user = $this->UserModel->getByID($user_id);
			unset($user['password']);
			$this->Auth->setUser($user);
			Action::send(Action::LOGIN, [$user]);
		}
	}
}