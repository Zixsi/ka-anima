<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		try
		{
			$this->db->trans_begin();

			$data['course'] = intval($data['course'] ?? 0);
			if(($course = $this->CoursesModel->getByID($data['course'])) === false)
				throw new Exception('Курс не найден');

			$data['date'] = ($data['date'] ?? null);
			if(empty($data['date']))
				throw new Exception('Не указана дата начала');

			$date_a = new DateTime(date('Y-m-d 00:00:00'));
			$date_b = new DateTime(date('Y-m-d 00:00:00', strtotime($data['date'])));
			if($date_a > $date_b)
				throw new Exception('Неверная дата начала');

			$data['type'] = ($data['type'] ?? null);
			if(empty($data['type']) || !array_key_exists($data['type'], GroupsModel::TYPE))
				throw new Exception('Неверный тип группы');

			$data['users'] = ($data['users'] ?? []);
			if($data['type'] === 'private' && empty($data['users']))
				throw new Exception('не выбраны ученики');


			// дата начала лекций
			$ts_start = clone $date_b;
			// если текущая дата не понедельник, получаем следующий понедельник
			if(intval($ts_start->format('N')) !== 1)
				$ts_start->modify('next monday');

			// Дата окончания курса
			$days = (intval($course['cnt_main']) + 1) * 7;
			$ts_end = clone $ts_start;
			$ts_end->add(new DateInterval('P'.$days.'D'));

			$item_data = [
				'type' => $data['type'],
				'teacher' => $course['teacher'],
				'code' => $this->makeCode($course['code'], $data['type'], $date_b->format('dmy')),
				'course_id' => $course['id'],
				'ts' => $date_b->format('Y-m-d 00:00:00'),
				'ts_end' => $ts_end->format('Y-m-d 00:00:00')
			];

			if(($group_id = $this->GroupsModel->add($item_data)) === false)
				throw new Exception('Ошибка создания группы');

			// добавляем лекции в группу
			if($lectures = $this->LecturesModel->listForCourse($course['id']))
			{
				$ts_item = clone $ts_start;				
				foreach($lectures as $item)
				{
					$ts = $date_a->format('Y-m-d 00:00:00');
					if((int) $item['type'] === 0)
					{
						$ts = $ts_item->format('Y-m-d 00:00:00');
						$ts_item->add(new DateInterval('P1W')); // +1 неделя
					}

					$this->LecturesModel->addLectureToGroupTs($group_id, $item['id'], $ts);
				}
			}

			// если закрытая группа
			if($data['type'] === 'private')
			{
				// добавляем выбранных учеников в группу
				foreach($data['users'] as $val)
				{
					$data = [
						'user' => $val,
						'type' => $data['type'],
						'target' => $group_id,
						'target_type' => 'course',
						'description' => $course['name'].' ('.strftime("%B %Y", strtotime($item_data['ts'])).')',
						'ts_start' => $item_data['ts'],
						'ts_end' => $item_data['ts_end'],
						'subscr_type' => 0,
						'amount' => 0,
						'data' => json_encode(['price' => 0])
					];

					$this->SubscriptionModel->add($data);
				}
			}

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка добавления');
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
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

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка удаления');
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
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
		// получаем идентификаторы групп
		$ids = [];
		foreach($data as $val)
		{
			$ids[] = $val['id'];
		}

		// получаем информацию по кол-ву подписок 
		$subscr_stat = [];
		if(count($ids))
			$subscr_stat = $this->SubscriptionModel->statForIds($ids);

		// проставляем информацию для каждой группы
		foreach($data as &$val)
		{
			$val['subscr_cnt'] = 0;
			if(array_key_exists($val['id'], $subscr_stat))
				$val['subscr_cnt'] = $subscr_stat[$val['id']]['cnt'];
		}
	}

	// код группы
	public function makeCode($course_code, $type, $date)
	{
		return $course_code.'-'.substr(trim($type), 0, 1).$date;
	}

	public function userAdd($data)
	{
		try
		{
			$this->db->trans_begin();

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
}