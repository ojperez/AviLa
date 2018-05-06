<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */

/**
 * Selector principal de rutas y acciones.
 * Se incluye en layout.php
 */
//var_dump($controllers);
global $controllers;
$controllers = apply_filters('_controllers', $controllers);
if (array_key_exists($controller, $controllers)) 
{
    if (in_array($action, $controllers[$controller]['actions'])) 
    {
        try 
        {
            actionCall($controller, $action);            
        } catch (Exception $ex) 
        {
            error_page ($ex);
        }
      
    }
    else 
      error_page ('Unknown action.');
    
} else 
    error_page ('Unknown controller.');
  
?>
