<?php

class Db_tools
{
	private $ci;
	private $db;
	private $bind_marker;

	public function __construct()
	{
		$this->ci = & get_instance();
		$this->db = $this->ci->db;
		$this->bind_marker = $this->db->bind_marker;
	}

	public function prepareBinds(&$sql, &$binds)
	{
		$bindOrder = null;
		
		$pattern = "/[^']:[A-Za-z0-9_]+[^']/";
		$preg = preg_match_all($pattern, $sql, $matches, PREG_OFFSET_CAPTURE);
		if($preg !== 0 and $preg !== false)
		{
			$bindList = null;
			
			foreach($matches[0] as $key => $val)
			{
				$bindOrder[$key] = trim($val[0]);
			}

			foreach($bindOrder as $field)
			{
				$sql = str_replace($field, $this->bind_marker, $sql);
				$bindList[] = $binds[$field];
			}

			$binds = $bindList;
		}
	}
}