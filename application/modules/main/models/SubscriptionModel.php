<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionModel extends APP_Model
{
	private const TABLE = 'subscription';
	private const TABLE_COURSES = 'courses';
	private const TABLE_COURSES_GROUPS = 'courses_groups';
	private const TABLE_FIELDS = ['user', 'type', 'service', 'description', 'ts_start', 'ts_end', 'price_month', 'price_full'];
	private const TYPES = [
		0, // курсы
		1, // резерв
		2, // резерв
		3, // резерв
		4, // резерв
		5, // резерв
		6, // резерв
		7 // резерв
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesGroupsModel']);
	}

	public function Add($data = [])
	{
		try
		{
			$this->_CheckFields($data);

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

	public function Update($id, $data = [])
	{
		try
		{
			$this->_CheckFields($data);

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

	public function Delete($id)
	{
		return false;
	}

	public function GetByID($id)
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
			elseif($this->Auth->Balance() < $item['price_'.$price_period])
			{
				throw new Exception('Insufficient funds', 1);
			}

			if($this->CheckSubscibe($user, $group))
			{
				throw new Exception('Already subscribed', 1);
			}

			//strtotime()
			$next_month = new DateTime($item['ts']);
			$next_month->modify('next month');
			$ts_next_month = $next_month->format('Y-m-d 00:00:00');

			$ts_end = ($price_period == 'full')?$item['ts_end']:$ts_next_month; // $item['ts']

			$data = [
				'user' => intval($user),
				'type' => 0,
				'service' => intval($group),
				'description' => $item['name'].' ('.date('F Y', strtotime($item['ts'])).')',
				'ts_start' => $item['ts'],
				'ts_end' => $ts_end,
				'price_month' => $item['price_month'],
				'price_full' => $item['price_full']
			];

			$this->db->trans_begin();

			if(($this->Add($data)) == false)
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

			if($this->TransactionsModel->Add($fields))
			{
				 $this->Auth->UpdateBalance();
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

	public function GroupList($user)
	{
		return false;
	}

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
				$result = $res;

				return $result;
			}
		}
		catch(Exception $e)
		{
			$this->LAST_ERROR = $e->getMessage();
		}

		return false;
	}

	private function _CheckFields(&$data = [])
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