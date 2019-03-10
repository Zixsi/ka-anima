<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'main/GroupsModel',
			'main/CoursesModel',
			'main/LecturesModel',
		]);
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

			// дата начала лекций
			$ts_start = clone $date_b;
			// если текущая дата не понедельник, получаем следующий понедельник
			if(intval($ts_start->format('N')) !== 1)
				$ts_start->modify('next monday');

			// Дата окончания курса
			$days = (intval($course['cnt_main']) + 1) * 7;
			$ts_end = clone $ts_start;
			$ts_end->add(new DateInterval('P'.$days.'D'));

			$data = [
				'type' => $data['type'],
				'code' => 'c'.$course['id'].'-'.$date_b->format('Ym'),
				'course_id' => $course['id'],
				'ts' => $date_b->format('Y-m-d 00:00:00'),
				'ts_end' => $ts_end->format('Y-m-d 00:00:00')
			];

			if(($group_id = $this->GroupsModel->add($data)) === false)
				throw new Exception($this->GroupsModel->getLastError());

			// добавляем лекции в группу
			if($lectures = $this->LecturesModel->listForCourse($course['id']))
			{
				$ts_item = clone $ts_start;				
				foreach($lectures as $item)
				{
					$this->LecturesModel->addLectureToGroupTs($group_id, $item['id'], $ts_item->format('Y-m-d 00:00:00'));
					$ts_item->add(new DateInterval('P1W')); // +1 неделя
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
}