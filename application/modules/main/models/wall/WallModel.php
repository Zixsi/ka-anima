<?php

class WallModel extends APP_Model
{
	const TABLE = 'wall';
	const TABLE_USERS = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	// добавить
	public function add(array $data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();

		return false;
	}

	// удаление 
	public function remove($id)
	{
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, ['del' => 1]))
			return true;
			
		return false;
	}

	// список сообщений для группы
	public function list(int $id, int $limit = 20, $offset = 0)
	{
		$bind = [$id];

		$sql = 'SELECT 
					w.*, w2.cnt as child_cnt, CONCAT_WS(\' \', u.name, u.lastname) as full_name, 
					u.email as email, u.role as role 
				FROM 
					'.self::TABLE.' as w 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = w.user) 
				LEFT JOIN 
					(SELECT target, count(*) as cnt FROM '.self::TABLE.' WHERE target > 0 AND del = 0 GROUP BY target) as w2 ON(w2.target = w.id) 
				WHERE 
					w.group_id = ? AND 
					w.target = 0 AND 
					del = 0 
				ORDER BY 
					w.id DESC 
				LIMIT '.$limit.' OFFSET '.$offset;

		if($res = $this->db->query($sql, $bind))
		{
			if($res = $res->result_array())
				$this->prepareList($res);

			return $res;
		}

		return [];
	}

	public function child(int $id)
	{
		$bind = [$id];

		$sql = 'SELECT 
					w.*, CONCAT_WS(\' \', u.name, u.lastname) as full_name, u.email as email, u.role as role  
				FROM 
					'.self::TABLE.' as w 
				LEFT JOIN 
					'.self::TABLE_USERS.' as u ON(u.id = w.user) 
				WHERE 
					w.target = ? AND w.del = 0 
				ORDER BY 
					w.id ASC';

		if($res = $this->db->query($sql, $bind))
		{
			if($res = $res->result_array())
				$this->prepareList($res);

			return $res;
		}

		return [];
	}

	private function prepareList(&$data = [])
	{
		if(count($data))
		{
			$user_img = [];
			foreach($data as &$val)
			{
				if(!isset($user_img[$val['user']]))
					$user_img[$val['user']] = $this->imggen->createIconSrc(['seed' => md5('user'.$val['user'])]);

				$val['text'] = htmlspecialchars($val['text']);
				$val['timestamp'] = strtotime($val['ts']);
				$val['img'] = $user_img[$val['user']];
				$val['role_name'] = UserModel::ROLES_NAME[$val['role']];
			}
		}
	}
}