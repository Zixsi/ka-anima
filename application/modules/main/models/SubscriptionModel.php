<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionModel extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['main/CoursesSubscriptionModel', 'main/CoursesGroupsModel']);
	}

	public function Group($user, $group, $price_period = null)
	{
		try
		{	
			if(intval($user) == 0)
			{
				throw new Exception('User not found', 1);
			}

			if(($item = $this->CoursesGroupsModel->GetByID($group)) == false)
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

			if($this->CoursesSubscriptionModel->CheckSubscibe($user, $group))
			{
				throw new Exception('Already subscribed', 1);
			}

			$data = [
				'user' => $user,
				'course_group' => $group,
				'price_month' => $item['price_month'],
				'price_full' => $item['price_full']
			];

			$this->db->trans_begin();

			if(($this->CoursesSubscriptionModel->Add($data)) == false)
			{
				throw new Exception($this->CoursesSubscriptionModel->LAST_ERROR, 1);
			}

			$fields = [
				'user' => $user,
				'type' => 'OUT',
				'amount' => $item['price_'.$price_period],
				'description' => 'Subscibe '.$item['code'],
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

	public function CoursesList($user)
	{
		try
		{
			if(intval($user) == 0)
			{
				throw new Exception('User not found', 1);
			}

			$sql = 'SELECT c.id, c.name, cg.ts, cg.id as course_group FROM courses_subscription as cs LEFT JOIN courses_groups as cg ON(cs.course_group = cg.id) LEFT JOIN courses as c ON(cg.course = c.id) WHERE cs.user = ? ORDER BY cg.ts DESC';
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
}