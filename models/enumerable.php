<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Modelo para CRUD de enumerables
 */
require_once '_model.php';

abstract class Enumerable extends _CRUD_Model
{
    public $ID;
    public $name;
    public static $type;
    public $active;
   
    
    protected static $_validation_rules=array();
    
    function __construct($ID, $name, $type, $active, $meta=array(), $hasArchive=false, $hasArchiveMeta=false) 
    {        
        self::$hasArchive = $hasArchive;
        self::$hasArchiveMeta = $hasArchiveMeta;
        $this->ID=$ID; 
        $this->name=$name; 
        self::$type=$type;
        $this->active=$active; 
        $this->meta=$meta;
        
        global $DB;
        $this->setDB($DB);
    }
    public function getType()
    {
        return self::$type;
    }
    public static function getReservedMeta()
    {
        return array('id','ID','name','type','active');
    }
    public function meta($key)
    {
        return (is_array($this->meta)&&isset($this->meta[$key]))?maybe_unserialize($this->meta[$key]):'';
    }
    public function setMeta($key, $value)
    {
        if (!is_array($this->meta))
            $this->meta = [];
        if (is_array($value))
            $value = serialize ($value);
        $this->meta[$key] = $value;
    }
    public static function _new()
    {        
        $thisClass=get_called_class();
        $meta_keys=$thisClass::getMetaKeys();
        $meta=array();
        foreach($meta_keys as $key)
            $meta[$key]='';
        return new $thisClass(0, '', 1, $meta); 
    }
    protected static function getMetaKeys()
    {
        //Este array tendra los meta_keys 
        //a ser utilizados para los enumerables.
        //Se definen en las clases derivadas        
        return array();
    }
    public static function setValidationRules($rules)
    {
        self::$_validation_rules=$rules;
    }
    public static function getValidationRules()
    {
        return self::$_validation_rules;
    }
    
