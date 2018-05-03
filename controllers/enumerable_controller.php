<?php

/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
require_once ('_controller.php');


class Enumerables_Controller extends _CRUD_Controller
{
    public $singular;
    public $plural;
    public $viewsPath;
    function __construct($singular, $plural, $viewsPath, $model)
    {        
        $this->singular=$singular;
        $this->plural=$plural;
        $this->viewsPath=$viewsPath;
        
        parent::__construct($model);
    }
    
    public function _list($context=false) 
    {
        if ($context!==false)
        {
            foreach($context as $name => $var)
                $$name=$var;
        }
//        
        
        $page_title=$this->plural;
        $page_subtitle='Lista de '.$this->plural;
        $tableName = $this->plural;
        $newButton = 'Nuevo '. $this->singular;
                
        load_model($this->model);

        $list = call_user_func(array($this->model, 'all'));
        $model = $this->model;
        
        require_once(_VIEWS.'parts/_page_header.php');
        
        if (file_exists(_VIEWS.$this->viewsPath.'list.php'))
            require_once _VIEWS.$this->viewsPath.'list.php';
        else
            require_once(_VIEWS.'enumerables/list.php');
    }
    public function _new($context=false) 
    {   
        if ($context!==false)
        {
            foreach($context as $name => $var)
                $$name=$var;
        }
        $page_title=$this->plural;
        $page_subtitle='Nueva '.$this->singular;
        
        load_model($this->model);    
        
//        var_dump($this->model);
        $postResult = call_user_func(array($this->model, 'postHandler'), $_POST);
        if (!$postResult['stop'])
        {
            $model = $this->model;
            $item=call_user_func(array($this->model, '_new'));   
            require_once(_VIEWS.'parts/_page_header.php');
            
            if (file_exists(_VIEWS.$this->viewsPath.'new-edit.php'))
                require_once _VIEWS.$this->viewsPath.'new-edit.php';
            else
                require_once(_VIEWS.'enumerables/new-edit.php');       
        }
    }
    public function edit($context=false) 
    {     
        if ($context!==false)
        {
            foreach($context as $name => $var)
                $$name=$var;
        }
        
        $page_title=$this->plural;
        $page_subtitle='Editar '.$this->singular;
        
        if (!isset($_GET['id']))
            error_page('Missing <b>id</b> parameter');
        
        
        load_model($this->model);
        
        $postResult = call_user_func(array($this->model, 'postHandler'), $_POST);
        
        if (!$postResult['stop'])
        {
            $item=call_user_func(array($this->model, 'get'), trim($_GET['id']));
            if (count($postResult['msgs'])>0)
                foreach($postResult['msgs'] as $msg)
                    add_alert($msg, 'danger');
            $model = $this->model;
            require_once(_VIEWS.'parts/_page_header.php');
         
            if (file_exists(_VIEWS.$this->viewsPath.'new-edit.php'))
                require_once _VIEWS.$this->viewsPath.'new-edit.php';
            else
                require_once(_VIEWS.'enumerables/new-edit.php'); 
        }
    }
    public function delete() 
    {   
        if (!isset($_GET['id']))
            error_page('Missing <b>id</b> parameter');
        load_model($this->model);
        $item=call_user_func(array($this->model, 'delete'), $_GET['id']);
        $this->_list();
    }
  }