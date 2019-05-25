<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends APP_Controller
{
	public function __construct()
	{

	}

	public function index()
	{
		// 
	}

	// Добавляем курс в список доступных для подписки
	// php index.php cli course createGroups
	public function createGroups()
	{
		/*var_dump('Create Groups');

		if($res = $this->CoursesGroupsModel->getListNeedCreate())
		{
			$start_months = $this->config->item('start_course_months');
			$current_month = intval(date('n'));
			$current_year = intval(date('Y'));

			// Высчитать следующий месяц новых курсов
			$next_month_index = ceil($current_month  / 3) - 1;
			$next_month_index = ($start_months[$next_month_index] == $current_month)?($next_month_index + 1):$next_month_index;
			if($next_month_index > (count($start_months) - 1))
			{
				$next_month_index = 0;
			}
			$next_month = $start_months[$next_month_index];
			$next_month = ($next_month < 10)?'0'.$next_month:$next_month;


			$current_year += ($next_month < $current_month)?1:0;
			$ts = date($current_year.'-'.$next_month.'-01 00:00:00');

			// получаем первый понедельник месяца в котором начинается курс
			$ts_start = new DateTime($ts);

			// Если текущая дата не понедельник
			// получаем следующий понедельник
			if(intval($ts_start->format('N')) !== 1)
			{
				$ts_start->modify('next monday');
			}
			
			foreach($res as $item)
			{
				$info = $this->LecturesModel->getCntByCourse($item['id']);
				// Если лекций нет - пропускаем
				if($info['cnt_main'] == 0) continue;

				// кол-во недель равного кол-ву основных курсов + 1 неделя
				$days = ($info['cnt_main'] + 1) * 7;
				$ts_end = clone $ts_start;
				$ts_end->add(new DateInterval('P'.$days.'D'));

				$data = [
					'code' => 'c'.$item['id'].'-'.$current_year.$next_month,
					'course_id' => $item['id'],
					'ts' => $ts,
					'ts_end' => $ts_end->format('Y-m-d 00:00:00')
				];

				//debug($data);
				if($group_id = $this->CoursesGroupsModel->add($data))
				{
					// Добавляем лекции в группу
					if($list_courses = $this->LecturesModel->listForCourse($item['id']))
					{
						$ts_item = clone $ts_start;

						// TODO: брать стартовую лекцию из настроек курса
						// Добавляем стартувую лекцию
						$this->LecturesModel->addLectureToGroupTs($group_id, 6, $ts_item->format('Y-m-d 00:00:00'));
						
						foreach($list_courses as $course)
						{
							$this->LecturesModel->addLectureToGroupTs($group_id, $course['id'], $ts_item->format('Y-m-d 00:00:00'));
							$ts_item->add(new DateInterval('P1W')); // +1 неделя
						}
					}
				}
				//break;
			}
		}*/
	}

	// Обновление ссылок на видео
	// php index.php cli course updateVideoUrl
	public function updateVideoUrl()
	{
		echo 'Update Video Url => Start'.PHP_EOL;
		$this->load->library(['ydvideo']);

		if($items = $this->VideoModel->forUpdate(3600))
		{
			echo 'Count video => '.count($items).PHP_EOL;
			foreach($items as $item)
			{
				$video = $this->ydvideo->getVideo($item['code']);
				$this->VideoModel->set($item['source_id'], $item['code'], $video['video'], $item['source_type'], $item['type']);
			}
		}
		echo 'Update Video Url => End'.PHP_EOL;
	}

	// php index.php cli course addLectureVideo
	public function addLectureVideo()
	{
		/*var_dump('addLectureVideo');
		$this->load->library(['youtube']);

		if($items = $this->LecturesModel->list())
		{
			foreach($items as $item)
			{
				if($this->VideoModel->prepareAndSet($item['id'], 'lecture', $item['video']) == false)
				{
					debug($this->VideoModel->getLastError()); die();
				}
			}
		}*/
	}
}
