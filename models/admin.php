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
    protected static $settings = false;
    
    function __construct() 
    {   
        global $DB;
        $this->setDB($DB);
        if (!self::$settings)
            self::$settings = self::getSettings(true); //include_reserved = true 
    }
        
    public static function _init()
    {
        parent::_init();
        if (!self::$settings)
            self::$settings = self::getSettings(true); //include_reserved = true 
        add_filter('post_handler', array('Admin', 'modulesPostHandler'), 1, 1);
    }
    public static function reloadSettings()
    {
        self::$settings = false;
        self::_init();
    }
    public static function modulesPostHandler($post)
    {
        if (isset($post['_save_modules'])) 
        {
            $remove = []; $add = [];
            global $modules;
            foreach($modules as $k => $module)
            {
                if (!isset($post['act_'.$module]))
                        unset($modules[$k]);
//                        $remove[] = $module;
            }
            foreach($post as $field => $value)
            {
                if (substr($field, 0, 4) == 'act_')
                {
                    $mod = substr($field,4);
                    if (!in_array($mod, $modules))
                            $modules[] = $mod;
                }
            }
            global $modules;
            self::saveModules($modules);
           
            add_alert( __('Modules saved.'), 'success');
           
            header('Location:'.$_SERVER['REQUEST_URI']);
            die;
        }
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
            
            
            $tmp = $post['_save_settings'];
            unset($post['_save_settings']);
            self::saveOptions($post);
            $post['_save_settings'] = $tmp;
            $resp['msg'] = 'Settings saved.';
        } 
        
        return $resp;
    }
    public static function saveModules($modules)
    {        
        update_setting('_active_modules', $modules);        
    }
    public static function reservedSettings()
    {
        return apply_filters('reserved_settings', ['_active_modules']);
    }
    public static function saveOptions($post)
    {
        $db = self::getDB();
        self::reloadSettings();
        $settings = self::getSettings();
        foreach($post as $name => $value)
        {
            if (!isset($settings[$name]) || ($settings[$name] != $value))
            {
                $query = "SET @name = '".$db->clean($name)."', @value = '".$db->clean(maybe_serialize($value))."'; INSERT INTO options (name, value) VALUES(@name, @value) ON DUPLICATE KEY UPDATE value = @value;";
//                    var_dump($query); die;
                $db->multi_query($query);
            }
        }
    }
    
    public static function getSettings($include_reserved = false)
    {
        $all = true;
        $rv = self::getSettingsText($all, $include_reserved);
        foreach($rv as $k => $v)
            $rv[$k] = maybe_unserialize ($v);        
        return $rv;
    }
    public static function getSetting($setting, $default = '')
    {
        self::_init();
        return isset(self::$settings[$setting])?self::$settings[$setting]:$default;                
    }
    public static function setSetting($setting, $value)
    {
        self::_init();
        self::$settings[$setting] = $value;
        self::saveOptions(self::$settings);
        
    }
    public static function getSettingsText($all = false, $include_reserved = false)
    {
        $rv = [];
        global $DB;     
//        var_dump( debug_backtrace()); die;
        self::$db=$DB;
        $db = self::getDB();
        $reserved = self::reservedSettings();
        $db=self::getDB();
        $result=$db->query('SELECT * FROM options ORDER BY updated DESC');
        
        if ($result && $db->num_rows($result)>0)
            while($row = $db->fetch_array($result))
            {
                if (!$include_reserved && in_array($row['name'], $reserved))
                    continue;

                $val = maybe_unserialize($row['value']);
                $val = (!$all && (is_object($val)||is_array($val)))?false:$val;
                
                if ($val !== false)
                    $rv[$row['name']] = $val;
            }
        
        return $rv;
    }
    
    /**
     * Modules
     */
    public static function getAvailableModules()
    {
        $rv = [];
        global $modules;
        $dirs = scandir(_MODULES);
        foreach($dirs as $dir)
        {
//            echo _MODULES.$dir.'/'.$dir.'.php';
            if ((is_dir(_MODULES.$dir)) && (file_exists(_MODULES.$dir.'/'.$dir.'.php')))                
                 $rv[$dir] = _MODULES.$dir.'/'.$dir.'.php';   
        }
        return $rv;            
    }
    /**
     * *************************************************************************
     */
    
    
}