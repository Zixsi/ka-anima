<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NotificationModel extends APP_Model
{
    const TABLE = 'notifications';

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

    public function getListByUser(int $user, int $limit = 9999)
    {
        $result = [];
        $res = $this->db->where(['user' => $user, 'status' => 0])
                ->limit($limit)
                ->order_by('id', 'DESC')
                ->get(self::TABLE);

        if ($res) {
            $result = $res->result_array();
        }

        return $result;
    }

    public function setItemStatus(int $id, int $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    public function setUserTypeStatus(int $user, string $type, int $status)
    {
        $result = false;
        $this->db->where(['user' => $user, 'type' => $type]);
        if ($this->db->update(self::TABLE, ['status' => $status])) {
            $result = true;
        }

        return $result;
    }
}
