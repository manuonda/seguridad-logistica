<!--     <div class="col-md-10 offset-md-1"> -->
    <div class="col-md-12">
        <!-- form user info -->
        <div class="card card-outline-secondary">            
            <div class="card-body">
            	
            		<div class="form-group row">
                    	<label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
                    </div>
                    <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">ID TRAMITE *:</label>
                <div class="col-lg-9">
                    <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['id_tramite'])) echo $dataInformation['id_tramite']; ?>" readonly />
                   </div>
                 </div>
            		<div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                        <div class="col-lg-9">
                            <input  class="form-control mayuscula" value="<?php if(isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control mayuscula" value="<?php if(isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                        <div class="col-lg-9">
                            <input type="date" class="form-control mayuscula" value="<?php if (isset($dataInformation['fechaNacimiento'])) echo $dataInformation['fechaNacimiento']; ?>" readonly/>
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                        <div class="col-lg-9">
                            <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($tipoDocumentos as $item): ?>
                                    <option value="<?php echo $item['id_tipo_documento']?>" <?php if(isset($dataInformation['id_tipo_documento']) && $dataInformation['id_tipo_documento']==$item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                        <div class="col-lg-9">
                            <input type="text"  class="form-control mayuscula" value="<?php if(isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>" readonly/>
                        </div>
                    </div>
                    <!-- 
                    <div class="form-group row" id="divNroTramiteDni">
                        <label class="col-lg-3 col-form-label form-control-label">N° de trámite que figura en tu DNI:</label>
                        <div class="col-lg-5">
                            <input type="text"  readonly class="form-control mayuscula" value="<?php //if (isset($dataInformation['nro_tramite_dni'])) echo $dataInformation['nro_tramite_dni']; ?>"  />
                        </div>
                    </div>
                     -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                        <div class="col-lg-9">
                            <input type="number" readonly class="form-control mayuscula" value="<?php if(isset($dataInformation['cuil'])) echo $dataInformation['cuil']; ?>"  />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                        <div class="col-lg-9">
                            <select disabled class="form-control" data-toggle="tooltip" data-placement="bottom" >
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($departamentos as $item): ?>
                                    <option value="<?php echo $item['id_departamento']?>" <?php if(isset($dataInformation['id_departamento']) && $dataInformation['id_departamento']==$item['id_departamento']) echo 'selected="selected"'; ?>><?php echo $item['depto']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="localidad">Localidad *:</label>
                        <div class="col-lg-9">
                         <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach ($localidades as $item) : ?>
                                    <option value="<?php echo $item['id_localidad'] ?>" <?php if (isset($dataInformation['id_localidad']) && $dataInformation['id_localidad'] == $item['id_localidad']) echo 'selected="selected"'; ?>><?php echo $item['localidad'] ?></option>
                                <?php endforeach; ?>
                         </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Barrio *:</label>
                        <div class="col-lg-8">
                        	<input type="hidden" readonly value="<?php if (isset($dataInformation['id_barrio'])) echo $dataInformation['id_barrio']; ?>" />
                            <input type="text"  readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>"  />
                        </div>
                        <div class="col-lg-1 col-form-label form-check">
                            <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio']===SIN_BARRIO) echo 'checked'; ?>>
                            <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                        <div class="col-lg-5">
                            <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['calle'])) echo $dataInformation['calle']; ?>" readonly/>
                        </div> 
                        <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Número *:</label>
                        <div class="col-lg-2">
                            <input type="text"  class="form-control" value="<?php if (isset($dataInformation['numero'])) echo $dataInformation['numero']; ?>" readonly onkeypress="return isNumber(event)"  <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'readonly'; ?> />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label"></label>
                        <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Manzana :</label>
                        <div class="col-lg-1">
                            <input type="text" name="manzana" id="manzana" class="form-control mayuscula" value="<?php if (isset($dataInformation['manzana'])) echo $dataInformation['manzana']; ?>" placeholder="Manzana" maxlength="10" spellcheck="false" readonly />
                        </div>
                        <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Lote :</label>
                        <div class="col-lg-1">
                            <input type="text" name="lote" id="lote" class="form-control mayuscula" value="<?php if (isset($dataInformation['lote'])) echo $dataInformation['lote']; ?>" placeholder="Lote" maxlength="10" spellcheck="false" readonly/>
                        </div>
                        <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0px;">Piso :</label>
                        <div class="col-lg-1" style="padding-left: 0px;">
                            <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['piso'])) echo $dataInformation['piso']; ?>"  readonly />
                        </div>
                        <div class="col-lg-1 col-form-label"></div>
                        <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0;">Dpto. :</label>
                        <div class="col-lg-1" style="padding-left: 0px;">
                            <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['dpto'])) echo $dataInformation['dpto']; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label fs-title"><b>Elemento/s extraviado/s</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label"></label>
                        <div class="col-lg-9">
                            <textarea readonly class="form-control mayuscula"  rows="6" data-toggle="tooltip" data-placement="bottom" spellcheck="false"><?php if(isset($dataInformation['elementos_extraviados'])) echo $dataInformation['elementos_extraviados']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                        <div class="col-lg-9">
                            <input type="text"  class="form-control mayuscula" value="<?php if(isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" readonly />
                        </div>
                    </div>
                    <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                        <div id="div_dependencia" class="form-group row">
                        	<label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
                            <div class="col-lg-9">
                                <select class="form-control dependencia" data-toggle="tooltip" data-placement="bottom" disabled >
                                    <option value="">-- SELECCIONAR --</option>
                                    <?php foreach($dependencias as $item): ?>
                                        <option value="<?php echo $item['id_dependencia']?>" <?php if(isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia']==$item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_COMISARIA_SECCIONAL) { ?>
                    	<input type="hidden"  value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" readonly />
                    	
                    <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) { ?>
                    	<div class="form-group row">
                            <label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
                            <div class="col-lg-9">
                                <select  class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                                    <option value="">-- SELECCIONAR --</option>
                                    <?php foreach ($dependencias as $item) : ?>
                                        <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <div class="form-group row">
                        <label class="col-lg-12 control-label fs-title"><b>Datos de contacto</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tel&eacute;fono *:</label>
                        <div class="col-lg-9">
                            <input type="number"  class="form-control mayuscula" value="<?php if(isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" readonly/>
                            <input type="hidden" value="<?php if(isset($dataInformation['porque_motivo'])) echo $dataInformation['porque_motivo']; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email :</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="<?php if(isset($dataInformation['email'])) echo $dataInformation['email']; ?>" readonly/>
                        </div>
                    </div>
                    
                    <?php if (isset($action) && $action == "edit" && isset($estados)) { ?>
                       	<div class="form-group row">
                            <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label" for="estados">Estados *:</label>
                            <div class="col-lg-9">
                                <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                                    <option value="">-- SELECCIONAR --</option>
                                    <?php foreach ($estados as $item) : ?>
                                        <option value="<?php echo $item; ?>" <?php if (isset($dataInformation['estado']) && $dataInformation['estado'] == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                        	<label class="col-lg-3 col-form-label form-control-label">Observaciones :</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" readonly placeholder="Observaciones"><?php if(isset($observaciones)) echo $observaciones; ?></textarea>
                            </div>
                        </div>
                    <?php }else if (empty($action)) { ?>
                    		<input type="hidden"  id="estado" value="<?php if(isset($dataInformation['estado'])) echo $dataInformation['estado']; ?>" /> 
                    <?php } ?>                   
            </div>
        </div>
    </div>

<script type="text/javascript">
function validar() {
	if($("#nombre").val().trim()=='') {
		showAlert("Debe ingresar su nombre", "nombre");
		return;
	}
	if($("#apellido").val().trim()=='') {
		showAlert("Debe ingresar su apellido", "apellido");
		return;
	}
	if ($("#fecha_nacimiento").val().trim() == '') {
        showAlert("Debe ingresar la Fecha de nacimiento", "fecha_nacimiento");
        return;
    }

	var id_tipo_documento = $("#id_tipo_documento").val().trim(); 
    if (id_tipo_documento == '') {
        showAlert("Debe ingresar el Tipo de documento", "id_tipo_documento");
        return;
    }
	if($("#documento").val().trim()=='') {
		showAlert("Debe ingresar el Documento", "documento");
		return;
	}

	var cuil = $("#cuil").val().trim(); 
	if(cuil == '') {
		showAlert("Debe ingresar el Cuil", "cuil");
		return;		
	}else {
		if(cuil.length != 11) {
			showAlert("El Cuil debe tener 11 digitos", "cuil");
			return;		
		}	
	}	
	
	if($("#id_departamento").val().trim()=='') {
		showAlert("Debe ingresar el Departamento donde reside", "id_departamento");
		return;
	}
	if ($("#id_localidad").val().trim() == '') {
        showAlert("Debe ingresar la localidad donde reside", "id_localidad");
        return;
    }
	if ($("#barrio").val().trim() == '') {
        showAlert("Debe ingresar el barrio donde reside", "barrio");
        return;
    }
	if($("#calle").val().trim()=='') {
		showAlert("Debe ingresar la calle, finca o ruta donde reside", "calle");
		return;
	}
	if ($("#numero").val().trim() == '') {
        showAlert("Debe ingresar el numero donde reside", "numero");
        return;
    }

	if($("#elementos_extraviados").val().trim()=='') {
		showAlert("Debe ingresar elemento/s extraviado/s", "elementos_extraviados");
		return;
	}
	if ($("#id_dependencia").val().trim() == '') {
        showAlert("Debe ingresar la Comisaría seccional donde se van a Verificar y validar sus datos e identidad", "id_dependencia");
        return;
    }
    if ($("#autoridad_presentar").val().trim() == '') {
        showAlert("Debe ingresar a donde va presentar el certificado", "autoridad_presentar");
        return;
    }
	
	if($("#telefono").val().trim()=='') {
		showAlert("Debe ingresar el numero de telefono", "telefono");
		return;		
	}
	var email = $("#email").val().trim();
	if(email != '') {
		if(!isValidEmail(email)) {
			showAlert("El email ingresado es invalido", "email");
			return;
		}
	}

	<?php if (isset($action) && $action == "edit") { ?>
    if ($("#estado").val().trim() == '') {
        showAlert("Debe ingresar el Estado del trámite", "estado");
        return;
    }
    <?php }?>

	return true;
}

function generar(form){
    if ( validar()) {
     $("#loading").show();
      form.submit();  
    }
}
</script>