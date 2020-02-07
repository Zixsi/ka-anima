<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupportController extends APP_Controller
{
    public function index()
    {
        $data = [];
        $data['error'] = null;
        $data['post'] = $this->input->post(null, true);
    
        if (cr_valid_key()) {
            try {
                $this->support->addTicket($data['post']);
                header("Refresh:0");
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }

        $data['items'] = $this->support->getUserTickets();
        $data['csrf'] = cr_get_key();
        $this->notifications->changeTypeStatus('support');

        // debug($data['items']); die();
        $this->load->lview('support/index', $data);
    }

    public function ticket($code)
    {
        $data = [];

        $data['error'] = null;
        $data['post'] = $this->input->post(null, true);
        if (($data['item'] = $this->SupportModel->getByCode($code)) === null) {
            show_404();
        }

        $data['items'] = $this->support->getTicketMessage($data['item']['id']);
        $data['isEnabledMessage'] = (count($data['items']) && (int) end($data['items'])['user'] === 0 && $data['item']['status'] === SupportModel::PENDING)?true:false;
    
        if ($data['isEnabledMessage'] && cr_valid_key()) {
            try {
                $data['post']['target'] = $data['item']['id'];
                $this->support->addTicketMessage($data['post']);
                header("Refresh:0");
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }

        $data['csrf'] = cr_get_key();
        // debug($data['items']); die();

        $this->load->lview('support/ticket', $data);
    }
}
