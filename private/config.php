<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 * LOS ARCHIVOS private/definitions.php y private/config.php NO DEBEN 
 * SER SOBREESCRITOS EN EL REPOSITORIO.
 */
/**
 * Variables de Desarrollo
 * _LOCAL nos indica que estamos trabajando en un servidor local
 * _PRODUCTION indica si estamos en modo de produccion
 * _DEBUG indica si estamos en modo debug
 * 
 */

/**
 * Archivo principal de definiciones para la aplicacion.
 * Credenciales y API keys se encuentran en definitions.php
 * LOS ARCHIVOS private/definitions.php y private/config.php NO DEBEN 
 * SER SOBREESCRITOS EN EL REPOSITORIO.
 */


/*DB Credentials*/
require_once 'hooks/hooks.php';
require_once 'client_config.php';
if (!defined('_TEMPLATE'))
    define('_TEMPLATE', 'default');

define('_VIEWS_DIR', 'views/');
define('_VIEWS', ABSPATH._VIEWS_DIR._TEMPLATE.'/');


define('_AVILA_VERSION', '0.1a');
if (!defined('_TITLE'))
    define('_TITLE', 'AviLa '._AVILA_VERSION);
define ('_PLUGIN_DIR', ABSPATH.'plugins');
if (!_PRODUCTION)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

/*Parametros GET para controlador y accion*/
define('_CONTROLER_PARAM','c');
define('_ACTION_PARAM','a');

define('_USES_DB', true); //La aplicacion require una BD
define('_REQUIRES_SESSION', true); //User must be logged in

/*Funciones basicas del sistema*/
require_once 'functions.php';

if (!_PRODUCTION)
    check_server_requirements();
/*Validador de datos*/
require_once 'input_validator.php';

/**
 * Contiene Variable principal de rutas.
 * 'controller' => [ 'actions' => [], 'controller' => 'Class Name']
 * 
 * AL AGREGAR UN CONTROLADOR O ACCION NUEVA, DEBE SER AGREGADO A ESTA VARIABLE
 * 
 */

require_once 'controller_routes.php';
 
/**
 * Variable $DB es la unica coneccion a la BD
 * @global resource $DB
 */

global $DB;
$DB=false;
if (file_exists(_VIEWS.'functions.php'))
    require_once _VIEWS.'functions.php';
else 
{
    die('Invalid template <b>'._TEMPLATE.'</b>.');    
}
if (_REQUIRES_SESSION&&!is_user_logged_in())
{
    if (!file_exists(_VIEWS.'login.php'))
    {
        error_page ('This app requires a session. Log In page not found.');
        die;
    }
}
if (_USES_DB)
{
    //Solo cargamos connection.php si la aplicacion requiere BD
    require_once 'connection.php';
    $DB=new DBConnection(_DB_HOST, _DB_USER, _DB_PASS, _DB_NAME);
//    var_dump($DB);
    if (!$DB->connected)
    {        
        error_page ('This app requires a DB. DB Connection failed.');
        die;
    }
}
