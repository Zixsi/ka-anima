<?php

class WorkshopEntity
{
	private $id = 0;
	private $code;
	private $type;
	private $title;
	private $description;
	private $video;
	private $video_list = [];
	private $video_description;
	private $img;
	private $teacher = 0;
	private $date;
	private $price = 0;
	private $status = 0;

	public function __construct(array $params = [])
	{
		$this->parseParams($params);
	}

	public function parseParams(array $params = [])
	{
		foreach($params as $key => $value)
		{
			if(property_exists($this, $key) === false)
				continue;

			if($key === 'video_list')
				$this->prepareVideoList($value);

			$this->$key = $value;
		}
	}

	public function setImg($value)
	{
		$this->img = $value;
	}

	public function makeCode()
	{
		$this->code = md5(microtime(true));
	}

	public function toArray()
	{
		$params = get_object_vars($this);
		return $params;
	}

	public function toDbArray()
	{
		$params = $this->toArray();
		$params['video_list'] = json_encode($params['video_list']);

		return $params;
	}

	private function prepareVideoList(&$value)
	{
		if(is_array($value) === false)
		{
			$value = json_decode($value);
			if(is_array($value) === false)
				$value = [];
		}

		foreach($value as $key => &$row)
		{
			$row['name'] = trim($row['name'] ?? '');
			$row['url'] = trim($row['url'] ?? '');

			if(empty($row['name']) && empty($row['url']))
				unset($value[$key]);
		}

		return array_values($value);
	}
}