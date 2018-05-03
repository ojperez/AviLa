<?php

/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
function filter_controllers($c)
{    
    return $c;
}
add_filter('_controllers', 'filter_controllers');
define('_VIEW_ASSETS_PATH', _VIEWS_DIR._TEMPLATE.'/core/');