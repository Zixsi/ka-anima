<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Загрузка файлов домашних заданий
$config['upload_homework'] = [
	'upload_path' => './data/',
	'allowed_types' => 'jpeg|jpg|png|mp4|rar|zip',
	'max_size' => 10240, // 10 мб
	'max_width' => 4096,
	'max_height' => 4096,
	'encrypt_name' => true,
	'remove_spaces' => true
];