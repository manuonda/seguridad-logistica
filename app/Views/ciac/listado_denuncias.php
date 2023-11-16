
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3>Listado de pedidos de constancias de denuncias</h3><br/>
            </div>
            <?php echo form_open('ciacDenuncia/buscar'); ?>
                <div class="form-group row">
                	<div class="col-lg-3"></div>
                    <label class="col-lg-2 col-form-label form-control-label" style="text-align: right">Nro. de Documento *:</label>
                    <div class="col-lg-3">
                        <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="Ingrese el Nro. de Documento" spellcheck="false" />
                    </div>
                    <div class="col-lg-4"></div>
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
                        <th>#</th>
                        <th>Fecha alta</th>
                        <th>Cuil</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Estado del trámite</th>
                        <th>Estado del pago</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($listado)): ?>
                    
                    <?php count($listado); ?>
                    
                        <?php foreach($listado as $index => $item): ?>
                            <tr>
                                <td><?php echo $item['id_tramite']; ?></td>
                                <td><?php echo date_format(date_create($item['fecha_alta']), 'd/m/Y');; ?></td>
                                <td><?php echo $item['cuil']; ?></td>
                                <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                                <td><?php echo $item['apellido'].' '.$item['nombre']; ?></td>
                                <td>
                                	<?php if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                                		<span class="badge badge-info" style="font-size: 80%;">PENDIENTE DE VALIDACION</span>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO</span>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO_VERIFICADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO Y VERIFICADO</span>
                                	<?php }else if($item['estado']==TRAMITE_INVALIDADO) { ?>
                                		<span class="badge badge-danger" style="font-size: 80%;">INVALIDADO</span>
                                	<?php }else { ?>
                                		NO ESPECIFICADO
                                	<?php } ?>	
                                </td>
                                <td id="col-estado-pago-<?php echo $item['id_tramite']; ?>">
                                	<?php echo $item['estado_pago']; ?>
                                </td>
                                <td data-title="Acción">
                                	<?php if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                            	 		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/validar/<?php echo $item['id_tramite']; ?>/ciacDenuncia" class="btn btn-info" style="padding: .315rem .25rem;">Validar</a>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                                		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/ver/<?php echo $item['id_tramite']; ?>/ciacDenuncia" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
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