    public function updateMeta()
    {
        $db=self::getDB();
        if (($this->ID != 0)&&(is_array($this->meta)))
        {
            $editor=current_user_id();
            $created=date('Y-m-d H:i:s');
            $currentMeta=array();
            $metaQResult=$db->query('SELECT meta_key, meta_value FROM enumerables_meta WHERE objectID='.$this->ID.' ORDER BY ID ASC');
            while($metaR=$db->fetch_array($metaQResult))
                $currentMeta[$metaR['meta_key']]=$metaR['meta_value'];
            $runMeta=false;                    
            $reservedMeta = self::getReservedMeta();
            $metaQ='INSERT INTO enumerables_meta (objectID, meta_key, meta_value, editor, created) VALUES ';
            foreach($this->meta as $key=>$value)
            {                        
                if (!in_array($key, $reservedMeta))
                {
                    if (is_array($value))
                            $value=serialize($value);
                    if ((isset($currentMeta[$key])&&$currentMeta[$key]!=$value)||(!isset($currentMeta[$key]))) {                                
                        $metaQ.='('.$this->ID.', "'.$db->clean($key).'","'.$db->clean($value).'",'.$editor.', "'.$created.'"), ';
                        $runMeta=true;
                    }
                } 
            }
            if ($runMeta)
            {
                $metaQ = rtrim($metaQ, ', ');
                $db->query($metaQ);
            }
//            var_dump($metaQ);
        }
    }
    public static function postHandler($post)
    {   
        self::_init();   
        
        $resp=array('stop'=>false, 'msgs'=>array());
        if (isset($post['ID'])) {
            $validate = new Validate();
            $validation = $validate->check($post, self::getValidationRules());
            if($validation->passed()) {  
                
                $id=$post['ID'];
                $meta_keys= self::getMetaKeys();
                $active=isset($post['active'])?$post['active']:1;
                $db=self::getDB();
                $meta=array();
                foreach($meta_keys as $mk)
                {
                    $mv=isset($post[$mk])?$post[$mk]:'';
                    $meta[]=array($mk=>$mv);
                }
//                var_dump($meta); die;       
                /*If editing a user and password is empty, unset*/
                if ($id!=0)
                {
                    $currentMeta=array();
                    $metaQResult=$db->query('SELECT meta_key, meta_value FROM enumerables_meta WHERE objectID='.$id.' ORDER BY ID ASC');
                    while($metaR=$db->fetch_array($metaQResult))
                        $currentMeta[$metaR['meta_key']]=$metaR['meta_value'];
                    $editor=current_user_id();
                    $created=date('Y-m-d H:i:s');
                    $db->query('UPDATE enumerables set name="'.$db->clean($post['name']).'", active='.$active.' WHERE ID='.$id);
                    
//                    $meta=isset($post['meta'])?$post['meta']:array();
                    $runMeta=false;                    
                    $reservedMeta = self::getReservedMeta();
                    $metaQ='INSERT INTO enumerables_meta (objectID, meta_key, meta_value, editor, created) VALUES ';
                    foreach($post as $key=>$value)
                    {                        
                        if (!in_array($key, $reservedMeta))
                        {
                            if (is_array($value))
                                    $value=serialize($value);
                            if ((isset($currentMeta[$key])&&$currentMeta[$key]!=$value)||(!isset($currentMeta[$key]))) {                                
                                $metaQ.='('.$id.', "'.$db->clean($key).'","'.$db->clean($value).'",'.$editor.', "'.$created.'"), ';
                                $runMeta=true;
                            }
                        } 
                    }
                    if ($runMeta)
                    {
                        $metaQ = rtrim($metaQ, ', ');
                        $db->query($metaQ);
                    }
                        
                } else
                {
                    $editor=current_user_id();
                    $created=date('Y-m-d H:i:s');
                    $insertQuery='INSERT INTO enumerables (name, type, active) VALUES("'.$db->clean($post['name']).'","'.self::$type.'", '.$active.')';
//                    var_dump($db); die();
                    $insertResult=$db->query($insertQuery);
                    if ($insertResult)
                    {
                        $id = $db->insert_id();
//                        $meta=isset($post['meta'])?$post['meta']:array();
                        $runMeta=false;
                        $reservedMeta = self::getReservedMeta();
                        $metaQ='INSERT INTO enumerables_meta (objectID, meta_key, meta_value, editor, created) VALUES ';
                        foreach($post as $key=>$value)
                        {
                            if (!in_array($key, $reservedMeta))
                            {
                                if (is_array($value))
                                    $value=serialize($value);
                                $metaQ.='('.$id.', "'.$db->clean($key).'","'.$db->clean($value).'",'.$editor.', "'.$created.'"), ';
                                $runMeta=true;
                            }
                        }
                        if ($runMeta)
                        {
//                            var_dump($metaQ); die;
                            $metaQ = rtrim($metaQ, ', ');
                            $db->query($metaQ);
                        }                    
                    }
                } 
                
                

                actionCall($_GET[_CONTROLER_PARAM], '_list');
                $resp['stop']=true;            
            } else {
                $resp['msgs']=$validation->errors();
            }            
        }
        return $resp;
    }
    
    
    public static function delete($id)
    {
        self::_init();
        $db=self::getDB();        
        $db->query('UPDATE enumerables set active=0 WHERE ID='.$id);        
    }
    public static function get($id)
    {
        self::_init();
        $db=self::getDB();
        if (trim($id!=''))
        {
            $result=$db->query('SELECT * from enumerables where ID='.$id);
            if ($db->num_rows($result)>0)
            {
                $row=$db->fetch_array($result);
                $currentMeta=array();
                $metaQResult=$db->query('SELECT meta_key, meta_value FROM enumerables_meta WHERE objectID='.$id.' ORDER BY ID ASC');
                while($metaR=$db->fetch_array($metaQResult))
                    $currentMeta[$metaR['meta_key']]=$metaR['meta_value'];

                $thisClass=get_called_class();
                return new $thisClass($row['ID'], $row['name'], $row['active'], $currentMeta);            
            }
        }
        return false;
    }
    public static function getByName($name)
    {
        self::_init();
        $db=self::getDB();
        if (trim($name!=''))
        {
            $result=$db->query('SELECT * from enumerables where name="'.$name.'"');
            if ($db->num_rows($result)>0)
            {
                $row=$db->fetch_array($result);
                $currentMeta=array();
                $metaQResult=$db->query('SELECT meta_key, meta_value FROM enumerables_meta WHERE objectID='.$row['ID'].' ORDER BY ID ASC');
                while($metaR=$db->fetch_array($metaQResult))
                    $currentMeta[$metaR['meta_key']]=$metaR['meta_value'];

                $thisClass=get_called_class();
                return new $thisClass($row['ID'], $row['name'], $row['active'], $currentMeta);            
            }
        }
        return false;
    }
    public static function all($query = false)
    {
        $thisClass=get_called_class();
        
        self::_init();
        $db=self::getDB();
        $metas=array(); 
        if ($query === false)
            $query = 'SELECT * from enumerables where type="'.self::$type.'" AND active=1';
        $result=$db->query($query);
        
        $resultMeta=$db->query('SELECT objectID, meta_key, meta_value FROM enumerables_meta WHERE objectID IN (SELECT ID from enumerables where type="'.self::$type.'" AND active=1) ORDER BY ID ASC');
        while($rowMeta=$db->fetch_array($resultMeta))
        {
            if (!isset($metas[$rowMeta['objectID']]))
                $metas[$rowMeta['objectID']]=array();
            $metas[$rowMeta['objectID']][$rowMeta['meta_key']]=$rowMeta['meta_value'];
        }
        
        $enumerables=array();
        
        while($row=$db->fetch_array($result))
        {  
            $meta=isset($metas[$row['ID']])?$metas[$row['ID']]:array();            
            $enumerables[$row['ID']]=new $thisClass($row['ID'], $row['name'], 1, $meta);  
            
        }
        
        return $enumerables;
    }
}