<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// (:any), (:num)

$route['default_controller'] = 'app/main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cli'] = 'cli/index';
$route['cli/(.*)'] = 'cli/$1';

$route['admin'] = 'admin/main';
$route['admin/courses/(:num)'] = 'admin/Courses/archive/$1';
$route['admin/courses/(:num)/lectures'] = 'admin/Lectures/index/$1';
$route['admin/courses/(:num)/lectures/(.*)'] = 'admin/Lectures/$2/$1';
$route['admin/courses/(.*)'] = 'admin/Courses/$1';
$route['admin/groups/(:any)'] = 'admin/Groups/item/$1';
$route['admin/faq/sections/add'] = 'admin/faq/addSections';
$route['admin/faq/sections/(:num)'] = 'admin/faq/editSections/$1';

$route['admin/support'] = 'admin/SupportController/index';
$route['admin/support/(:any)/(:any)'] = 'admin/SupportController/$1/$2';
$route['admin/support/(:any)'] = 'admin/SupportController/$1';

$route['admin/workshop'] = 'admin/workshop/index';
$route['admin/workshop/item/(:num)'] = 'admin/workshop/item/$1';
$route['admin/workshop/view/(:num)'] = 'admin/workshop/view/$1';
$route['admin/workshop/add'] = 'admin/workshop/add';
$route['admin/workshop/(.*)'] = 'admin/workshop/$1';

$route['admin/(.*)'] = 'admin/$1';

$route['profile'] = 'app/Profile/index';
$route['profile/(:num)'] = 'app/Profile/index/$1';
$route['profile/(.*)'] = 'app/Profile/$1';
$route['profile/messages/(:num)'] = 'app/Profile/messages/$1';

$route['transactions'] = 'app/transactions/index';
$route['users'] = 'app/users/index';
$route['faq'] = 'app/faq/index';
$route['pay'] = 'app/pay/index';

$route['subscription'] = 'app/subscription/index';

$route['groups'] = 'app/groups/index';
$route['groups/(:any)'] = 'app/groups/item/$1';
$route['groups/(:any)/lecture/(:num)'] = 'app/groups/item/$1/$2';
$route['groups/(:any)/group'] = 'app/groups/group/$1';
$route['groups/(:any)/review'] = 'app/groups/review/$1';
$route['groups/(:any)/review/(:num)'] = 'app/groups/review/$1/$2';
$route['groups/(:any)/stream'] = 'app/groups/stream/$1';
$route['groups/(:any)/stream/(:num)'] = 'app/groups/stream/$1/$2';
$route['groups/(:any)/(.*)'] = 'app/groups/$2/$1';


$route['teachingstreams'] = 'app/TeachingStreams';
$route['teachingstreams/(:num)'] = 'app/TeachingStreams/item/$1';
$route['teachingstreams/(.*)'] = 'app/TeachingStreams/$1';

$route['video/(.*)'] = 'app/video/index/$1';
$route['news/(.*)'] = 'app/news/item/$1';

$route['support'] = 'app/SupportController/index';
$route['support/(:any)/(:any)'] = 'app/SupportController/$1/$2';
$route['support/(:any)'] = 'app/SupportController/$1';

//$route['groups/(:num)'] = 'app/Groups/index/$1';
$route['courses'] = 'app/courses/index';
$route['courses/enroll'] = 'app/courses/enroll';
//$route['courses/(:any)'] = 'app/courses/index/$1';
$route['courses/(:any)'] = 'app/courses/item/$1';
$route['courses/(:any)/lecture/(:num)'] = 'app/courses/index/$1/$2';
$route['courses/(:any)/group'] = 'app/courses/group/$1';
$route['courses/(:any)/review'] = 'app/courses/review/$1';
$route['courses/(:any)/review/(:num)'] = 'app/courses/review/$1/$2';
$route['courses/(:any)/stream'] = 'app/courses/stream/$1';
$route['courses/(:any)/stream/(:num)'] = 'app/courses/stream/$1/$2';

$route['workshop'] = 'app/workshop/index';
$route['workshop/item/(:any)/(:any)'] = 'app/workshop/item/$1/$2';
$route['workshop/item/(:any)'] = 'app/workshop/item/$1';
$route['workshop/(.*)'] = 'app/workshop/$1';

$route['auth'] = 'app/auth/index';
$route['auth/register'] = 'app/auth/register';
$route['auth/forgot'] = 'app/auth/forgot';
$route['auth/recovery'] = 'app/auth/recovery';
$route['auth/confirmation'] = 'app/auth/confirmation';
$route['auth/logout'] = 'app/auth/logout';

$route['ajax'] = 'app/ajax/index';

$route['file/download/(:num)'] = 'app/file/download/$1';


// $route['(.*)'] = 'app/$1';