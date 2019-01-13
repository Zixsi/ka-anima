<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends APP_Controller
{
	public function index($id = 0)
	{
		$data = [];
		$user_id = $this->Auth->userID();
		$data['id'] = (intval($id) > 0)?$id:$user_id;
		if(($data['user'] = $this->UserModel->getByID($data['id'])) === false)
		{
			show_404();
		}
		$data['owner'] = ($user_id === $data['user']['id']);
		
		$this->load->lview('profile/index', $data);
	}

	public function edit()
	{
		$data = [];
		if(($data['user'] = $this->UserModel->getByID($this->Auth->userID())) === false)
		{
			show_404();
		}

		$data['error'] = false;
		if(cr_valid_key())
		{
			try
			{
				$form_data = $this->input->post(null, true);
				$form_data = array_intersect_key($form_data, $data['user']);
				$form_data = $form_data + $data['user'];
				$this->form_validation->reset_validation();
				$this->form_validation->set_data($form_data);

				if($this->form_validation->run('profile_edit') == FALSE)
				{
					throw new Exception($this->form_validation->error_string(), 1);
				}

				if($id = $this->UserModel->updateProfile($data['user']['id'], $form_data))
				{
					header('Location: ../');
				}
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}
		$data['csrf'] = cr_get_key();
		
		$this->load->lview('profile/edit', $data);
	}

	public function messages()
	{
		var_dump('messages'); die();
		$this->load->lview('profile/messages');
	}
}
