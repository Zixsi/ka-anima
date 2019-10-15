<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupportController extends APP_Controller
{
	public function index()
	{
		$data = [];
		$data['items'] = $this->support->getTickets();
		$this->support->prepareAdminTickets($data['items']);
		$data['items'] = $this->support->splitByStatus($data['items']);
		$data['statusList'] = $this->support->getStatusList();

		// debug($data['items']); die();
		$this->load->lview('support/index', $data);
	}

	public function ticket($code)
	{
		$data = [];

		$data['error'] = null;
		$data['post'] = $this->input->post(null, true);
		if(($data['item'] = $this->SupportModel->getByCode($code)) === null)
			show_404();

		$this->support->prepareTicket($data['item']);
		$data['statusList'] = $this->support->getStatusList();

		if(cr_valid_key())
		{
			try
			{
				switch($data['post']['type'])
				{
					case 'update':
						$this->SupportModel->update($data['item']['id'], ['status' => $data['post']['status']]);
						redirect('/admin/support/');
						break;
					
					default:
						$data['post']['target'] = $data['item']['id'];
						$this->support->addTicketMessage($data['post']);
						break;
				}
				
				header("Refresh:0");
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['items'] = $this->support->getTicketMessage($data['item']['id']);
		$data['csrf'] = cr_get_key();
		// debug($data['items']); die();

		$this->load->lview('support/ticket', $data);
	}
}
