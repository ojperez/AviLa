<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//ddtdhttyt
//throw new ModuleMsg('I dont wanna be activated.', 'warning');
global $DB;
class Translator
{
    public static $phrases = [];
    public static $path = _MODULES.'translations/data/current.json';
    public static function _init($path = false)
    {
        add_filter('__(', array('Translator', 'catcher'));
        
        add_controller('translator', array( 'actions' => ['dashboard'], 'controller'=>_MODULES_DIR.'translations/controllers/TranslationsController') );
//        die;
//        global $controllers;
//        var_dump($controllers); die;
        add_filter('_main_menu', array('Translator', 'translations_menu'));
//        (file_exists(ABSPATH.$controller_path. '_controller.php'))
        
        if ($path !== false)
            self::$path = $path;
        if (file_exists(self::$path))
            self::$phrases = json_decode(file_get_contents(self::$path), true);
        add_action('very_end', array('Translator', '_destroy'));
    }
    public static function catcher($text)
    {
        if (!isset(self::$phrases[$text]))
            self::$phrases[$text] = '';
        return $text;
    }
    public static function _destroy()
    {
        file_put_contents(self::$path, json_encode(self::$phrases));
    }
    public static function translations_menu($menu)
    {
        if (isset($menu['Admin']) && isset($menu['Admin']['submenu']) && is_array($menu['Admin']['submenu']))
        {
            $menu['Admin']['submenu']['Translations'] = ['c'=>'translator', 'a' => 'dashboard'];
        }
        return $menu;    
    }
    
}
Translator::_init();
?>
