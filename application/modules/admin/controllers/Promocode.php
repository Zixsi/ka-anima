<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(CLASSES_DIR  . 'WorkshopEntity.php');

class Promocode extends APP_Controller
{
    public function index()
    {
        $data = [];
        $data['types'] = $this->getTypeList();
        $data['items'] = $this->PromocodeModel->getList();

        $this->load->lview('promocode/index', $data);
    }

    public function item($id = 0)
    {
        $data = [];
        if (($data['item'] = $this->PromocodeModel->getItem($id)) === null) {
            show_404();
        }

        $data['error'] = null;
        $data['types'] = $this->getTypeList();
        $data['course_list'] = $this->CoursesModel->listActive(['id', 'name']);
        $data['workshop_list'] = $this->WorkshopModel->getListActive(['id', 'title']);

        if (($params = $this->input->post(null, true))) {
            unset($params['code']);
            if ($this->validateForm($params)) {
                $this->PromocodeModel->update($id, $params);
                redirect('/admin/promocode/item/'.$id.'/');
            } else {
                $data['error'] = $this->form_validation->error_string();
            }
        }

        $this->load->lview('promocode/item', $data);
    }

    public function add()
    {
        $data = [];
        $data['error'] = null;
        $data['types'] = $this->getTypeList();
        $data['course_list'] = $this->CoursesModel->listActive(['id', 'name']);
        $data['workshop_list'] = $this->WorkshopModel->getListActive(['id', 'title']);

        if (($params = $this->input->post(null, true))) {
            if ($this->validateForm($params)) {
                $this->PromocodeModel->add($params);
                redirect('/admin/promocode/');
            } else {
                $data['error'] = $this->form_validation->error_string();
            }
        }
        
        $this->load->lview('promocode/add', $data);
    }

    private function validateForm($params = [])
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($params);

        if (isset($params['code'])) {
            $this->form_validation->set_rules('code', 'Код', 'required|alpha_dash|is_unique[promocodes.code]');
        }

        $this->form_validation->set_rules('value', 'Значение', 'required|numeric|greater_than[0]');


        return $this->form_validation->run();
    }


    private function getTypeList()
    {
        return [
            'course' => 'Курс',
            'workshop' => 'Мастерская'
        ];
    }
}
