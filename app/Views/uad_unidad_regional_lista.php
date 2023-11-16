
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3>Listar trámites</h3><br/>
            </div>

            <form action="<?php echo base_url().'/tramiteReba/buscar'; ?>" method="post" id="form">
            	<div class="form-group row">
            		<div class="col-lg-1 col-form-label"></div>
                    <label class="col-lg-1 col-form-label form-control-label">Fecha desde :</label>
                    <div class="col-lg-3">
                    	<input type="date" name="fechaDesde" id="fechaDesde" class="form-control mayuscula" value="<?php if (isset($fechaDesde)) echo $fechaDesde; ?>" placeholder="Fecha desde" spellcheck="false" />
                    </div>
                    <div class="col-lg-2 col-form-label"></div>
                    <label class="col-lg-1 col-form-label form-control-label">Fecha hasta :</label>
                    <div class="col-lg-3">
                        <input type="date" name="fechaHasta" id="fechaHasta" class="form-control mayuscula" value="<?php if (isset($fechaHasta)) echo $fechaHasta; ?>" placeholder="Fecha hasta" spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                	<div class="col-lg-1"></div>
                    <label class="col-lg-1 col-form-label form-control-label" style="text-align: left;">Nro. de Documento *:</label>
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

            <hr>
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° Orden de pago</th>
                        <th>Fecha alta</th>
                        <th>Cuil</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Tipo de tramite</th>
                        <th>Estado del trámite</th>
                        <th>Estado del pago</th>
                        <th>Forma de pago</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($listado)): ?>
                        <?php foreach($listado as $index => $item): ?>
                            <tr>
                                <!-- <td><?php //echo $index+1; ?></td>  -->
                                <td><?php echo $item['id_tramite']; ?></td>
                                <td><?php echo date_format(date_create($item['fecha_alta']), 'd/m/Y'); ?></td>
                                <td><?php echo $item['cuil']; ?></td>
                                <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                                <td><?php echo $item['apellido'].' '.$item['nombre']; ?></td>
                                <td><?php echo $item['tipo_tramite'].' '.$item['categoria_reba']; ?></td>
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
                                	<?php echo $item['estado_pago'];  ?> 
                                </td>
                                <td id="col-forma-pago-<?php echo $item['id_tramite']; ?>">
                                	<?php if($item['referencia_pago']==BANCO_MACRO) { ?>
                                		<span class="badge badge-primary" style="font-size: 80%;">MACRO CLIC</span>
                                	<?php }else if($item['referencia_pago']==COMISARIA_PAGO) { ?>
                                		<span class="badge badge-success" style="font-size: 80%;">PAGO EN EFECTIVO</span>
                                	<?php }else if($item['referencia_pago']==MERCADO_PAGO) { ?>
                                		<span class="badge badge-info" style="font-size: 80%;">MERCADO PAGO</span>
                                	<?php }else { ?>
<!--                                 		NO ESPECIFICADO -->
                                	<?php } ?>
                                </td>
                                <td data-title="Acción">
                                	<a href="<?php echo base_url(); ?>/tramiteReba/edit/<?php echo $item['id_tramite']; ?>" title="Ver"><span class="oi oi-document" style="color:#3380FF"></span></a>&nbsp;&nbsp;
                                	<?php if($item['estado_pago'] != ESTADO_PAGO_PAGADO) { ?>
                                		<a href="<?php echo base_url(); ?>/tramiteReba/getCuponesPago/<?php echo $item['id_tramite']; ?>" id="link-pago-efectivo-<?php echo $item['id_tramite']; ?>" target="_blank" title="Descargar Cupones de Pago"><span class="oi oi-print" style="color:blue"></span></a>&nbsp;&nbsp;
                                        <a href="#" id="link-cobrar-<?php echo $item['id_tramite']; ?>" onclick="module_pago.mostrarFormPagoEfectivoReba('<?php echo $item['id_tramite']; ?>', '<?php echo $item['estado_pago']; ?>', '<?php echo $item['tipo_tramite'].' '.$item['categoria_reba']; ?>', '<?php echo $item['suma']; ?>')" title="Registrar pago"><span class="oi oi-check"></span></a>&nbsp;&nbsp;
                                        <!--
                                        <a href="#" id="link-pago-<?php //echo $item['id_tramite']; ?>" onclick="module_pago.verPagoEfectivo('<?php //echo $item['id_tramite']; ?>', '<?php //echo $item['estado_pago']; ?>', '<?php //echo $item['tipo_tramite']; ?>', '<?php //echo $item['precio']; ?>')" title="Ver registro de pago" style="display: none;"><span class="oi oi-comment-square"></span></a>&nbsp;
                                        -->
                                        <!-- 
                                        <?php //if($item['estado_pago'] != ESTADO_PAGO_IMPAGO) { ?>
                                        	<button onclick="pagarBancoMacro(<?php //echo $item['id_tramite']; ?>)" title="Cobrar con tarjeta"><span class="oi oi-credit-card" style="color:blue"></span></button>
                                        <?php //} ?>
                                         -->
                                	<?php } ?>
                                	<?php if($item['estado_pago'] == ESTADO_PAGO_PAGADO) { ?>
                                		<a href="<?php echo base_url(); ?>/tramiteReba/getCuponPagoOnline/<?php echo $item['id_tramite']; ?>" id="link-pago-efectivo-<?php echo $item['id_tramite']; ?>" target="_blank" title="Descargar Cupon de Pago"><span class="oi oi-tablet" style="color:blue"></span></a>&nbsp;&nbsp;
                                		<?php if(!empty($item['contiene_firma_digital']) && $item['contiene_firma_digital']) { ?>
                                			<a href="<?php echo base_url(); ?>/tramite/imprimir/<?php echo $item['id_tramite']; ?>" class="btn btn-info" style="padding: .315rem .25rem;" target="_blank" title="Descargar Certificado Reba"><span class="oi oi-print" style="color:yellow"></span></a>&nbsp;&nbsp;
                                		<?php } ?>
                                		<a style="cursor:pointer" title="Subir Certificado Reba" onclick="module_util.mostrarModalFirmaDigital('<?php echo $item['id_tramite']; ?>', '<?php echo $item['controlador']; ?>')">
    										<span class="oi oi-data-transfer-upload" style="grey"></span>
    									</a>
                                	<?php } ?>

                                    
		                        <?php if($item['referencia_pago'] == BANCO_MACRO  && ($item['estado_pago'] === ESTADO_PAGO_PENDIENTE  || empty($item['estado_pago'])))  { ?>
			                      <a style="cursor:pointer" title="Sincronizar Pago" onclick="module_pago.mostrarPago('<?php echo $item['id_tramite']; ?> ')">
		       	               	    <span class="oi oi-loop-circular" style="color:red"></span>' .
				                  </a>
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
<?= $this->include('dashboard/modales/modal_pago_reba.php') ?>
<?= $this->include('dashboard/modales/modal_estado_pago.php') ?>

<form id="form-tramite-tabla">
<?= $this->include('dashboard/modales/modal_firmadigital.php') ?>
</form>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>
<?= $this->include('js/module_util.php') ?>

<script type="text/javascript">
var baseUrl = "<?php echo base_url(); ?>";

// function que realiza el pago por Banco Macro 
function pagarBancoMacro(idTramite){
$("#loading").show();
const baseUrl ='<?php echo base_url(); ?>';
const url_banco_macro ='<?php if(isset($urlBancoMacro)) echo $urlBancoMacro; ?>';
//const idTramite = document.getElementById('id_tramite').value;
//const isPersonaValidada = document.getElementById('isPersonaValidada2').value;
fetch(baseUrl + '/DescargarCertificado/pagoBancoMacro/' + idTramite + '/' + true + '/dashboard')
.then(response => response.json())
.then(data => {
    console.log(data);
    if ( data.status === "OK" ) {
        console.log("aqui ingreso");
        $("#loading").hide(); 

        let dataBancoMacro = data[0];
       
        // document.getElementById("CallbackSuccess").value = dataBancoMacro.call_back;
        // document.getElementById("CallbackCancel").value = dataBancoMacro.call_cancel;
        // document.getElementById("Comercio").value = dataBancoMacro.comercio;
        // document.getElementById("Hash").value = dataBancoMacro.hash_generate;
        // document.getElementById("TransaccionComercioId").value = dataBancoMacro.transaction_comercio_id;
        // document.getElementById("Monto").value = dataBancoMacro.monto;
        // document.getElementById("Producto").value = "dataBancoMacro.producto";
        // document.getElementById("ClientData.CUIT").value = dataBancoMacro.titular_cuit;
        // document.getElementById("ClientData.NombreApellido").value = dataBancoMacro.titular_nombre_apellido;
        
        
        let form  =  document.createElement("form");
        form.action = url_banco_macro;
        form.method ="POST";
        
        let callBackSuccess = document.createElement("input");
        callBackSuccess.name = "CallbackSuccess";
        callBackSuccess.value = dataBancoMacro.call_back;
        form.appendChild(callBackSuccess);


        
        let callbackCancell = document.createElement("input");
        callbackCancell.name = "CallbackCancel";
        callbackCancell.value = dataBancoMacro.call_cancel;
        form.appendChild(callbackCancell);

        console.log("3");
        let comercio  = document.createElement("input");
        comercio.name = "Comercio";
        comercio.value = dataBancoMacro.comercio;
        form.appendChild(comercio);

        console.log("4");
        let hash =  document.createElement("input");
        hash.name = "Hash";
        hash.value =  dataBancoMacro.hash_generate;
        form.appendChild(hash);

        let transaccionComercioId = document.createElement("input");
        transaccionComercioId.name = "TransaccionComercioId";
        transaccionComercioId.value =  dataBancoMacro.transaction_comercio_id;
        form.appendChild(transaccionComercioId);


        let monto = document.createElement("input");
        monto.name="Monto";
        monto.value = dataBancoMacro.monto;
        form.appendChild(monto);

        console.log(dataBancoMacro.productos);
        if ( dataBancoMacro.productos && dataBancoMacro.productos.length > 0 ) {
            for( let i = 0; i < dataBancoMacro.productos.length ; i++) {
                let producto = document.createElement("input");
                     producto.name =  "Producto["+i+"]";
                     producto.value = dataBancoMacro.productos[i];
                     // producto.value = dataBancoMacro.producto;
                     form.appendChild(producto);
              
            }
        }
       
   

        let clienteCuit = document.createElement("input");
        clienteCuit.name = "ClientData.CUIT";
        clienteCuit.value = dataBancoMacro.titular_cuit;
        form.appendChild(clienteCuit); 


        let nombreApellido    = document.createElement("input");
        nombreApellido.name   = "ClientData.NombreApellido";
        nombreApellido.value  = dataBancoMacro.titular_nombre_apellido;
        form.appendChild(nombreApellido);

        let sucursalComercio   = document.createElement("input");
        sucursalComercio.name  = "SucursalComercio";
        sucursalComercio.value = dataBancoMacro.sucursal_comercio;
        form.appendChild(sucursalComercio);

        //console.log(form);

        document.body.appendChild(form);
        
        form.submit();
        // window.location.href = data.link;
    } else {
            $("#loading").hide();
            alert('Error Al al Realizar Pago por Banco Macro, intente de nuevo');
        }
})
.catch(error => {
        $("#loading").hide();
});
}


</script>