<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PayHelper extends APP_Model
{
    private $error_promocode = null;

    public function __construct()
    {
        parent::__construct();
    }
    
//    public function createOrder($input)
//    {
//        // очистить предыдущие заказы
//        $sessionKeys = array_keys($_SESSION);
//        foreach ($sessionKeys as $key) {
//            if (strpos($key, 'ORDER_') !== false) {
//                unset($_SESSION[$key]);
//            }
//        }
//
//        $userId = $this->Auth->userID();
//        $input['user'] = $userId;
//        $order = $this->parse($input);
//        $orderId = md5($userId . microtime(true));
//
//        $this->session->set_userdata('ORDER_' . $orderId, $order);
//        
//        return $orderId;
//    }

    // разбираем входные данные
    public function parse($data)
    {
        $result = null;

        switch (($data['action'] ?? '')) {
            case 'new': // новая подписка
                $result = $this->parseNew($data);
                break;
            case 'renewal': // обновления подписки
                $result = $this->parseRenewal($data);
                break;
            default:
                throw new Exception('неопределенное действие', 1);
            break;
        }

        return $result;
    }

    // создаем транзакцию оплаты
    public function pay($data)
    {
        $tx = [
            'user' => $data['user'],
            'type' => TransactionsModel::TYPE_IN,
            'amount' => $data['price'],
            'description' => $this->makePayDescription($data),
            'data' => json_encode($data),
            'course_id' => (int) ($data['params']['course_id'] ?? 0),
            'group_id' => (int) ($data['params']['group_id'] ?? 0),
            'source' => (($data['object']['type'] === 'workshop')?'workshop':'course'),
            'promocode' => $data['promocode']
        ];

        if (($id = $this->TransactionsHelper->add($tx)) === false) {
            throw new Exception('ошибка создания транзакции', 1);
        }

        $base_url = $this->config->item('base_url');
        $tx_item = $this->TransactionsModel->getByID($id);
        
        // если цена 0.00, запускаем обработку данных транзакции
        if ((int) $tx['amount'] === 0) {
            if ($this->TransactionsHelper->processingData($tx_item['data'])) {
                $this->TransactionsModel->update($tx_item['id'], ['status' => TransactionsModel::STATUS_SUCCESS]);
            }
            
            header('Location: '.$base_url.PAY_RETURN_URL);
        } else {
            try {
                // обработка транзакций системой оплаты
                if (($system = $this->PaySystem->select(PaySystem::YANDEX_KASSA)) === null) {
                    throw new Exception('система оплаты неопледелена', 1);
                }

                $system->setReturnUrl($base_url.PAY_RETURN_URL);
                $system->setBase($data['price'], 'Оплата услуг '.$this->config->item('project_name'));
                $system->setItems($data['list']);

                $user = $this->Auth->user();
                $system->setCustomer($user['full_name'], $user['email']);
                $system->setMeta(['hash' => $tx_item['hash']]);

                $system->run();
                $this->TransactionsModel->update($id, ['pay_system_hash' => $system->getOrderId()]);

                header('Location: '.$system->getPayUrl());
            } catch (Exception $e) {
                $this->TransactionsModel->update($tx_item['id'], ['status' => TransactionsModel::STATUS_ERROR]);
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
    }

    // разобрать параметры обновления
    private function parseRenewal($data)
    {
        if (($item = $this->SubscriptionModel->getByHash(($data['hash'] ?? ''))) === false) {
            throw new Exception('неверный идентификатор подписки', 1);
        }

        $params = [];

        if ($item['target_type'] === 'course') {
            if (($group_item = $this->GroupsModel->getById($item['target'])) !== false) {
                $params['course_id'] = $group_item['course_id'];
            }
            $params['group_id'] = $item['target'];
        }

        $res = $this->SubscriptionHelper->prepareSubscr($item);
        $params['amount'] = $res['amount'];

        $payData = new PayData(PayData::OBJ_TYPE_SUBSCR, $item['id'], $item['type']);
        foreach ($res['items'] as $val) {
            $payData->addRow($val['description'], $val['price']);
        }

        $payData->setPeriod($res['ts_start'], $res['ts_end']);
        $payData->setParams($params);
        $payData->calcPrice();

        return $payData;
    }

    // разобрать параметры новой подписки
    private function parseNew($data)
    {
        $payData = null;
        switch (($data['target'] ?? null)) {
            case 'workshop':
                $payData = $this->parseWorkshop($data);
                break;
            default:
                $payData = $this->parseCurse($data);
                break;
        }

        // return $payData->toArray();
        return $payData;
    }

    private function parseWorkshop($data)
    {
        if (($item = $this->WorkshopModel->getByField('code', ($data['code'] ?? null))) === false) {
            throw new Exception('неверный код', 1);
        }

        $payData = new PayData(PayData::OBJ_TYPE_WORKSHOP, $item['id'], 'standart');
        $payData->setName($item['title']);
        $payData->addRow($item['title'], $item['price']);

        $dateFrom = new DateTime();
        $dateTo = clone $dateFrom;
        $dateTo->modify('+1 year');
        
        $payData->setPeriod($dateFrom->format(DATE_FORMAT_DB_FULL), $dateTo->format(DATE_FORMAT_DB_FULL));
        $payData->setParams([
            'course_id' => $item['id']
        ]);
        $payData->setNew(true);
        $payData->calcPrice();

        return $payData;
    }

    private function parseCurse($data)
    {
        if (($course_item = $this->CoursesModel->getByCode(($data['course'] ?? null))) === false) {
            throw new Exception('неверный код курса', 1);
        }

        if (!array_key_exists(($data['type'] ?? null), $this->SubscriptionModel::TYPES)) {
            throw new Exception('неверный тип подписки', 1);
        }

        if (!in_array(($data['period'] ?? null), $this->SubscriptionModel::PERIOD_SUBSCR)) {
            throw new Exception('неверный период подписки', 1);
        }

        // TODO проверить есть ли у пользователя уже активная подписка VIP

        $data['group'] = $this->GroupsHelper->makeCode($course_item['code'], $data['type'], date('dmy', strtotime($data['group'] ?? 0)));
//        if (($group_item = $this->GroupsModel->getByCode($data['group'])) === false) {
//            throw new Exception('неверный код группы', 1);
//        }

        // debug($group_item); die();

        $data['course'] = $course_item['id'];
//        $data['group'] = $group_item['id'];
        $group = $this->SubscriptionHelper->prepareGroup($data);
        $group['data'] = json_decode($group['data'], true);

        $payData = new PayData(PayData::OBJ_TYPE_COURSE, $group['target'], $data['type']);
        $payData->setName($group['description']);
        $payData->addRow($group['description'].' '.date(DATE_FORMAT_SHORT, strtotime($group['ts_start'])).' - '.date(DATE_FORMAT_SHORT, strtotime($group['ts_end'])), $group['data']['price']);
        $payData->setPeriod($group['ts_start'], $group['ts_end']);
        $payData->setParams([
            'amount' => $group['amount'],
            'price' => ($group['data']['price'] ?? null),
            'subscr_type' => $group['subscr_type'],
            'course_id' => $course_item['id'],
            'group_id' => $group['target']
        ]);
        $payData->setNew(true);
        $payData->calcPrice();

        return $payData;
    }

    // описание оплаты из списка
    private function makePayDescription($data)
    {
        $result = [];
        if (isset($data['list']) && is_array($data['list']) && count($data['list'])) {
            foreach ($data['list'] as $val) {
                $result[] = $val['description'];
            }
        }

        if (empty($result)) {
            $result[] = $data['name'].' '.date(DATE_FORMAT_SHORT, strtotime($data['ts_start'])).' - '.date(DATE_FORMAT_SHORT, strtotime($data['ts_end']));
        }

        return implode(' + ', $result);
    }
}
