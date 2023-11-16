<div class="col-md-12">
    <!-- form user info -->
    <div class="card card-outline-secondary">
        <div class="card-body">
            <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
            <input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            <input type="hidden" name="id_persona_titular" id="id_persona_titular" value="<?php if (isset($id_persona_titular)) echo $id_persona_titular; ?>" />
            
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-9">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" name="tipo_planilla" id="tipo_planilla" 
                            <?php if (isset($tipo_planilla) && $tipo_planilla == PRIMERA_VEZ) { echo "checked"; }; ?> value="<?php echo PRIMERA_VEZ; ?>"><b>Primera vez</b>
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" name="tipo_planilla" id="tipo_planilla" 
                            <?php if (isset($tipo_planilla) && $tipo_planilla == RENOVACION) { echo "checked"; }; ?> value="<?php echo RENOVACION; ?>"><b>Renovación</b>
                        </label>
                    </div>
                </div>
            </div>
            <div id="divRequisitosPrimeraVez" class="form-group row" style="display: none;">
            	<label class="col-lg-3 col-form-label fs-title">Requisitos</label>
                <div class="col-lg-9">
                	<div class="form-check-inline p-3 mb-2 bg-light border rounded">
                    	<ul style="padding-left: 1rem;">
                    		<li>Certificado de Nacimiento Original y Fotocopia</li>
                    		<li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
                    		<li>Asistir con lapicera propia ya sea de color negra o azul</li>
                    		<li>$ 300,00 para estampillas. Comprar estampillas en dirección de administración y finanzas del departamento Central</li>
                    		<li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
                    		<li>Dos (2) Fotografías 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
                    		<li>Grupo Sanguineo firmado por el Médico o autoridad competente</li>
                    		<li>Horario: 06:00</li>
                    	</ul>
                    </div>
                </div>
            </div>
            <div id="divRequisitosRenovacion" class="form-group row" style="display: none;">
            	<label class="col-lg-3 col-form-label fs-title">Requisitos</label>
                <div class="col-lg-9">
                	<div class="form-check-inline p-3 mb-2 bg-light border rounded">
                    	<ul style="padding-left: 1rem;">
                    		<li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
                    		<li>Asistir con lapicera propia ya sea de color negra o azul</li>
                    		<li>$ 300,00 para estampillas</li>
                    		<li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
                    		<li>Dos (2) Fotografías 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
                    		<li>Horario: De acuerdo al turno otorgado por WEB</li>
                    	</ul>                    	
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                <div class="col-lg-9">
                    <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if (isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if (isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control mayuscula" value="<?php if (isset($fecha_nacimiento)) echo $fecha_nacimiento; ?>" required spellcheck="false" />
                </div>
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
                <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                <div class="col-lg-9">
                    <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row" id="divNroTramiteDni">
                <label class="col-lg-3 col-form-label form-control-label">N° de trámite que figura en tu DNI *:</label>
                <div class="col-lg-5">
                    <input type="text" name="nro_tramite_dni" id="nro_tramite_dni" class="form-control mayuscula" value="<?php if (isset($nro_tramite_dni)) echo $nro_tramite_dni; ?>" placeholder="Ingresá los 11 dígitos de tu número de trámite" maxlength="11" required spellcheck="false" />
                </div>
                <div class="col-lg-4">
                	<button id="linkNroTramiteDni" type="button" class="btn btn-info"><span class="oi oi-media-play"></span> Consultá el número de trámite según la versión de tu DNI</button>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                <div class="col-lg-9">
                    <input type="number" name="cuil" id="cuil" class="form-control mayuscula" value="<?php if (isset($cuil)) echo $cuil; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select name="id_departamento" id="id_departamento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($departamentos as $item) : ?>
                            <option value="<?php echo $item['id_departamento'] ?>" <?php if (isset($id_departamento) && $id_departamento == $item['id_departamento']) echo 'selected="selected"'; ?>><?php echo $item['depto'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="localidad">Localidad *:</label>
                <div class="col-lg-9">
                 <select name="id_localidad" id="id_localidad" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($localidades as $item) : ?>
                            <option value="<?php echo $item['id_localidad'] ?>" <?php if (isset($id_localidad) && $id_localidad == $item['id_localidad']) echo 'selected="selected"'; ?>><?php echo $item['localidad'] ?></option>
                        <?php endforeach; ?>
                 </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Barrio *:</label>
                <div class="col-lg-9">
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($barrio)) echo $barrio; ?>" placeholder="Barrio" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Dirección *:</label>
                <div class="col-lg-9">
                    <input type="text" name="domicilio" id="domicilio" class="form-control mayuscula" value="<?php if (isset($domicilio)) echo $domicilio; ?>" placeholder="Domicilio" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text" name="autoridad_presentar" id="autoridad_presentar" class="form-control mayuscula" value="<?php if (isset($autoridad_presentar)) echo $autoridad_presentar; ?>" placeholder="Autoridad a Presentar" required spellcheck="false" />
                </div>
            </div>
            <?php if (empty($id_tramite)) { ?>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                        <select name="id_dependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($id_dependencia) && $id_dependencia == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_COMISARIA_SECCIONAL) { ?>
            	<input type="hidden" name="id_dependencia" id="id_dependencia" value="<?php if(isset($id_dependencia)) echo $id_dependencia; ?>" />
            	
            <?php } else if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) { ?>
            	<div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                    	<input type="hidden" name="id_dependencia" id="id_dependencia" value="<?php if(isset($id_dependencia)) echo $id_dependencia; ?>" />
                        <select name="dep" id="dep" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($id_dependencia) && $id_dependencia == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
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
                    <input type="number" name="telefono" id="telefono" class="form-control mayuscula" value="<?php if (isset($telefono)) echo $telefono; ?>" placeholder="Tel&eacute;fono, solo numeros" maxlength="20" required spellcheck="false" />
                    <input type="hidden" name="porque_motivo" id="porque_motivo" value="<?php if (isset($porque_motivo)) echo $porque_motivo; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email *:</label>
                <div class="col-lg-9">
                    <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($email)) echo $email; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" spellcheck="false" />
                </div>
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
            </div>
            
            <?php if (isset($action) && ($action == "edit" || $action == "new") && isset($estados)) { ?>
            	<div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="estados">Estados *:</label>
                    <div class="col-lg-9">
                        <select name="estado" id="estado" class="form-control" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($estados as $item) : ?>
                                <option value="<?php echo $item; ?>" <?php if (isset($estado) && $estado == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php }else if (empty($action)) { ?>
            		<input type="hidden" name="estado" id="estado" value="<?php if(isset($estado)) echo $estado; ?>" /> 
            <?php } ?>
            
            <?php if (!empty($id_tramite)) : ?>
                <div class="form-group row">
                	<label class="col-lg-3 col-form-label form-control-label">Observaciones :</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones"><?php if(isset($observaciones)) echo $observaciones; ?></textarea>
                    </div>
                </div>
			<?php endif; ?>

            <?php if (empty($id_tramite)) : ?>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotografía 4x4 color</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Foto de frente :</label>
                    <div class="col-lg-9">
                        <input id="foto" name="foto" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">
                        <input id="documentoFrente" name="documentoFrente" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <input id="documentoDorso" name="documentoDorso" type="file" class="form-control-file" />
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(".inputRadio").click(function() {
//         alert(this.value);
    	if(this.value=='<?php echo PRIMERA_VEZ; ?>') {
    		$("#divRequisitosRenovacion").hide();
    		$("#divRequisitosPrimeraVez").show();
    	}else if(this.value=='<?php echo RENOVACION; ?>') {
    		$("#divRequisitosPrimeraVez").hide();
    		$("#divRequisitosRenovacion").show();
    	}
    });

    function validar() {
        if ($("#nombre").val().trim() == '') {
            showAlert("Debe ingresar su nombre", "nombre");
            return;
        }
        if ($("#apellido").val().trim() == '') {
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
        if ($("#documento").val().trim() == '') {
            showAlert("Debe ingresar el Documento", "documento");
            return;
        }
        if (id_tipo_documento == 1) { // Si el tipo doc es Dni
        	if ($("#nro_tramite_dni").val().trim() == '') {
                showAlert("Debe ingresar el N° de trámite que figura en tu DNI", "nro_tramite_dni");
                return;
            }
        }

        var cuil = $("#cuil").val().trim();
        if (cuil == '') {
            showAlert("Debe ingresar el Cuil", "cuil");
            return;
        } else {
            if (cuil.length != 11) {
                showAlert("El Cuil debe tener 11 digitos", "cuil");
                return;
            }
        }

        if ($("#id_departamento").val().trim() == '') {
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
        if ($("#domicilio").val().trim() == '') {
            showAlert("Debe ingresar el domicilio donde reside", "domicilio");
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
        if ($("#telefono").val().trim() == '') {
            showAlert("Debe ingresar el número de teléfono", "telefono");
            return;
        }

        var email = $("#email").val().trim();
        if (email == '') {
            showAlert("Debe ingresar el email", "email");
            return;
        } else {
            if (!isValidEmail(email)) {
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

    function generar(form) {
        if (validar()) {
            $("#loading").show();
            form.submit();
        }
    }
</script>