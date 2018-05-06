<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Controlador para traducciones
 * 
 */
require_once (ABSPATH.'controllers/_controller.php');
class TranslationsController extends _Controller
{    
    public function dashboard() 
    {
      $page_title='Dashboard';
      $page_subtitle='Translations Dashboard';
      
      require_once(_VIEWS.'parts/_page_header.php');
      require_once(_VIEWS.'pages/home.php');
    }
  }