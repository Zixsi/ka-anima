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

$config['course_add'] = [
	[
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'required'
	],
	[
		'field' => 'period',
		'label' => 'Period',
		'rules' => 'required|integer'
	],
	[
		'field' => 'price_month',
		'label' => 'Price month',
		'rules' => 'required|numeric|greater_than[0]'
	],
	[
		'field' => 'price_full',
		'label' => 'Price full',
		'rules' => 'required|numeric|greater_than[0]'
	]
];

$config['lectures_add'] = [
	[
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'required|min_length[3]'
	],
	[
		'field' => 'course',
		'label' => 'Course',
		'rules' => 'required|integer'
	],
	[
		'field' => 'video',
		'label' => 'Video',
		'rules' => 'required|valid_url'
	],
	[
		'field' => 'sort',
		'label' => 'Sort',
		'rules' => 'required|integer|greater_than[0]|less_than[65535]'
	]
];