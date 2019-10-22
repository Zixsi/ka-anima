<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Задачи
**/
class Task extends APP_Controller
{
	public function __construct()
	{

	}

	// отправка писем
	// php index.php cli task handleEmail
	public function handleEmail()
	{
		echo 'START'.PHP_EOL;
		
		// $list = $this->TasksHelper->getEmailTasks();
		// foreach($list as $item)
		// {
		// 	$status = false;
		// 	$item['data'] = json_decode($item['data'], true);
		// 	switch($item['event'])
		// 	{
		// 		case Action::REGISTRATION:
		// 			$status = $this->EmailHelper->registration($item['data']);
		// 			break;
		// 		case Action::FORGOT:
		// 			$status = $this->EmailHelper->forgot($item['data']);
		// 			break;
		// 		default:
		// 				// empty
		// 			break;
		// 	}

		// 	if($status)
		// 		$this->TasksModel->setStatus($item['id'], 1);
		// }

		echo 'END'.PHP_EOL;
	}
}
