<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TasksHelper extends APP_Model
{
	/**
	* Создание задачи на отправку письма
	* @param string $type тип задачи 
	* @param string $event событие
	* @param array $params параметры
	* @param int $priority приоритет (1 - 5, где 5 максимальный)
	* @param string $target цель 
	**/
	public function add(string $type, string $event, array $params = [], int $priority = 1, string $target = null)
	{
		$data = [
			'type' => $type,
			'event' => $event,
			'data' => json_encode($params),
			'priority' => $priority,
			'target' => $target
		];
		return $this->TasksModel->add($data);
	}

	public function getEmailTasks()
	{
		$filter = [
			'type' => TasksModel::TYPE_EMAIL,
			'status' => 0,
			'limit' => 100
		];
		return $this->TasksModel->list($filter);
	}
}