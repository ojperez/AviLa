<?php

/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */

?>
<section class="content-header">
      <h1>
        <?php echo $page_title; ?>
        <small><?php echo $page_subtitle; ?></small>
      </h1>
<!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
<?php
    //Ver private/functions.php para ver funciones del sistema de alertas al usuario
    if (has_alerts())
    { ?> <section class="content-alerts"> <?php
        $alerts = get_alerts();
        foreach($alerts as $alertType => $alertMsgs)
        {
            foreach($alertMsgs as $alertMsg)
            {
                ?>
                <div class="alert alert-<?php echo $alertType; ?>" role="alert"><?php echo $alertMsg; ?></div>    
                <?php
            }
        }
        ?></section><?php
    }
    ?>