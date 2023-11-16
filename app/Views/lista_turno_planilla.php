
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Atención de turnos Planilla Prontuarial</b></h3><br/>
            </div>
            <?php echo form_open('turnoPlanillaProntuarial/buscar'); ?>
                <div class="form-group row">
                	<div class="col-lg-4"></div>
                    <label class="col-lg-2 col-form-label form-control-label">Fecha turno *:</label>
                    <div class="col-lg-2">
                        <input type="date" name="fecha_turno" id="fecha_turno" class="form-control mayuscula" value="<?php if (isset($fecha_turno)) echo $fecha_turno; ?>" placeholder="Fecha turno" spellcheck="false" required/>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="col-md-12 text-center"><br/>
                	<button class="btn btn-primary" type="submit"><span class="oi oi-magnifying-glass"></span> Buscar</button>
                    <a  class="btn btn-primary" href="<?php echo base_url().'/planillaProntuarial/nuevaPlanilla/dashboard' ;?>"><span class="oi oi-document"></span> Crear</a>
                </div>
                 
            <?php echo form_close(); ?>
            <br/>
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° trámite</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Nro. Prontuario</th>
                        <th>Tipo de trámite</th>
                        <th>Estado del trámite</th>
                        <th>Estado del pago</th>
                        <th>Referencia del pago</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($listado as $index => $item): ?>
                        <tr>
                            <td><?php echo $item['id_tramite']; ?></td>
                            <td><?php echo date_format(date_create($item['fecha']), 'd/m/Y'); ?></td>
                            <td><?php echo $item['hora']; ?></td>
                            <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                            <td><?php echo $item['apellido'].' '.$item['nombre']; ?></td>
                            <td>
                            	<?php if(!empty($item['num_prontuario']) && !empty($item['letra_prontuario'])) { echo $item['num_prontuario'].'-'.$item['letra_prontuario']; } ?>
                            </td>
                            <td><?php echo $item['tipo_tramite'].' - '.$item['tipo_planilla']; ?></td>
                            <td>
                            	<?php
                            	if(empty($item['estado'])) {
                            	    $estado = '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VALIDACION.'</h8></span><br />';
                            	}else if($item['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
                            	    $estado = '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VALIDACION.'</h8></span><br />';
                            	}else if($item['estado'] == TRAMITE_VALIDADO) {
                            	    $estado = '<span class="badge badge-success"><h8>'.TRAMITE_VALIDADO.'</h8></span><br />';
                            	}else {
                            	    $estado = $item['estado'];
                            	}
                            	
                            	if(empty($item['estado_verificacion'])) {
                            	    $estado .= '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VERIFICACION.'</h8></span>';
                            	}else if($item['estado_verificacion']==TRAMITE_PENDIENTE_VERIFICACION) {
                            	    $estado .= '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VERIFICACION.'</h8></span>';
                            	}else if($item['estado_verificacion']==TRAMITE_VERIFICADO) {
                            	    $estado .= '<span class="badge badge-success"><h8>'.TRAMITE_VERIFICADO.'</h8></span>';
                            	}else if($item['estado_verificacion']==TRAMITE_VERIFICADO_CON_OBSERVACION) {
                            	    $estado .= '<span class="badge badge-info"><h8>'.TRAMITE_VERIFICADO_CON_OBSERVACION.'</h8></span>';
                            	}else if($item['estado_verificacion']==TRAMITE_VERIFICADO_CON_INFORME) {
                            	    $estado .= '<span class="badge badge-info"><h8>'.TRAMITE_VERIFICADO_CON_INFORME.'</h8></span>';
                            	}else {
                            	    $estado .= $item['estado_verificacion'];
                            	}
                            	
                            	echo $estado;
                            	?>
                            </td>
                            <td id="col-estado-pago-<?php echo $item['id_tramite']; ?>">
                            	<?php echo $item['estado_pago']; ?>
                            </td>
                            <td>
                            	<?php if($item['referencia_pago']==BANCO_MACRO) { ?>
                            		<span class="badge badge-primary" style="font-size: 80%;">MACRO CLICK</span>
                            	<?php }else if($item['referencia_pago']==COMISARIA_PAGO) { ?>
                            		<span class="badge badge-danger" style="font-size: 80%;">PAGO EN EFECTIVO</span>
                            	<?php }else if($item['referencia_pago']==MERCADO_PAGO) { ?>
                            		<span class="badge badge-info" style="font-size: 80%;">MERCADO PAGO</span>
                            	<?php }else { ?>
                            		NO ESPECIFICADO
                            	<?php } ?>
                            </td>
                            <td data-title="Acción">                            	 
                            	<?php if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                            	 	&nbsp;&nbsp;<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/validar/<?php echo $item['id_tramite']; ?>/turnoPlanillaProntuarial" title="Validar"><span class="oi oi-document" style="color:#3380FF"></span></a>
                            	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                            		&nbsp;&nbsp;<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/ver/<?php echo $item['id_tramite']; ?>/turnoPlanillaProntuarial" title="Ver"><span class="oi oi-document" style="color:#3380FF"></span></a>
                            	<?php }else { ?>
                            		&nbsp;&nbsp;<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/validar/<?php echo $item['id_tramite']; ?>/turnoPlanillaProntuarial" title="Validar"><span class="oi oi-document" style="color:#3380FF"></span></a>
                            	<?php } ?>
                            	
                            	&nbsp;&nbsp;<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/turnoPlanillaProntuarial" title="ver antecedentes"><span class="oi oi-clipboard" style="color: red"></span></a>

                            	<?php if($item['estado_pago'] != ESTADO_PAGO_PAGADO) { ?>
                            		&nbsp;&nbsp;
                                    <a href="#" style="cursor:pointer" onclick="module_pago.mostrarFormPagoEfectivoPlanillaProntuarial('<?php echo $item['id_tramite'] ?>','<?php echo $item['estado_pago']; ?>','<?php echo  $item['tipo_tramite'];?>', '<?php echo $item['precio'];?>')" title="Registrar pago">
					                  <span class="oi oi-check"></span>
					                </a> 
                            	<?php } ?>
                            	<?php if($item['contiene_firma_digital']) { ?>
                            		&nbsp;&nbsp;<a href="<?php echo base_url(); ?>/tramite/imprimir/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>

                                <?php if ($item['referencia_pago'] == BANCO_MACRO  && ($item['estado_pago'] === ESTADO_PAGO_PENDIENTE  || empty($item['estado_pago']))) { ?>
                                	  &nbsp;&nbsp;
		                    	      <a style="cursor:pointer" title="Sincronizar Pago" onclick="module_pago.mostrarPago(<?php echo $item['id_tramite'] ;?>)">
				                      	<span class="oi oi-loop-circular" style="color:red"></span>
				                      </a>
                                <?php } ?>
                                &nbsp;&nbsp;
                                <a target="_blank" href="<?php echo base_url(); ?>/planillaProntuarial/getDocumentoPlanillaProntuarial/<?php echo  $item['id_tramite'];?>" style="cursor:pointer" title="Ver Planilla">
			 						<span class="oi oi-print" style="color:blue"></span>
			 					</a>
			 					&nbsp;&nbsp;
			 					<a target="_blank" href="<?php echo base_url(); ?>/planillaProntuarial/getConstanciaPlanillaProntuarial/<?php echo  $item['id_tramite'];?>" style="cursor:pointer" title="Cupon de pago">
			 						<span class="oi oi-data-transfer-download" style="color:green"></span>
			 					</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

<?= $this->include('dashboard/modales/modal_pago_planilla_prontuarial.php')  ?>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>