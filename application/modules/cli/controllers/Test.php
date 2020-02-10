<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends APP_Controller
{
    public function __construct()
    {
        $this->load->library(['ydvideo']);
    }

    // php index.php cli test index 1 qwerty
    public function index()
    {
        var_dump('TEST');

        $res  = $this->PromocodeModel->getCountUsed('TEST900');
        var_dump($res);
    }
}
