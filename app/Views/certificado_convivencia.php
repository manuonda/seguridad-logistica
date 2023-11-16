
<div class="container" style="padding-top: 70px">
<!--     <div class="col-md-10 offset-md-1"> -->
    <div class="col-md-12">
        <!-- form user info -->
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0">CERTIFICADO DE CONVIVENCIA</h5>
            </div>
            <?php echo form_open('certificadoResidencia/guardar'); ?>
            
            <div class="card-body">
            		<?= \Config\Services::validation()->listErrors('my_errors'); ?>
					<?php if (isset($error) and !empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                	<?php endif; ?>
            		<input type="hidden" name="id_tramite" id="id_tramite" value="<?php if(isset($id_tramite)) echo $id_tramite; ?>" />
            		<input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if(isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            		<div class="form-group row">
                    	<label class="col-lg-12 control-label"><b>Datos personales</b></label>
                    </div>
            		<div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if(isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if(isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                        <div class="col-lg-9">
                            <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($tipoDocumentos as $item): ?>
                                    <option value="<?php echo $item['id_tipo_documento']?>" <?php if(isset($id_tipo_documento) && $id_tipo_documento==$item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if(isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                        <div class="col-lg-9">
                            <input type="number" name="cuil" id="cuil" class="form-control mayuscula" value="<?php if(isset($cuil)) echo $cuil; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label"><b>Domicilio personal y datos de contacto</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Domicilio *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="domicilio" id="domicilio" class="form-control mayuscula" value="<?php if(isset($domicilio)) echo $domicilio; ?>" placeholder="Domicilio" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="localidad">Localidad *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="localidad" id="localidad" class="form-control mayuscula" value="<?php if(isset($localidad)) echo $localidad; ?>" placeholder="Localidad" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                        <div class="col-lg-9">
                            <select name="id_departamento" id="id_departamento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($departamentos as $item): ?>
                                    <option value="<?php echo $item['id_departamento']?>" <?php if(isset($id_departamento) && $id_departamento==$item['id_departamento']) echo 'selected="selected"'; ?>><?php echo $item['depto']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tel&eacute;fono *:</label>
                        <div class="col-lg-9">
                            <input type="number" name="telefono" id="telefono" class="form-control mayuscula" value="<?php if(isset($telefono)) echo $telefono; ?>" placeholder="Tel&eacute;fono, solo numeros" maxlength="20" required spellcheck="false" />
                            <input type="hidden" name="porque_motivo" id="porque_motivo" value="<?php if(isset($porque_motivo)) echo $porque_motivo; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email :</label>
                        <div class="col-lg-9">
                            <input type="text" name="email" id="email" class="form-control" value="<?php if(isset($email)) echo $email; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label"><b>Parte interesada</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nombre del tutor :</label>
                        <div class="col-lg-9">
                            <input type="text" name="nombre_tutor" id="nombre_tutor" class="form-control mayuscula" value="<?php if(isset($nombre_tutor)) echo $nombre_tutor; ?>" placeholder="Nombre del tutor" spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Apellido del tutor :</label>
                        <div class="col-lg-9">
                            <input type="text" name="apellido_tutor" id="apellido_tutor" class="form-control mayuscula" value="<?php if(isset($apellido_tutor)) echo $apellido_tutor; ?>" placeholder="Apellido del tutor" spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_tipo_documento_tutor">Tipo documento del tutor :</label>
                        <div class="col-lg-9">
                            <select name="id_tipo_documento_tutor" id="id_tipo_documento_tutor" class="form-control" data-toggle="tooltip" data-placement="bottom">
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($tipoDocumentos as $item): ?>
                                    <option value="<?php echo $item['id_tipo_documento']?>" <?php if(isset($id_tipo_documento_tutor) && $id_tipo_documento_tutor==$item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Documento del tutor :</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento_tutor" id="documento_tutor" class="form-control mayuscula" value="<?php if(isset($documento_tutor)) echo $documento_tutor; ?>" placeholder="N° DE DOCUMENTO DEL TUTOR" maxlength="15" spellcheck="false"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="autoridad_presentar" id="autoridad_presentar" class="form-control mayuscula" value="<?php if(isset($autoridad_presentar)) echo $autoridad_presentar; ?>" placeholder="Autoridad a Presentar" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
                        <div class="col-lg-9">
                            <select name="id_dependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom">
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($dependencias as $item): ?>
                                    <option value="<?php echo $item['id_dependencia']?>" <?php if(isset($id_dependencia) && $id_dependencia==$item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label">El presente certificado solo tiene validez acompañado por DNI.</label>
                    </div>                    
                    <div class="form-group row">
                    	<label class="col-lg-12 control-label text-justify">
                    	Declaro bajo juramento que los datos consignados precedentemente y a
continuación responde a mi situación actual, comprometiéndome a comunicar
todo cambio que modifique los términos de esta declaración. -
El presente certificado se enmarca en el contexto de la emergencia sanitaria y
del “aislamiento social preventivo y obligatorio” determinado por el DNU Nº
297/20, representando el presente una Declaración Jurada sobre la realización
de tareas críticas y esenciales autorizadas por la normativa vigente.
						</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label form-control-label text-justify"><b>
                        	La falsedad o inexactitud de cualquiera de los datos vertidos en la
presente declaración jurada como la falta de justificativo de la misma,
implicara el cese inmediato de la misma y su portador será objeto de la
aplicación de flagrancia establecida por régimen sancionatorio
excepcional previsto en el decreto 741-G-2020, pudiendo ser sancionado
con multa, más accesoria de arresto, inhabilitación, comiso, como así
también de las sanciones previstas por el Título 4to. del Código Penal y
Código Contravencional y demás normas vigentes. -</b>
                        </label>
                    </div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                    <div class="form-group row">
                        <div class="col-lg-12 text-center">
                        	<button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</button>
                            <button class="btn btn-primary" type="button" id="btnReiniciar"><span class="oi oi-reload"></span> Reiniciar</button>

                            <button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Generar trámite</button>
                        </div>
                    </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>
<script type="text/javascript">
$("#id_dependencia").select2();

$( "#btnVolver" ).click(function() {
	location.href = '<?php echo base_url(); ?>/tramite';
});
$( "#btnReiniciar" ).click(function() {
	location.href = '<?php echo base_url(); ?>/certificadoResidencia';
});

function generar(form) {
	if($("#nombre").val().trim()=='') {
		showAlert("Debe ingresar su nombre", "nombre");
		return;
	}
	if($("#apellido").val().trim()=='') {
		showAlert("Debe ingresar su apellido", "apellido");
		return;
	}
	if($("#documento").val().trim()=='') {
		showAlert("Debe ingresar el Documento", "documento");
		return;
	}
	if ($("#nro_tramite_dni").val().trim() == '') {
        showAlert("Debe ingresar el N° de trámite que figura en tu DNI", "nro_tramite_dni");
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
	
	if($("#domicilio").val().trim()=='') {
		showAlert("Debe ingresar el domicilio donde reside", "domicilio");
		return;
	}
	if($("#localidad").val().trim()=='') {
		showAlert("Debe ingresar la localidad donde reside", "localidad");
		return;
	}
	if($("#id_departamento").val().trim()=='') {
		showAlert("Debe ingresar el Departamento donde reside", "id_departamento");
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
	
	$("#loading").show();
	grecaptcha.ready(function() {
        grecaptcha.execute('6Lf4wOQUAAAAAOazF-mb5Ce8oWwZZsz0plTCMZhU', {action: 'form'}).then(function(token) {
           document.getElementById("recaptchaResponse").value= token; 
           form.submit();
        });
    });
}
</script>