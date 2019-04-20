<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 
// $config['access'][role][dir] = [view, edit];
// 

$config['access'] = [];

// Юзер / Ученик
$config['access'][0] = [
	'all' => ['view' => 1, 'edit' => 1],
	'user' => ['view' => 1, 'edit' => 1],
	'user_menu' => ['view' => 1, 'edit' => 0], // меню ученика
];

// Преподаватель
$config['access'][1] = [
	'all' => ['view' => 1, 'edit' => 1],
	'teach_menu' => ['view' => 1, 'edit' => 0], // меню преподавателя
];

// Админ
$config['access'][5] = [
	'all' => ['view' => 1, 'edit' => 1]
];