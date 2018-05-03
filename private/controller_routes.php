<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
/**
 * Variable principal de rutas.
 * 'controller' => [ 'actions' => [], 'controller' => 'Class Name']
 * 
 * AL AGREGAR UN CONTROLADOR O ACCION NUEVA, DEBE SER AGREGADO A ESTA VARIABLE
 * 
 */
function crud_actions()
{
    return ['_list', 'edit', '_new', 'delete'];
}
$controllers = array
(    
    'users' => array( 'actions' => crud_actions(), 'controller'=>'UsersController'),
    'roles' => array( 'actions' => crud_actions(), 'controller'=>'RolesController'),
    'pages' => array('actions'=> array('home', 'error'), 'controller'=>'PagesController'),
    'login' => array('actions'=>array('login', 'logout'), 'controller'=>'LoginController'), //Fake controller, login action is catched before this point   
);
if (user_can('admin'))
    $controllers['admin'] = array( 'actions' => ['dashboard'], 'controller'=>'AdminController');