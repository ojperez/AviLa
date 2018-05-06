<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */

require_once ('_controller.php');
class AdminController extends _Controller
{
    function __construct()
    {        
        parent::__construct('admin');
    }
    public function dashboard($context = false)
    {
        $this->settings($context);
    }
    public function settings($context = false)
    {
        if ($context !== false)
        {
            foreach($context as $name => $var)
                $$name = $var;
        }
        
        $page_title=__('Opciones');
        $page_subtitle='Opciones';
        
        load_model($this->model);
        
        $postResult = call_user_func(array($this->model, 'postHandler'), $_POST);
        
        if (!$postResult['stop'])
        {
            $items=call_user_func(array($this->model, 'getSettingsText'));
            
            if (count($postResult['msgs'])>0)
                foreach($postResult['msgs'] as $msg)
                    add_alert($msg, 'danger');
            $model = $this->model;
            require_once(_VIEWS.'parts/_page_header.php');
            if (file_exists(_VIEWS.'admin/settings.php'))
                require_once(_VIEWS.'admin/settings.php');
            else
                error_page ('Template error: Missing admin/settings view.');
            
        }
    }
    public function modules($context = false)
    {
        if ($context !== false)
        {
            foreach($context as $name => $var)
                $$name = $var;
        }
        
        $page_title=__('Módulos');
        $page_subtitle=__('Activar y Desactivar Módulos');
        
        load_model($this->model);
        
        $postResult = array('stop'=>false, 'msgs'=>array()); //call_user_func(array($this->model, 'postHandler'), $_POST);
        
        if (!$postResult['stop'])
        {
            $items=call_user_func(array($this->model, 'getAvailableModules'));
            
            if (count($postResult['msgs'])>0)
                foreach($postResult['msgs'] as $msg)
                    add_alert($msg, 'danger');
            $model = $this->model;
            require_once(_VIEWS.'parts/_page_header.php');
            if (file_exists(_VIEWS.'admin/modules.php'))
                require_once(_VIEWS.'admin/modules.php');
            else
                error_page ('Template error: Missing admin/modules view.');
            
        }
    }
    
    

  }
?>