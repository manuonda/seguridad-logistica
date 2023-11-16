
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Búsqueda de Personas</b></h3><br/>
            </div>
            <?php echo form_open('dap/buscarTramitePersona'); ?>
               

                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fecha Desde</label>
                    <input type="date" id="fechaDesde" name="fechaDesde" class="form-control mayuscula" value="<?php if (isset($fechaDesde)) echo $fechaDesde;?>" aria-describedby="emailHelp" placeholder="Fecha Desde">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fecha Hasta</label>
                    <input type="date" id="fechaHasta" name="fechaHasta" class="form-control mayuscula" value="<?php if (isset($fechaHasta)) echo $fechaHasta; ?>" aria-describedby="emailHelp" placeholder="Fecha Hasta">
                  </div>
                </div> 

                  <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control mayuscula" value="<?php if (isset($nombre)) echo $nombre;?>" aria-describedby="emailHelp" placeholder="Nombre">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control mayuscula" value="<?php if (isset($apellido)) echo $apellido; ?>" aria-describedby="emailHelp" placeholder="Apellido">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Documento</label>
                    <input type="number" id="documento" name="documento" class="form-control" value="<?php if (isset($documento)) echo $documento; ?>" aria-describedby="emailHelp" placeholder="Documento">
                  </div>
                  
                </div> 

                  <div class="row">
                 
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Nro. Tramite</label>
                    <input type="number" name="numeroTramite" id="numeroTramite" class="form-control mayuscula" value="<?php if (isset($numeroTramite)) echo $numeroTramite; ?>" placeholder="Numero Tramite" spellcheck="false" />
                  </div>
               
                 
                    <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Dependencia:</label>
                    <select name="idDependencia" id="idDependencia" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($dependencias as $item) : ?>
                        <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($id_dependencia) && $id_dependencia == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Tipo Planilla:</label>
                    <select name="tipoPlanilla" id="tipoPlanilla" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($tipo_planillas as $item) : ?>
                        <option value="<?php echo $item ?>" <?php if (isset($tipoPlanilla) && $tipoPlanilla == $item) echo 'selected="selected"'; ?>>
                        <?php if($item == 'RENOVACION')  echo "Renovación" ; ?>
                        <?php if($item == 'PRIMERA_VEZ')  echo "Primera Vez" ; ?>
                         
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>


                </div>

                <div class="col-md-12 text-center"><br/>
                	<button class="btn btn-primary" type="submit"><span class="oi oi-magnifying-glass"></span> Buscar</button>
                    <button class="btn btn-secondary" type="button" onclick="resetForm()"><span class=""></span> Limpiar</button>
                </div>
 
            <?php echo form_close(); ?>
            <br/>
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° trámite</th>
                        <th>Fecha de solicitud</th>
                        <th>Dependencia</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Nro. Prontuario</th>
                        <th>Fecha Nacimiento</th>
                        <th>Tipo de trámite</th>
                        <th>Estado del trámite</th>
                        <th>Estado del pago</th>
                        <th>Referencia del pago</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($listado as $index => $item): ?>
                        <tr  style="<?php if($item['urgente'] ==  INT_UNO) echo "background-color:#F6CED8"; ?>" >                            
                            <td style="<?php if($item['urgente'] ==  INT_UNO) echo "background-color:#F6CED8"; ?>"><?php echo $item['id_tramite']; ?></td>
                            <td><?php echo date_format(date_create($item['fecha_alta']), 'd/m/Y H:i'); ?></td>
                            <td><?php echo $item['dependencia']; ?></td>
                            <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                            <td><?php echo $item['apellido'].', '.$item['nombre']; ?></td>
                            <td>
                            	<?php if(!empty($item['num_prontuario']) && !empty($item['letra_prontuario'])) { echo $item['num_prontuario'].'-'.$item['letra_prontuario']; } ?>
                            </td>
                            <td><?php echo date_format(date_create($item['fecha_nacimiento']), 'd/m/Y'); ?></td>
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
                            	<?php if(empty($item['estado_verificacion']) || $item['estado_verificacion']==TRAMITE_PENDIENTE_VERIFICACION) { ?>
                            	 	<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/dapBuscarPersona" class="btn btn-primary" style="padding: .315rem .25rem;">Verificar</a>
                            	<?php }else { ?>
                            		<a href="<?php echo base_url(); ?>/<?php echo $item['controlador']; ?>/verificar/<?php echo $item['id_tramite']; ?>/dapBuscarPersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>
                            		<a target="_blank" href="<?php echo base_url(); ?>/planillaProntuarial/getDocumentoPlanillaProntuarial/<?php echo  $item['id_tramite'];?>" style="cursor:pointer" title="Ver Planilla">
    			 						<span class="oi oi-print" style="color:blue"></span>
    			 					</a>
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
<script type="text/javascript">
    function resetForm(){
        document.getElementById("nombre").value = "";
        document.getElementById("apellido").value="";
        document.getElementById("documento").value =0;
        document.getElementById("numeroTramite").value = "";
        document.getElementById("idDependencia").value ="";
        document.getElementById("tipoPlanilla").value="";
        document.getElementById("fechaDesde").value = new Date();
        document.getElementById("fechaHasta").value = new Date();
    }
</script>