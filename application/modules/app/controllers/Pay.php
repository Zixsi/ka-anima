<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pay extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->Auth->isActive()) {
            header('Location: /');
        }
    }

    public function index()
    {
        if ($this->input->get('order')) {
            $this->processingOrder();
        } else {
            $this->createOrder();
        }
    }

    private function processingOrder()
    {
        $data = [];
        $data['error'] = null;
        $data['promocode'] = null;

        try {
            $order_id = $this->input->get('order');
            $order = $this->session->userdata('ORDER_' . $order_id);
            if (empty($order)) {
                throw new Exception('Отсутствуют данные о заказе', 1);
            }
            $data['item'] = $order->toArray();

            if ($order->isNew()) {
                if ($promocode = $this->input->post('promocode')) {
                    if ($this->input->post('del')) {
                        $promocode_item = null;
                    } else {
                        $promocode_item = $this->PromocodeModel->check($promocode);
                        if ($promocode_item['target_type'] !== '' && $promocode_item['target_type'] != $order->getObjectType()) {
                            throw new Exception("Неподходящий промокод", 1);
                        }

                        if ($promocode_item['target_type'] !== '' && (int) $promocode_item['target_id'] > 0) {
                            if ($promocode_item['target_type'] === PayData::OBJ_TYPE_COURSE && (int) $data['course'] !== (int) $promocode_item['target_id']) {
                                throw new Exception("Неподходящий промокод", 1);
                            } elseif ($promocode_item['target_type'] === PayData::OBJ_TYPE_WORKSHOP && (int) $order->getObjectId() !== (int) $promocode_item['target_id']) {
                                throw new Exception("Неподходящий промокод", 1);
                            }
                        }
                    }

                    $order->setPromocode($promocode_item);
                    $this->session->set_userdata('ORDER_' . $order_id, $order);
                }
            }

            $order->applyPromocode();
            $order->calcPrice();
            $data['item'] = $order->toArray();
            $data['promocode'] = $order->getPromocode();

            if (cr_valid_key()) {
                $data['item']['user'] = $this->Auth->userID();
                $this->PayHelper->pay($data['item']);
            }
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }

        $data['csrf'] = cr_get_key();
        $this->load->lview('pay/index', $data);
    }

    public function createOrder()
    {
        $data = [];
        $data['error'] = null;

        try {
            // очистить предыдущие заказы
            $session_keys = array_keys($_SESSION);
            foreach ($session_keys as $key) {
                if (strpos($key, 'ORDER_') !== false) {
                    unset($_SESSION[$key]);
                }
            }

            $user_id = $this->Auth->userID();
            $input = $this->input->get(null, true);
            $input['user'] = $user_id;
            $order = $this->PayHelper->parse($input);
            $order_id = md5($user_id . microtime(true));

            $this->session->set_userdata('ORDER_' . $order_id, $order);
            header('Location: /pay/?order=' . $order_id);
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        }
       
        $this->load->lview('pay/create_order', $data);
    }
}
