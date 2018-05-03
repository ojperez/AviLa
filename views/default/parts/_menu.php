<?php

/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package WAF
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
$menu = get_main_menu();
?>
<ul class="sidebar-menu" data-widget="tree">
          <li class="header">&nbsp;</li>
<?php
foreach($menu as $name => $first_level)
{
    $href = ($first_level['c'] != '')?
            '?c='.$first_level['c'].'&a='.$first_level['a']:'#';
    $i = (isset($first_level['i'])&&($first_level['i']))!=''?$first_level['i']:'link';
    if (isset($first_level['submenu']))
    {
        
        
        ?>
        <li class="treeview menu-open">
          <a href="<?php echo $href; ?>"><i class="fa fa-<?php echo $i; ?>"></i> <span><?php echo $name; ?></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu" style="display: block;">
            <?php
            foreach($first_level['submenu'] as $subname => $second_level)
            {
                $href = ($second_level['c'] != '')?
                    '?c='.$second_level['c'].'&a='.$second_level['a']:'#';
            ?>
              <li><a href="<?php echo $href; ?>"><?php echo $subname; ?></a></li>            
            <?php
            }
            ?>
          </ul>
        </li>
<?php        
    } else
    {        
        ?>
        <li><a href="<?php echo $href; ?>"><i class="fa fa-<?php echo $i; ?>"></i> <span><?php echo $name; ?></span></a></li>
<?php        
    }
}
?>
      </ul>
