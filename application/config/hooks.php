<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['post_controller_constructor'][] = array(
	'class'    => 'SystemHook',
	'function' => 'initOptions',
	'filename' => 'SystemHook.php',
	'filepath' => 'hooks',
	'params'   => []
);

$hook['post_controller_constructor'][] = array(
	'class'    => 'SystemHook',
	'function' => 'checkAuth',
	'filename' => 'SystemHook.php',
	'filepath' => 'hooks',
	'params'   => []
);

$hook['post_controller_constructor'][] = array(
	'class'    => 'SystemHook',
	'function' => 'profiler',
	'filename' => 'SystemHook.php',
	'filepath' => 'hooks',
	'params'   => []
);

$hook['pre_controller_constructor'][] = array(
	'class'    => 'LayoutHook',
	'function' => 'default',
	'filename' => 'LayoutHook.php',
	'filepath' => 'hooks',
	'params'   => []
);
