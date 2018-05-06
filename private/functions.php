<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Funcion principal para llamar la accion de algun controlador
 */

function actionCall($controller, $action)
{
    if ($controller=='login')
    {
        $controller='pages';
        $action = 'home';
    }
    global $controllers;
    $do = true;
    if (!isset($controllers[$controller]['controller']))
    {
        error_page ('Unknown controller class.');
        $do=false;
    }
//    if (file_exists(ABSPATH.'controllers/' . $controller . '_controller.php'))
//        require_once(ABSPATH.'controllers/' . $controller . '_controller.php');
    $controller_path = $controllers[$controller]['controller'];
    if (strpos($controller_path, '/') === false)
    {
        $instanciable = $controller_path;
        $controller_path = 'controllers/'.$controller;
    } else
    {
        $path_bits = explode('/', $controller_path);
        $instanciable = end($path_bits);
    }
//    var_dump(ABSPATH.$controller_path. '_controller.php'); die;
    if (file_exists(ABSPATH.$controller_path. '_controller.php'))
        require_once(ABSPATH.$controller_path . '_controller.php');
    else
    {
        error_page ('Missing controller class.');
        $do=false;
    }
    if ($do)
    {
//        $c = new $controllers[$controller]['controller']();
        $c = new $instanciable();
        $c->{ $action }();
    }
}
/*Devuelve true si el usuario tiene una sesion activa*/
function is_user_logged_in()
{
    return (isset($_SESSION['current_user'])&&($_SESSION['current_user']!=false));
}
function user_can($permission)
{
//    var_dump($_SESSION['current_user']['role']);
    return (is_user_logged_in()&&isset($_SESSION['current_user']['role']['permissions'][$permission]));
}
function get_main_menu()
{
    global $_menu;
    return apply_filters('_main_menu', $_menu);
}

function is_model_loaded($model)
{
    global $_loaded_models;
    return (is_array($_loaded_models) && isset($_loaded_models[$model]));
}
/*Funcion para cargar los modelos*/
function add_model($model, $path='', $force = false)
{
    global $models;
     if (($force) || (!isset($models[$model])))
        return ($models[$model] = $path);
    else 
    {
        if (!_PRODUCTION)
            add_alert (__('Error, modelo ya existe: ').'<b>'.$model.'</b>', 'danger');
        return false;
    }    
}
function remove_model($model)
{
    global $models;
    if (isset($models[$model]))
    {
        if (is_model_loaded($model))
        {
            add_alert( __('Error, no se puede eliminar un modelo que ya se ha cargado: ').'<b>'.$model.'</b>', 'danger');
            return false;
        }
        unset($models[$model]);
        return true;
    }
    return false;
}
function load_model($model)
{
    global $models;
    global $_loaded_models;
    
    if (is_model_loaded($model))
        return;
    
    if (isset($models[$model]))
    {
        if (trim($models[$model]) == '')
            $path = _MODELS;
        else
            $path = rtrim($models[$model], '/').'/';
    }
    
    if (file_exists($path.$model.'.php'))
    {
            require_once $path.$model.'.php';
            if (method_exists($model, '_init'))
                call_user_func(array($model, '_init'));
            $_loaded_models[$model] = true;
    }
    else
        throw new Exception ('Error, model <b>'.$model.'</b> not found.');
}
/*Muestra la pagina de error*/
function error_page($error)
{
    $_SESSION['exception']=array('message'=>$error);
    actionCall('pages', 'error'); 
}
function current_user_id()
{
    if (isset($_SESSION['current_user'])&&isset($_SESSION['current_user']['ID']))
        return $_SESSION['current_user']['ID'];
    return 0;
}
function get_server_requirements()
{
    global $server_requirements;
    return $server_requirements;
}
function check_server_requirements()
{
    $requirements = get_server_requirements();
    foreach ($requirements as $label => $result) 
    {
       if (!$result)
           add_alert ('Server Requirement Not Met: <b>'.$label.'</b>', 'danger');    
    }

}
/**
 * Controller functions
 */
