<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends APP_Controller
{
	public $user_id = null;

	public function __construct()
	{
		parent::__construct();

		$this->user_id = $this->Auth->userID();
	}

	public function index($id = 0)
	{
		$data = [];
		$data['id'] = intval((intval($id) > 0)?$id:$this->user_id);
		if(($data['user'] = $this->UserModel->getByID($data['id'])) === false)
			show_404();
		$data['owner'] = ($this->user_id === intval($data['user']['id']));
		$data['is_friends'] = $this->UserFriendsModel->isFriends($this->user_id, $data['id']);
		$data['friends_cnt'] = $this->UserFriendsModel->cnt($data['id']);
		
		$this->load->lview('profile/index', $data);
	}

	public function edit()
	{
		$data = [];
		if(($data['user'] = $this->UserModel->getByID($this->user_id)) === false)
			show_404();

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
					throw new Exception($this->form_validation->error_string(), 1);

				if($img = $this->UsersHelper->prepareProfileImg('img'))
					$form_data['img'] = '/'.$img;

				if($id = $this->UserModel->updateProfile($data['user']['id'], $form_data))
				{
					$user = $this->UserModel->getByID($data['user']['id']);
					$this->Auth->setUser($user);
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

	public function friends()
	{
		$data = [];
		if(($data['user'] = $this->UserModel->getByID($this->user_id)) === false)
			show_404();
		
		$data['message'] = null;

		if(($id = intval($this->input->post('id', true))) > 0)
		{
			if($this->UserFriendsModel->delete($this->user_id, intval($id)) !== false)
			{
				$data['message']['type'] = true;
				$data['message']['text'] = 'Success';
			}
		}

		$data['items'] = $this->UserFriendsModel->list($this->user_id);

		$this->load->lview('profile/friends', $data);
	}

	public function messages($id = 0)
	{
		$data = [];

		if(($data['user'] = $this->UserModel->getByID($this->user_id)) === false)
			show_404();

		$data['id'] = (int) $id;
		$data['messages'] = [];
		$data['error'] = false;

		if(cr_valid_key())
		{
			try
			{
				$form_data = $this->input->post(null, true);
				$this->form_validation->reset_validation();
				$this->form_validation->set_data($form_data);

				if($this->form_validation->run('message') == FALSE)
				{
					throw new Exception($this->form_validation->error_string(), 1);
				}

				$message_data = [
					'user' => $this->user_id,
					'target' => ($data['id'] > 0)?$data['id']:$form_data['target'],
					'text' => $form_data['text']
				];
				
				if($this->UserMessagesModel->add($message_data) === false)
				{
					throw new Exception($this->UserMessagesModel->getLastError(), 1);
				}

				if($message_data['target'] !== $data['id'])
				{
					header('Location: /profile/messages/'.$message_data['target'].'/');
				}
			}
			catch(Exception $e)
			{
				$data['error'] = $e->getMessage();
			}
		}
		$data['csrf'] = cr_get_key();

		$data['chats'] = $this->UserMessagesModel->listChats($this->user_id);
		$data['friends'] = $this->UserFriendsModel->list($this->user_id);

		if($data['id'] > 0)
		{
			$this->UserMessagesModel->chatSetReadAll($data['id'], $data['user']['id']);
			$data['chats'][$data['id']]['unread'] = 0;

			$data['messages'] = $this->UserMessagesModel->listForChat($this->user_id, $data['id']);
			if(empty($data['messages']))
			{
				if(($target = $this->UserModel->getByID($data['id'])) === false)
					header('Location: /profile/messages/');
				
				$data['chats'][] = [
					'id' => $target['id'],
					'name' => $target['full_name'],
					'img' => $target['img']
				];
			}

			$this->load->lview('profile/messages', $data);
		}
		else
		{
			$this->load->lview('profile/messages_blank', $data);
		}
	}
}
