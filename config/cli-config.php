<?php

define('BASEPATH', '');
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/application/config/database.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\HelperSet;

$connection = DriverManager::getConnection(
    [
        'driver' => 'pdo_mysql',
        'user' => $db['default']['username'],
        'password' => $db['default']['password'],
        'dbname' => $db['default']['database'],
        'host' => $db['default']['hostname'],
        'charset'  => $db['default']['char_set'],
        'driverOptions' => [
            1002 => 'SET NAMES utf8'
        ]
    ]
);

return new HelperSet([
    'db' => new ConnectionHelper($connection),
]);