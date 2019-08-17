<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = [];
		if(($id = $this->input->post('id', true)) && $this->input->post('type') === 'delete')
			$this->NewsModel->delete($id);
		$data['items'] = $this->NewsModel->list('desc');

		$this->load->lview('news/index', $data);
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

				if($this->form_validation->run('news_add') === false)
					throw new Exception($this->form_validation->error_string());

				if($img = $this->NewsHelper->prepareImg('img'))
					$form_data['img'] = $img;

				$form_data = [
					'title' => htmlspecialchars_decode($form_data['title']),
					'text' => htmlspecialchars_decode($form_data['text']),
					'description' => htmlspecialchars_decode($form_data['description'] ?? ''),
					'img' => ($form_data['img'] ?? '')
				];
				if($this->NewsModel->add($form_data) === false)
					throw new Exception($this->NewsModel->getLastError());

				header('Location: ../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		$data['csrf'] = cr_get_key();

		$this->load->lview('news/add', $data);
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

				if($this->form_validation->run('news_edit') === false)
					throw new Exception($this->form_validation->error_string());

				if($img = $this->NewsHelper->prepareImg('img'))
					$form_data['img'] = '/'.$img;

				$form_data = [
					'title' => htmlspecialchars_decode($form_data['title']),
					'text' => htmlspecialchars_decode($form_data['text']),
					'description' => htmlspecialchars_decode($form_data['description'] ?? ''),
					'img' => ($form_data['img'] ?? '')
				];

				if($this->NewsModel->update($id, $form_data) === false)
					throw new Exception($this->NewsModel->getLastError());

				header('Location: ../../');
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}

		if(($data['item'] = $this->NewsModel->getById($id)) === false)
		{
			header('Location: ../');
		}
		$data['csrf'] = cr_get_key();

		$this->load->lview('news/edit', $data);
	}
}
