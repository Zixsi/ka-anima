<?php

class UserMessagesHelper extends APP_Model
{
	public function listChats(int $user)
	{
		$list = $this->UserMessagesModel->listChats($user);
		if(count($list))
		{
			foreach($list as &$val)
			{
				$user = $this->UserModel->getByID($val['id']);
				$val['user'] = [
					'name' => $user['full_name'],
					'img' => $user['img'],
					'role' => $user['role'],
					'role_name' => $user['role_name']
				];
			}
		}

		return $list;
	}

	public function chatArraySetRead(array &$data, int $id)
	{
		foreach($data as &$val)
		{
			if($val['id'] === $id)
				$val['unread'] = 0;
		}	
	}
}