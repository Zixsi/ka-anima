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
		'field' => 'name',
		'label' => 'Имя',
		'rules' => 'required|min_length[2]'
	],
	[
		'field' => 'lastname',
		'label' => 'Фамилия',
		'rules' => 'required|min_length[2]'
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
		'rules' => 'required|in_list[0,1]'
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

$config['review_add'] = [
	[
		'field' => 'group_id',
		'label' => 'Group id',
		'rules' => 'required|integer'
	],
	[
		'field' => 'lecture_id',
		'label' => 'Lecture id',
		'rules' => 'required|integer'
	],
	[
		'field' => 'video_url',
		'label' => 'Url',
		'rules' => 'required'
	]
];

$config['stream_add'] = [
	[
		'field' => 'group_id',
		'label' => 'Group id',
		'rules' => 'required|integer|greater_than[0]'
	],
	[
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'required|min_length[5]'
	],
	[
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'required|min_length[5]'
	],
	[
		'field' => 'ts',
		'label' => 'Date',
		'rules' => 'required|min_length[5]'
	],
];

$config['profile_edit'] = [
	[
		'field' => 'name',
		'label' => 'Имя',
		'rules' => 'required|min_length[2]'
	],
	[
		'field' => 'lastname',
		'label' => 'Фамилия',
		'rules' => 'required|min_length[2]'
	]
];

$config['faq_add'] = [
	[
		'field' => 'question',
		'label' => 'Вопрос',
		'rules' => 'required|min_length[10]'
	],
	[
		'field' => 'answer',
		'label' => 'Ответ',
		'rules' => 'required|min_length[10]'
	]
];
$config['faq_edit'] = $config['faq_add'];

$config['news_add'] = [
	[
		'field' => 'title',
		'label' => 'Заголовок',
		'rules' => 'required|min_length[3]'
	],
	[
		'field' => 'text',
		'label' => 'Описание',
		'rules' => 'required|min_length[10]'
	]
];
$config['news_edit'] = $config['news_add'];