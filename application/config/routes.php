<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// (:any), (:num)

$route['default_controller'] = 'app/main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/main';
$route['admin/(.*)'] = 'admin/$1';


$route['teachingcourses'] = 'app/TeachingCourses';
$route['teachingcourses/(.*)'] = 'app/TeachingCourses/$1';

$route['(.*)'] = 'app/$1';