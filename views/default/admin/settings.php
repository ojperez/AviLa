<?php
/**
 * @copyright Copyright (C) 2018, Global Tech Network
 * @package STX
 * @version 0.1a
 * @author OJ Perez <otto@globaltech.network> 
 */
/**
 * Lista de opciones
 * @param array $items Lista de opciones ['name'=>'value']
 */
?>
<div class="row">
  <div class="col-md-12">
    <form id="user-form" method="post">   
      <?php foreach($items as $name => $value)
            { ?>
        <div class="row">          
        
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label"><?php echo $name; ?></label>            
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">            
            <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
          </div>
        </div>        
      </div>
            <?php } ?>
        <hr>
        <h2>Nueva Opci&oacute;n</h2>
        <div class="row">          
        
        <div class="col-md-6">
          <div class="form-group">
              <label class="control-label">Nombre</label>
            <input type="text" class="form-control" id="new_name" name="new_name" value="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">            
              <label class="control-label">Valor</label>
            <input type="text" class="form-control" id="new_value" name="new_value" value="">
          </div>
        </div>        
      </div>
        <div class="row m-t-20">
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success" type="submit" name='_save_settings'>OK</button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>