<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LecturesHelper extends APP_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remove($id)
	{
		try
		{
			$this->db->trans_begin();

			if(($item = $this->LecturesModel->getByID((int) $id)) === false)
				throw new Exception('лекция не найдена');

			// удаление связанных с лекцией записей
			$this->VideoModel->remove($item['id']);
			$this->LecturesGroupModel->removeByLecture($item['id']);

			// удаление самой лекции
			$this->LecturesModel->remove($item['id']);

			if($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				throw new Exception('Ошибка удаления');
			}

			$this->db->trans_commit();
		}
		catch(Exception $e)
		{
			$this->db->trans_rollback();
			throw new Exception($e->getMessage(), $e->getCode());
		}

		return true;
	}
}