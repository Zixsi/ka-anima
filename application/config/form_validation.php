<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = [];
$config['signin'] = [
	[
		'field' => 'email',
		'label' => 'Email',
		'rules' => 'required|valid_email'
	],
	[
		'field' => 'password',
		'label' => 'Password',
		'rules' => 'trim|required'
	]
];

$config['signup'] = [
	[
		'field' => 'email',
		'label' => 'Email',
		'rules' => 'required|valid_email|is_unique[users.email]'
	],
	[
		'field' => 'password',
		'label' => 'Password',
		'rules' => 'trim|required|min_length[6]|max_length[64]'
	],
	[
		'field' => 'repassword',
		'label' => 'Re-Password',
		'rules' => 'trim|matches[password]'
	]
];