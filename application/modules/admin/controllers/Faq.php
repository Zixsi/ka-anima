<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];
		if(($id = $this->input->post('id', true)) && $this->input->post('type') === 'delete')
		{
			$this->FaqModel->delete($id);
		}
		$data['items'] = $this->FaqModel->list();

		$this->load->lview('faq/index', $data);
	}

	public function add()
	{
		$data = [];
		$data['error'] = null;

		if(cr_valid_key())
		{
			try
			{
				$form_data = $this->input->post(null, true);

				$this->form_validation->reset_validation();
				$this->form_validation->set_data($form_data);

				if($this->form_validation->run('faq_add') === false)
				{
					throw new Exception($this->form_validation->error_string());
				}

				$form_data = [
					'question' => $form_data['question'],
					'answer' => $form_data['answer']
				];
				if($this->FaqModel->add($form_data) === false)
					throw new Exception($this->FaqModel->getLastError());

				header('Location: ../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['csrf'] = cr_get_key();

		$this->load->lview('faq/add', $data);
	}

	public function edit($id = 0)
	{
		$data = [];
		$data['error'] = null;

		if(cr_valid_key())
		{
			try
			{
				$form_data = $this->input->post(null, true);
				$this->form_validation->reset_validation();
				$this->form_validation->set_data($form_data);

				if($this->form_validation->run('faq_edit') === false)
				{
					throw new Exception($this->form_validation->error_string());
				}

				$form_data = [
					'question' => $form_data['question'],
					'answer' => $form_data['answer']
				];

				if($this->FaqModel->update($id, $form_data) === false)
				{
					throw new Exception($this->FaqModel->getLastError());
				}

				header('Location: ../../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		if(($data['item'] = $this->FaqModel->getById($id)) === false)
		{
			header('Location: ../');
		}
		$data['csrf'] = cr_get_key();

		$this->load->lview('faq/edit', $data);
	}
}
