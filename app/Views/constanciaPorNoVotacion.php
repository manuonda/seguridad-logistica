<link rel="stylesheet" media="only screen and (max-width: 768px)" href="/public/assets/css/main.css">
<div class="col-md-12">
    <div class="card card-outline-secondary">
        <div class="card-body">
        	<?= \Config\Services::validation()->listErrors('my_errors'); ?>
			<?php if (isset($error) and !empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
        	<?php endif; ?>                   
            <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
            <input type="hidden" name="id_turno" id="id_turno" value="" />
            <input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            <input type="hidden" name="id_persona_titular" id="id_persona_titular" value="<?php if (isset($id_persona_titular)) echo $id_persona_titular; ?>" />
            <input type="hidden" name="estado_pago" id="estado_pago" value="<?php if(isset($estado_pago)) echo $estado_pago; ?>" />
            <input type="hidden" name="isPersonaValidada" id="isPersonaValidada" value="false" />
            <input type="hidden" name="id_tramite_votacion" id="id_tramite_votacion" value="<?php if (isset($id_tramite_votacion)) echo $id_tramite_votacion; ?>" />
           
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <?php if (!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA): ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">ID TRAMITE *:</label>
                    <div class="col-lg-9">
                        <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" readonly />
                    </div>
                </div>
            <?php endif;  ?>
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
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil <?php if (empty($userInSession)) echo '*'; ?>:</label>
                <div class="col-lg-9">
                    <input type="text" name="cuil" id="cuil" class="form-control mayuscula" value="<?php if (isset($cuil)) echo $cuil; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" required spellcheck="false" required onkeypress="return isNumber(event)" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_pais">Nacionalidad *:</label>
                <div class="col-lg-9">
                    <select name="id_pais" id="id_pais" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($paises as $item) : ?>
                            <option value="<?php echo $item['id_pais'] ?>" <?php if (isset($id_pais) && $id_pais == $item['id_pais']) echo 'selected="selected"'; ?>><?php echo $item['origen'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_estado_civil">Estado civil *:</label>
                <div class="col-lg-9">
                    <select name="id_estado_civil" id="id_estado_civil" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($estadosCiviles as $item) : ?>
                            <option value="<?php echo $item['id_estado_civil'] ?>" <?php if (isset($id_estado_civil) && $id_estado_civil == $item['id_estado_civil']) echo 'selected="selected"'; ?>><?php echo $item['situacion'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Profesión *:</label>
                <div class="col-lg-9">
                    <input type="text" name="profesion" id="profesion" class="form-control mayuscula" value="<?php if (isset($profesion)) echo $profesion; ?>" placeholder="Profesión" maxlength="100" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">¿Donde trabaja? *:</label>
                <div class="col-lg-9">
                    <input type="text" name="lugar_de_trabajo" id="lugar_de_trabajo" class="form-control mayuscula" value="<?php if (isset($lugar_de_trabajo)) echo $lugar_de_trabajo; ?>" placeholder="Lugar donde trabaja" maxlength="100" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Empresa de transporte en que viaja *:</label>
                <div class="col-lg-9">
                    <input type="text" name="empresa_transporte" id="empresa_transporte" class="form-control mayuscula" value="<?php if (isset($empresa_transporte)) echo $empresa_transporte; ?>" placeholder="Empresa de transporte en que viaja" maxlength="100" required spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Motivo :</label>
                <div class="col-lg-9">
                    <input type="text" name="motivo" id="motivo" class="form-control mayuscula" value="<?php if (isset($motivo)) echo $motivo; ?>" placeholder="por ejemplo: corte de ruta" maxlength="100" spellcheck="false" />
                </div>
            </div> -->
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
                <div class="col-lg-8">
                	<input type="hidden" name="id_barrio" id="id_barrio" value="<?php if (isset($id_barrio)) echo $id_barrio; ?>" />
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($barrio)) echo $barrio; ?>" placeholder="Barrio" required spellcheck="false" <?php if (!empty($barrio) && $barrio===SIN_BARRIO) echo 'readonly'; ?>/>
                </div>
                <div class="col-lg-1 col-form-label form-check responsive-element">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($barrio) && $barrio===SIN_BARRIO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-5">
                    <input type="text" name="calle" id="calle" class="form-control mayuscula" value="<?php if (isset($calle)) echo $calle; ?>" placeholder="Calle / Finca / Ruta" required spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label  numero " style="padding-right: 0px;">Número *:</label>
                <div class="col-lg-2">
                    <input type="text" name="numero" id="numero" class="form-control" value="<?php if (isset($numero)) echo $numero; ?>" placeholder="Número" maxlength="10" required onkeypress="return isNumber(event)" spellcheck="false" <?php if (!empty($numero) && $numero===SIN_NUMERO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check responsive-element">
                  <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($numero) && $numero===SIN_NUMERO) echo 'checked'; ?>>
                  <label class="form-check-label" for="checkSinNumero">Sin número</label>
                </div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Manzana :</label>
                <div class="col-lg-1">
                    <input type="text" name="manzana" id="manzana" class="form-control mayuscula" value="<?php if (isset($manzana)) echo $manzana; ?>" placeholder="Manzana" maxlength="10" spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Lote :</label>
                <div class="col-lg-1">
                    <input type="text" name="lote" id="lote" class="form-control mayuscula" value="<?php if (isset($lote)) echo $lote; ?>" placeholder="Lote" maxlength="10" spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Piso :</label>
                <div class="col-lg-1">
                    <input type="text" name="piso" id="piso" class="form-control mayuscula" value="<?php if (isset($piso)) echo $piso; ?>" placeholder="Piso" maxlength="3" spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Dpto. :</label>
                <div class="col-lg-1">
                    <input type="text" name="dpto" id="dpto" class="form-control mayuscula" value="<?php if (isset($dpto)) echo $dpto; ?>" placeholder="Dpto." maxlength="5" spellcheck="false" />
                </div>
            </div>

             
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>No Podra emitir su voto por :</b></label>
            </div>

            <div id="divTipoConvivencia" class="form-group col">
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="localidad">Motivo  *:</label>
                <div class="col-lg-9">
                 <select name="razon_social" id="razon_social" class="form-control" data-toggle="tooltip" 
                 data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($razones as $key => $value) : ?>
                            <option value="<?php echo $key ?>" <?php if (isset($razon_social) && $razon_social == $key) echo 'selected="selected"'; ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                 </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Descripción :</label>
                <div class="col-lg-9">
                    <input type="text" name="descripcion" id="descripcion" class="form-control mayuscula" value="<?php if (isset($descripcion)) echo $descripcion; ?>" placeholder="DESCRIPCIÓN DEL MOTIVO" required spellcheck="false" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha de Votación  *:</label>
                <div class="col-lg-9">
                    <input type="date" name="fecha_votacion" id="fecha_votacion" class="form-control mayuscula" value="<?php if (isset($fecha_votacion)) echo $fecha_votacion; ?>" required spellcheck="false" />
                </div>
            </div>

          

            <?php if(isset($id_tramite_votacion)){ ?> 
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Elecciones :</label>
                <div class="col-lg-9">
                    <input type="text" readonly name="nombre_eleccion" class="form-control mayuscula" value="<?php if (isset($nombre_eleccion)) echo $nombre_eleccion; ?>" required spellcheck="false" />
                </div>
            </div>
            <?php } ?>


            </div>

            



            

            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
           
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="localidad"> Juzgado Electoral de la Provincia  *:</label>
                <div class="col-lg-9">
                 <select name="id_provincia" id="id_provincia" class="form-control" data-toggle="tooltip" 
                 data-placement="bottom" required>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($provincias as $item) : ?>
                            <option value="<?php echo $item['id_provincia'] ?>" <?php if (isset($id_provincia) && $id_provincia == $item['id_provincia']) echo 'selected="selected"'; ?>><?php echo $item['nombre'] ?></option>
                        <?php endforeach; ?>
                 </select>
                </div>
            </div>
           
            <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                <div id="div_dependencia" class="form-group row">
                    <label id="label_id_dependencia" class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
                    <div class="col-lg-9">
                        <select name="id_dependencia" id="id_dependencia" class="form-control dependencia" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($id_dependencia) && $id_dependencia == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty($userInSession) && ($userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5)) { ?>
            	<input type="hidden" name="id_dependencia" id="id_dependencia" value="<?php if(isset($id_dependencia)) echo $id_dependencia; ?>" />
            	
            <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) { ?>
                <input type="hidden" id="unidad_administrativa" value="si"> 
            	<div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
                    <div class="col-lg-9">
                        <select name="id_dependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom" >
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
                <label class="col-lg-3 col-form-label form-control-label">Email <?php if (empty($userInSession)) echo '*'; ?>:</label>
                <div class="col-lg-9">
                    <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($email)) echo $email; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" spellcheck="false" />
                </div>
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
            </div>
            <?php if (isset($action) && $action == "edit" && isset($estados)) { ?>
                <div class="card p-4 mt-5 mb-5" style="background: #AED6F1">
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
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Observaciones :</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones"><?php if(isset($observaciones)) echo $observaciones; ?></textarea>
                        </div>
                    </div>
                </div>
            <?php }else if (empty($action)) { ?>
            		<input type="hidden" name="estado" id="estado" value="<?php if(isset($estado)) echo $estado; ?>" /> 
            <?php } ?>           
        </div>
    </div>
</div>

<script>
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

        var hoy = new Date();
        var cumpleanos = new Date($("#fecha_nacimiento").val().trim()+" GMT-0300");
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();
        var d = hoy.getDate() - cumpleanos.getDate();
        if (m < 0 || (m == 0 && d < 0)) {
            edad--;
        }
        if (edad < 18) {
            showAlert("Debe ser mayor a 18 años para realizar este tramite ", "fecha_nacimiento");
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

		var cuil = $("#cuil").val().trim();
		<?php if (empty($userInSession)) { ?>
        if (cuil == '') {
            showAlert("Debe ingresar el Cuil", "cuil");
            return;
        }
        <?php }?>
        if (cuil !== '') {
        	if (cuil.length != 11) {
                showAlert("El Cuil debe tener 11 digitos", "cuil");
                return;
            }
        }

        var id_pais = $("#id_pais").val().trim();
        if (id_pais == '') {
            showAlert("Debe ingresar su nacionalidad", "id_pais");
            return;
        }
        var id_estado_civil = $("#id_estado_civil").val().trim();
        if (id_estado_civil == '') {
            showAlert("Debe ingresar su estado civil", "id_estado_civil");
            return;
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
        if ($("#calle").val().trim() == '') {
            showAlert("Debe ingresar la calle, finca o ruta donde reside", "calle");
            return;
        }
        if ($("#numero").val().trim() == '') {
            showAlert("Debe ingresar el numero donde reside", "numero");
            return;
        }
        
    
        if ($("#razon_social").val().trim() == '') {
            showAlert("Debe seleccionar el motivo.", "razon_social");
            return;
        }

        
        if ($("#fecha_votacion").val().trim() == '') {
            showAlert("Debe ingresar la Fecha de la votación que necesita justificar.", "fecha_votacion");
            return;
        }

 
     


    
        if ($("#id_provincia").val().trim() == '') {
            showAlert("Debe Seleccionar el Juzgado Electoral de la Provincia", "id_provincia");
            return;
        }


        var unidadAdministrativa = document.getElementById("unidad_administrativa");
        var isVisibleDependencia = $("#div_dependencia").is(":visible");
        if (isVisibleDependencia && $("#id_dependencia").val().trim() == '' && unidadAdministrativa == null) {
            showAlert("Debe ingresar la Comisaría seccional donde se van a validar sus datos e identidad", "id_dependencia");
            return;
        }

        if ($("#telefono").val().trim() == '') {
            showAlert("Debe ingresar el número de teléfono", "telefono");
            return;
        }

        var email = $("#email").val().trim();
        <?php if (empty($userInSession)) { ?>
        if (email == '') {
            showAlert("Debe ingresar el email", "email");
            return;
        }
        <?php }?>
        if (email !== '') {
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
