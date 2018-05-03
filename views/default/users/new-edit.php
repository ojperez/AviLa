<?php
/**
 * @copyright Copyright (C) 2018, Global Tech Network
 * @package STX
 * @version 0.1a
 * @author OJ Perez <otto@globaltech.network> 
 */
/**
 * Lista de usuarios
 * @param object $user Objeto tipo User con la informacion del usuario a editar, o vacio para agregar usuario nuevo
 * @param int $role ID del rol del usuario a editar, o 0 para usuario nuevo
 * @param array $roles Lista de todos los roles del appClient
 */
//var_dump($roles);
//var_dump(ifset($roles, intval($user->role), get_class($user)::_new())->ID);
?>
<div class="row">
  <div class="col-md-12">
    <form id="user-form" method="post">
      <input type="hidden" name="ID" value="<?php echo $user->ID; ?>">      
      <div class="row">          
        
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">E-mail</label>
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>">
          </div>
        </div>
           <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Nombre</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Apellido</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Rol</label>
             <select id="role" name="role" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
            <option value="0">Seleccione una opci&oacute;n</option>
            <?php
            echo getSelectOptions($roles, ifset($roles, intval($user->role), get_class($user)::_new())->ID );
            ?>
        </select>     
          </div>
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