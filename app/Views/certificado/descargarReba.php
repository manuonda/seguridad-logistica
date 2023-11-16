<div class="container" style="padding-top: 70px">
<!--     <div class="col-md-10 offset-md-1"> -->
    <div class="col-md-12">
        <!-- form user info -->
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>BUSCAR TRAMITE</b></h5>
            </div>
           <form action="<?php echo base_url().'/descargarCertificadoReba/verificar'; ?>" method="post" id="form">            
            <div class="card-body">
             <?php if ( isset($status) && $status == 'ERROR') { ?>
              <div class="alert alert-danger alert-dismissible">
                   <h6><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Información </h6>
                   <ul id="list_message_error">
                    <li> <?php echo $message; ?></li>
                   </ul>
               </div>
              <?php }?>
                    
                    <?= \Config\Services::validation()->listErrors('my_errors'); ?>
					<?php if (isset($error) and !empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                	<?php endif; ?>
            		<div class="form-group row">
                    	<label class="col-lg-12 control-label"><b>Datos personales</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                        <div class="col-lg-9">
                            <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach ($tipoDocumentos as $item) : ?>
                                    <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($id_tipo_documento) && $id_tipo_documento == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
            		<div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">N° de documento *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento" id="documento" class="form-control mayuscula" 
                             value="<?php if(isset($documento)) echo $documento; ?>" 
                             placeholder="Ingrese el nro. de documento" required spellcheck="false" />
                        </div>
                      </div>
                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">N° de orden de pago *:</label>
                       <div class="col-lg-9">
                            <input type="text" name="id_tramite" id="id_tramite" class="form-control mayuscula" 
                             value="<?php if(isset($id_tramite)) echo $id_tramite; ?>" 
                             placeholder="Ingrese el Numero de Orden de pago"  spellcheck="false" />
                        </div>
                   </div>                   
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                    <div class="form-group row">
                        <div class="col-lg-12 text-center">
                        	<a  href="<?php echo base_url();?>" class="btn btn-primary"><span class="oi oi-home"></span> Volver</a>
                            <button class="btn btn-primary" type="button" id="btnReiniciar" onclick="enviar()"><span class="oi oi-reload"></span> Buscar</button>
                      </div>
                    </div>

                    <?php if( isset($tramites)) {
                        ?>
                       <table class="table dataTable" id="tabla">
                            <thead>
                               <tr>
                                   <td>Nro. Orden de pago</td>
                                   <td>Tipo trámite</td>
                                   <td>Fecha de solicitud</td>
                                   <td>Estado del trámite</td>
                                   <td>Estado del pago</td>   
                                   <td>Operación </td>
                                </tr>
                            </thead>
                           <tbody>
                           <?php foreach($tramites as $tramite){
                                echo '<tr>';
                                echo '<td>'.$tramite['id_tramite'].'</td>';
                                echo '<td>'.$tramite['tipoTramite'].'</td>';
                                echo '<td>'.$tramite['fecha_alta'].'</td>';
                                echo '<td>'.$tramite['estado_aprobado_label'].
                                            $tramite['estado_aprobado_message'].'</td>';
                                echo '<td>'.$tramite['estado_pago_label'].
                                            $tramite['estado_pago_message'].'</td>';            
                                echo '<td>'.$tramite['action'].'</td>';
                                echo  '</tr>';   
                             } ?>
                           </tbody> 
                       </table> 
                     <?php    

                     } else if(isset($cuil) && !isset($tramites)) {
                         echo "<span> No Hay Trámites Realizados</span>";
                     }   
                    ?>
            </div>
            <input type="hidden" name="porque_motivo" id="porque_motivo" value="<?php if (isset($porque_motivo)) echo $porque_motivo; ?>">
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Formulario de pago de Banco Maro -->
<form method="post" id="formBancoMacro" action="https://sandboxpp.asjservicios.com.ar"/>
<input type="hidden" id="CallbackSuccess" name="CallbackSuccess" value=""/> 
<input type="hidden" id="CallbackCancel" name="CallbackCancel"  value=""/> 
<input type="hidden" id="Comercio" name="Comercio" value="" />
<input type="hidden" id="SucursalComercio" name="SucursalComercio" value=""/>
<input type="hidden" id="Hash" name="Hash" value="" />
<input type="hidden" id="TransaccionComercioId" name="TransaccionComercioId" value="" />
<input type="hidden" id="Monto" name="Monto" value=""/>
<input type="hidden" id="Producto"  name="Producto[0]" value="" />
<!-- opcional -->
<input type="hidden" name= "Informacion" value= "vhOBWNrIATF5r3Td5++2iEPPyoTVO12AZTF2hqC4KRY=" />
<!-- opcional -->
<input type="hidden"" id="ClientData.CUIT" name="ClientData.CUIT" value="" />
<!--opcional -->
<input type="hidden"" id="ClientData.NombreApellido" name="ClientData.NombreApellido" value="" />
</form>

<script>
function limpiar() {
    $("#numero_tramite").val("");
    $("#tipo_documento").val("");
    $("#documento").val("");
}

function validar() {
    var id_tipo_documento = $("#id_tipo_documento").val().trim();
    if (id_tipo_documento == '') {
        showAlert("Debe ingresar el Tipo de documento", "id_tipo_documento");
        return;
    }
    if ($("#documento").val().trim() == '') {
        showAlert("Debe ingresar el N° de Documento", "documento");
        return;
    }
    if ($("#id_tramite").val().trim() == '') {
        showAlert("Debe ingresar el N° de Orden de pago", "id_tramite");
        return;
    }
    
    return true;
}

function enviar(){
    grecaptcha.ready(function() {
        grecaptcha.execute('6Lf4wOQUAAAAAOazF-mb5Ce8oWwZZsz0plTCMZhU', {action: 'form'}).then(function(token) {
           document.getElementById("recaptchaResponse").value= token; 
           if ( validar()) {
               let form  =  document.getElementById("form");
               form.submit();
           }
           
        });
    });
}

$("#linkNroTramiteDni").click(function() {
	var box = bootbox.alert({
	    message: '<div class="text-center"><img src="<?php echo base_url('assets/img/nro-tramite-dni.jpg'); ?>" class="img-fluid" /></div>',
	    locale: 'es'
	});
});

$("#id_tipo_documento").change(function() {
	if(this.value == 1) {
		$("#divNroTramiteDni").show();
	}else {
		$("#divNroTramiteDni").hide();
	}	
});

$(".pagoBancoMacro").click(function() {
    event.preventDefault()
    //alert(this.dataset.idTramite);
    pagarBancoMacro(this.dataset.idTramite);
});

// function que realiza el pago por Banco Macro 
function pagarBancoMacro(idTramite){
$("#loading").show();
const baseUrl ='<?php echo base_url(); ?>';
const url_banco_macro ='<?php if(isset($urlBancoMacro)) echo $urlBancoMacro; ?>';
//const idTramite = document.getElementById('id_tramite').value;
//const isPersonaValidada = document.getElementById('isPersonaValidada2').value;
fetch(baseUrl + '/DescargarCertificado/pagoBancoMacro/' + idTramite + '/' + true + '/wizard')
.then(response => response.json())
.then(data => {
    console.log(data);
    if ( data.status === "OK" && data.link !== "") {
        $("#loading").hide(); 

        let dataBancoMacro = data[0];
       
        document.getElementById("CallbackSuccess").value = dataBancoMacro.call_back;
        document.getElementById("CallbackCancel").value = dataBancoMacro.call_cancel;
        document.getElementById("Comercio").value = dataBancoMacro.comercio;
        document.getElementById("Hash").value = dataBancoMacro.hash_generate;
        document.getElementById("TransaccionComercioId").value = dataBancoMacro.transaction_comercio_id;
        document.getElementById("Monto").value = dataBancoMacro.monto;
        document.getElementById("Producto").value = "dataBancoMacro.producto";
        document.getElementById("ClientData.CUIT").value = dataBancoMacro.titular_cuit;
        document.getElementById("ClientData.NombreApellido").value = dataBancoMacro.titular_nombre_apellido;
        
        console.log("data => ",dataBancoMacro);

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

        console.log(form);

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
<?php echo view('templates/frontend-base/footer.php'); ?>
