
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Búsqueda de trámites</b></h3><br/>
            </div>
            <?php echo form_open('buscarTramitePersona/buscar'); ?>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label form-control-label">Fecha desde :</label>
                    <div class="col-lg-3">
                    	<input type="date" name="fechaDesde" id="fechaDesde" class="form-control mayuscula" value="<?php if (isset($fechaDesde)) echo $fechaDesde; ?>" placeholder="Fecha desde" spellcheck="false" />
                    </div>
                    <div class="col-lg-2 col-form-label"></div>
                    <label class="col-lg-2 col-form-label form-control-label">Fecha hasta :</label>
                    <div class="col-lg-3">
                        <input type="date" name="fechaHasta" id="fechaHasta" class="form-control mayuscula" value="<?php if (isset($fechaHasta)) echo $fechaHasta; ?>" placeholder="Fecha hasta" spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label form-control-label">Nro. Documento :</label>
                    <div class="col-lg-3">
                        <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" spellcheck="false" />
                    </div>
                </div>
                <div class="col-md-12 text-center"><br/>
                	<button class="btn btn-primary" type="submit"><span class="oi oi-magnifying-glass"></span> Buscar</button>
                </div>
                <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <!-- <button type="button" class="btn btn-primary" onClick="descargar();">Descargar Archivos Digitales</button>
                    <button type="button" class="btn btn-primary" onClick="module_fileupload_multiple.mostrarModalFirmaDigitalMultiple();">Subir Archivos Digitales</button> -->
                    <a href="<?php echo base_url() . '/dashboard/listado_verificacion_domicilio_comisaria' ?>" class="btn btn-primary">Listado de tramites para verificacion de domicilio</a>
                  </div>
            <?php echo form_close(); ?>
            <br/>
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° trámite</th>
                        <th>Fecha de solicitud</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
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
                            <td><?php echo date_format(date_create($item['fecha_alta']), 'd/m/Y H:i'); ?></td>
                            <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                            <td><?php echo $item['apellido'].', '.$item['nombre']; ?></td>
                            <td><?php echo $item['tipo_tramite']; ?></td>
                            <td>
                                <?php if($item['id_tipo_tramite']==TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                                	<?php
                                	   $estado = "";
                                	   if(empty($item['estado'])) {
                                	       $estado .= TRAMITE_PENDIENTE_VALIDACION;
                                	   }else {
                                	       $estado .= $item['estado'];
                                	   }
    
                                	   $estado .= ' - ';
                                	   if(empty($item['estado_verificacion'])) {
                                	       $estado .= TRAMITE_PENDIENTE_VERIFICACION;
                                	   }else {
                                	       $estado .= $item['estado_verificacion'];
                                	   }
                                	   echo $estado;
                                	?>
                                <?php } else { ?>
                                	<?php if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                                		<span class="badge badge-info" style="font-size: 80%;">PENDIENTE DE VALIDACION</span>
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO</span>
                                	<?php }else if($item['estado']==TRAMITE_NO_VERIFICADO) { ?>
                                		<span class="badge badge-danger" style="font-size: 80%;">NO VERIFICADO</span>	
                                	<?php }else if($item['estado']==TRAMITE_VALIDADO_VERIFICADO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">VALIDADO Y VERIFICADO</span>
                                	<?php }else if($item['estado']==TRAMITE_INVALIDADO) { ?>
                                		<span class="badge badge-danger" style="font-size: 80%;">INVALIDADO</span>
                                	<?php }else { ?>
                                		NO ESPECIFICADO
                                	<?php } ?>
                                <?php } ?>
                            </td>
                            <td id="col-estado-pago-<?php echo $item['id_tramite']; ?>">
                            	<?php 
                            	   if(empty($item['estado_pago'])) {
                            	       echo ESTADO_PAGO_PENDIENTE; 
                            	   }else {
                            	       echo $item['estado_pago'];
                            	   }
                            	?>
                            </td>
                            <td>
                            	<?php if($item['referencia_pago']==BANCO_MACRO) { ?>
                            		<span class="badge badge-primary" style="font-size: 80%;">BANCO MACRO</span>
                            	<?php }else if(date_format(date_create($item['fecha_alta']), 'm/d/Y') == FECHA_VOTACION) { ?>
                            		<span class="badge badge-info" style="font-size: 80%;">GRATIS</span>
                            	<?php }else if($item['referencia_pago']==MERCADO_PAGO) { ?>
                            		<span class="badge badge-info" style="font-size: 80%;">MERCADO PAGO</span>
								<?php }else if($item['referencia_pago']==COMISARIA_PAGO) { ?>
                            		<span class="badge badge-danger" style="font-size: 80%;">PAGO EN COMISARIA</span>
                            	
								<?php }else { ?>
                            		<span class="badge badge-danger" style="font-size: 80%;">PAGO EN COMISARIA</span>
                            	<?php } ?>
                            </td>
                            <td data-title="Acción">
                            	<?php if($item['estado']==TRAMITE_PENDIENTE_VALIDACION) { ?>
                            	 	<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/validar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Validar</a>
                            	<?php }else if($item['estado']==TRAMITE_VALIDADO) { ?>
                            		<?php if($util->isTramiteOnline($item['id_tipo_tramite'])) { ?>
                                		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/ver/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
                                		<?php if($item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA || $item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA || $item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
                                			<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-primary" style="padding: .315rem .25rem;">Verificar</a>
                                		<?php } ?>
                            		<?php }else { ?>
                            			<a href="<?php echo base_url(); ?>/tramite/editar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
                            		<?php } ?>
                            	<?php }else if($item['estado']==TRAMITE_NO_VERIFICADO) { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/ver/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
                            		<?php if($item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA || $item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA || $item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
                            			<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-primary" style="padding: .315rem .25rem;">Verificar</a>
                            		<?php } ?>
                            	<?php }else if($item['estado']==TRAMITE_VALIDADO_VERIFICADO) { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/ver/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
                            	<?php }else { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/validar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Validar</a>
                            	<?php } ?>
                            	
                            	<?php if($item['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
									&nbsp;<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/buscarTramitePersona" title="ver antecedentes"><span class="oi oi-clipboard" style="color: red"></span></a>&nbsp;
								<?php } ?>
								
                            	<?php if($item['estado_pago'] != ESTADO_PAGO_PAGADO) { ?>
                            		<a href="#" id="link-cobrar-<?php echo $item['id_tramite']; ?>" onclick="module_pago.mostrarFormPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php if(empty($item['estado_pago'])) { echo ESTADO_PAGO_PENDIENTE; }else { echo $item['estado_pago']; } ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $item['precio']; ?>')" class="btn btn-danger" style="padding: .315rem .25rem;">Cobrar</a>
                            		<a href="#" id="link-pago-<?php echo $item['id_tramite']; ?>" onclick="module_pago.verPagoEfectivo('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite']; ?>', '<?php echo $item['precio']; ?>')" class="btn btn-danger" style="display: none; padding: .315rem .25rem;">Ver pago</a>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/descargar/<?php echo $item['id_tramite']; ?>" id="link-descargar-<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="display: none; padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>
                            	<?php if($item['estado_pago'] == ESTADO_PAGO_PAGADO && $item['id_tipo_tramite']==TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/descargar/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>
                            	<?php if($item['contiene_firma_digital'] && $item['id_tipo_tramite'] != TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) { ?>
                            		<a href="<?php echo base_url(); ?>/tramite/imprimir/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>

								<?php if($item['contiene_firma_digital'] && $item['id_tipo_tramite'] != TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) { ?>
                            		<a href="<?php echo base_url(); ?>/tramite/imprimir/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>

                            	<!-- 
                            	<?php //if(!$util->isTramiteOnline($item['id_tipo_tramite'])) { ?>
                            		<a href="<?php //echo base_url(); ?>/tramite/descargar/<?php //echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php //} ?>
                                 -->    
                                <?php if ($item['referencia_pago'] == BANCO_MACRO  && ($item['estado_pago'] === ESTADO_PAGO_PENDIENTE  || empty($item['estado_pago']))) { ?>
		                    	      <a style="cursor:pointer" title="Sincronizar Pago" onclick="module_pago.mostrarPago(<?php echo $item['id_tramite'] ;?>)">
				                      <span class="oi oi-loop-circular" style="color:red"></span>
				                      </a>
                                <?php } ?>
                                <?php if($item['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL && $item['estado_pago'] != ESTADO_PAGO_PAGADO) { ?>
                                	<?php if($userInSession['id_dependencia'] != ID_DEP_UAD_LA_QUIACA_UR5) { ?>
        			    		          <a href="#" style="cursor:pointer" onclick="module_pago.mostrarFormPagoEfectivoPlanillaProntuarial(<?php echo $item['id_tramite'].',"PENDIENTE","Planilla",'.$item['precio']; ?>)">
        					              	<span class="oi oi-check"></span>
        					              </a>
    					             <?php } ?> 
                                <?php } ?>
                                
                                <?php if($item['id_tipo_tramite']==TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE && $item['estado_pago'] == ESTADO_PAGO_PAGADO && $item['estado']==TRAMITE_VALIDADO) { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/descargar/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	<?php } ?>

								
								<?php if($item['id_tipo_tramite']==TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION &&  $item['estado']==TRAMITE_VALIDADO) { ?>
                            		<?php if(date_format(date_create($item['fecha_alta']), 'm/d/Y') == FECHA_VOTACION || $item['estado_pago'] == ESTADO_PAGO_PAGADO) { ?>
									  <a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/descargar/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Imprimir"><span class="oi oi-print" style="color:blue"></span></a>
                            	     
									<?php } ?>
				                
								<?php } ?>
				 
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
<?= $this->include('dashboard/modales/modal_comisaria_pago.php') ?>
<?= $this->include('dashboard/modales/modal_estado_pago.php')  ?>
<?= $this->include('dashboard/modales/modal_pago_planilla_prontuarial.php')  ?>




<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>