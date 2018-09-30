<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionModel extends APP_Model
{
	private const TABLE = 'subscription';
	private const TABLE_COURSES = 'courses';
	private const TABLE_COURSES_GROUPS = 'courses_groups';
	private const TABLE_USERS = 'users';
	private const TABLE_FIELDS = ['user', 'type', 'service', 'description', 'ts_start', 'ts_end', 'subscr_type', 'price_month', 'price_full', 'amount'];
	
	// Типы сервисов подписки
	private const TYPES = [
		0, // курсы
		1, // резерв
		2 // резерв
	];

	// Тип подписки
	private const TYPES_SUBSCR = [
		0, // полная (подписка произведена на весь срок)
		1, // частичная (подписка на месяц / несколько месяцев)
		2 // продление
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel']);
	}

	public function add($data = [])
	{
		try
		{
			$this->_checkFields($data);

			if($this->db->insert(self::TABLE, $data))
			{
				return $this->db->insert_id();
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function update($id, $data = [])
	{
		try
		{
			$this->_checkFields($data);

			$this->db->where('id', $id);
			if($this->db->update(self::TABLE, $data))
			{
				return true;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	public function delete($id)
	{
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



	public function CheckSubscibe($user, $service, $type = 0)
	{
		$res = $this->db->query('SELECT * FROM '.self::TABLE.' WHERE user = ? AND service = ? AND type = ?', [$user, $service, $type]);
		if($res->row())
		{
			return true;
		}

		return false;
	}

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
				'description' => $item['name'].' ('.date('F Y', strtotime($item['ts'])).')',
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

	// Курсы на которые подписан пользователь
	public function coursesList($user)
	{
		try
		{
			if(intval($user) == 0)
			{
				throw new Exception('User not found', 1);
			}

			$sql = 'SELECT 
						c.id, c.name, c.type, cg.ts, cg.id as course_group  
					FROM 
						'.self::TABLE.' as s 
					LEFT JOIN 
						'.self::TABLE_COURSES_GROUPS.' as cg ON(s.service = cg.id AND s.type = 0) 
					LEFT JOIN 
						'.self::TABLE_COURSES.' as c ON(cg.course_id = c.id) 
					WHERE 
						s.user = ? 
					ORDER BY 
						cg.id ASC';

			$res = $this->db->query($sql, [intval($user)]);
			if($res = $res->result_array())
			{
				foreach($res as $key => &$val)
				{
					$val['ts_f'] = date('F Y', strtotime($val['ts']));
				}

				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	// Подписки пользователя
	public function byUser($user)
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? ORDER BY id DESC';
			$res = $this->db->query($sql, [intval($user)]);
			if($res = $res->result_array())
			{
				$result = [];

				foreach($res as &$val)
				{
					$val['active'] = (strtotime($val['ts_end']) > time())?true:false;
					$result[] = $val;
				}

				return $result;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	// Подписки пользователя по сервису
	public function byUserService($user, $service_id, $type = 0)
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND service = ? AND type = ? ORDER BY id DESC';
			if($res = $this->db->query($sql, [intval($user), intval($service_id), $type])->row_array())
			{
				$res['active'] = (strtotime($res['ts_end']) > time())?true:false;

				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	// Подписки пользователя по типу
	public function byUserType($user, $type = 0)
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABLE.' WHERE user = ? AND type = ? ORDER BY id DESC';
			if($res = $this->db->query($sql, [intval($user), intval($type)])->result_array())
			{
				foreach($res as &$val)
				{
					$val['ts_end_mark'] = strtotime($val['ts_end']);
					$val['active'] = ($val['ts_end_mark'] > time())?true:false;
				}
				
				return $res;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
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
				$item['amount'] = floatval($item['amount']);
				$item['price_month'] = floatval($item['price_month']);
				$ts_end_mark = strtotime($item['ts_end']);

				switch($item['type'])
				{
					case '0':
						// Продление на месяц
						if($item['amount'] > 0)
						{
							if(($service = $this->CoursesGroupsModel->getByID($item['service'])) == false)
							{
								throw new Exception('Group not found', 1);
							}

							$price = ($item['amount'] > $item['price_month'])?($item['amount'] - $item['price_month']):$item['price_month'];

							$ts_end = new DateTime($item['ts_end']);
							$ts_end->modify('next month');
							$ts_end_format = $ts_end->format('Y-m-d 00:00:00');

							$item['ts_end'] = (strtotime($ts_end_format) > strtotime($service['ts_end']))?$service['ts_end']:$ts_end_format;
							$item['amount'] -= $price;
						}
						// Продление на год
						elseif($ts_end_mark < time())
						{
							$price = $item['price_month'];
							$ts_end = new DateTime($item['ts_end']);
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
				{
					throw new Exception('Insufficient funds', 1);
				}

				if($this->update($id, $item) == false)
				{
					throw new Exception('Update error', 1);
				}

				if($price > 0)
				{
					$tx = [
						'user' => $item['user'],
						'type' => '1',
						'amount' => $price,
						'description' => $item['description'],
						'service' => 'group',
						'service_id' => $item['service']
					];

					if($this->TransactionsModel->add($tx))
					{
						$this->Auth->updateBalance();
					}
					else
					{
						throw new Exception('Pay error', 1);
					}
				}

				if($this->db->trans_status() === FALSE)
				{
					throw new Exception('Renew error', 1);
				}

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

	public function getGroupUsers($group)
	{
		if($group > 0)
		{
			$sql = 'SELECT 
						u.id, u.email 
					FROM 
						'.self::TABLE.' as s 
					LEFT JOIN 
						'.self::TABLE_USERS.' as u ON(s.user = u.id) 
					WHERE 
						s.service = ? AND type = 0 
					GROUP BY 
						s.user 
					ORDER BY 
						s.user ASC';

			if($res = $this->db->query($sql, [intval($group)])->result_array())
			{
				return $res;
			}
		}

		return false;
	}

	private function _checkFields(&$data = [])
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('subscription') == FALSE)
		{
			throw new Exception($this->form_validation->error_string(), 1);
		}

		foreach($data as $key => $val)
		{
			if(in_array($key, self::TABLE_FIELDS) == false)
			{
				unset($data[$key]);
			}
		}
		
		return true;
	}
}