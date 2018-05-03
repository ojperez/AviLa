<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
/**
 * Archivo de principal de configuracion 
 */
define('_LOCAL', true); //false = remote server
define('_PRODUCTION', false); //false = development
define('_DEBUG', true); //false = debug mode off

define('_VERSION', '0.1a');
define('_TEMPLATE', 'default');
define('_TITLE', 'AviLa');
global $server_requirements;
$server_requirements = [
    'PHP 7.0' => version_compare(PHP_VERSION, '7.0', '>='),
    'PHP extension XML' => extension_loaded('xml'),
    'PHP extension xmlwriter' => extension_loaded('xmlwriter'),
    'PHP extension mbstring' => extension_loaded('mbstring'),    
    'PHP extension GD (optional)' => extension_loaded('gd'),
    'PHP extension dom (optional)' => extension_loaded('dom'),
    ];

if (_LOCAL)
{
    define('_BASE_URL','http://localhost/avila/');
    define('_DB_HOST', 'localhost');
    define('_DB_NAME', 'avila');
    define('_DB_USER', 'avila');
    define('_DB_PASS', '');
}
else
{
    //Comment the following line out after setting up remote DB
    die('Missing remote DB settings in client_config.');     
//    define('_BASE_URL','http://www.example.com/');
//    define('_DB_HOST', 'localhost');
//    define('_DB_NAME', 'example_db');
//    define('_DB_USER', 'user');
//    define('_DB_PASS', 'password');

}
/**
 * 'c': Controller
 * 'a': Action
 * 'i': Icono (depende del template utilizado)
 */
global $_menu;
$_menu = 
[
    'Users' => ['submenu'=>
    [
        'User\'s List' => ['c' => 'users', 'a' => '_list'],
        'Roles' => ['c' => 'roles', 'a' => '_list']
    ], 'c' => '', 'a' => '', 'i'=>'users'],   
   /* 'Admin' => ['c' => 'admin', 'a' => 'dashboard', 'i' => 'key'] */
]; 

        
