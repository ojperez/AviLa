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
global $modules;
?>
<div class="row">
  <div class="col-md-12">
    <form id="user-form" method="post">   
        <div class="row">          
            <div class="col-md-4">Module</div>
            <div class="col-md-4">Path</div>
            <div class="col-md-4">Active</div>
        </div>
      <?php foreach($items as $name => $value)
            { ?>
        <div class="row">          
        
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label"><?php echo $name; ?></label>            
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">            
            <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo substr($value, strlen(_MODULES)); ?>" disabled>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">            
              <input type="checkbox" id="act_<?php echo $name; ?>" name="act_<?php echo $name; ?>" value="1"<?php if (in_array($name, $modules)) echo ' checked="checked"'; ?>/>
          </div>
        </div>
      </div>
            <?php } ?>
        <hr>
       
        <div class="row m-t-20">
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success" type="submit" name='_save_modules'>OK</button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>