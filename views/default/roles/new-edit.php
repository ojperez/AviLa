<?php
/**
 * @copyright Copyright (C) 2018, Global Tech Network
 * @package STX
 * @version 0.1a
 * @author OJ Perez <otto@globaltech.network> 
 */
/**
 * Lista de usuarios
 * @param object $role Objeto tipo User con la informacion del usuario a editar, o vacio para agregar usuario nuevo
 * @param int $role ID del rol del usuario a editar, o 0 para usuario nuevo
 * @param array $roles Lista de todos los roles del appClient
 */

?>
<div class="row">
  <div class="col-md-12">
    <form id="role-form" method="post">
      <input type="hidden" name="ID" value="<?php echo $role->ID; ?>">      
      <div class="row">          
        
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Rol</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $role->name; ?>">
          </div>
        </div>
        <div class="col-md-6">
            <?php
            $permissions = $role->getPermissionsById();
            $all_permissions = Role::getAllPermissionsGroupedById();
//            var_dump($all_permissions); die;
            foreach($all_permissions as $id => $perm)
            {
            ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="perm_<?php echo $id; ?>" name="perm[<?php echo $id; ?>]" value="1"<?php if (isset($permissions[$id])) echo ' checked="checked"'; ?>>
              <label class="form-check-label" for="defaultCheck1">
                <?php echo $perm['name']; ?>
              </label>
            </div>            
            <?php
                if (count($perm['children']) > 0)
                { 
                    foreach($perm['children'] as $childId => $perm_children)
                    {
                    ?>
            <div class="form-check" style="margin-left: 30px;">
              <input class="form-check-input" type="checkbox" value="" id="perm_<?php echo $childId; ?>" name="perm[<?php echo $childId; ?>]" value="1"<?php if (isset($permissions[$childId])) echo ' checked="checked"'; ?>>
              <label class="form-check-label" for="defaultCheck1">
                <?php echo $perm_children['name']; ?>
              </label>
            </div>     
                    <?php                                        
                    }
                }
            
            }
            ?>
        </div>
       
      </div>
     
        <div class="row m-t-20">
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success" type="submit">OK</button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>