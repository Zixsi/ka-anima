<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionModel extends APP_Model
{
	const TABLE = 'subscription';
	const TABLE_COURSES = 'courses';
	const TABLE_COURSES_GROUPS = 'courses_groups';
	const TABLE_USERS = 'users';

	// Тип подписки
	const TYPES_SUBSCR = [
		0, // полная (подписка произведена на весь срок)
		1, // частичная (подписка на месяц / несколько месяцев)
		2 // продление
	];

	// тип объекта подписки
	const TYPES_TARGET = [
		'course', // курс
	];

	// вариант подписки 
	const TYPES = [
		'standart' => ['title' => 'Стандартная'],
		'advanced' => ['title' => 'Расширенная'],
		'vip' => ['title' => 'VIP'],
		'private' => ['title' => 'Закрытая']
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel']);
	}

	public function add($data = [])
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	public function update($id, $data = [])
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			return true;

		return false;
	}

	public function getByID($id)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE id = ?', [$id]);
		if($row = $res->row_array())
		{
			return $row;
		}

		return false;
	}

	
	public function сheck($user, $target, $target_type = 'course')
	{
		$bind = [$user, $target, $target_type];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND target = ? AND target_type = ?';
		$res = $this->db->query($sql, $bind);
		if($res->row())
			return true;
		
		return false;
	}

	public function get($user, $target, $target_type = 'course')
	{
		$bind = [$user, $target, $target_type];
		$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND target = ? AND target_type = ?';
		$res = $this->db->query($sql, $bind);
		if($item = $res->row_array())
		{
			$item['active'] = (strtotime($item['ts_end']) > time())?true:false;
			return $item;
		}
		
		return false;
	}

	// курсы на которые подписан пользователь
	public function coursesList($user)
	{
		if( (int) $user === 0)
			return [];

		$bind = [(int) $user, date('Y-m-d H:i:s')];
		$sql = 'SELECT 
					c.id, c.name, cg.ts, cg.ts_end, cg.id as course_group, cg.code as code   
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_COURSES_GROUPS.' as cg ON(cg.id = s.target AND s.target_type = \'course\') 
				LEFT JOIN 
					'.self::TABLE_COURSES.' as c ON(c.id = cg.course_id) 
				WHERE 
					s.user = ? AND cg.ts_end > ? 
				ORDER BY 
					cg.id ASC';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			foreach($res as $key => &$val)
			{
				$val['ts_f'] = date('F Y', strtotime($val['ts']));
			}

			return $res;
		}

		return [];
	}

	// список идентификаторов курсов на которые подписан пользователь
	public function listCoursesId($user)
	{
		$result = [];
		if($res = $this->coursesList($user))
		{
			foreach($res as $val)
			{
				$result[] = $val['id'];
			}
		}

		return $result;
	}

	
	// Подписки пользователя
	public function byUser($user)
	{
		$sql = 'SELECT 
					s.*, cg.code  
				FROM 
					'.self::TABLE.' as s 
				LEFT JOIN 
					'.self::TABLE_COURSES_GROUPS.' as cg ON(cg.id = s.target AND s.target_type = \'course\') 
				WHERE 
					user = ? 
				ORDER BY 
					id DESC';
		$res = $this->db->query($sql, [intval($user)]);
		if($res = $res->result_array())
		{
			foreach($res as &$val)
				$val['active'] = (strtotime($val['ts_end']) > time())?true:false;

			return $res;
		}


		return false;
	}

	// пользователи группы
	public function getGroupUsers($group,  $type = null)
	{
		if($group > 0)
		{
			$bind = [(int) $group];
			$sql_where = '';

			if($type && array_key_exists($type, self::TYPES))
			{
				$bind[] = $type;
				$sql_where .= ' AND s.type = ? ';
			}

			$sql = 'SELECT 
						u.id, u.email, CONCAT_WS(\' \', u.name, u.lastname) as full_name
					FROM 
						'.self::TABLE.' as s 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(s.user = u.id) 
					WHERE 
						s.target = ? AND s.target_type = \'course\' '.$sql_where.' 
					GROUP BY 
						s.user 
					ORDER BY 
						s.user ASC';

			if($res = $this->db->query($sql, $bind))
			{
				// debug($res); die();
				$result = [];
				$res = $res->result_array();
				foreach($res as $val)
				{
					$val['img'] = $this->imggen->createIconSrc(['seed' => md5('user'.$val['id'])]);
					$result[$val['id']] = $val;
				}

				unset($res);
				return $result;
			}
		}

		return false;
	}

	public function statForIds($ids = [])
	{
		$bind = [];
		$sql = 'SELECT 
					target as id, count(*) as cnt 
				FROM 
					'.self::TABLE.' 
				WHERE 
					target IN('.implode(',', $ids).') AND 
					target_type = \'course\' AND 
					type != \'standart\' 
				GROUP BY 
					target';

		if($res = $this->db->query($sql, $bind))
		{
			$res = $res->result_array();
			$result = [];
			foreach($res as $val)
			{
				$result[$val['id']] = $val;
			}
			
			unset($res);
			return $result;
		}

		return [];
	}

	
	// Продление подписки
	public function renewItem($id)
	{
		try
		{
			if($item = $this->getByID($id))
			{
				unset($item['id']);
				$price = 0;
				$item['data'] = json_decode($item['data'], true);
				$item['amount'] = floatval($item['amount']);
				$ts_end_mark = strtotime($item['ts_end']);

				// debug($item); die();

				switch($item['target_type'])
				{
					case 'course':
						// Продление на месяц
						if($item['amount'] > 0)
						{
							if(($service = $this->CoursesGroupsModel->getByID($item['target'])) == false)
								throw new Exception('Group not found', 1);

							$price = ($item['amount'] > $item['data']['price'])?$item['data']['price']:$item['amount'];

							$ts_end = new DateTime($item['ts_end']);
							$ts_end->modify('next month');
							$ts_end_format = $ts_end->format('Y-m-d 00:00:00');

							$item['ts_end'] = (strtotime($ts_end_format) > strtotime($service['ts_end']))?$service['ts_end']:$ts_end_format;
							$item['amount'] -= $price;
						}
						// Продление на год
						elseif($ts_end_mark < time())
						{
							// TODO: сумма продления на год 
							$price = 0;
							$ts_end = new DateTime();
							$ts_end->modify('next year');
							$item['ts_end'] = $ts_end->format('Y-m-d 00:00:00');
						}
					break;
					default:
						// empty	
					break;
				}

				$this->db->trans_begin();

				if($this->Auth->balance() < $price)
					throw new Exception('Insufficient funds', 1);

				$item['data'] = json_encode($item['data']);
				if($this->update($id, $item) == false)
					throw new Exception('Update error', 1);

				if($price >= 0)
				{
					$tx = [
						'user' => $item['user'],
						'type' => '1',
						'amount' => (float) $price,
						'description' => $item['description'],
						'service' => 'group',
						'service_id' => $item['target']
					];

					if($this->TransactionsModel->add($tx))
						$this->Auth->updateBalance();
					else
						throw new Exception('Pay error', 1);
				}

				if($this->db->trans_status() === FALSE)
					throw new Exception('Renew error', 1);

				$this->db->trans_commit();

				return true;
			}
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}
	

	/*
	public function List($filter = [], $order = [], $select = [])
	{
		$select = count($select)?implode(', ', $select):'*';
		$this->db->select($select);
	
		count($filter)?$this->db->where($filter):$this->db->where('id >', 0);
		foreach($order as $key => $val)
		{
			$this->db->order_by($key, $val);
		}

		if($res = $this->db->get(self::TABLE))
		{
			return $res->result_array();
		}

		return false;
	}

	public function group($user, $group, $price_period = null)
	{
		try
		{	
			if(intval($user) == 0)
			{
				throw new Exception('User not found', 1);
			}

			if(($item = $this->CoursesGroupsModel->getByID($group)) == false)
			{
				throw new Exception('Group not found', 1);
			}

			if(in_array($price_period , ['month', 'full']) == false)
			{
				throw new Exception('Invalid price period', 1);
			}
			elseif($this->Auth->balance() < $item['price_'.$price_period])
			{
				throw new Exception('Insufficient funds', 1);
			}

			if($this->CheckSubscibe($user, $group))
			{
				throw new Exception('Already subscribed', 1);
			}

			$ts_end = $item['ts_end'];
			$subscr_type = 0;
			$amount = 0; // остаток для оплаты

			// Оплата за месяц
			if($price_period == 'month')
			{
				$ts_curr = new DateTime($item['ts']);
				$ts_month = new DateTime($item['ts']);
				$ts_month->modify('next month');
				$diff = $ts_curr->diff($ts_month);
				
				// Если разница между датой окончания и месяцем оплаты меньше или равно 1 недели 
				// то устанавливаем дату окончания курса
				if(strtotime($ts_month->format('Y-m-d 00:00:00')) < strtotime($ts_end) && $diff->d > 7)
				{
					$subscr_type = 1;
					$ts_end = $ts_month->format('Y-m-d 00:00:00');

					// Рассчитываем остаток оплаты при покупке месяца
					$diff = $ts_curr->diff(new DateTime($item['ts_end']));
					if($diff->d > 7)
					{
						$diff->m++;
					}

					$amount = ($diff->m * $item['price_month']) - $item['price_month'];
				}
			}

			$data = [
				'user' => intval($user),
				'type' => 0,
				'service' => intval($group),
				'description' => $item['name'].' ('.strftime("%B %Y", strtotime($item['ts'])).')',
				'ts_start' => $item['ts'],
				'ts_end' => $ts_end,
				'subscr_type' => $subscr_type,
				'price_month' => $item['price_month'],
				'price_full' => $item['price_full'],
				'amount' => $amount
			];

			$this->db->trans_begin();

			if(($this->add($data)) == false)
			{
				throw new Exception($this->LAST_ERROR, 1);
			}

			$fields = [
				'user' => $user,
				'type' => '1',
				'amount' => $item['price_'.$price_period],
				'description' => $data['description'],
				'service' => 'group',
				'service_id' => $group
			];

			if($this->TransactionsModel->add($fields))
			{
				 $this->Auth->updateBalance();
			}

			if($this->db->trans_status() === FALSE)
			{
				throw new Exception('Subscibe error', 1);
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}
	*/

}