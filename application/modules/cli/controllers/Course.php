<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends APP_Controller
{
	public function __construct()
	{
		$this->load->model(['main/CoursesModel', 'main/LecturesModel', 'main/CoursesGroupsModel']);
	}

	public function index()
	{
		// 
	}

	// Добавляем курс в список доступных для подписки
	// php www/index.php cli course createGroups
	public function createGroups()
	{
		var_dump('Create Groups');

		$start_months = $this->config->item('start_course_months');
		$current_month = intval(date('n'));
		$current_year = intval(date('Y'));
		$next_month_index = round($current_month  / 3) - 1;
		$next_month = $start_months[$next_month_index];
		$next_month = ($next_month < 10)?'0'.$next_month:$next_month;
		$current_year += ($next_month < $current_month)?1:0;
		$ts = date($current_year.'-'.$next_month.'-01 00:00:00');

		// Высчитываем дату окончания курсов
		// получаем первый понедельник месяца в котором начинается курс
		$ts_obj = new DateTime($ts);
		$day = compute_day(1, 1, $ts_obj->format('n'), $ts_obj->format('Y')) - 1;

		if($res = $this->CoursesGroupsModel->GetListNeedCreate())
		{
			foreach($res as $item)
			{
				$info = $this->LecturesModel->getCntByCourse($item['id']);

				$days = $day + (($info['cnt_main'] + 1) * 7);
				$ts_item = new DateTime($ts);
				$ts_item->add(new DateInterval('P'.$days.'D'));

				$data = [
					'code' => 'c'.$item['id'].'-'.$current_year.$next_month,
					'course_id' => $item['id'],
					'ts' => $ts,
					// и прибавляем кол-во недель равного кол-ву основных курсов + 1 неделя
					'ts_end' => $ts_item->format('Y-m-d 00:00:00')
				];

				$this->CoursesGroupsModel->Add($data);
			}
		}
	}

	// Делаем доступной следующую лекцию в группе
	// php www/index.php cli course createLectures
	public function createLectures()
	{
		var_dump('Create Lectures');

		if($groups = $this->CoursesGroupsModel->getActiveGroups())
		{
			foreach($groups as $group)
			{
				$trigger = false;
				$lectures = $this->LecturesModel->listAvailableForAddToGroup($group['course_id'], $group['cnt_available']);

				// Добавить вводную лекцию в список
				/*if($group['cnt_all'] == 0)
				{
					$group['cnt_all']++;
					$this->LecturesModel->addLectureToGroup($group['id'], 6);
				}*/

				foreach($lectures as $val)
				{
					// Если обычная лекция
					if($val['type'] == 0)
					{
						if($trigger == false)
						{
							$trigger = true;
							$group['cnt_all']++;
							$this->LecturesModel->addLectureToGroup($group['id'], $val['id']);
						}
						else
						{
							break;
						}
					}
					else
					{
						// бонусные лекции которые идут сразу после основной 
						// делаем доступными сразу
						$group['cnt_all']++;
						$this->LecturesModel->addLectureToGroup($group['id'], $val['id']);
					}
				}
			}
		}
	}

	// Обновление ссылок на видео
	// php www/index.php cli course updateLecturesVideoUrl
	public function updateLecturesVideoUrl()
	{
		var_dump('Update Lectures Video Url');
		$this->load->library(['youtube']);

		if($items = $this->LecturesModel->listLecturesVideoForUpdate(300))
		{
			foreach($items as $item)
			{
				$code  = $this->youtube->extractVideoId($item['video']);
				$video_array = $this->youtube->getVideo($code);
				foreach($video_array as $key => $val)
				{
					$video = current($val);
					$this->LecturesModel->updateLectureVideo($item['id'], $video , $key);
				}
			}
		}
	}

	// php www/index.php cli course test
	public function test()
	{
		/*var_dump('Test');
		$this->load->library(['youtube']);

		$code  = $this->youtube->extractVideoId('https://www.youtube.com/watch?v=x8XXOvIEVco');
		var_dump($code);
		$video_array = $this->youtube->getInfo($code);
		//$video_array = $this->youtube->getVideo($code);
		var_dump($video_array);*/
	}
}
