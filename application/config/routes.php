<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// (:any), (:num)

$route['default_controller'] = 'app/main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cli'] = 'cli/index';
$route['cli/(.*)'] = 'cli/$1';

$route['admin'] = 'admin/main';
$route['admin/courses/(:num)/lectures'] = 'admin/Lectures/index/$1';
$route['admin/courses/(:num)/lectures/(.*)'] = 'admin/Lectures/$2/$1';
$route['admin/courses/(.*)'] = 'admin/Courses/$1';
$route['admin/(.*)'] = 'admin/$1';

$route['profile'] = 'app/Profile/index';
$route['profile/(:num)'] = 'app/Profile/index/$1';
$route['profile/(.*)'] = 'app/Profile/$1';
$route['profile/messages/(:num)'] = 'app/Profile/messages/$1';



$route['groups/(:any)'] = 'app/groups/item/$1';
$route['groups/(:any)/(.*)'] = 'app/groups/$2/$1';

// $route['teachinggroups'] = 'app/TeachingGroups';
// $route['teachinggroups/(:num)'] = 'app/TeachingGroups/group/$1';
// $route['teachinggroups/(:num)/lecture/(:num)'] = 'app/TeachingGroups/lecture/$1/$2';
// $route['teachinggroups/(:num)/lecture/(:num)/review/(:num)'] = 'app/TeachingGroups/review/$1/$2/$3';
// $route['teachinggroups/(.*)'] = 'app/TeachingGroups/$1';

$route['teachingstreams'] = 'app/TeachingStreams';
$route['teachingstreams/(:num)'] = 'app/TeachingStreams/item/$1';
$route['teachingstreams/(.*)'] = 'app/TeachingStreams/$1';

//$route['video/(.*)/(.*)'] = 'app/video/index/$1/$2';
$route['video/(.*)'] = 'app/video/index/$1';

//$route['groups/(:num)'] = 'app/Groups/index/$1';
$route['courses'] = 'app/courses/index';
$route['courses/(:num)'] = 'app/courses/index/$1';
$route['courses/(:num)/lecture/(:num)'] = 'app/courses/index/$1/$2';
$route['courses/(:num)/group'] = 'app/courses/group/$1';
$route['courses/(:num)/review'] = 'app/courses/review/$1';
$route['courses/(:num)/review/(:num)'] = 'app/courses/review/$1/$2';
$route['courses/(:num)/stream'] = 'app/courses/stream/$1';
$route['courses/(:num)/stream/(:num)'] = 'app/courses/stream/$1/$2';
$route['(.*)'] = 'app/$1';