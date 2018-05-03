<?php
/**
 * @copyright Copyright (C) 2018, Global Tech Network
 * @package STX
 * @version 0.1a
 * @author OJ Perez <otto@globaltech.network> 
 */
/**
 * Lista de usuarios
 * @param string $tableName Nombre de la tabla
 * @param array $roles Array conteniendo objetos tipo User
 */
?>
<div class="card card-inverse card-flat p-20" style="min-height:50vh;">
<div class="row m-b-20">
    <div class="col-sm-12">
        <a href="?c=roles&a=_new" class="btn btn-success">Nuevo Rol</a>
    </div>
</div>


					<div class="card-header">
						<div class="card-title"><?php echo $tableName; ?></div>
					</div>
					<div class="card-block no-pb">
						<p></p>
					</div>
					<table class="table datatable table-striped">
						<thead>
							<tr>								
								<th>Rol</th>
						
															
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
                                                <?php
                                                if (count($roles)>0)
                                                {
                                                ?>
						<tbody>
                                                    <?php
                                                    foreach($roles as $role)
                                                    {
                                                    ?>
							<tr>
								
                                                                <td><?php echo $role->name; ?></td>                                                                						
								<td class="text-center">
                                                                     <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=edit&id=<?php echo $role->ID; ?>" class=""><i class="fa fa-fw fa-edit"></i></a>
                                                                    <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=delete&id=<?php echo $role->ID; ?>" class=""><i class="fa fa-fw fa-trash"></i></a>		
								</td>
							</tr>
                                                 <?php 
                                                    } ?>
							
						</tbody>
                                                <?php
                                                } ?>
					</table>
				</div>