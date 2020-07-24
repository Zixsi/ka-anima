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
            'user' => $this->Auth->userID()
        ];

        $order = $this->PayHelper->parse($orderData);
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
            'user' => $this->Auth->userID()
        ];

        $order = $this->PayHelper->parse($orderData);
        $orderArray = $order->toArray();                
        $orderArray['user'] = $this->Auth->userID();
        $this->PayHelper->pay($orderArray);
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
}
