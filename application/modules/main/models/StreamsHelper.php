<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StreamsHelper extends APP_Model
{
	public function prepareList(&$data)
	{
		$users = [];
		foreach($data as &$value)
		{
			if(array_key_exists($value['author'], $users) === false)
				$users[$value['author']] = $this->UserModel->getByID($value['author']);
			
			$value['author_name'] = $users[$value['author']]['full_name'];
		}
	}
}