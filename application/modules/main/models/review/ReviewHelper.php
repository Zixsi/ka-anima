<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewHelper extends APP_Model
{
	private const TABLE = 'review';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data = [])
	{
		try
		{
			$this->db->trans_begin();

			$data['group'] = (int) ($data['group'] ?? 0);

			if(($group = $this->GroupsModel->getByID($data['group'])) === false)
				throw new Exception('группа не найдена');
			$data['group_id'] = $group['id'];

			$data['lecture'] = (int) ($data['lecture'] ?? 0);
			if(($lecture = $this->LecturesModel->getByID($data['lecture'])) === false)
				throw new Exception('лекция не найдена');
			$data['lecture_id'] = $lecture['id'];

			$data['user'] = (int) ($data['user'] ?? 0);
			if(($user = $this->UserModel->getByID($data['user'])) === false)
				throw new Exception('пользователь не найден');

			$data['video_url'] = ($data['video_url'] ?? '');
			if(empty($data['video_url']))
				throw new Exception('пустой url видео');
			elseif(!filter_var($data['video_url'], FILTER_VALIDATE_URL))
				throw new Exception('невалидный url видео');

			// $data['file_url'] = ($data['file_url'] ?? '');
			// if(empty($data['file_url']))
			// 	throw new Exception('пустой url файла');
			// elseif(!filter_var($data['file_url'], FILTER_VALIDATE_URL))
			// 	throw new Exception('невалидный url файла');

			if(($id = $this->ReviewModel->add($data)) === false)
				throw new Exception('ошибка добавления');

			if($this->VideoModel->prepareAndSet($id, 'review', $data['video_url']))
				throw new Exception('ошибка добавления');

			$actionParams = [
				'group_code' => $group['code'], 
				'lecture_name' => $lecture['name'], 
				'user_id' => $user['id'],
				'user_name' => $user['full_name']
			];
			Action::send(Action::REVIEW_ADD, [$actionParams]);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('ошибка добавления');
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

	public function edit($id, $data = [])
	{
		try
		{
			$this->db->trans_begin();
			$params = [];

			if(($item = $this->ReviewModel->getByID($id)) === false)
				throw new Exception('элемент не найден');

			if(isset($data['video_url']))
			{
				$data['video_url'] = ($data['video_url'] ?? '');
				if(empty($data['video_url']))
					throw new Exception('пустой url видео');
				elseif(!filter_var($data['video_url'], FILTER_VALIDATE_URL))
					throw new Exception('невалидный url видео');

				$params['video_url'] = $data['video_url'];
			}

			if(isset($data['file_url']))
			{
				// $data['file_url'] = ($data['file_url'] ?? '');
				// if(empty($data['file_url']))
				// 	throw new Exception('пустой url файла');
				if(empty($data['file_url']) === false && filter_var($data['file_url'], FILTER_VALIDATE_URL) === false)
					throw new Exception('невалидный url файла');

				$params['file_url'] = $data['file_url'];
			}

			if(isset($data['text']))
				$params['text'] = $data['text'];

			if(($id = $this->ReviewModel->update($id, $params)) === false)
				throw new Exception('ошибка обновления');

			if($item['video_url'] !== $params['video_url'])
			{
				if($this->VideoModel->prepareAndSet($id, 'review', $params['video_url']))
					throw new Exception('ошибка обновления');
			}

			// $actionParams = [
			// 	'group_code' => $group['code'], 
			// 	'lecture_name' => $lecture['name'], 
			// 	'user_id' => $user['id'],
			// 	'user_name' => $user['full_name']
			// ];
			// Action::send(Action::REVIEW_ADD, [$actionParams]);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('ошибка обновления');
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

	public function delete($id)
	{
		try
		{
			$this->db->trans_begin();

			if(($item = $this->ReviewModel->getByID($id)) === false)
				throw new Exception('элемент не найден');

			$user = $this->UserModel->getByID($item['user']);
			$group = $this->GroupsModel->getByID($item['group_id']);

			if($this->ReviewModel->delete($id) === false)
				throw new Exception('ошибка удаления 1 ');

			if($this->VideoModel->remove($id, 'review') === false)
				throw new Exception('ошибка удаления 2');

			$actionParams = [
				'group_code' => $group['code'], 
				'lecture_name' => $item['lecture_name'], 
				'user_id' => $user['id'],
				'user_name' => $user['full_name']
			];
			Action::send(Action::REVIEW_DELETE, [$actionParams]);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('ошибка удаления 3');
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