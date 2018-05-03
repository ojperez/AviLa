<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Controlador del componente Users
 * Extiende _CRUD_Controller
 */
require_once ('_controller.php');
class UsersController extends _CRUD_Controller
{
    function __construct()
    {
        //Vamos a utilizar el modelo 'user'
        parent::__construct('user');
    }
    
    public function _list() 
    {      
        //Lista de todos los usuarios
        //Definimos variables principales de la vista
        $page_title='Usuarios';
        $page_subtitle='Control de Usuarios';
        $tableName="Lista de Usuarios";
        
        //Cargamos el modelo y la lista de usuarios
        load_model($this->model);
        load_model('role');
        $roles = Role::all();
        $users=User::all();
        
        //Llamamos las vistas
        require_once(_VIEWS.'parts/_page_header.php');
        require_once(_VIEWS.'users/list.php');
    }
    public function _new() 
    {   //Crear un usuario nuevo
        //Definimos variables principales de la vista
        $page_title='Usuarios'; 
        $page_subtitle='Nuevo Usuario';
        
        //Cargamos los modelos
        load_model($this->model);
        load_model('role');
        $roles = Role::all();
        //Invocamos el postHandler (ver detalles en model/user.php)
        //postHandler ejecuta acciones post referentes a la creacion de usuarios (en este caso)
        //devuelve un array('stop' => bool, 'msgs' => array('mensajes', 'de', 'validacion'))
        //$postResult['stop']==true indica que el postHandler invoco alguna vista y no debemos continuar
        //aca con la ejecucion del controlador
        $postResult=User::postHandler($_POST);
        if (!$postResult['stop'])
        {
            //el postHandler no invoco ninguna vista, continuamos...
            //ejecutamos acciones en el modelo para obtener un $user vacio, definimos $role
            //vacio con valor 0, y una lista de todos los $roles del appClient (ver model/role.php)
            
            $user=User::_new();            
            
            //Llamamos las vistas
            require_once(_VIEWS.'parts/_page_header.php');
            require_once(_VIEWS.'users/new-edit.php');
        }
    }
    public function edit() 
    {   //Editar un usuario   
        //Definimos variables principales de la vista
        $page_title='Usuarios';
        $page_subtitle='Editar Usuario';
        //Requerimos id de usuario a editar
        if (!isset($_GET['id']))
            error_page('Missing <b>id</b> parameter');
        
        //Cargamos los modelos
        load_model($this->model);
        load_model('role');
        $roles = Role::all();
        //Invocamos el postHandler (ver detalles en model/user.php)
        //postHandler ejecuta acciones post referentes a la edicion de usuarios (en este caso)
        //devuelve un array('stop' => bool, 'msgs' => array('mensajes', 'de', 'validacion'))
        //$postResult['stop']==true indica que el postHandler invoco alguna vista y no debemos continuar
        //aca con la ejecucion del controlador
        $postResult=User::postHandler($_POST);
        
        if (!$postResult['stop'])
        { //el postHandler no invoco ninguna vista, continuamos...
            //ejecutamos acciones en el modelo para obtener un $user, su $role y una lista
            //de todos los $roles del appClient (ver model/role.php)
            
            $user=User::get(trim($_GET['id']));
            //Agregamos los mensajes provenientes del model como 'alerts' (ver private/functions.php)
            //para mostrarlos al usuario. (en este caso, errores de validacion del usuario editado)
            if (count($postResult['msgs'])>0)
                foreach($postResult['msgs'] as $msg)
                    add_alert($msg, 'danger');
            
            //Llamamos las vistas (para mostrar errores de validacion y permitir
            //intentar nuevamente)            
            require_once(_VIEWS.'parts/_page_header.php');
            require_once(_VIEWS.'users/new-edit.php');
        }
    }
    public function delete() 
    {   //Como pueden notar, este metodo no llama a ninguna vista
        // de forma directa
        //Eliminar un usuario
        
        //Requerimos id de usuario a eliminar
        if (!isset($_GET['id']))
            error_page('Falta parametro <b>id</b>');
        //Cargamos modelo
        load_model($this->model);
        //Ejecutamos la accion
        $users=User::delete($_GET['id']);
        //Llamamos a _list() para mostrar
        //la vista del listado de usuarios
        $this->_list();
    }
    
    

  }
?>