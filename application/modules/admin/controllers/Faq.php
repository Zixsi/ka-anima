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
		$data['sections'] = $this->FaqModel->listSections();

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
					'section' => (int) $form_data['section'],
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

		if(($data['item'] = $this->FaqModel->getById($id)) === false)
			header('Location: ../');

		$data['sections'] = $this->FaqModel->listSections();

		if(cr_valid_key())
		{
			try
			{
				$post = $this->input->post(null, true);
				$data['item'] = $post + $data['item'];

				$this->form_validation->reset_validation();
				$this->form_validation->set_data($data['item']);

				if($this->form_validation->run('faq_edit') === false)
				{
					throw new Exception($this->form_validation->error_string());
				}

				$params = [
					'section' => (int) $data['item']['section'],
					'question' => $data['item']['question'],
					'answer' => $data['item']['answer']
				];

				if($this->FaqModel->update($id, $params) === false)
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

		$data['csrf'] = cr_get_key();

		$this->load->lview('faq/edit', $data);
	}

	public function sections()
	{
		$data = [];
		if(($id = $this->input->post('id', true)) && $this->input->post('type') === 'delete')
			$this->FaqModel->deleteSection($id);
		$data['items'] = $this->FaqModel->listSections();

		$this->load->lview('faq/sections_index', $data);
	}

	public function addSections()
	{
		$data = [];
		$data['error'] = null;

		if(cr_valid_key())
		{
			try
			{
				$post = $this->input->post(null, true);
				$this->FaqHelper->addSection($post);
				header('Location: ../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['csrf'] = cr_get_key();

		$this->load->lview('faq/sections_add', $data);
	}

	public function editSections($id = 0)
	{
		$data = [];
		$data['error'] = null;

		if(($data['item'] = $this->FaqModel->getSectionById($id)) === false)
			header('Location: ../');

		if(cr_valid_key())
		{
			try
			{
				$post = $this->input->post(null, true);
				$data['item'] = ($post + $data['item']);
				$this->FaqHelper->updateSection($id, $data['item']);
				header('Location: ../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		
		$data['csrf'] = cr_get_key();

		$this->load->lview('faq/sections_edit', $data);
	}
}
