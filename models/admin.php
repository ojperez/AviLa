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

class Admin extends _Model
{
    
    function __construct() 
    {   
        global $DB;
        $this->setDB($DB);
    }
        
    
    
    public static function postHandler($post)
    {   
        self::_init();   
        
        $resp=array('stop'=>false, 'msgs'=>array());
        $db=self::getDB();
        if (isset($post['_save_settings'])) 
        {
            
            $new_name = trim($post['new_name']);
            if ($new_name != '')
            {
                $new_value = trim($post['new_value']);             
                $post[$new_name] = $new_value;
            }
            unset($post['new_name']);
            unset($post['new_value']);
            
            $settings = self::getSettings();
            
            $tmp = $post['_save_settings'];
            unset($post['_save_settings']);
            foreach($post as $name => $value)
            {
                if (!isset($settings[$name]) || ($settings[$name] != $value))
                {
                    $query = "SET @name = '".$db->clean($name)."', @value = '".$db->clean(maybe_serialize($value))."'; INSERT INTO options (name, value) VALUES(@name, @value) ON DUPLICATE KEY UPDATE value = @value;";
//                    var_dump($query); die;
                    $db->multi_query($query);
                }
            }
            $post['_save_settings'] = $tmp;
            $resp['msg'] = 'Settings saved.';
        }
        return $resp;
    }
    
    public static function getSettings()
    {
        $all = true;
        $rv = self::getSettingsText($all);
        foreach($rv as $k => $v)
            $rv[$k] = maybe_unserialize ($v);        
        return $rv;
    }
    public static function getSettingsText($all = false)
    {
        $rv = [];
        self::_init();
        $db=self::getDB();
        $result=$db->query('SELECT * FROM options ORDER BY updated DESC');
        
        if ($result && $db->num_rows($result)>0)
            while($row = $db->fetch_array($result))
            {

                $val = maybe_unserialize($row['value']);
                $val = (!$all && (is_object($val)||is_array($val)))?false:$val;
                
                if ($val !== false)
                    $rv[$row['name']] = $val;
            }
        
        return $rv;
    }
    
}