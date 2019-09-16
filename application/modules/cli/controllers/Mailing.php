<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Рассылка
**/
class Mailing extends APP_Controller
{
	// php index.php cli mailing send
	public function send()
	{
		echo 'START SEND'.PHP_EOL;

		$number = 0;
		$list = $this->TasksModel->list([
			'type' => TasksModel::TYPE_EMAIL,
			'status' => 0
		]);

		foreach($list as $item)
		{
			if($res = $this->EmailHelper->mailing($item))
			{
				$number++;
				$this->TasksModel->setStatus($item['id'], 1);
			}
		}

		echo $number.' emails sending'.PHP_EOL;
		echo 'END SEND'.PHP_EOL;
	}

	// php index.php cli mailing checkEveryday
	public function checkEveryday()
	{
		$this->prepareStartCourse();
		$this->prepareStartStreams();
	}

	/**
	* О начале курса
	**/
	private function prepareStartCourse()
	{
		echo 'START COURSE'.PHP_EOL;

		$number = 0;
		$listCourses = [];

		// выбираем список курсов которые начинаются через N дней
		$listGroups = $this->GroupsModel->getListForMailing(2);
		foreach($listGroups as $group)
		{
			if(array_key_exists($group['course_id'], $listCourses) === false)
				$listCourses[$group['course_id']] = $this->CoursesModel->getByID($group['course_id']);

			$course = $listCourses[$group['course_id']];
			// выбрать список учеников подписанных на эту группу
			if($users = $this->SubscriptionModel->getGroupUsers($group['id'], $group['type']))
			{
				foreach($users as $user)
				{
					// создаем задание на отправку уведомления
					$taskParams = [
						'type' => 'group',
						'email' => $user['email'],
						'user_name' => $user['full_name'],
						'name' => $course['name'],
						'group_code' => $group['code'],
						'date' => date(DATE_FORMAT_SHORT, strtotime($group['ts']))
					];

					$this->TasksHelper->add(TasksModel::TYPE_EMAIL, Action::MAILING, $taskParams, 1, $user['email']);
					$number++;
				}
			}
		}

		echo $number.' items'.PHP_EOL;
		echo '==='.PHP_EOL;
	}

	/**
	* О начале трансляции
	**/
	private function prepareStartStreams()
	{
		echo 'START STREAMS'.PHP_EOL;

		$number = 0;

		// выбираем список трансляций которые начинаются через N дней
		$listStreams = $this->StreamsModel->getListForMailing(2);
		foreach($listStreams as $stream)
		{
			// выбрать список учеников подписанных на группу
			if($users = $this->SubscriptionModel->getGroupUsers($stream['group_id'], $stream['group_type']))
			{

				foreach($users as $user)
				{
					// создаем задание на отправку уведомления
					$taskParams = [
						'type' => 'stream',
						'email' => $user['email'],
						'user_name' => $user['full_name'],
						'id' => $stream['id'],
						'name' => $stream['name'],
						'group_code' => $stream['group_code'],
						'date' => date(DATE_FORMAT_SHORT, strtotime($stream['ts']))
					];

					$this->TasksHelper->add(TasksModel::TYPE_EMAIL, Action::MAILING, $taskParams, 1, $user['email']);
					$number++;
				}
			}
		}

		echo $number.' items'.PHP_EOL;
		echo '==='.PHP_EOL;
	}
}
