<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends APP_Controller
{
    public function index()
    {
        $data = [];
        $userId = $this->Auth->getUserId();
        $data['items'] = $this->SubscriptionModel->getByUserList($userId);
        $this->SubscriptionHelper->prepareList($data['items']);
        $data['types'] = $this->SubscriptionHelper->getObjectTypes();

        $this->notifications->changeTypeStatus('subscription');

        $this->load->lview('subscription/index', $data);
    }
}
