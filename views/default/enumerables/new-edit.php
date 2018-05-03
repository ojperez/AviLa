<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Lista de usuarios
 * @param object $user Objeto tipo User con la informacion del usuario a editar, o vacio para agregar usuario nuevo
 * @param int $role ID del rol del usuario a editar, o 0 para usuario nuevo
 * @param array $roles Lista de todos los roles del appClient
 */

?>
<?php require_once 'parts/_form_open.php';  ?>
<div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $item->name; ?>">        
      </div>
    </div>
<?php
    $columns=$item::getColumns();
    foreach($columns as $meta => $label)
    {       
        ?>
    <div class="col-md-6">
      <div class="form-group">
        <label class="control-label"><?php echo $label; ?></label>
        <input type="text" class="form-control" id="<?php echo $meta; ?>" name="<?php echo $meta; ?>" value="<?php echo $item->meta($meta); ?>">        
      </div>
    </div>

<?php
    }
    ?>  
</div>
<?php require_once 'parts/_form_close.php';  ?>       