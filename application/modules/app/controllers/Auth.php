<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->layout = 'auth';
    }

    public function index()
    {
        $data = [];
        $data['remembered'] = $this->Auth->getLoginRemember();

        // $this->EmailHelper->registration(['email' => 'zixxsi@gmail.com', 'code' => '123456']);
        // die();

        $this->load->lview('auth/login', $data);
    }

    public function logout()
    {
        $this->Auth->logout();
        redirect('/');
    }

    public function register()
    {
        $data = [];
        $data['landUrl'] = $this->config->item('land_url');

        $this->load->lview('auth/register', $data);
    }

    public function forgot()
    {
        $data = [];

        $this->load->lview('auth/forgot', $data);
    }

    public function recovery()
    {
        $data = [];
        $data['code'] = ($_GET['code'] ?? '');

        $this->load->lview('auth/recovery', $data);
    }

    public function confirmation()
    {
        $data['message'] = [
            'status' => false,
            'text' => null
        ];

        try {
            $this->Auth->confirm(($_GET['code'] ?? ''));
            $data['message'] = [
                'status' => true,
                'text' => 'Регистрация успешно подтверждена. Пожалуйста, авторизуйтесь, перейдя по ссылке'
            ];
        } catch (Exception $e) {
            $data['message']['text'] = $e->getMessage();
        }

        $this->load->lview('auth/confirmation', $data);
    }

    public function blocked()
    {
        $this->load->lview('auth/blocked');
    }

    public function soc()
    {
        if ($this->Auth->check()) {
            redirect('/');
        }

        $user_soc = $this->ulogin->getUser();
        if (empty($user_soc) || empty($user_soc->getUid()))
            redirect('/');

        $login = $user_soc->makeEmail();
        $user = $this->UserModel->getByLogin($login);

        if ($user) {
            if ((int) $user['blocked'] === 1) {
                redirect('/auth/blocked/');
            } else {
                $this->AuthSoc->socAuth($user['id']);
            }
        } else {
            if ($id = $this->AuthSoc->socRegister($user_soc)) {
                $this->AuthSoc->socAuth($id);
            }
        }
        
        if ($backUri = getRequestBackUri()) {
            clearRequestBackUri();
            redirect(urldecode($backUri));
        } else {
            redirect('/');
        }
    }

}
