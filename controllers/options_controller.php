<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */

require_once ('_controller.php');
class OptionsController extends _CRUD_Controller
{
    function __construct()
    {
        //Vamos a utilizar el modelo 'user'
        parent::__construct('user');
    }
    
    public function _list() 
    {      
        //Lista de todos los usuarios
   
    }
    public function _new() 
    {   //Crear un usuario nuevo
     
    }
    public function edit() 
    {   //Editar un usuario   
       
    }
    public function delete() 
    {   
      
    }
    
    

  }
?>