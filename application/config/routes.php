<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// (:any), (:num)

$route['default_controller'] = 'app/main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cli'] = 'cli/index';
$route['cli/(.*)'] = 'cli/$1';

$route['admin'] = 'admin/main';
$route['admin/(.*)'] = 'admin/$1';


$route['teachingcourses'] = 'app/TeachingCourses';
$route['teachingcourses/(:num)/lectures'] = 'app/TeachingLectures/index/$1';
$route['teachingcourses/(:num)/lectures/(.*)'] = 'app/TeachingLectures/$2/$1';
$route['teachingcourses/(.*)'] = 'app/TeachingCourses/$1';


$route['teachinggroups'] = 'app/TeachingGroups';
$route['teachinggroups/(:num)'] = 'app/TeachingGroups/group/$1';
$route['teachinggroups/(:num)/(:num)'] = 'app/TeachingGroups/lecture/$1/$2';
$route['teachinggroups/(.*)'] = 'app/TeachingGroups/$1';


$route['video/(.*)'] = 'app/video/index/$1';

//$route['groups/(:num)'] = 'app/Groups/index/$1';
$route['courses'] = 'app/courses/index';
$route['courses/(:num)'] = 'app/courses/index/$1';
$route['courses/(:num)/(:num)'] = 'app/courses/index/$1/$2';
$route['(.*)'] = 'app/$1';