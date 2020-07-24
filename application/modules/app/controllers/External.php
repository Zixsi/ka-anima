<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class External extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->layout = 'auth';
    }

    public function index()
    {
        // empty
    }

    public function pay()
    {
        $this->load->library('session');
        $isAuth = $this->Auth->check();
        
        if($isAuth === false) {
            setRequestBackUri();
        }
        
        $data = [
            'type' => ($_POST['type'] ?? $_GET['type'] ?? 'standart'),
            'period' => ($_POST['period'] ?? $_GET['period'] ?? 'full'),
            'email' => $this->input->post('email', true),
            'isAuth' => $isAuth,
            'target' => ($_GET['target'] ?? 'course'),
            'promocode' => ($_POST['promocode'] ?? $_GET['promocode'] ?? null),
            'error' => null
        ];
        
        try {
            $data['item'] = $this->getItem();
            
//            debug($data); die();
            if ($this->input->method() === 'post') {
                if ($isAuth === false) {
                    $userPassword = random_string('alnum', 8);
                    $newUser = [
                        'email' => $data['email'],
                        'name' => 'Name',
                        'lastname' => 'Lastname',
                        'password' => $userPassword,
                        're_password' => $userPassword,
                        'agree' => true,
                        'active' => 1
                    ];
                    $this->Auth->register($newUser);
                    $isAuth = true;
                    $data['isAuth'] = true;
                }
                
                clearRequestBackUri();
                switch ($data['target']) {
                     case 'workshop':
                        $this->payWorkshop($data);
                        break;
                    default:
                        $this->payCourse($data);
                        break;
                }
            }
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }

        $this->load->lview('external/pay', $data);
    }
    
    private function payCourse($data = [])
    {
        $group = ($data['type'] === 'vip')?date('d.m.Y', next_monday_ts()):($data['item']['next_offer']['ts_formated'] ?? null);
        $orderData = [
            'course' => $data['item']['code'],
            'type' => $data['type'],
            'period' => $data['period'],
            'group' => $group,
            'action' => 'new',
            'promocode' => $data['promocode'],
            'user' => $this->Auth->userID()
        ];

        $order = $this->PayHelper->parse($orderData);
        $this->setPromocode($order, $data['promocode']);
        $orderArray = $order->toArray();                
        $orderArray['user'] = $this->Auth->userID();
        $this->PayHelper->pay($orderArray);
    }
    
    private function payWorkshop($data = [])
    {
        $orderData = [
            'code' => $data['item']['code'],
            'target' => 'workshop',
            'action' => 'new',
            'promocode' => $data['promocode'],
            'user' => $this->Auth->userID()
        ];

        $order = $this->PayHelper->parse($orderData);
        $this->setPromocode($order, $data['promocode']);
        $orderArray = $order->toArray();                
        $orderArray['user'] = $this->Auth->userID();
        $this->PayHelper->pay($orderArray);
        
        header('Location: ' . $this->config->item('base_url') . PAY_RETURN_URL);
    }
    
    private function getItem()
    {
        $item = null;
        $code = $this->input->get('code');
        
        switch ($this->input->get('target')) {
            case 'workshop':
                $item = $this->WorkshopModel->getByField('code', $code);
                if ($item) {
                    $item['name'] = $item['title'];
                }
                break;
            default:
                $item = $this->CoursesHelper->getCourseForExternalPay($code);
                break;
        }
        
        if (empty($item)) {
            throw new Exception('неверный код', 1);
        }
        
        return $item;
    }
    
    private function setPromocode(&$order, $promocode)
    {
        if (empty($promocode)) {
            return;
        }
        
        $promocode_item = $this->PromocodeModel->check($promocode);
        if ($promocode_item['target_type'] !== '' && $promocode_item['target_type'] != $order->getObjectType()) {
            throw new Exception("Неподходящий промокод", 1);
        }

        if ((int) $promocode_item['count'] > 0) {
            if ($this->PromocodeModel->getCountUsed($promocode_item['code']) >= (int) $promocode_item['count']) {
                throw new Exception("Достигнут лимит использования данного промокода", 1);
            }
        }

        if ($promocode_item['target_type'] !== '' && (int) $promocode_item['target_id'] > 0) {
            if ($promocode_item['target_type'] === PayData::OBJ_TYPE_COURSE &&
                (int) $order->getCourseId() !== (int) $promocode_item['target_id']
            ) {
                throw new Exception("Неподходящий промокод", 1);
            } elseif ($promocode_item['target_type'] === PayData::OBJ_TYPE_WORKSHOP &&
                (int) $order->getObjectId() !== (int) $promocode_item['target_id']
            ) {
                throw new Exception("Неподходящий промокод", 1);
            }
        }

        if (empty($promocode_item['subscr_type']) === false &&
            $promocode_item['target_type'] === $order->getObjectType() &&
            $order->getObjectType() ===  PayData::OBJ_TYPE_COURSE &&
            $order->getSubscrType() !== $promocode_item['subscr_type']
        ) {
            throw new Exception("Неподходящий промокод", 1);
        }

        $order->setPromocode($promocode_item);
        $order->applyPromocode();
        $order->calcPrice();
    }
}