function crud_actions()
{
    return ['_list', 'edit', '_new', 'delete'];
}
function add_controller($handle, $controller, $force = false)
{
    global $controllers;
    if (($force) || (!isset($controllers[$handle])))
        return ($controllers[$handle] = $controller);
    else 
    {
        if (!_PRODUCTION)
            add_alert (__('Error, controlador ya existe: ').'<b>'.$handle.'</b>', 'danger');
        return false;
    }    
}
function remove_controller($handle)
{
    global $controllers;
    if (isset($controllers[$handle]))
    {
        unset($controllers[$handle]);
        return true;
    }
    return false;
}
/*
 * ****************************************************************************
 */
/**
 * Settings functions
 */

//phpinfo(); die;

function get_setting($setting, $default = '')
{
    return Admin::getSetting($setting, $default);
}
function update_setting($setting, $value)
{
    return Admin::setSetting($setting, $value);
}
/*
 * ****************************************************************************
 */

/*
 * Alerts
 * Maneja las alertas al usuario
 * Ver ejemplo de generar alertas en:
 * UsersController::edit()
 * Y ejemplo de mostrar alertas en [views]/[template]/parts/_page_header.php
 */
function get_alert_types()
{
    return array('info','success','danger','warning');
}
function add_alert($alert, $type='info')
{
    $types=get_alert_types();
    if (!in_array($type, $types))
            $type='info';
    $alerts=isset($_SESSION['alerts'])?$_SESSION['alerts']:array();
    foreach($types as $t)
        if (!isset($alerts[$t]))
            $alerts[$t]=array();
        
    $alerts[$type][]=$alert;    
    $_SESSION['alerts']=$alerts;    
}
function clear_alerts()
{
    $_SESSION['alerts']=array();
    $types=get_alert_types();
    foreach($types as $t)
        $_SESSION['alerts'][$t]=array();
}
function get_alerts()
{
    $rv = $_SESSION['alerts'];
    clear_alerts();    
    return $rv;
}
function has_alerts()
{
    $alerts=isset($_SESSION['alerts'])?$_SESSION['alerts']:array();    
    foreach($alerts as $a)
        if (is_array($a)&&count($a)>0)
            return true;
    return false;
}
/*
 * ****************************************************************************
 */
/**
 * Components
 */

/*
 * ****************************************************************************
 */


/*
 *  ***************************************************************************
 */
/**
 * Template Functions
 */
function __($text)
{
    return apply_filters('__(', $text);
}
function assets_path($file='')
{
    if (!defined('_VIEW_ASSETS_PATH'))
        return _VIEWS;
    if ($file!='')
        $file=ltrim($file,'/');
    return _VIEW_ASSETS_PATH.$file;
}
function ifset($array, $key,$default='')
{
    
    return isset($array[$key])?$array[$key]:$default;
}
function getSelectOptions($list, $value=false)
{
    $rv='';
    foreach($list as $item)
    {
        $rv.='<option value="'.$item->ID.'"';
        if (($value!==false)&&($item->ID==$value))
            $rv.=' selected';
        $rv.= '>'.$item->name.'</option>'.PHP_EOL;
    }
    return $rv;
}
/*
 * ****************************************************************************
 */
/**
 * Menu System
 */
function _main_menu($menu)
{
    if (!user_can('admin'))
    {
        unset($menu['admin']);
    }
    return $menu;
}
add_filter('_main_menu', '_main_menu');

/**
 * WordPress functions
 */
/**
 * Unserialize value only if it was serialized.
 *
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}
function maybe_serialize( $original )
{
    return (is_array($original) || is_object($original))?serialize($original):$original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 *
 * @param string $data   Value to check to see if was serialized.
 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data, $strict = true ) {
	// if it isn't a string, it isn't serialized.
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
 	if ( 'N;' == $data ) {
		return true;
	}
	if ( strlen( $data ) < 4 ) {
		return false;
	}
	if ( ':' !== $data[1] ) {
		return false;
	}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {
			return false;
		}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace )
			return false;
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
			// or else fall through
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}
/**
 * ****************************************************************************
 */