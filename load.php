<?php

session_start(); //La sesion siempre esta activa
if ( !defined('ABSPATH') ) //Definimos la ruta 'root'
 define('ABSPATH', dirname(__FILE__) . '/');

require_once('private/config.php');
$action     = (isset($_GET[_ACTION_PARAM]))?$_GET[_ACTION_PARAM]:'home';  
$controller =(isset($_GET[_CONTROLER_PARAM]))? $_GET[_CONTROLER_PARAM]:'pages'; 
if (_REQUIRES_SESSION)
    require_once 'private/auth.php';
