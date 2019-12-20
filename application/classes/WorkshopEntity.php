<?php

class WorkshopEntity
{
	private $id = 0;
	private $code;
	private $type;
	private $title;
	private $description;
	private $video;
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

	public function getId()
	{
		return (int) $this->id;
	}

	public function toArray()
	{
		$params = get_object_vars($this);
		return $params;
	}

	public function toDbArray()
	{
		$params = $this->toArray();

		return $params;
	}
}