<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsModel extends APP_Model
{
	const TABLE = 'courses_groups';
	const TABLE_HOMEWORK = 'lectures_homework';
	const TABLE_FILES = 'files';

	const TYPE = [
		'standart' => ['title' => 'Стандартная группа'], // без проверки дз и онлайн встреч
		'advanced' => ['title' => 'Расширенная группа'], // расширенный (с дз и онлайн встречами)
		'vip' => ['title' => 'VIP группа'], // advanced + чатик с преподом
		'private' => ['title' => 'Закрытая группа'] // закрытая группа
	]; 
	
	public function __construct()
	{
		parent::__construct();
	}

	// добавить группу
	public function add($data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function getImageFiles($id)
	{
		try
		{
			$sql = 'SELECT 
						f.id, f.full_path as src 
					FROM 
						'.self::TABLE_HOMEWORK.' as hw 
					LEFT JOIN 
						'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
					WHERE 
						hw.group_id = ? AND 
						f.is_image = 1';

			if($res = $this->db->query($sql, [$id])->result_array())
			{
				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function getActiveGroups()
	{
		try
		{
			$bind = [date('Y-m-d 00:00:00')];
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE ts_end > ? ORDER BY ts_end ASC';
			if(($res = $this->db->query($sql, $bind)->result_array()) !== false)
			{
				return $res;
			}

			return  $result;
		}
		catch(Exception $e)
		{
			// 
		}

		return false;
	}

	public function makeRoadmap($data)
	{
		$result = [
			'head' => [],
			'body' => []
		];
		$week_width = 25;
		$courses = [];

		// шапка
		$last_date = 'now';
		if(is_array($data) && count($data))
			$last_date = (end($data)['ts_end'] ?? 'now');
		$result['head'] = roadmap_months($last_date);

		// 
		$date_a = new DateTime(date('Y-m-01 00:00:00'));
		$date_a->modify('-1 month');

		foreach($data as $val)
		{
			$date_b = new DateTime($val['ts']);
			$date_b1 = $date_b;
			$date_c = new DateTime($val['ts_end']);
			//debug($val);

			// расчет позиции
			$left = 0;
			$week = 0;
			if($date_b > $date_a)
			{
				// неделя месяца (1 - 4)
				$day = $date_b->format('j');
				$week = intval(floor($day / 7));
				$week = ($week > 4)?4:$week;

				$m = date_diff_months($date_a, $date_b);
				$left = ((($m * 4) + $week) * $week_width);

				$diff_width_months = date_diff_months($date_b, $date_c);
			}
			else
			{
				$date_b1 = $date_a;
				$diff_width_months = date_diff_months($date_a, $date_c);
			}

			// расчет ширины
			$days = 0;
			if($diff_width_months > 0)
			{
				$days = intval($date_c->format('j'));
				$days = ($days > 28)?28:$days;
			}
			else
			{
				$days = intval($date_c->format('j')) - intval($date_b1->format('j'));
			}

			$width = ((($diff_width_months * 4) + intval(ceil($days / 7)) - $week) * $week_width); 

			$val['mark'] = [
				'width' => $width,
				'left' => $left
			];
			$val['title'] = $date_b->format('d.m').' - '.$date_c->format('d.m');

			$row_index = roadmap_check_intersect($val, ($courses[$val['course_id']] ?? []));
			$courses[$val['course_id']][$row_index][] = $val;
		}

		$result['body'] = $courses;

		return $result;
	}
}