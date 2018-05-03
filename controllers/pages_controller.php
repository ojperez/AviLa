<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Controlador para paginas simples
 * Al momento solo el homepage y la pagina de errores
 */
require_once ('_controller.php');
class PagesController extends _Controller
{    
    public function home() 
    {
      $page_title='Homepage';
      $page_subtitle='';
      
      require_once(_VIEWS.'parts/_page_header.php');
      require_once(_VIEWS.'pages/home.php');
    }

    public function error() 
    {
        $page_title='Error';
        $page_subtitle='There\'s been some kind of error...';
        require_once(_VIEWS.'parts/_page_header.php');
        require_once(_VIEWS.'pages/error.php');
    }
  }
?>