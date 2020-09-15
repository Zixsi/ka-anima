<?php

class Notifications
{
    private $c;
    private $list = [];
    private $list_from_db = [];
    private $user_id;
    
    const INFO = 'info';
    const PRIMARY = 'primary';
    const DANGER = 'danger';
    const WARNING = 'warning';

    public function __construct()
    {
        $this->c = &get_instance();
        $this->loadUserId();
    }

    public function load()
    {
        if (!$this->c->Auth->isActive()) {
            $this->add(self::WARNING, 'Пользователь неактивирован', '/profile/');
        }

        $this->loadFromDb();
    }

    public function loadFromDb()
    {
        $res = $this->c->NotificationModel->getListByUser($this->getUserId(), 30);
        if (is_array($res)) {
            $this->list_from_db = $res;
        }
    }

    public function list()
    {
        return $this->list;
    }

    public function add($type, $text, $href = null, $icon = null)
    {
        $item = [
            'type' => $type,
            'text' => $text,
            'href' => $href,
            'icon' => ($icon ?? 'mdi mdi-alert-circle')
        ];

        array_unshift($this->list, $item);
    }

    private function getListFromDb()
    {
        return $this->list_from_db;
    }

    private function getUserId()
    {
        return (int) $this->user_id;
    }

    private function loadUserId()
    {
        $this->user_id = $this->c->Auth->isAdmin()?0:$this->c->Auth->getUserId();
    }

    public function check($type)
    {
        $result = false;
        $items = $this->getListFromDb();

        if (count($items)) {
            foreach ($items as $row) {
                if ($row['type'] === $type) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    public function addItem(
        int $user,
        string $type,
        string $text = null,
        string $param1 = null,
        string $param2 = null,
        string $param3 = null
    ) {
        $this->c->NotificationModel->add(get_defined_vars());
    }

    public function changeItemStatus(int $id)
    {
        return $this->c->NotificationModel->setItemStatus($id, 1);
    }

    public function changeTypeStatus($type)
    {
        $this->c->NotificationModel->setUserTypeStatus($this->getUserId(), $type, 1);
    }
    
    public function changeTragetTypeStatus($type, $targetId)
    {
        $this->c->NotificationModel->setTargetTypeStatus($this->getUserId(), $type, $targetId, 1);
    }
    
    public function setReadTragetTypeStatus($type, $targetId)
    {
        $this->c->NotificationModel->setAllTargetTypeStatus($type, $targetId, 1);
    }
    
    public function getListUnreadTypeIds($type)
    {
        $list = $this->c->NotificationModel->getListUnreadType($this->getUserId(), $type);
        
        return array_map(
            function ($row) {
                return $row['param1'];
            }, 
            $list);
    }

    public function showPoint($class = 'relative')
    {
        echo '<span class="notification-ripple notification-ripple-bg notification-' . $class . '">
                <span class="ripple notification-ripple-bg"></span>
                <span class="ripple notification-ripple-bg"></span>
                <span class="ripple notification-ripple-bg"></span>
            </span>';
    }
}
