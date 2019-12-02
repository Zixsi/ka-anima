<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class APP_Controller extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(isset($this->db) && isset($this->db->conn_id))
			$this->db->conn_id->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
}
