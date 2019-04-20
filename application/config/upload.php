<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Загрузка файлов домашних заданий
$config['upload_homework'] = [
	'upload_path' => './data/homework/',
	'allowed_types' => 'jpeg|jpg|png|mp4|rar|zip',
	'max_size' => 10240, // 10 мб
	'max_width' => 4096,
	'max_height' => 4096,
	'encrypt_name' => true,
	'remove_spaces' => true
];

// Изображения курсов
$config['upload_course'] = [
	'upload_path' => './data/courses/',
	'allowed_types' => 'jpeg|jpg|png',
	'max_size' => 2048, // 2 мб
	'max_width' => 2048,
	'max_height' => 2048,
	'encrypt_name' => true,
	'remove_spaces' => true
];

// Файлы лекций
$config['upload_lectures'] = [
	'upload_path' => './data/lectures/',
	'allowed_types' => 'jpeg|jpg|png|mp4|rar|zip',
	'max_size' => 2048, // 2 мб
	'max_width' => 2048,
	'max_height' => 2048,
	'encrypt_name' => true,
	'remove_spaces' => true
];