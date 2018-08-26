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

$config['course'] = [
	[
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'required'
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

$config['lectures'] = [
	[
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'required|min_length[3]'
	],
	[
		'field' => 'course_id',
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

$config['transaction'] = [
	[
		'field' => 'user',
		'label' => 'User',
		'rules' => 'required|integer'
	],
	[
		'field' => 'type',
		'label' => 'Type',
		'rules' => 'required|in_list[IN,OUT]'
	],
	[
		'field' => 'amount',
		'label' => 'Amount',
		'rules' => 'required|numeric|greater_than_equal_to[0.01]'
	]
];

$config['subscription'] = [
	[
		'field' => 'user',
		'label' => 'User',
		'rules' => 'required|integer'
	],
	[
		'field' => 'service',
		'label' => 'Service',
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

$config['courses_groups'] = [
	[
		'field' => 'code',
		'label' => 'Code',
		'rules' => 'required|min_length[3]'
	],
	[
		'field' => 'course_id',
		'label' => 'Course',
		'rules' => 'required|integer'
	],
	[
		'field' => 'ts',
		'label' => 'Ts',
		'rules' => 'required'
	]
];