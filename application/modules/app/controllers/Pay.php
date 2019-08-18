<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->Auth->isActive())
			header('Location: /');
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
			$data['item']['user'] = $input['user']; 

			if(cr_valid_key())
				$this->PayHelper->pay($data['item']);
		}
		catch(Exception $e)
		{
			$data['error'] = $e->getMessage();
		}

		$data['csrf'] = cr_get_key();

		$this->load->lview('pay/index', $data);
	}
}
