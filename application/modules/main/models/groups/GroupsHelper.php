<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public static function getTypeName($type)
	{
	 	return (GroupsModel::TYPE[$type]['title'] ?? $type);
	}

	public function add($data)
	{
		try
		{
			$this->db->trans_begin();

			$data['course'] = (int) ($data['course'] ?? 0);
			if(($course = $this->CoursesModel->getByID($data['course'])) === false)
				throw new Exception('курс не найден');

			$data['date'] = ($data['date'] ?? null);
			if(empty($data['date']))
				throw new Exception('не указана дата начала');

			$date_a = new DateTime(date('Y-m-d 00:00:00'));
			$date_b = new DateTime(date('Y-m-d 00:00:00', strtotime($data['date'])));
			if($date_a > $date_b)
				throw new Exception('неверная дата начала');

			$data['type'] = ($data['type'] ?? null);
			if(empty($data['type']) || !array_key_exists($data['type'], GroupsModel::TYPE))
				throw new Exception('неверный тип группы');


			$data['users'] = ($data['users'] ?? []);
			if($data['type'] === GroupsModel::TYPE_PRIVATE && empty($data['users']))
				throw new Exception('не выбраны ученики');

			if($data['type'] === GroupsModel::TYPE_PRIVATE)
			{
				if($group_id = $this->makeGroup($course, $data['type'], $date_b->getTimestamp()))
				{
					$group_item = $this->GroupsModel->getByID($group_id);

					// добавляем выбранных учеников в группу
					foreach($data['users'] as $val)
					{
						$user_data = [
							'user' => $val,
							'type' => $data['type'],
							'target' => $group_item['id'],
							'target_type' => 'course',
							'description' => $course['name'].' '.date(DATE_FORMAT_SHORT, strtotime($group_item['ts'])).' - '.date(DATE_FORMAT_SHORT, strtotime($group_item['ts_end'])),
							'ts_start' => $group_item['ts'],
							'ts_end' => $group_item['ts_end'],
							'subscr_type' => 0,
							'amount' => 0,
							'data' => json_encode(['price' => 0])
						];

						$this->SubscriptionModel->add($user_data);
					}
				}
			}
			else
			{
				// для каждого типа создаем группу
				foreach(GroupsModel::TYPE as $type => $val)
				{
					if($type === GroupsModel::TYPE_PRIVATE || $type === GroupsModel::TYPE_VIP)
						continue;

					$this->makeGroup($course, $type, $date_b->getTimestamp(), 1);
				}
			}

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка добавления');
			}

			$this->db->trans_commit();
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			throw new Exception($e->getMessage(), $e->getCode());
		}

		return true;
	}

	public function addSimple($course, $type, $start)
	{
		try
		{
			$this->db->trans_begin();

			if(($course = $this->CoursesModel->getByID($course)) === false)
				throw new Exception('Курс не найден');

			if(!array_key_exists($type, GroupsModel::TYPE))
				throw new Exception('Неверный тип группы');

			$start = ($start instanceof DateTime)?$start: new DateTime($start);

			// дата начала лекций
			$ts_start = clone $start;
			// если текущая дата не понедельник, получаем следующий понедельник
			if(intval($ts_start->format('N')) !== 1)
				$ts_start->modify('next monday');

			// Дата окончания курса
			$days = (intval($course['cnt_main']) + 1) * 7;
			$ts_end = clone $ts_start;
			$ts_end->add(new DateInterval('P'.$days.'D'));

			$data = [
				'type' => $type,
				'teacher' => $course['teacher'],
				'code' => $this->makeCode($course['code'], $type, $start->format('dmy')),
				'course_id' => $course['id'],
				'ts' => $start->format('Y-m-d 00:00:00'),
				'ts_end' => $ts_end->format('Y-m-d 00:00:00')
			];

			if(($group_id = $this->GroupsModel->add($data)) === false)
				throw new Exception('Ошибка создания группы');

			// добавляем лекции в группу
			if($lectures = $this->LecturesModel->listForCourse($course['id']))
			{
				$ts_item = clone $ts_start;				
				foreach($lectures as $item)
				{
					$ts = $start->format('Y-m-d 00:00:00');
					if((int) $item['type'] === 0)
					{
						$ts = $ts_item->format('Y-m-d 00:00:00');
						$ts_item->add(new DateInterval('P1W')); // +1 неделя
					}

					$this->LecturesModel->addLectureToGroupTs($group_id, $item['id'], $ts);
				}
			}

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка добавления');
			}

			$this->db->trans_commit();

			return $group_id;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}

	public function remove($id)
	{
		try
		{
			$this->db->trans_begin();

			if(($item = $this->GroupsModel->getByID((int) $id)) === false)
				throw new Exception('Группа не найдена');

			$this->GroupsModel->remove($item['id']);
			$this->LecturesGroupModel->removeByGroup($item['id']);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка удаления');
			}

			$this->db->trans_commit();
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			throw new Exception($e->getMessage(), $e->getCode());
		}

		return true;
	}

	// проставляем пользователям статус домашних заданий
	public function setUsersHomeworkStatus($id, &$users)
	{
		if(!$users)
			return;

		$status = $this->GroupsModel->groupHomeworkStatus($id);
		foreach($users as $key => &$val)
		{
			$val['homeworks'] = (int) ($status[$val['id']]['homeworks'] ?? 0);
			$val['reviews'] = (int) ($status[$val['id']]['reviews'] ?? 0);
		}
	}

	// подготовить список групп для преподавателя
	public function prepareListForTeacher(&$data = [])
	{
		$result = [];

		// проставляем информацию для каждой группы
		foreach($data as $val)
		{
			if(!isset($result[$val['status']]))
			{
				$info = $this->statusInfo($val['status']);
				$info['status'] = $val['status'];
				$info['items'] = [];
				$result[$val['status']] = $info;
			}

			$result[$val['status']]['items'][] = $val;
		}

		ksort($result);
		$result = array_values($result);
		$data = $result;
	}

	public function prepareListForAdmin(&$data = [])
	{
		$result = [];

		// проставляем информацию для каждой группы
		foreach($data as $val)
		{
			$val['subscription_cnt'] = (int) $val['subscription_cnt'];
			$val['timestamp_start'] = strtotime($val['ts']);
			$val['timestamp_end'] = strtotime($val['ts_end']);
			$val['status'] = $this->getStatus($val['timestamp_start'], $val['timestamp_end']);

			if(!isset($result[$val['status']]))
			{
				$info = $this->statusInfo($val['status']);
				$info['status'] = $val['status'];
				$info['items'] = [];
				$result[$val['status']] = $info;
			}

			$result[$val['status']]['items'][] = $val;
		}

		ksort($result);
		$result = array_values($result);
		$data = $result;
	}

	public function statusInfo($value)
	{
		$result = null;

		switch($value)
		{
			case -1:
				$result = [
					'text' => 'Завершен',
					'class' => 'secondary'
				];
				break;
			case 1:
				$result = [
					'text' => 'Скоро',
					'class' => 'warning'
				];
				break;
			default:
				$result = [
					'text' => 'В процессе',
					'class' => 'success'
				];
		}

		return $result;
	}

	public function getStatus($start, $end)
	{
		$ts = time();
		return ($end > $ts)?(($start > $ts)?1:0):-1;
	}

	// код группы
	public function makeCode($course_code, $type, $date)
	{
		return $course_code.'-'.substr(trim($type), 0, 1).$date;
	}

	public function userAdd($data)
	{
		$data['group'] = (int) ($data['group'] ?? 0);
		if(($group = $this->GroupsModel->getByID($data['group_id'])) === false)
			throw new Exception('группа не найдена');

		if(($course = $this->CoursesModel->getByID($group['course_id'])) === false)
			throw new Exception('неверные параметры', 1);

		$data['user'] = (int) ($data['user'] ?? 0);
		if(($user = $this->UserModel->getByID($data['user'])) === false)
			throw new Exception('пользователь не найден');

		$data['type'] = ($data['type'] ?? null);
		if(empty($data['type']) || !array_key_exists($data['type'], SubscriptionModel::TYPES))
			throw new Exception('неверный тип подписки');

		if($this->SubscriptionModel->сheck($user['id'], $group['id']))
			throw new Exception('уже подписан');

		$params = [
			'user' => $user['id'],
			'type' => $data['type'],
			'target' => $group['id'],
			'target_type' => 'course',
			'description' => $course['name'].' ('.strftime("%B %Y", strtotime($group['ts'])).')',
			'ts_start' => $group['ts'],
			'ts_end' => $group['ts_end'],
			'subscr_type' => 0,
			'amount' => 0,
			'data' => json_encode(['price' => 0])
		];

		$this->SubscriptionModel->add($params);

		$actionParams = [
			'group_code' => $group['code'], 
			'period' => 'full'
		];
		Action::send(Action::SUBSCRIPTION, [$actionParams, $user['id']]);

		return true;
	}

	// создание группы с заданным типом
	public function makeGroup($course, $type, $ts, $cnt = 1)
	{
		$result = null;
		if($cnt > 1)
			$result = [];

		// переданная дата начала
		$ts_start = new DateTime();
		$ts_start->setTimestamp((int) $ts);
		$ts_start_orign = clone $ts_start;

		// если дата не понедельник, получаем следующий за этой датой понедельник
		if((int) $ts_start->format('N') !== 1)
			$ts_start->modify('next monday');

		// вип всегда начинаются в понедельник
		if($type == GroupsModel::TYPE_VIP)
			$ts_start_orign = clone $ts_start;

		// продолжительность курса
		$days = ((int) $course['cnt_main'] + 1) * 7;
		$group_interval = new DateInterval('P'.$days.'D');
		// интервал лекций
		$interval = new DateInterval('P1W');
		
		// лекции
		$lectures = null;

		for($i = 0; $i < $cnt; $i++)
		{
			// дата начала обучения
			$ts_start_group = clone $ts_start;
			// дата окончания обучения
			$ts_end_group = clone $ts_start_group;
			$ts_end_group->add($group_interval);

			$group_item_params = [
				'type' => $type,
				'teacher' => $course['teacher'],
				'code' => $this->makeCode($course['code'], $type, $ts_start_orign->format('dmy')),
				'course_id' => $course['id'],
				'ts' => $ts_start_orign->format('Y-m-d 00:00:00'),
				'ts_end' => $ts_end_group->format('Y-m-d 00:00:00')
			];

			if($group_item = $this->GroupsModel->getByCode($group_item_params['code']))
			{
				$group_item_id = $group_item['id'];
			}
			else
			{
				if(($group_item_id = $this->GroupsModel->add($group_item_params)) === false)
					throw new Exception('ошибка создания группы');

				if($lectures === null)
					$lectures = $this->LecturesModel->listForCourse($course['id']);

				// добавляем лекции в группу
				if($lectures)
				{
					$ts_item = clone $ts_start_group;				
					foreach($lectures as $item)
					{
						$ts = $ts_start_orign->format('Y-m-d 00:00:00');
						if((int) $item['type'] === 0)
						{
							$ts = $ts_item->format('Y-m-d 00:00:00');
							$ts_item->add($interval);
						}

						$this->LecturesModel->addLectureToGroupTs($group_item_id, $item['id'], $ts);
					}
				}
			}

			if($cnt === 1)
				$result = $group_item_id;
			else
				$result[] = $group_item_id;

			// делаем смещение на 1 неделю
			$ts_start->modify('next monday');
			$ts_start_orign->add($interval);
		}

		return $result;
	}

	// индекс курса по дате
	public function selectOfferByDate($date, $offers = [])
	{
		if(empty($date) || empty($offers) || !is_array($offers))
			return null;

		foreach($offers as $key => $val)
		{
			if(trim($date) === $val['ts_formated'])
				return $key;
		}

		return null;
	}

	// 
	public function buildHomeworkInfo($lectures, $homeworks = [], $reviews = [])
	{
		$result = [];

		// подготавливаем лекции
		foreach($lectures as $val)
		{
			if((int) $val['type'] === LecturesModel::TYPE_INTRO)
				continue;

			$val['status'] = 'info';
			$val['homeworks'] = [];
			$val['review'] = null;
			$result[$val['id']] = $val;
		}

		// присваиваем ревью к лекции
		foreach($reviews as $val)
		{
			$val['ts_timestamp'] = strtotime($val['ts']);
			$result[$val['lecture_id']]['review'] = $val;
		}

		// присваиваем домашние задания к лекции
		foreach($homeworks as $val)
		{
			$val['is_new'] = false;
			$val['ts_timestamp'] = strtotime($val['ts']);

			if(empty($result[$val['lecture_id']]['review']))
				$result[$val['lecture_id']]['status'] = 'danger';
			elseif($result[$val['lecture_id']]['review']['ts_timestamp'] < $val['ts_timestamp'])
			{
				$val['is_new'] = true;
				$result[$val['lecture_id']]['status'] = 'warning';
			}

			$result[$val['lecture_id']]['homeworks'][] = $val;
		}

		return array_values($result);
	}
}