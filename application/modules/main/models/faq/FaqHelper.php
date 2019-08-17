<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FaqHelper extends APP_Model
{
	public function addSection($data)
	{
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('faq_section_add') === false)
			throw new Exception($this->form_validation->error_string());

		$params = [
			'name' => $data['name']
		];

		return $this->FaqModel->addSection($params);	
	}

	public function updateSection($id, $data)
	{
		$params = [];

		$this->form_validation->reset_validation();
		$this->form_validation->set_data($data);
		if($this->form_validation->run('faq_section_edit') === false)
			throw new Exception($this->form_validation->error_string());

		$params = [
			'name' => $data['name']
		];

		return $this->FaqModel->updateSection($id, $params);	
	}
}