<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PromocodeModel extends APP_Model
{
    const TABLE = 'promocodes';

    public function add($data)
    {
        $result = false;
        if ($this->db->insert(self::TABLE, $data)) {
            $result = $this->db->insert_id();
        }
    
        return $result;
    }

    public function update($id, $data = [])
    {
        $result = false;
        unset($data['id']);
        $this->db->where('id', $id);
        if ($this->db->update(self::TABLE, $data)) {
            $result = true;
        }

        return $result;
    }

    public function getItem($id)
    {
        return $this->getByField('id', $id);
    }

    public function getByCode($code)
    {
        return $this->getByField('code', $code);
    }

    public function getByField($key, $value)
    {
        return $this->db
        ->from(self::TABLE)
        ->where($key, $value)
        ->get()->row_array();
    }

    public function getList($filter = [])
    {
        $result = [];
        $filterParams = $this->parseListFilter($filter);
        $binds = $filterParams['binds'];

        $sql = 'SELECT * FROM '.self::TABLE.' ';
        if (count($filterParams['where'])) {
            $sql .= ' WHERE '.implode(' AND ', $filterParams['where']);
        }

        if ($res = $this->query($sql, $binds)->result_array()) {
            $result = $res;
        }

        return $result;
    }

    public function parseListFilter($params = [])
    {
        $result = [
            'binds' => [],
            'where' => [],
            'offset' => 0,
            'limit' => 9999
        ];

        return $result;
    }

    public function check($code)
    {
        $item = null;
        try {
            if ($item = $this->PromocodeModel->getByCode($code)) {
                if ($item['date_from'] !== '0000-00-00 00:00:00' && empty($item['date_from']) === false && strtotime($item['date_from']) > time()) {
                    throw new Exception("Срок действия промокода истек #1", 1);
                }

                if ($item['date_to'] !== '0000-00-00 00:00:00' && empty($item['date_to']) === false && strtotime($item['date_to']) <= time()) {
                    throw new Exception("Срок действия промокода истек #2", 1);
                }
            } else {
                throw new Exception("Неверный промокод", 1);
            }
        } catch (Exception $e) {
            throw new Exception("Неверный промокод", 1);
        }

        return $item;
    }
}
