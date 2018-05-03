<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Define un modelo abstracto, con una instancia propia de la BD 
 * Uso:
 * global $DB;
 * _Model::setDB($DB)
 */
abstract class _Model
{
    protected static $db=false;
    
    protected static function getDB()
    {
        return self::$db;
    }
    protected function setDB($db)
    {
        self::$db = $db;
    }
    protected static function _init()
    {
        global $DB;        
        self::$db=$DB;
    }    
}
/*
Define las funciones basicas de un modelo usado para CRUD
 */
abstract class _CRUD_Model extends _Model
{    
     public $meta=array();
     public static $hasArchive = false;
     public static $hasArchiveMeta = false;
    /*Lista de todos los meta_keys utilizados por este modelo*/
    protected static function getMetaKeys() {}
    /*Funcion para procesar requerimientos POST referentes a este modelo*/
    public static function postHandler($post) {}
    /*Crea una instancia vacia*/
    public static function _new() {}
    /*Eliminar un elemento del CRUD*/
    public static function delete($id) {}
    /*Devuelve un elemento del CRUD*/
    public static function get($id) {}
    /*Lista todos los elementos del CRUD*/
    public static function all() {}   
    public function meta($key)
    {
        $meta_keys = $this->getMetaKeys();
        if (in_array($key, $meta_keys))
            return isset($this->meta[$key])?$this->meta[$key]:'';
        else
            return '';
    }
}
