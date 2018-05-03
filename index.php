<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
/**
 * Este es el punto de entrada principal a la aplicacion
 */
session_start(); //La sesion siempre esta activa
if ( !defined('ABSPATH') ) //Definimos la ruta 'root'
 define('ABSPATH', dirname(__FILE__) . '/');

/** private/config se encarga de cargar todo lo necesario
    para la aplicacion, incluyendo private/definitions, private/functions 
    y private/input_validator
*/
 require_once('private/config.php');


 /**
  * Definimos nuestro controlador y accion por defecto
  * _ACTION_PARAM y _CONTROLLER_PARAM definen el nombre de los
  * parametros GET para ambos casos.
  */
$action     = (isset($_GET[_ACTION_PARAM]))?$_GET[_ACTION_PARAM]:'home';  
$controller =(isset($_GET[_CONTROLER_PARAM]))? $_GET[_CONTROLER_PARAM]:'pages';    

/**
 * private/auth.php verifica si la sesion ha sido iniciada, si el usuario 
 * quiere hacer login o logout. 
 * Este archivo 'secuestra' el controlador 'login' y las acciones 'login' y
 * 'logout'.
 * Se asume que la unica pagina disponible antes de iniciar sesion, es la de
 * login. Para utilizar el sistema sin necesidad de iniciar sesion, comentar la
 * linea siguiente.
 */
if (_REQUIRES_SESSION)
    require_once 'private/auth.php';

/**
 * En este punto, se carga el layout principal si el usuario tiene una sesion
 * activa, o cargamos la pagina de login si no.
 */

if (_REQUIRES_SESSION&&!is_user_logged_in())
{
    require_once(_VIEWS.'login.php');
} else
{   
    require_once(_VIEWS.'layout.php');
}

?>
