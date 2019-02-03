<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends APP_Controller
{
	public function __construct()
	{
		$this->load->library(['ydvideo']);
	}

	// php index.php cli test index
	public function index()
	{
		var_dump('TEST');

		//$res = $this->ydvideo->getVideo('https://yadi.sk/i/5zTqQ--4gPE3FQ');
		//$res = $this->ydvideo->getVideo('https://yadi.sk/i/6Ojacwq_-4DMRA');
		//var_dump($res);
	}
}
