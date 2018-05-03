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
 * @param array $users Array conteniendo objetos tipo User
 */
?>
<div class="card card-inverse card-flat p-20" style="min-height:50vh;">
<div class="row m-b-20">
    <div class="col-sm-12">
        <a href="?c=users&a=_new" class="btn btn-success">Nuevo Usuario</a>
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
								<th>E-Mail</th>
								<th>Nombre</th>
															
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
                                                <?php
                                                if (count($users)>0)
                                                {
                                                ?>
						<tbody>
                                                    <?php
                                                    foreach($users as $user)
                                                    {
                                                    ?>
							<tr>
								
								<td><?php echo $user->email; ?></td>
                                                                <td><?php echo $user->name; ?></td>                                                                
								<td class="text-center">
                                                                     <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=edit&id=<?php echo $user->ID; ?>" class=""><i class="fa fa-fw fa-edit"></i></a>
                                                                    <a href="?c=<?php echo $_GET[_CONTROLER_PARAM]; ?>&a=delete&id=<?php echo $user->ID; ?>" class=""><i class="fa fa-fw fa-trash"></i></a>		
								</td>
							</tr>
                                                 <?php 
                                                    } ?>
							
						</tbody>
                                                <?php
                                                } ?>
					</table>
				</div>