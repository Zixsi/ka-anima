<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsModel extends APP_Model
{
	const TABLE = 'courses_groups';
	const TABLE_COURSES = 'courses';
	const TABLE_SUBSCRIPTION = 'subscription';
	const TABLE_HOMEWORK = 'lectures_homework';
	const TABLE_FILES = 'files';

	const TYPE = [
		'standart' => ['title' => 'Стандартная'], // без проверки дз и онлайн встреч
		'advanced' => ['title' => 'Расширенная'], // расширенный (с дз и онлайн встречами)
		'vip' => ['title' => 'VIP'], // advanced + старт в ближайший понедельник
		'private' => ['title' => 'Закрытая'] // закрытая группа
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

	// группа по id
	public function getByID($id)
	{
		$bind = [$id];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE id = ?';
		if($res = $this->db->query($sql, $bind))
		{
			return $res->row_array();
		}

		return false;
	}

	public function getByIdDetail($id)
	{
		$bind = [$id];
		$sql = 'SELECT 
					c.id, c.name, c.description, c.price, c.only_standart, c.teacher, g.id as group_id, 
					g.code, g.ts, g.ts_end, f.full_path as img_src 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img) 
				WHERE 
					g.id = ?';
		if($res = $this->db->query($sql, $bind))
		{
			return $res->row_array();
		}

		return false;
	}

	// поиск подходящей ближайше вип группы
	public function getNearVip($course)
	{
		$date = new DateTime('now');
		if(intval($date->format('N')) !== 1)
			$date->modify('next monday');

		$bind = [$course, $date->format('Y-m-d 00:00:00')];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE course_id = ? AND type = \'vip\' AND deleted = 0 AND ts = ? ';
		if($res = $this->db->query($sql, $bind))
		{
			return $res->row_array();
		}

		return false;
	}

	// удаление 
	public function remove($id)
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, ['deleted' => 1]))
			return true;
			
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

	// список активных групп
	public function getActiveGroups()
	{
		$bind = [date('Y-m-d 00:00:00')];
		$sql = 'SELECT 
					g.* , s.cnt as subscription_cnt 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					(SELECT target, count(*) as cnt FROM '.self::TABLE_SUBSCRIPTION.' WHERE target_type = \'course\' GROUP BY target) as s ON(s.target = g.id) 
				WHERE 
					deleted = 0 AND 
					ts_end > ? 
				ORDER BY 
					ts_end ASC';
		if($res = $this->db->query($sql, $bind))
		{
			return $res->result_array();
		}

		return  [];
	}

	// список предложений 
	public function listOffers()
	{
		$result = [];
		$now = new DateTime('now');
		$now->setTime(0, 0, 0);
		$start_ts = clone $now;
		$start_ts->modify('-2 weeks'); // за 2 недели после старата
		$end_ts = clone $now;
		$end_ts->modify('+3 months'); // за 3 месяца до старта
		
		// debug($start_ts->format('Y-m-d 00:00:00')); die();


		$bind = [
			$start_ts->format('Y-m-d 00:00:00'), 
			$end_ts->format('Y-m-d 00:00:00'), 
			$now->format('Y-m-d 00:00:00')
		];

		$sql = 'SELECT 
					c.id, c.name, c.description, c.price, c.only_standart , g.id as group_id, 
					g.code, g.ts, f.full_path as img_src   
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = c.img) 
				WHERE 
					c.active = 1 AND 
					g.deleted = 0 AND 
					(g.ts > ? AND g.ts < ?) AND 
					g.ts_end > ? AND 
					g.type NOT IN(\'vip\', \'private\')
				ORDER BY 
					g.course_id ASC, g.ts ASC';


		if($res = $this->db->query($sql, $bind))
		{
			$rows = $res->result_array();
			foreach($rows as $val)
			{
				if(!array_key_exists($val['id'], $result))
				{
					$result[$val['id']] = [
						'id' => $val['id'],
						'name' => $val['name'],
						'img' => $val['img_src'],
						'only_standart' => $val['only_standart'],
						'description' => $val['description'],
						'free' => false,
						'price' => json_decode($val['price'], true),
						'groups' => []
					];
				}

				$result[$val['id']]['groups'][] = [
					'id' => $val['group_id'],
					'code' => $val['code'],
					'ts' => $val['ts'] 
				];

				if((int) $result[$val['id']]['only_standart'] === 1 && (int) $result[$val['id']]['price']['month'] === 0)
				{
					$result[$val['id']]['free'] = true;
				}
			}
		}

		return $result;
	}

	// карта курсов (для админки)
	public function makeRoadmap($data)
	{
		$result = [
			'head' => [],
			'body' => [],
			'week' => [
				'left' => 0
			]
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

		// параметры текущей недели
		$date_now = new DateTime('now');
		$cday = $date_now->format('j');
		$сweek = intval(floor(($cday - 1) / 7));
		$сweek = ($сweek > 3)?3:$сweek;
		$result['week']['left'] = ((4 + $сweek) * $week_width);

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
			$val['subscription_cnt'] = intval($val['subscription_cnt']);
			$val['days'] = 0;
			if($date_now < $date_b)
			{
				$diff = $date_b->diff($date_now);
				$val['days'] = (int) $diff->days;
			}

			$val['style'] = 'fas fa-chess-pawn';
			switch($val['type'])
			{
				case 'advanced':
					$val['style'] = 'fas fa-chess-bishop';
				break;
				case 'vip':
					$val['style'] = 'fas fa-chess-king';
				break;
				case 'private':
					$val['style'] = 'fas fa-chess';
				break;
				default:
					$val['style'] = 'fas fa-chess-pawn';
				break;
			}

			$row_index = roadmap_check_intersect($val, ($courses[$val['course_id']] ?? []));
			$courses[$val['course_id']][$row_index][] = $val;
		}

		$result['body'] = $courses;

		return $result;
	}
}