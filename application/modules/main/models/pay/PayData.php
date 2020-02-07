<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PayData
{
    const OBJ_TYPE_COURSE = 'course'; // курс
    const OBJ_TYPE_SUBSCR = 'subscription'; // подписка
    const OBJ_TYPE_WORKSHOP = 'workshop'; // мастерская
    const OBJ_TYPES = [ // список типов
        self::OBJ_TYPE_COURSE,
        self::OBJ_TYPE_SUBSCR,
        self::OBJ_TYPE_WORKSHOP
    ];

    const ITEM = [
        'object' => [ // объект оплаты
            'type' => null, // тип
            'id' => null // идентификатор
        ],
        'name'  => null, // имя
        'price' => 0, // сумма
        'origin_price' => 0, // сумма
        'ts_start' => null, // дата начала
        'ts_end' => null, // дата окончания
        'type' => null, // тип подписки
        'is_new' => false,
        'list' => [], // состав подписки
        'params' => [], // дополнительные параметры
        'promocode' => null
    ];

    private $data;
    private $promocode;

    public function __construct($type, $id, $subscr_type)
    {
        $this->data = self::ITEM;
        $this->setObject($type, $id);
        $this->data['type'] = $subscr_type;
    }

    // установить объект оплаты
    public function setObject($type, $id)
    {
        if (empty($type) || !in_array($type, self::OBJ_TYPES)) {
            throw new Exception('неверный тип объекта оплаты', 1);
        }

        if (empty($id)) {
            throw new Exception('не задан идентификатор объекта оплаты', 1);
        }

        $this->data['object']['type'] = $type;
        $this->data['object']['id'] = $id;
    }

    // задать имя
    public function setName($value)
    {
        $this->data['name'] = $value;
    }

    // добавить элемент оплаты
    public function addRow($description, $price)
    {
        $this->data['list'][] = [
            'description' => $description,
            'price' => (float) $price,
            'origin_price' => (float) $price
        ];
    }

    // установить период оплаты
    public function setPeriod($start, $end)
    {
        $this->data['ts_start'] = $start;
        $this->data['ts_end'] = $end;
    }

    // новая подписка или продление
    public function setNew($value = false)
    {
        $this->data['is_new'] = $value;
    }

    public function isNew()
    {
        return (bool) $this->data['is_new'];
    }

    // задать дополнительные параметры
    public function setParams($value = [])
    {
        $this->data['params'] = $value;
    }

    public function setPromocode($data)
    {
        $this->promocode = $data;
    }

    public function getPromocode()
    {
        return $this->promocode;
    }

    public function getCourseId()
    {
        return ($this->data['params']['course_id'] ?? 0);
    }
    
    // расчитать итоговую сумму
    public function calcPrice()
    {
        $this->data['price'] = (float) 0;
        $this->data['origin_price'] = (float) 0;
        foreach ($this->data['list'] as $val) {
            $this->data['price'] += $val['price'];
            $this->data['origin_price'] += $val['origin_price'];
        }
    }

    public function applyPromocode()
    {
        if (($item = $this->getPromocode()) && isset($item['value']) && isset($item['code'])) {
            $this->data['promocode'] = $item['code'];
            $value = ($item['value'] / 100);
            foreach ($this->data['list'] as &$val) {
                $val['price'] = number_format(($val['origin_price'] - ($val['origin_price'] * $value)), 2, '.', '');
                if ($val['price'] < 0) {
                    $val['price'] = 0;
                }
            }
        } else {
            foreach ($this->data['list'] as &$val) {
                $val['price'] = $val['origin_price'];
            }
        }
    }

    public function getObjectType()
    {
        return $this->data['object']['type'];
    }

    public function getObjectId()
    {
        return $this->data['object']['id'];
    }

    // объект ввиде json
    public function toJson()
    {
        return json_encode($this->data);
    }

    // объект ввиде json
    public function toArray()
    {
        return $this->data;
    }
}
