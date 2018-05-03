<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Clase abstracta que define un controlador
 */
abstract class _Controller
{
    protected $model;
    function __construct($model='') 
    {
        $this->model=$model;
    }
    
}
/**
 * Clase abstracta que define el controlador y las
 * acciones minimas para un CRUD
 */
abstract class _CRUD_Controller extends _Controller
{
    function __construct($model = '') 
    {
        parent::__construct($model);
    }
    abstract public function _list();
    
    abstract public function delete();
    
    abstract public function edit();
    
    abstract public function _new();
}