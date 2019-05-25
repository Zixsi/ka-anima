<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActionsHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{	
			$data['action'] = ($data['action'] ?? '');

			if(empty($data['action']) || !array_key_exists($data['action'], UserActionsModel::ACTIONS))
				throw new Exception('Действие не определено', 1);

			$data['user'] = (int) ($data['user'] ?? 0);
			if($data['user'] === 0)
				throw new Exception('Неуказан пользователь', 1);

			$data['data'] = is_array($data['data'])?$data['data']:[];

			$params = [
				'user' => $data['user'],
				'date_ts' => time(),
				'action' => $data['action'],
				'data' => json_encode($data['data']),
				'hash' => $this->UserActionsModel->hash($data['user'], $data['action'])
			];

			return $this->UserActionsModel->add($params);
		}
		catch(Exception $e)
		{
			$this->setLastException($e);
		}

		return false;
	}

	public function prepareDescription($item)
	{
		$data = json_decode($item['data'], true);
		$result = UserActionsModel::ACTIONS[$item['action']]['description'];
		switch($item['action'])
		{
			case UserActionsModel::ACTION_COURSE_SUBSCR:
			case UserActionsModel::ACTION_COURSE_RENEW_SUBSCR:
				$result .= ' <a href="/admin/groups/'.$data['group_code'].'/" target="_blank">'.$data['group_code'].'</a>';
			break;
			case UserActionsModel::ACTION_PAY_ADD_FOUNDS:
				$result .= ' на сумму '.number_format((float) $data['value'], 2, '.', ' ').' руб. с помощью '.$data['pay_system'];
			break;
			case UserActionsModel::ACTION_HOMEWORK_FILE_ADD:
				$result .= ' <a href="/admin/groups/'.$data['group_code'].'/?user='.$item['user'].'" target="_blank">'.$data['group_code'].'</a> ('.$data['lecture_name'].') - '.$data['file_name'];
			break;
			case UserActionsModel::ACTION_HOMEWORK_FILE_DOWNLOAD:
			case UserActionsModel::ACTION_REVIEW_ADD:
			case UserActionsModel::ACTION_REVIEW_DEL:
				$result .= ' <a href="/admin/groups/'.$data['group_code'].'/?user='.$data['user_id'].'" target="_blank">'.$data['group_code'].'</a> ('.$data['lecture_name'].') -  <a href="/admin/users/user/'.$data['user_id'].'/" target="_blank">'.$data['user_name'].'</a>';
			break;
			case UserActionsModel::ACTION_STREAM_ADD:
			case UserActionsModel::ACTION_STREAM_EDIT:
				$result .= ' <a href="/admin/streams/item/'.$data['item_id'].'/" target="_blank">'.$data['item_name'].'</a>';
			break;
			default:
				// empty
			break;
		}

		// debug($item);
		return $result;
	}
}