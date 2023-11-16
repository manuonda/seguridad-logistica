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
            <input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            <input type="hidden" name="id_persona_titular" id="id_persona_titular" value="<?php if (isset($id_persona_titular)) echo $id_persona_titular; ?>" />
            <input type="hidden" name="estado_pago" id="estado_pago" value="<?php if (isset($estado_pago)) echo $estado_pago; ?>" />
            <input type="hidden" name="isPersonaValidada" id="isPersonaValidada" value="false" />
            <input type="hidden" name="estado_verificacion" id="estado_pago" value="<?php if (isset($estado_verificacion)) echo $estado_verificacion; ?>" />
             
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label fs-title">Seleccione una opción</label>
                <div class="col-lg-9">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" 
                            name="tipo_planilla" id="tipo_planilla" <?php if (isset($tipo_planilla) && $tipo_planilla == PRIMERA_VEZ) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }; ?> value="<?php echo PRIMERA_VEZ; ?>"><b>Primera vez</b>
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" name="tipo_planilla" id="tipo_planilla" <?php if (isset($tipo_planilla) && $tipo_planilla == RENOVACION) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            }; ?> value="<?php echo RENOVACION; ?>"><b>Renovación</b>
                        </label>
                    </div>
                </div>
            </div>
            <?php if(empty($userInSession)) { ?>
            <div id="divRequisitosPrimeraVez" class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label fs-title">Requisitos</label>
                <div class="col-lg-9">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <ul style="padding-left: 1rem;">
                            <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
                            <li>Certificado de Nacimiento Actualizado, Original y Fotocopia</li>
                            <li>Asistir con lapicera propia ya sea de color negra o azul</li>
                            <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
                            <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
                            <li>Grupo Sanguineo firmado por el Médico o autoridad competente</li>
							<li>$ 1000 si es que va a realizar el pago en efectivo.</li>
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
                            <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
                            <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
							<li>$ 1000 si es que va a realizar el pago en efectivo.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php }?>
            
            <div id="divForm" <?php if(empty($userInSession)) { ?>style="display: none;"<?php }?>>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>

            <?php if(empty($userInSession)) { ?>
               <div class="form-group row">
               <label class="col-lg-3 col-form-label form-control-label">Nro. de DNI *:</label>
               <div class="col-lg-9">
                   <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DNI" maxlength="8" required spellcheck="false" />
               </div>
               </div>
            <?php } else if(!empty($userInSession)) { ?>
               <div class="form-group row">
                   <label class="col-lg-3 col-form-label form-control-label">Nro. de DNI *:</label>
                   <div class="col-lg-3">
                       <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DNI" maxlength="8" required spellcheck="false" />
                   </div>
                   <button type="button" class="btn btn-primary" onclick="buscarPersona()">
                   		<span class="oi oi-magnifying-glass"></span> Buscar
                   </button>
               </div>
            <?php } ?>
            

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
                <div class="col-lg-8">
                    <input type="hidden" name="id_barrio" id="id_barrio" value="<?php if (isset($id_barrio)) echo $id_barrio; ?>" />
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($barrio)) echo $barrio; ?>" placeholder="Barrio" required spellcheck="false" <?php if (!empty($barrio) && $barrio === SIN_BARRIO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($barrio) && $barrio === SIN_BARRIO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-5">
                    <input type="text" name="calle" id="calle" class="form-control mayuscula" value="<?php if (isset($calle)) echo $calle; ?>" placeholder="Calle / Finca / Ruta" required spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label">Número *:</label>
                <div class="col-lg-2">
                    <input type="text" name="numero" id="numero" class="form-control" value="<?php if (isset($numero)) echo $numero; ?>" placeholder="Número" maxlength="10" required onkeypress="return isNumber(event)" spellcheck="false" <?php if (!empty($numero) && $numero === SIN_NUMERO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($numero) && $numero === SIN_NUMERO) echo 'checked'; ?>>
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
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text" name="autoridad_presentar" id="autoridad_presentar" class="form-control mayuscula" value="<?php if (isset($autoridad_presentar)) echo $autoridad_presentar; ?>" placeholder="Autoridad a Presentar" required spellcheck="false" />
                </div>
            </div>

            <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                <div id="div_dependencia" class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Validar en *:</label>
                    <div class="col-lg-9">
                        <select name="id_dependencia" id="id_dependencia" class="form-control dependencia" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($id_dependencia) && $id_dependencia == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty($userInSession) && ($userInSession['id_rol']== ROL_COMISARIA_SECCIONAL || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5
                  || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL  || $userInSession['id_rol']==ROL_ANTECEDENTE || $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA )) { ?>
            	<input type="hidden" name="id_dependencia" id="id_dependencia" value="<?php if(isset($id_dependencia)) echo $id_dependencia; ?>" />
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
            <?php if (isset($action) && $action == "edit" && isset($estados)) { ?>
                <div class="card p-4 mt-5 mb-5" style="background: #AED6F1">
                    <div class="form-group row">
                        <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="estados">Estado *:</label>
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

            <?php if(!empty($userInSession)) {?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label fs-title">¿Es urgente?</label>
                    <div class="col-lg-2">
                        <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                         <label class="form-check-label">
                          	<input type="checkbox" class="form-check-input inputRadio" style="transform: scale(1.3);" onclick="cambioEstadoUrgente();"
                                   name="urgente" id="urgente" <?php if (isset($urgente) && $urgente == INT_UNO) { echo "checked"; }; ?> 
                                   value="<?php echo $urgente; ?>"/><b>&nbsp;&nbsp;SI</b>
                         </label>
                         </div>
                    </div>
                   <label id="idLabelPrecio" class="col-lg-1 col-form-label form-control-label" style="padding-right: 0px; display: none;">Precio $ :</label>
                   <div id="idDivInputPrecio" class="col-lg-2" style="padding-left: 0px; display: none;">
                    	<input type="text" name="precio" id="precio" class="form-control mayuscula" 
                          <?php if( $urgente == INT_CERO) echo "readonly"; ?>  
                           value="1040" placeholder="Precio"  spellcheck="false" />
                	</div>
                </div>
            <?php } ?>     
           
            <?php if (empty($id_tramite)) { ?>
                <div class="form-group row" <?php if(empty($userInSession)) {?>style="display: none;"<?php } ?>>
                    <label class="col-lg-12 control-label fs-title"><b>Fotografía 4x4 color, fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</b></label>
                </div>
                <div class="form-group row" <?php if(empty($userInSession)) {?>style="display: none;"<?php } ?>>
                    <label class="col-lg-3 col-form-label form-control-label">Foto de frente :</label>
                    <div class="col-lg-9">
                        <input id="fotoColor" name="fotoColor" type="file" onchange="return validarArchivoFotoFrente()" class="form-control-file" />
                    </div>
                </div>
                <div id="divTitleSubirDni" class="form-group row" style="display: none;">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div id="divFotoDniFrente" class="form-group row" style="display: none;">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">
                        <input id="documentoFrente" name="documentoFrente" type="file" onchange="return validarArchivoFrente()" class="form-control-file" />
                    </div>
                </div>
                <div id="divFotoDniDorso" class="form-group row" style="display: none;">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <input id="documentoDorso" name="documentoDorso" type="file" onchange="return validarArchivoDorso()" class="form-control-file" />
                    </div>
                </div>
            <?php } else { ?>

                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotografía 4x4 color, fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Archivo o foto :</label>
                    <div class="col-lg-9">
                        <?php if (isset($fotoColor) && $fotoColor != "" && $fotoColor != null) { ?>
                            <div id="FotoColor-<?php echo $fotoColorId; ?>">
                               
                                <img id="FotoColor<?php echo $fotoColorId; ?>" src="<?php if (isset($fotoColor)) echo $fotoColor; ?>" width="300" height="300" />
                                <button type="button" class="btn-danger" onclick="eliminarFoto('Foto Color-<?php echo $fotoColorId; ?>')">
                                    Eliminar
                                </button>
                            </div>
                        <?php } ?>
                        <input value="<?php if (isset($fotoColor)) echo $fotoColor; ?>" id="fotoColor" name="fotoColor" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">

                        <?php if (isset($fotoFrente) && $fotoFrente != "" && $fotoFrente != null) { ?>
                            <div id="FotoFrente-<?php echo $fotoFrenteId; ?>">
                                <img id="dni-frente" src="<?php echo $fotoFrente; ?>" width="300" height="300" />
                                <button type="button" class="btn-danger" onclick="eliminarFoto('Foto Frente-<?php echo $fotoFrenteId; ?>')">
                                    Eliminar
                                </button>
                            </div>
                        <?php } ?>
                        <input id="documentoFrente" name="documentoFrente" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <?php if (isset($fotoDorso) && $fotoDorso != "" && $fotoDorso != null) { ?>
                            <div id="FotoDorso-<?php echo $fotoDorsoId; ?>">
                                <img src="<?php echo $fotoDorso; ?>" width="300" height="300" />
                                <button type="button" class="btn-danger" onclick="eliminarFoto('Foto Dorso-<?php echo $fotoDorsoId; ?>')">
                                    Eliminar
                                </button>
                            </div>
                        <?php } ?>
                        <input id="documentoDorso" name="documentoDorso" type="file" class="form-control-file" />
                    </div>
                </div>

            <?php } ?>
			
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var  estilo = "\
    .imageWrapper { \
    position: relative; \
    //width: 300px; \
    //height: 500px; \
    width:70%; \
    display:block; \
    margin:auto; \
    } \
    .imageWrapper img { \
        display: block; \
    } \
    .imageWrapper .cornerLink { \
        opacity: 0; \
        position: absolute; \
        top: 15px; \
        left: 40px; \
        right: 40px; \
        padding: 2px 0px; \
        color: #ffffff; \
        background: #000000; \
        text-decoration: none; \
        text-align: center; \
        -webkit-transition: opacity 500ms; \
        -moz-transition: opacity 500ms; \
        -o-transition: opacity 500ms; \
        transition: opacity 500ms; \
        height: 40px; \
    } \
    .imageWrapper:hover .cornerLink {\
        opacity: 0.5; \
    }"; 

    <?php if(empty($userInSession)) { ?>
    $(".inputRadio").click(function() {
        //         alert(this.value);
        if (this.value == '<?php echo PRIMERA_VEZ; ?>') {
            $("#divRequisitosRenovacion").hide();
            $("#divRequisitosPrimeraVez").show();
            var id_dependencia = '<?php echo ID_DEP_UAD_CENTRAL; ?>';
            var desc_tipo_tramite = '<?php echo PRIMERA_VEZ; ?>';


            $.getJSON('<?php echo base_url(); ?>/turno/hayTurnoParaLaDependenciaPorTramite/'+id_dependencia+'/'+<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL; ?>+'/'+desc_tipo_tramite, function (data) {
        		if(!data) {
        			$("#divForm").hide();
        			$("#btnEnvioDatoPersonales").prop("disabled", true);
//         			var dependencia = $( "#id_dependencia option:selected" ).text();
        			var box = bootbox.alert({
//                 	    message: 'Disculpe, no hay turnos disponibles para '+dependencia+'.',
                	    message: 'Disculpe, no hay turnos disponibles para Solicitar Planilla por primera vez en la D.A.D. de la Central de Policía.',
                	    size: 'small',
                	    title: "Alerta",
                	    locale: 'es'
                	});
        		}else {
        			$("#btnEnvioDatoPersonales").show();
        			$("#btnEnvioDatoPersonales").removeAttr('disabled');
        			$("#divForm").show();
        		}		
             });
            
        } else if (this.value == '<?php echo RENOVACION; ?>') {
            
            $("#divRequisitosPrimeraVez").hide();
            $("#divRequisitosRenovacion").show();
            $("#btnEnvioDatoPersonales").show();
            $("#divForm").show();
        }
    });
    <?php }?>


    function buscarPersona(){
        let documento = document.getElementById("documento").value;
        if ( documento === "") {
            showAlert("Debe ingresar el documento", "documento");
            return;
        }

        $.blockUI({
                message: '<h2><img src="<?php echo base_url(); ?>/assets/img/loading.gif" /> Buscando...</h2>'
            });

            $.ajax({
                url: '/planillaProntuarial/getdatospersonas/' + documento,
                method: 'GET',
                contentType: 'application/json',
                type: 'json',
                global: false, //
                success: function(data) {
                    $.unblockUI();
                    if (data.status === 'OK') {
                        if ( data.persona !== null && data.persona!== "") {
                            document.getElementById("nombre").value = data.persona.nombre;
                            document.getElementById("apellido").value = data.persona.apellido;
                            document.getElementById("fecha_nacimiento").value = data.persona.fecha_nacimiento;
                            document.getElementById("cuil").value = data.persona.cuil;
                            document.getElementById("telefono").value = data.persona.telefono;
                            document.getElementById("email").value = data.persona.email;
                            document.getElementById("calle").value = data.persona.calle;
                            document.getElementById("barrio").value = data.persona.barrio;
                            document.getElementById("numero").value = data.persona.numero;
                            document.getElementById("piso").value = data.persona.piso;
                            document.getElementById("manzana").value = data.persona.manzana;  
                            document.getElementById("lote").value = data.persona.lote;
                            document.getElementById("dpto").value = data.persona.dpto
                            document.getElementById("id_departamento").value = data.persona.id_departamento;
    
                            let option = [];
                            resetaCombo('id_localidad');
                            if ( data.localidades !== undefined && data.localidades.length > 0 ) {
                                $("#id_localidad").select2("destroy");
                                $.each(data.localidades, function (i, obj) {
                                option[i] = document.createElement('option');
                                $(option[i]).attr({value: obj.id_localidad});
                                $(option[i]).append(obj.localidad);
                                console.log(option[i]);
                                $("select[name=id_localidad]").append(option[i]);
                               });
                        }
                        document.getElementById("id_localidad").value = data.persona.id_localidad;
                        $("#id_localidad").select();
                        } else {
                            showAlert("No se encontraron datos de Persona cargadas para este documento", "documento");
                        }
                        


                        
                        console.log(data);                    
                    } else {
                       alert("Se produjo un error");

                    }
                    $.unblockUI();
                },
                error: function(error) {
                    $.unblockUI();
                    alert("Se produjo un error , contacte al operador");
                }

            })

    }

    function cambioEstadoUrgente(){
        if (document.getElementById('urgente').checked){
        	$("#idLabelPrecio").show();
        	$("#idDivInputPrecio").show();
            $("#precio").attr("readonly", false);
            $("#urgente").val(1);
            $("#precio").val(1040);
        } else {
        	$("#idLabelPrecio").hide();
        	$("#idDivInputPrecio").hide();
            $("#precio").attr("readonly", true);
            $("#urgente").val(0);
            $("#precio").val(520);
        }
    }

    function eliminarFoto(parameter) {
        var values = parameter.split("-");
        var resp = confirm("Desea eliminar la imagen : " + values[0]);
        if (resp) {
            $.blockUI({
                message: '<h2><img src="<?php echo base_url(); ?>/assets/img/loading.gif" /> Eliminando...</h2>'
            });

            $.ajax({
                url: '/RenderImage/deleteImage/' + values[1],
                method: 'GET',
                contentType: 'application/json',
                type: 'json',
                global: false, //
                success: function(data) {
                    if (data.status === 'ERROR') {
                        showAlert(data.message);
                    } else {
                        showAlert("Imagen Eliminada");
                        parameter = parameter.replace(/\s/g, '');
                        $("#" + parameter).hide();

                    }
                    $.unblockUI();
                },
                error: function(error) {
                    $.unblockUI();
                    alert("Se produjo un error , contacte al operador");
                }

            })
        }
    }

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

//          var hoy = new Date();
//          var cumpleanos = new Date($("#fecha_nacimiento").val().trim()+" GMT-0300");
//          var edad = hoy.getFullYear() - cumpleanos.getFullYear();
//          var m = hoy.getMonth() - cumpleanos.getMonth();
//          var d = hoy.getDate() - cumpleanos.getDate();
//          if (m < 0 || (m == 0 && d < 0)) {
//              edad--;
//          }
//          if (edad < 18) {
//              showAlert("Debe ser mayor a 18 años para realizar este tramite ", "fecha_nacimiento");
//              return;
//          }

        
        if ($("#documento").val().trim() == '') {
            showAlert("Debe ingresar el Documento", "documento");
            return;
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
        if ($("#calle").val().trim() == '') {
            showAlert("Debe ingresar la calle, finca o ruta donde reside", "calle");
            return;
        }
        if ($("#numero").val().trim() == '') {
            showAlert("Debe ingresar el numero donde reside", "numero");
            return;
        }

        var unidadAdministrativa = document.getElementById("unidad_administrativa");
        var isVisibleDependencia = $("#div_dependencia").is(":visible");
        if (isVisibleDependencia && $("#id_dependencia").val().trim() == '' && unidadAdministrativa == null) {
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

        var fotoColor = $("#fotoColor").val().trim();

        var idFotoColor = "";
        var idTramite = $("#id_tramite").val().trim();
        

        <?php if(isset($fotoColorId)) { ?>
            idFotoColor ="FotoColor<?php echo $fotoColorId; ?>"; 
        <?php } ?>

        var elementFind = document.getElementById(idFotoColor);
        console.log("ElementFind : ", elementFind);
//         if (fotoColor == '' && idTramite === "") {
//             showAlert("Debe adjuntar una foto del rostro de frente", "fotoColor");
//             return;
//         } else if ( idTramite !== "" && ( elementFind === null  &&  elementFind?.src === "")){
//             showAlert("Debe adjuntar una foto del rostro de frente", "fotoColor");
//             return;  
//         }
        
        var isPersonaValidada1 = $("#isPersonaValidada").val().trim();
        var documentofrente = $("#documentoFrente").val().trim();
        var documentodorso = $("#documentoDorso").val().trim();
        if (isPersonaValidada1 == 'true'){
            if (documentofrente == ''){
                showAlert("Debe adjuntar una imagen del frente de su DNI", "documentoFrente");
                return;
            }
            if (documentodorso == ''){
                showAlert("Debe adjuntar una imagen del dorso de su DNI", "documentoDorso");
                return;
            }
        }

        <?php if (isset($action) && $action == "edit") { ?>
            if ($("#estado").val().trim() == '') {
                showAlert("Debe ingresar el Estado del trámite", "estado");
                return;
            }
        <?php } ?>

        return true;
    }

    function validarArchivoFotoFrente() {
        var fileInput = document.getElementById('fotoColor');    
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            showAlert('Solo se permiten archivos PNG, JPEG y JPG');
            var dfrente = typeof(fileInput.files); //Obtiene el tipo de dato de fileInput antes de que se resetee
            fileInput.value = ''; //Limpia el input de frentedocumento
            return;
        } else{
            if (dfrente != "undefined") {
                var size = parseFloat(fileInput.files[0].size/1024).toFixed(2);
                if (size >= 5120) {
                    showAlert("El tamaño del archivo no debe superar los 3MB");
                    fileInput.value = '';
                    return;
                }
            }
        }
    }

    function validarArchivoFrente() {
        var fileInput = document.getElementById('documentoFrente');    
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        if (!allowedExtensions.exec(filePath)) {
            showAlert('Solo se permiten archivos PNG, JPEG, JPG y PDF');
            var dfrente = typeof(fileInput.files); //Obtiene el tipo de dato de fileInput antes de que se resetee
            fileInput.value = ''; //Limpia el input de frentedocumento
            return;
        } else{
            if (dfrente != "undefined") {
                var size = parseFloat(fileInput.files[0].size/1024).toFixed(2);
                if (size >= 5120) {
                    showAlert("El tamaño del archivo no debe superar los 3MB");
                    fileInput.value = '';
                    return;
                }
            }
        }
    }

    function validarArchivoDorso() {
        var fileInput1 = document.getElementById('documentoDorso');      
        var filePath1 = fileInput1.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        if (!allowedExtensions.exec(filePath1)) {
            showAlert('Solo se permiten archivos PNG, JPEG, JPG y PDF');
            var ddorso = typeof(fileInput1.files); //Obtiene el tipo de dato de fileInput antes de que se resetee
            fileInput1.value = ''; //Limpia el input de frentedocumento
            return;
        } else{
            if (ddorso != "undefined") {
                var size1 = parseFloat(fileInput1.files[0].size/1024).toFixed(2);
                if (size1 >= 5120) {
                    showAlert("El tamaño del archivo no debe superar los 3MB");
                    fileInput1.value = '';
                    return;
                }
            }
        }
    }

    $("div[id^='FotoFrente']").click(function() {

        var newTab = window.open();
        frente = '';
        <?php if (!empty($fotoFrente)) { ?>
            frente = "<?php echo $fotoFrente; ?>"
        <?php } ?>
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="dni-frente.jpeg" href= "' + frente + '" ><img style="display: block; margin: auto;" src= "' + frente + '" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

    });

    $("div[id^='FotoDorso']").click(function() {

        var newTab = window.open();
        dorso = '';
        <?php if (!empty($fotoDorso)) { ?>
            dorso = "<?php echo $fotoDorso; ?>"
        <?php } ?>
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="dni-dorso.jpeg" href= "' + dorso + '" ><img style="display: block; margin: auto;" src= "' + dorso + '" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

    });

    $("div[id^='FotoColor']").click(function() {

        var newTab = window.open();
        factura = '';
        <?php if (!empty($fotoColor)) { ?>
            factura = "<?php if (isset($fotoColor)) echo $fotoColor; ?>"
        <?php } ?>
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="factura.jpeg" href= "' + factura + '" ><img style="display: block; margin: auto;" src= "' + factura + '" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

    });

    function generar(form) {
        console.log("generar");
        if (validar()) {
            $("#loading").show();
            form.submit();
        }
    }
</script>