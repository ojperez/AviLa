<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package AviLa
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com> 
 */
/**
 * Lista de enumerables (Vista por defecto)
 * @param string $tableName Nombre de la tabla
 * @param array $list Array conteniendo objetos tipo User
 */
?>
<div class="card card-inverse card-flat p-20 list-enumerable-crud" style="min-height:50vh;">
	<div class="row m-b-20">
	    <div class="col-sm-12">
	        <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=_new" class="btn btn-success"><?php echo isset($newButton)?$newButton:'Nuevo Elemento'; ?></a>
                <button class="btn btn-info" type="button" onclick="window.history.back();">Volver</button>
	    </div>
	</div>
	<div class="card-header">
		<div class="card-title"><?php echo $tableName; ?></div>
	</div>
	<table class="table datatable table-striped">
		<thead>
			<tr>
                            <th>Nombre</th>
                            <?php
                            $columns=$model::getColumns();
                            foreach($columns as $meta => $label)
                                echo '<th>'.$label.'</th>'.PHP_EOL;
                            ?>                            
                            <th class="text-center">Acciones</th>
			</tr>
		</thead>
		    <?php
		    if (count($list)>0)
		    {
		    ?>
		<tbody>
			<?php
			foreach($list as $item)
			{
			?>
				<tr>
					<td><?php echo $item->name; ?></td>  
                                        <?php
                                        $columns=$item::getColumns();
                                        foreach($columns as $meta => $label)
                                            echo '<td>'.@$item->meta($meta).'</td>'.PHP_EOL;
                                        ?>  
					<td class="text-center">
                                            <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=edit&id=<?php echo $item->ID; ?>" class=""><i class="fa fa-fw fa-edit"></i></a>
                                            <?php 
                                            if (user_can('eliminar_'.get_class($item)::$type))
                                            {
                                            ?>
                                            <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=delete&id=<?php echo $item->ID; ?>" class=""><i class="fa fa-fw fa-trash"></i></a>								
                                            <?php } ?>
					</td>
				</tr>
	        <?php 
	        } 
	        ?>
		</tbody>
	    <?php
	    } ?>
	</table>
</div>