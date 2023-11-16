
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3>Cobrar trámites</h3><br/>
            </div>
            <?php echo form_open('dafVentanilla/buscar'); ?>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Nro Tramite</label>
                    <input type="text" id="tramite" name="tramite" class="form-control mayuscula" value="<?php if (isset($tramite)) echo $tramite;?>" aria-describedby="emailHelp" placeholder="Nro Tramite">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Documento</label>
                    <input type="text" id="documento" name="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" aria-describedby="emailHelp" placeholder="Documento">
                  </div>
                </div> 
                    

                  
                <div class="col-md-12 text-center"><br/>
                	<button class="btn btn-primary" type="submit"><span class="oi oi-magnifying-glass"></span> Buscar</button>
                </div>
            <?php echo form_close(); ?>
            <br/>
            
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° de Tramite</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Tipo de tramite</th>
                        <th>Estado del trámite</th>
                        <th>Estado del pago</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($listado)): ?>
                        <?php foreach($listado as $index => $item): ?>
                            <tr>
                                <td><?php echo $item['id_tramite']; ?></td>
                                <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                                <td><?php echo $item['apellido'].' '.$item['nombre']; ?></td>
                                <td><?php echo $item['tipo_tramite'];?></td>
                                <td>
                                	<?php  if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                                		<span class="badge badge-info" style="font-size: 80%;">PENDIENTE DE VALIDACION</span>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO</span>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO_VERIFICADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO Y VERIFICADO</span>
                                	<?php }else if($item['estado']==TRAMITE_INVALIDADO) { ?>
                                		<span class="badge badge-danger" style="font-size: 80%;">INVALIDADO</span>
                                	<?php }else { 
                                       
                                        ?>
                                		NO ESPECIFICADO
                                	<?php } ?>	
                                </td>
                                <td id="col-estado-pago-<?php echo $item['id_tramite']; ?>">
                                	<?php echo $item['estado_pago']; ?>
                                </td>
                                <td data-title="Acción">
                                	<?php if($item['estado_pago'] != ESTADO_PAGO_PAGADO) { ?>
                                        <?php if($item['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL && $item['urgente'] == 1) {?>
                                            <a href="#" id="link-cobrar-<?php echo $item['id_tramite']; ?>" onclick="module_pago.mostrarFormPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $item['precio_planilla']; ?>')" class="btn btn-danger" style="padding: .315rem .25rem;">Cobrar</a>
                                    	    <a href="#" id="link-pago-<?php echo $item['id_tramite']; ?>" onclick="module_pago.verPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $item['precio_planilla']; ?>')" class="btn btn-danger" style="padding: .315rem .25rem; display: none;">Ver pago</a>
    
                                        <?php } else if ($item['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { 
                                                 $precio = $item['precio'] + $item['importe_adicional'];
                                                ?> 
                                            	<a href="#" id="link-cobrar-<?php echo $item['id_tramite']; ?>" onclick="module_pago.mostrarFormPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $precio; ?>')" class="btn btn-danger" style="padding: .315rem .25rem;">Cobrar</a>
                                            	<a href="#" id="link-pago-<?php echo $item['id_tramite']; ?>" onclick="module_pago.verPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $precio; ?>')" class="btn btn-danger" style="padding: .315rem .25rem; display: none;">Ver pago</a>
                                       <?php }else { ?>
                                       			<a href="#" id="link-cobrar-<?php echo $item['id_tramite']; ?>" onclick="module_pago.mostrarFormPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $precio; ?>')" class="btn btn-danger" style="padding: .315rem .25rem;">Cobrar</a>
                                            	<a href="#" id="link-pago-<?php echo $item['id_tramite']; ?>" onclick="module_pago.verPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $precio; ?>')" class="btn btn-danger" style="padding: .315rem .25rem; display: none;">Ver pago</a>
                                       <?php } ?>
                                   <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
<?= $this->include('dashboard/modales/modal_comisaria_pago.php') ?>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>