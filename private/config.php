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
define('_MODELS_DIR', 'models/');
define('_MODELS', ABSPATH._MODELS_DIR);
define('_MODULES_DIR', 'modules/');
define('_MODULES', ABSPATH._MODULES_DIR);


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
 * Update May 5th 2018: Se agregaron funciones add_controller() y remove_controller()
 * No debe modificarse la variable $controller directamente.
 */
global $models;
$models = [];
global $_loaded_models;
$_loaded_models = [];

add_model('enumerable');
add_model('role');
add_model('user');
add_model('admin');




global $controllers;
$controllers = [];

add_controller('users',array( 'actions' => crud_actions(), 'controller'=>'UsersController') );
add_controller('roles',array( 'actions' => crud_actions(), 'controller'=>'RolesController') );
add_controller('pages',array('actions'=> array('home', 'error'), 'controller'=>'PagesController') );
add_controller('login',array('actions'=>array('login', 'logout'), 'controller'=>'LoginController') ); //Fake controller, login action is catched before this point   

if (user_can('admin'))
    add_controller('admin',array( 'actions' => ['settings', 'modules', 'dashboard'], 'controller'=>'AdminController') );


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
        error_page (__('This app requires a session. Log In page not found.'));
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
        error_page (__('This app requires a DB. DB Connection failed.'));
        die;
    }
}
class ModuleMsg extends Exception
{
    public $avl_type;
    function __construct(string $message = "", string $avl_type = 'success', int $code = 0, \Throwable $previous = null) 
    {
        $this->avl_type = $avl_type;
        parent::__construct($message, $code, $previous);
    }
}

load_model('admin');
Admin::reloadSettings();
global $modules;
$modules = get_setting('_active_modules', []);
$available_modules = Admin::getAvailableModules();

foreach($modules as $module)
{
    if (isset($available_modules[$module]))
    {
        try 
        {
            require_once $available_modules[$module];
        }
        catch (ModuleMsg $msg)
        {
            add_alert('<b>'.$module.'</b>: '.$msg->getMessage(), $msg->avl_type);
        }
        catch (Exception $ex)
        {
            add_alert('<b>'.$module.'</b>: '.$ex->getMessage(), 'danger');
        }
    } else
        add_alert (__('Missing module: ').'<b>'.$module.'</b>', 'danger');
}

$_POST = apply_filters('post_handler', $_POST);