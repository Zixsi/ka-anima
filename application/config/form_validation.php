<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = [];
$config['auth_login'] = [
	[
		'field' => 'email',
		'label' => 'E-mail',
		'rules' => 'required|valid_email'
	],
	[
		'field' => 'password',
		'label' => 'Пароль',
		'rules' => 'trim|required'
	]
];

$config['auth_register'] = [
	[
		'field' => 'name',
		'label' => 'Имя',
		'rules' => 'required|min_length[2]|alpha_numeric_spaces_ru'
	],
	[
		'field' => 'lastname',
		'label' => 'Фамилия',
		'rules' => 'required|min_length[2]|alpha_numeric_spaces_ru'
	],
	[
		'field' => 'email',
		'label' => 'E-mail',
		'rules' => 'required|valid_email|is_unique[users.email]'
	],
	[
		'field' => 'password',
		'label' => 'Пароль',
		'rules' => 'trim|required|min_length[6]|max_length[64]'
	],
	[
		'field' => 're_password',
		'label' => 'Повтор пароля',
		'rules' => 'trim|matches[password]'
	]
];

// alpha_numeric_spaces

$config['auth_recovery'] = [
	[
		'field' => 'password',
		'label' => 'Пароль',
		'rules' => 'trim|required|min_length[6]|max_length[64]'
	],
	[
		'field' => 're_password',
		'label' => 'Повтор пароля',
		'rules' => 'trim|matches[password]'
	]
];

$config['course'] = [
	[
		'field' => 'name',
		'label' => 'Название',
		'rules' => 'required'
	],
	[
		'field' => 'code',
		'label' => 'Символьный код',
		'rules' => 'rtrim|required|min_length[3]|max_length[40]|alpha_dash|is_unique[courses.code]'
	],
	[
		'field' => 'teacher',
		'label' => 'Преподаватель',
		'rules' => 'required|integer'
	]
];

$config['course_edit'] = [
	[
		'field' => 'name',
		'label' => 'Название',
		'rules' => 'required'
	],
	[
		'field' => 'teacher',
		'label' => 'Преподаватель',
		'rules' => 'required|integer'
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

// $config['subscription'] = [
// 	[
// 		'field' => 'user',
// 		'label' => 'Пользователь',
// 		'rules' => 'required|integer'
// 	],
// 	[
// 		'field' => 'type',
// 		'label' => 'Тип',
// 		'rules' => 'required|in_list[standart,advanced,vip,private]'
// 	],
// 	[
// 		'field' => 'target',
// 		'label' => 'Цель',
// 		'rules' => 'required|integer'
// 	],
// 	[
// 		'field' => 'target_type',
// 		'label' => 'Тип цели',
// 		'rules' => 'required'
// 	],
// 	[
// 		'field' => 'ts_start',
// 		'label' => 'Дата начала',
// 		'rules' => 'required'
// 	],
// 	[
// 		'field' => 'ts_end',
// 		'label' => 'Дата окончания',
// 		'rules' => 'required'
// 	]
// ];

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
		'rules' => 'required|min_length[3]'
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
		'rules' => 'required|min_length[2]|alpha_numeric_spaces_ru'
	],
	[
		'field' => 'lastname',
		'label' => 'Фамилия',
		'rules' => 'required|min_length[2]|alpha_numeric_spaces_ru'
	],
	[
		'field' => 'title',
		'label' => 'Цель',
		'rules' => 'max_length[255]'
	],
	[
		'field' => 'soc',
		'label' => 'Ссылка на профиль соцсети',
		'rules' => 'max_length[255]|valid_url|prep_url'
	],
	
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

$config['faq_section_add'] = [
	[
		'field' => 'name',
		'label' => 'Название',
		'rules' => 'required|min_length[3]|max_length[255]'
	],
];
$config['faq_section_edit'] = $config['faq_section_add'];

$config['news_add'] = [
	[
		'field' => 'title',
		'label' => 'Заголовок',
		'rules' => 'required|min_length[3]'
	],
	[
		'field' => 'description',
		'label' => 'Описание',
		'rules' => 'required|min_length[10]'
	],
	[
		'field' => 'text',
		'label' => 'Детальное описание',
		'rules' => 'required|min_length[10]'
	],
];
$config['news_edit'] = $config['news_add'];

$config['message'] = [
	[
		'field' => 'text',
		'label' => 'Сообщение',
		'rules' => 'required|min_length[1]'
	]
];

$config['support_add_ticket'] = [
	[
		'field' => 'text',
		'label' => 'Текст обращения',
		'rules' => 'required|min_length[10]'
	]
];

$config['support_add_ticket_message'] = [
	[
		'field' => 'text',
		'label' => 'Текст сообщения',
		'rules' => 'required|min_length[10]'
	]
];

//========================================================================//

// создание пользователя
$config['user_add'] = [
	[
		'field' => 'role',
		'label' => 'Роль',
		'rules' => 'required|in_list[0,1]'
	],
	[
		'field' => 'email',
		'label' => 'E-mail',
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
		'label' => 'Пароль',
		'rules' => 'trim|required|min_length[6]|max_length[64]'
	],
	[
		'field' => 're_password',
		'label' => 'Повтор пароля',
		'rules' => 'trim|matches[password]'
	]
];

// =========================================================================== //


$config['video'] = [
	[
		'field' => 'source_id',
		'label' => 'Идентификатор источника',
		'rules' => 'required|integer'
	],
	[
		'field' => 'source_type',
		'label' => 'Тип источника',
		'rules' => 'required'
	],
	// [
	// 	'field' => 'code',
	// 	'label' => 'Ссылка на видео',
	// 	'rules' => 'required|isValidYandexVideoUrl',
	// 	'errors' => ['isValidYandexVideoUrl' => 'Невалидная ссылка яндекс видео']
	// ],
	[
		'field' => 'code',
		'label' => 'Ссылка на видео',
		'rules' => 'required',
	],
	[
		'field' => 'video_code',
		'label' => 'Код видео',
		'rules' => 'required|is_unique[video.video_code]'
	],
];

$config['video_workshop'] = [
	[
		'field' => 'title',
		'label' => 'Название',
		'rules' => 'trim|required|min_length[3]|max_length[255]'
	]
];
$config['video_workshop'] += $config['video'];
