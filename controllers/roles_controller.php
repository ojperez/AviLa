<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Controlador del componente Roles
 * Extiende _CRUD_Controller
 */
require_once ('_controller.php');
class RolesController extends _CRUD_Controller
{
    function __construct()
    {
        
        parent::__construct('role');
    }
    
    public function _list() 
    {      
        //Lista de todos los usuarios
        //Definimos variables principales de la vista
        $page_title='Roles';
        $page_subtitle='Roles de Usuario';
        $tableName="Lista de Roles";
        
        //Cargamos el modelo y la lista de usuarios
        load_model($this->model);
        
        $roles = Role::all();
        
        
        //Llamamos las vistas
        require_once(_VIEWS.'parts/_page_header.php');
        require_once(_VIEWS.'roles/list.php');
    }
    public function _new() 
    {   //Crear un usuario nuevo
        //Definimos variables principales de la vista
        $page_title='Roles'; 
        $page_subtitle='Nuevo Rol';
        
        //Cargamos los modelos
        load_model($this->model);
                
        $postResult=Role::postHandler($_POST);
        if (!$postResult['stop'])
        {
            
            $role=Role::_new();            
            
            //Llamamos las vistas
            require_once(_VIEWS.'parts/_page_header.php');
            require_once(_VIEWS.'roles/new-edit.php');
        }
    }
    public function edit() 
    {   //Editar un usuario   
        //Definimos variables principales de la vista
        $page_title='Roles'; 
        $page_subtitle='Editar Rol';
        //Requerimos id de usuario a editar
        if (!isset($_GET['id']))
            error_page('Missing <b>id</b> parameter');
        
        //Cargamos los modelos
        load_model($this->model);
        $postResult=Role::postHandler($_POST);        
        if (!$postResult['stop'])
        { //el postHandler no invoco ninguna vista, continuamos...
            //ejecutamos acciones en el modelo para obtener un $user, su $role y una lista
            //de todos los $roles del appClient (ver model/role.php)
            
            $role=Role::get(trim($_GET['id']));
            //Agregamos los mensajes provenientes del model como 'alerts' (ver private/functions.php)
            //para mostrarlos al usuario. (en este caso, errores de validacion del usuario editado)
            if (count($postResult['msgs'])>0)
                foreach($postResult['msgs'] as $msg)
                    add_alert($msg, 'danger');
            
            //Llamamos las vistas (para mostrar errores de validacion y permitir
            //intentar nuevamente)            
            require_once(_VIEWS.'parts/_page_header.php');
            require_once(_VIEWS.'roles/new-edit.php');
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
        $roles=Role::delete($_GET['id']);
        //Llamamos a _list() para mostrar
        //la vista del listado de usuarios
        $this->_list();
    }
    
    

  }
?>