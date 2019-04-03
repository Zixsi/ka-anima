<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'main/CoursesModel',
			'main/SubscriptionModel',
			'main/GroupsModel',
			'main/groups/GroupsHelper'
		]);
	}

	public function group($data = [])
	{
		try
		{	
			$this->db->trans_begin();

			if((int) $data['user'] === 0)
				throw new Exception('Неверные параметры', 1);

			if(empty(($data['type'] ?? '')) || !array_key_exists($data['type'], SubscriptionModel::TYPES))
				throw new Exception('Неверные параметры', 1);

			$group_id = ($data['group'] ?? 0);
			$course_id = ($data['course'] ?? 0);

			if(($course_item = $this->CoursesModel->getByID($course_id)) === false)
				throw new Exception('Неверные параметры', 1);

			// если неверный тип подписки 
			if(empty(($data['type'] ?? '')) || !array_key_exists($data['type'], SubscriptionModel::TYPES) 
				||  ((int) $course_item['only_standart'] === 1 && $data['type'] !== 'standart'))
				throw new Exception('Неверные параметры', 1);

			// если VIP
			if($data['type'] == 'vip')
			{
				// проверяем есть ли подходящая группа
				if($res = $this->GroupsModel->getNearVip($course_id))
				{
					$group_id = $res['id'];
				}
				else
				{
					// создаем отдельную группу
					$date = new DateTime('now');
					if(intval($date->format('N')) !== 1)
						$date->modify('next monday');
					if(($group_id = $this->GroupsHelper->addSimple($course_id, 'vip',  $date)) === false)
							throw new Exception('Ошибка подписки', 1);
				}
			}

			if(($item = $this->GroupsModel->getByIdDetail($group_id)) === false)
					throw new Exception('Неверные параметры', 1);

			$item['price'] = json_decode($item['price'], true);

			if(!isset($item['price'][$data['type']][$data['period']]))
				throw new Exception('Неверные параметры', 1);

			$price = $item['price'][$data['type']][$data['period']];
			if((int) $price === 0 && (int) $course_item['only_standart'] !== 1)
				throw new Exception('Ошибка подписки', 1);

			if((int) $this->Auth->balance() < $price)
				throw new Exception('Недостаточно средств на счету', 1);

			// проверяем подписал юзер на эту группу или нет
			if($this->SubscriptionModel->сheck($data['user'], $item['group_id']))
				throw new Exception('Уже подписаны', 1);

			////////////////////////////////////////////////////////////////////////

			// если бесплатный курс, то подписка полностью
			if($price === 0)
				$data['period'] = 'full';

			
			$ts_end = $item['ts_end'];
			$subscr_type = 0; // полная подписка
			$amount = 0; // остаток для оплаты

			// если оплата за месяц
			if($data['period'] == 'month')
			{
				$subscr_type = 1;
				$ts_curr = new DateTime($item['ts']);
				if(intval($ts_curr->format('N')) !== 1)
					$ts_curr->modify('next monday');
				$ts_month = clone $ts_curr;
				$ts_month->modify('+4 weeks'); // 4 недели 

				$ts_end_obj = new DateTime($ts_end);
				$diff = $ts_end_obj->diff($ts_month);
				
				// Если разница между датой окончания и месяцем оплаты меньше или равно 1 недели 
				// то устанавливаем дату окончания курса
				if($ts_month < $ts_end_obj && (int) $diff->days > 7)
				{
					$ts_end = $ts_month->format('Y-m-d 23:59:59');

					// рассчитываем остаток оплаты 
					$amount = ceil($diff->days / 28) * $price;
				}
			}

			$data = [
				'user' => $data['user'],
				'type' => $data['type'],
				'target' => $item['group_id'],
				'target_type' => 'course',
				'description' => $item['name'].' ('.strftime("%B %Y", strtotime($item['ts'])).')',
				'ts_start' => $item['ts'],
				'ts_end' => $ts_end,
				'subscr_type' => $subscr_type,
				'amount' => number_format($amount, 2, '.', ''),
				'data' => json_encode(['price' => $price])
			];

			$this->SubscriptionModel->add($data);

			$fields = [
				'user' => $data['user'],
				'type' => '1',
				'amount' => $price,
				'description' => $data['description'],
				'service' => 'group',
				'service_id' => $item['group_id']
			];

			$this->TransactionsModel->add($fields);
			$this->Auth->updateBalance();
			
			if($this->db->trans_status() === FALSE)
			{
				throw new Exception('Ошибка подписки', 1);
			}

			$this->db->trans_commit();

			return true;
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}
}