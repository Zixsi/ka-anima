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

		if($res = $this->CoursesGroupsModel->GetListNeedCreate())
		{
			foreach($res as $item)
			{
				$data = [
					'code' => 'c'.$item['id'].'-'.$current_year.$next_month,
					'course_id' => $item['id'],
					'ts' => $ts,
					'ts_end' => 0
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

				if($group['cnt_all'] == 0)
				{
					$group['cnt_all']++;
					$this->LecturesModel->addLectureToGroup($group['id'], 6);
				}

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

}
