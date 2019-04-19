<?php

class WallHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// добавить
	public function add($data = [])
	{
		try
		{
			$this->db->trans_begin();

			$data['group'] = (int) ($data['group'] ?? 0);
			if($this->GroupsModel->getByID($data['group']) === false)
				throw new Exception('ошибка добавления');

			$data['user'] = (int) ($data['user'] ?? 0);
			if($data['user'] === 0)
				throw new Exception('ошибка добавления');
			$data['target'] = (int) ($data['target'] ?? 0);
			
			$data['text'] = ($data['text'] ?? '');
			if(mb_strlen(trim($data['text'])) == 0)
				throw new Exception('текст сообщения пустой');

			$params = [
				'group_id' => $data['group'],
				'user' => $data['user'],
				'text' => $data['text'],
				'target' => $data['target']
			];
			$this->WallModel->add($params);

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

	// список
	public function list($data = [])
	{
		try
		{
			$data['group'] = (int) ($data['group'] ?? 0);
			if($this->GroupsModel->getByID($data['group']) === false)
				throw new Exception('ошибка получения списка');

			$limit = 20;
			$offset = (int) ($data['page'] ?? 0) * $limit;

			return $this->WallModel->list($data['group'], $limit, $offset);
		}
		catch(Exception $e)
		{
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}

	public function child($data = [])
	{
		try
		{
			$data['id'] = (int) abs($data['id'] ?? 0);
			if($data['id'] == 0)
				throw new Exception('не выбран родительский элемент');


			return $this->WallModel->child($data['id']);
		}
		catch(Exception $e)
		{
			$this->setLastError($e->getMessage(), $e->getCode());
		}

		return false;
	}
}