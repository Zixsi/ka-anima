<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];
		$data['error'] = null;

		try
		{
			$input = $this->input->get(null, true);
			$input['user'] = $this->Auth->userID();
			$data['item'] = $this->PayHelper->parse($input);

			if(cr_valid_key())
			{
				$this->PayHelper->pay($data['item']);
				// $res = $this->PayHelper->pay($data['item']);
				// if($res === true)
				// 	header('Location: /groups/');
				// elseif($res)
				// 	header('Location: /?hash='.$res);
			}

			
		}
		catch(Exception $e)
		{
			$data['error'] = $e->getMessage();
		}

		$data['csrf'] = cr_get_key();

		$this->load->lview('pay/index', $data);
	}

	// public function index()
	// {
	// 	$data = [];
	// 	$data['error'] = null;
	// 	$input = $this->input->get(null, true);

	// 	try
	// 	{
	// 		$data['item'] = $this->PayHelper->parse($input);
	// 	}
	// 	catch(Exception $e)
	// 	{
	// 		show_404();
	// 	}

	// 	$data['item']['user'] = $this->Auth->userID();

	// 	if(cr_valid_key())
	// 	{
	// 		try
	// 		{
	// 			$res = $this->PayHelper->pay($data['item']);
	// 			if($res === true)
	// 				header('Location: /groups/');
	// 			elseif($res)
	// 				header('Location: /?hash='.$res);
	// 		}
	// 		catch(Exception $e)
	// 		{
	// 			$data['error'] = $e->getMessage();
	// 		}
	// 	}

	// 	$data['csrf'] = cr_get_key();

	// 	$this->load->lview('pay/index', $data);
	// }
}
