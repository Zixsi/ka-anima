<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorkshopModel extends APP_Model
{
	const TABLE = 'workshop';

	private $types = [
		'webinar' => 'Вебинар',
		'collection' => 'Коллекция'
	];

	public function add($data)
	{
		if($this->db->insert(self::TABLE, $data))
			return $this->db->insert_id();
	
		return false;
	}

	public function update($id, $data = [])
	{
		$result = false;
		unset($data['id']);
		$this->db->where('id', $id);
		if($this->db->update(self::TABLE, $data))
			$result = true;

		return $result;
	}

	public function getItem($id)
	{
		return $this->getByField('id', $id);
	}

	public function getByField($key, $value)
	{
		return $this->db
		->from(self::TABLE)
		->where($key, $value)
		->get()->row_array();
	}

	public function getList($filter = [])
	{
		$result = [];
		$filterParams = $this->parseListFilter($filter);
		$binds = $filterParams['binds'];

		$sql = 'SELECT * FROM '.self::TABLE.' ';
		if(count($filterParams['where']))
			$sql .= ' WHERE '.implode(' AND ', $filterParams['where']);

		if($res = $this->query($sql, $binds)->result_array())
		{
			$result = $res;
		}		

		return $result;
	}
	
	public function validate($params)
	{
		$this->load->library('form_validation');

		$this->form_validation->reset_validation();
		$this->form_validation->set_data($params);
		
		$this->form_validation->set_rules('title', 'Название', 'required|min_length[3]');
		$this->form_validation->set_rules('video', 'Ссылка на трейлер / вебинар', ['required', ['video', function($value){
			if(isValidYoutubeVideoUrl($value) === false)
				$this->form_validation->set_message('video', 'Неверный источник видео');

			return true;
		}]]);

		switch(($params['type'] ?? null))
		{
			case 'webinar':
				$this->form_validation->set_rules('date', 'Дата начала', 'required');
				break;
			
			case 'collection':
			default:
				// empty
				break;
		}

		if($this->form_validation->run() === false)
			throw new Exception($this->form_validation->error_string(), 1);
	}

	public function getTypes()
	{
		return $this->types;
	}

	public function parseListFilter($params = [])
	{
		$result = [
			'binds' => [],
			'where' => [],
			'offset' => 0,
			'limit' => 9999
		];

		if(isset($params['id']) && empty($params['id']) === false)
		{
			if(is_array($params['id']))
				$result['where'][] = 'id IN('.implode(',', $params['id']).')';
			else
			{
				$result['binds'][':id'] = $params['id'];
				$result['where'][] = 'id = :id';
			}
		}

		if(isset($params['ignore']) && empty($params['ignore']) === false)
		{
			if(is_array($params['ignore']))
				$result['where'][] = 'id NOT IN('.implode(',', $params['ignore']).')';
			else
			{
				$result['binds'][':ignore'] = $params['ignore'];
				$result['where'][] = 'id != :ignore';
			}
		}

		if(isset($params['status']))
		{
			$result['binds'][':status'] = ($params['status'])?1:0;
			$result['where'][] = 'status = :status';
		}

		return $result;
	}
}