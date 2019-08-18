<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupsModel extends APP_Model
{
	const TABLE = 'courses_groups';
	const TABLE_COURSES = 'courses';
	const TABLE_SUBSCRIPTION = 'subscription';
	const TABLE_LECTURES_GROUPS = 'lectures_groups';
	const TABLE_HOMEWORK = 'lectures_homework';
	const TABLE_LECTURES = 'lectures';
	const TABLE_FILES = 'files';
	const TABLE_REVIEW = 'review';

	const TYPE_STANDART = 'standart'; 	// без проверки дз и онлайн встреч
	const TYPE_ADVANCED = 'advanced'; 	// расширенный (с дз и онлайн встречами)
	const TYPE_VIP = 'vip'; 			// advanced + старт в ближайший понедельник
	const TYPE_PRIVATE = 'private'; 	// закрытая группа

	const TYPE = [
		self::TYPE_STANDART => ['title' => 'Стандартная'], 
		self::TYPE_ADVANCED => ['title' => 'Расширенная'], 
		self::TYPE_VIP => ['title' => 'VIP'], 
		self::TYPE_PRIVATE => ['title' => 'Закрытая']
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
			$item = $res->row_array();
			$this->prepareItem($item);

			return $item;
		}

		return false;
	}

	// группа по коду
	public function getByCode($code)
	{
		$bind = [$code];
		$sql = 'SELECT 
					g.*, c.name, lg.cnt as cnt 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN
					(SELECT COUNT(*) as cnt, tlg.group_id FROM '.self::TABLE_LECTURES_GROUPS.' as tlg LEFT JOIN '.self::TABLE_LECTURES.' as tl ON(tl.id = tlg.lecture_id) WHERE tl.type = 0 GROUP BY tlg.group_id) as lg ON(lg.group_id = g.id) 
				WHERE 
					g.code = ?';
		if($res = $this->db->query($sql, $bind))
		{
			if($val = $res->row_array())
			{
				$now_ts = time();
				
				$val['timestamp_start'] = strtotime($val['ts']);
				$val['timestamp_end'] = strtotime($val['ts_end']);
				$val['status'] = ($val['timestamp_end'] > $now_ts)?(($val['timestamp_start'] > $now_ts)?1:0):-1;

				return $val;
			}
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
		return $this->db->delete(self::TABLE);
	}
	
	public function getImageFiles($id)
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
			foreach($res as &$val)
			{
				$val['src_thumb'] = thumb($val['src']);
			}

			return $res;
		}

		return [];
	}

	public function getVideoFiles($id)
	{
		$sql = 'SELECT 
					f.id, f.full_path as src 
				FROM 
					'.self::TABLE_HOMEWORK.' as hw 
				LEFT JOIN 
					'.self::TABLE_FILES.' as f ON(f.id = hw.file) 
				WHERE 
					hw.group_id = ? AND 
					f.file_ext = \'.mp4\'';

		if($res = $this->db->query($sql, [$id]))
		{
			$res = $res->result_array();
			foreach($res as &$val)
			{
				$val['src_thumb'] = IMG_DEFAULT_300_300;
			}

			return $res;
		}

		return [];
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

	// список групп преподавателя
	public function getTeacherGroups($teacher, $active = true, $filter = [])
	{
		$now = new DateTime('now');
		$now_ts = $now->getTimestamp();
		$bind = [$teacher, self::TYPE_STANDART];
		$sql_where = '';

		// только автивные группы
		if($active)
		{
			$bind[] = $now->format('Y-m-d H:i:s');
			$bind[] = $now->format('Y-m-d H:i:s');
			$sql_where .= ' AND (g.ts <= ? AND g.ts_end > ?) ';
		}

		if(($filter['with_subscribed'] ?? false))
		{
			$sql_where .= ' AND s.subscr_cnt > 0 ';
		}

		$sql = 'SELECT 
					g.*, c.name, s.subscr_cnt 
				FROM 
					'.self::TABLE.' as g 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = g.course_id) 
				LEFT JOIN 
					(SELECT 
						target, count(*) as subscr_cnt  
					FROM 
						'.self::TABLE_SUBSCRIPTION.' 
					WHERE 
						target_type = \'course\' 
					GROUP BY 
						target) as s ON(s.target = g.id) 
				WHERE 
					g.deleted = 0 AND 
					g.teacher = ? AND 
					g.type != ?  
					'.$sql_where.' 
				ORDER BY 
					g.ts_end ASC, g.ts ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			foreach($res as &$val)
			{
				$val['subscr_cnt'] = (int) $val['subscr_cnt'];
				$val['timestamp_start'] = strtotime($val['ts']);
				$val['timestamp_end'] = strtotime($val['ts_end']);
				$val['status'] = ($val['timestamp_end'] > $now_ts)?(($val['timestamp_start'] > $now_ts)?1:0):-1;
			}

			return $res;
		}

		return  [];
	}

	// список предложений 
	/*public function listOffers()
	{
		$result = [];
		$now = new DateTime('now');
		$now->setTime(0, 0, 0);
		$start_ts = clone $now;
		$start_ts->modify('-2 weeks'); // за 2 недели после старата
		$end_ts = clone $now;
		$end_ts->modify('+3 months'); // за 3 месяца до старта

		$bind = [
			$start_ts->format('Y-m-d 00:00:00'), 
			$end_ts->format('Y-m-d 00:00:00'), 
			$now->format('Y-m-d 00:00:00')
		];

		$sql = 'SELECT 
					c.id, c.code, c.name, c.description, c.price, c.only_standart, f.full_path as img   
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
				GROUP BY 
					c.id 
				ORDER BY 
					c.id ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$rows = $res->result_array();
			if(is_array($rows))
				$result = $rows;

			$this->prepareOffersList($result);
		}

		return $result;
	}*/

	// список предложений для курса
	public function listOffersForCourse($course)
	{
		$result = [];
		$now = new DateTime('now');
		$now->setTime(0, 0, 0);

		$before_start_mod = '+'.OptionsModel::getNumberWeeksBeforeStartCourse().' weeks';
		$after_start_mod = '-'.OptionsModel::getNumberWeeksAfterStartCourse().' weeks';

		$start_ts = clone $now;
		$start_ts->modify($after_start_mod); // за n недель после старта
		$end_ts = clone $now;
		$end_ts->modify($before_start_mod); // за n недель до старта

		$bind = [
			$course, 
			$start_ts->format('Y-m-d 00:00:00'), 
			$end_ts->format('Y-m-d 00:00:00'), 
			$now->format('Y-m-d 00:00:00'),
			self::TYPE_STANDART
		];

		$sql = 'SELECT 
					* 
				FROM 
					'.self::TABLE.' 
				WHERE 
					course_id = ? AND 
					deleted = 0 AND 
					(ts > ? AND ts < ?) AND 
					ts_end > ? AND 
					type = ? 
				ORDER BY 
					ts ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$rows = $res->result_array();
			if(is_array($rows))
				$result = $rows;

			foreach($result as &$val)
			{
				$this->prepareItem($val);
			}
		}

		return $result;
	}

	// подготовить список предложений
	public function prepareOffersList(&$data)
	{
		if(!is_array($data))
			return;

		foreach($data as &$val)
		{
			$val['free'] = false;
			$val['only_standart'] = (int) ($val['only_standart'] ?? 0);

			if(isset($val['price']))
			{
				$val['price'] = json_decode($val['price'], true);

				if($val['only_standart'])
					$val['free'] = ((int) $val['price']['standart']['month'] === 0);
				else
				{
					foreach($val['price'] as $v)
					{
						if(((int) $v['month'] === 0))
						{
							$val['free'] = true;
							break;
						}
					}
				}
			}

			if(array_key_exists('img', $val))
				$val['img'] = empty($val['img'])?IMG_DEFAULT_16_9:'/'.$val['img'];
		}
	}

	public function prepareItem(&$item)
	{
		if(!is_array($item))
			return;

		$item['ts_timestamp'] = strtotime($item['ts']);
		$item['ts_end_timestamp'] = strtotime($item['ts_end']);
		$item['ts_formated'] = date(DATE_FORMAT_SHORT, $item['ts_timestamp']);
		$item['ts_end_formated'] = date(DATE_FORMAT_SHORT, $item['ts_end_timestamp']);
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
					$val['style'] = 'mdi mdi-brightness-5';
				break;
				case 'vip':
					$val['style'] = 'mdi mdi-brightness-6';
				break;
				case 'private':
					$val['style'] = 'mdi mdi-brightness-7';
				break;
				default:
					$val['style'] = 'mdi mdi-brightness-1';
				break;
			}

			$row_index = roadmap_check_intersect($val, ($courses[$val['course_id']] ?? []));
			$courses[$val['course_id']][$row_index][] = $val;
		}

		$result['body'] = $courses;

		return $result;
	}

	// статус домашних заданий для группы
	public function groupHomeworkStatus($id)
	{
		$bind = [$id];
		$sql = 'SELECT 
					MAX(lh.id) as id, lh.group_id, lh.lecture_id, lh.user, lr.id as review  
				FROM 
					'.self::TABLE_HOMEWORK.' as lh 
				LEFT JOIN
					(SELECT MAX(id) as id, group_id, lecture_id, user FROM '.self::TABLE_REVIEW.' GROUP BY group_id, lecture_id, user) as lr ON(lr.group_id = lh.group_id AND lr.lecture_id = lh.lecture_id AND lr.user = lh.user) 
				WHERE 
					lh.group_id = ? 
				GROUP BY 
					lh.group_id, lh.lecture_id, lh.user';

		$result = [];			
		if($res = $this->db->query($sql, $bind))
		{
			if($res = $res->result_array())
			{
				foreach($res as $val)
				{
					if(!array_key_exists($val['user'], $result))
					{
						$result[$val['user']] = [
							'homeworks' => 0,
							'reviews' => 0
						];
					}

					$result[$val['user']]['homeworks']++;
					if((int) $val['review'] > 0)
						$result[$val['user']]['reviews']++;
				}
			}
		}

		return $result;
	}
}