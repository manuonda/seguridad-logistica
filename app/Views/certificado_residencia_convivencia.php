<link rel="stylesheet" media="only screen and (max-width: 768px)" href="/public/assets/css/main.css">

<div class="col-md-12">
    <!-- form user info -->
    <div class="card card-outline-secondary">
        <div class="card-body">
            <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
            <input type="hidden" name="id_turno" id="id_turno" value="" />
            <input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            <input type="hidden" name="id_persona_titular" id="id_persona_titular" value="<?php if (isset($id_persona_titular)) echo $id_persona_titular; ?>" />
            <input type="hidden" name="id_persona_tutor" id="id_persona_tutor" value="<?php if (isset($id_persona_tutor)) echo $id_persona_tutor; ?>" />
            <input type="hidden" name="cntPersonasAgregadas" id="cntPersonasAgregadas" value="<?php if (isset($cntPersonasAgregadas)) echo $cntPersonasAgregadas; ?>" />
            <input type="hidden" name="tipoForm" id="tipoForm" value="<?php if (isset($tipoForm)) echo $tipoForm; ?>" />
            <input type="hidden" name="estado_pago" id="estado_pago" value="<?php if(isset($estado_pago)) echo $estado_pago; ?>" />
            <input type="hidden" name="isPersonaValidada" id="isPersonaValidada" value="false" />
            <?php if (!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA): ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">ID TRAMITE *:</label>
                    <div class="col-lg-9">
                        <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" readonly />
                    </div>
                </div>
            <?php endif;  ?>
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
                            <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if ($id_tipo_documento === $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
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
            <!-- 
            <?php //if (empty($id_tipo_documento) || (!empty($id_tipo_documento) && $id_tipo_documento==1)) { ?>
                <div class="form-group row" id="divNroTramiteDni">
                    <label class="col-lg-3 col-form-label form-control-label">N° de trámite que figura en tu DNI *:</label>
                    <div class="col-lg-5">
                        <input type="text" name="nro_tramite_dni" id="nro_tramite_dni" class="form-control mayuscula" value="<?php //if (isset($nro_tramite_dni)) echo $nro_tramite_dni; ?>" placeholder="Ingresá los 11 dígitos de tu número de trámite" maxlength="11" required spellcheck="false" />
                    </div>
                    <div class="col-lg-4">
                        <button id="linkNroTramiteDni" type="button" class="btn btn-info"><span class="oi oi-media-play"></span> Consultá el número de trámite según la versión de tu DNI</button>
                    </div>
                </div>
            <?php //} ?>
             -->
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil <?php if (empty($userInSession)) echo '*'; ?>:</label>
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
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($barrio)) echo $barrio; ?>" placeholder="Barrio" required spellcheck="false" <?php if (!empty($barrio) && $barrio===SIN_BARRIO) echo 'readonly'; ?>/>
                </div>
                <div class="col-lg-1 col-form-label form-check responsive-element">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($barrio) && $barrio===SIN_BARRIO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-6">
                    <input type="text" name="calle" id="calle" class="form-control mayuscula" value="<?php if (isset($calle)) echo $calle; ?>" placeholder="Calle / Finca / Ruta" required spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Número *:</label>
                <div class="col-lg-1">
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
                <label class="col-lg-12 control-label fs-title"><b>¿Convive solo o con otras personas?</b></label>
            </div>
            <div id="divTipoConvivencia" class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_convivencia" id="conviveSolo" value="<?php echo CONVIVE_SOLO; ?>" <?php if (isset($tipo_convivencia) && $tipo_convivencia == CONVIVE_SOLO) echo 'checked'; ?>>
                        <label class="form-check-label" for="conviveSolo"> Convivo solo/a </label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_convivencia" id="conviveConOtrasPersonas" value="<?php echo CONVIVE_CON_OTRAS_PERSONAS; ?>" <?php if (isset($tipo_convivencia) && $tipo_convivencia == CONVIVE_CON_OTRAS_PERSONAS) echo 'checked'; ?>>
                        <label class="form-check-label" for="conviveConOtrasPersonas"> Convivo con otras personas </label>
                    </div>
                </div>
            </div>
			
            <?php if (!isset($convivientes) || empty($convivientes) || (!empty($id_tramite) && isset($tipo_convivencia) && $tipo_convivencia == CONVIVE_SOLO)) : ?>
            <!--PRIMER BLOQUE SE MUESTRA AL SELECCIONAR CONVIVE CON OTROS-->
            <div id="divPersona-0" class="persona borde container" style="display: none;">
            	<input type="hidden" name="operaciones[]" id="operaciones-0" value="insert" />
            	<input type="hidden" name="id_personas[]" id="id_personas-0" value="" />
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 control-label" for="id_tipo_documento">Tipo documento *:</label><?php } ?>
                    <div class="col-sm-3">
                        <select name="id_tipo_documentos[]" id="id_tipo_documentos-0" class="form-control tipo-documentos" required>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($tipoDocumentos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($id_tipo_documento) && $id_tipo_documento == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nro. Documento *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text" name="documentos[]" id="documentos-0" class="form-control mayuscula documentos" placeholder="Nro. Documento" required spellcheck="false" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Apellido *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text" name="apellidos[]" id="apellidos-0" class="form-control mayuscula apellidos" placeholder="Apellido" required spellcheck="false" autocomplete="off" />
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nombre *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text" name="nombres[]" id="nombres-0" class="form-control mayuscula nombres" placeholder="Nombre" required spellcheck="false" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label form-control-label">Cuil :</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="number" name="cuiles[]" id="cuiles-0" class="form-control mayuscula cuiles" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" spellcheck="false" />
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Parentesco *:</label><?php } ?>
                    <div class="col-sm-3">
                        <select name="parentescos[]" id="parentescos-0" class="form-control parentescos" required>
                            <?php if ($ua->isMobile()) : ?>
                                <option value="">Parentesco</option>
                            <?php endif; ?>
                            <?php if (!$ua->isMobile()) : ?>
                                <option value="">--Seleccionar--</option>
                            <?php endif; ?>
                            <?php foreach ($tipoParentescos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_parentesco'] ?>" <?php if (isset($id_tipo_parentesco) && $id_tipo_parentesco == $item['id_tipo_parentesco']) echo 'selected="selected"'; ?>><?php echo $item['parentesco'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-form-label"></label>
                    <button type="button" class="btn btn-danger" id="eliminar-persona" data-id="divPersona-0" onclick="eliminar(this, '')">
                        <span class="oi oi-delete"></span> Eliminar Persona
                    </button>
                </div>
            </div>
            <?php endif; ?>
            <?php if (isset($convivientes) && !empty($convivientes) && isset($tipo_convivencia) && $tipo_convivencia == CONVIVE_CON_OTRAS_PERSONAS) : ?>
                <?php foreach($convivientes as $index => $conviviente): ?>
                    <div id="divPersona-<?php echo $index; ?>" class="persona borde container">
                    	<input type="hidden" name="operaciones[]" id="operaciones-<?php echo $index; ?>" value="update" />
                    	<input type="hidden" name="id_personas[]" id="id_personas-<?php echo $index; ?>" value="<?php echo $conviviente['id_persona'] ?>" />
                        <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 control-label" for="id_tipo_documento">Tipo documento *:</label><?php } ?>
                            <div class="col-sm-3">
                                <select name="id_tipo_documentos[]" id="id_tipo_documentos-<?php echo $index; ?>" class="form-control tipo-documentos" required>
                                    <option value="">-- SELECCIONAR --</option>
                                    <?php foreach ($tipoDocumentos as $item) : ?>
                                        <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if($conviviente['id_tipo_documento'] == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nro. Documento *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text" name="documentos[]" id="documentos-<?php echo $index; ?>" class="form-control mayuscula documentos" placeholder="Nro. Documento" value="<?php echo $conviviente['documento'] ?>" required spellcheck="false" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Apellido *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text" name="apellidos[]" id="apellidos-<?php echo $index; ?>" class="form-control mayuscula apellidos" placeholder="Apellido" value="<?php echo $conviviente['apellido'] ?>" required spellcheck="false" autocomplete="off" />
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nombre *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text" name="nombres[]" id="nombres-<?php echo $index; ?>" class="form-control mayuscula nombres" placeholder="Nombre" value="<?php echo $conviviente['nombre'] ?>" required spellcheck="false" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label form-control-label">Cuil :</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="number" name="cuiles[]" id="cuiles-<?php echo $index; ?>" class="form-control mayuscula cuiles" placeholder="Cuil sin guiones ni puntos, 11 digitos" value="<?php echo $conviviente['cuil'] ?>" maxlength="11" spellcheck="false" />
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Parentesco *:</label><?php } ?>
                            <div class="col-sm-3">
                                <select name="parentescos[]" id="parentescos-<?php echo $index; ?>" class="form-control parentescos" required>
                                    <?php if ($ua->isMobile()) : ?>
                                        <option value="">Parentesco</option>
                                    <?php endif; ?>
                                    <?php if (!$ua->isMobile()) : ?>
                                        <option value="">--Seleccionar--</option>
                                    <?php endif; ?>
                                    <?php foreach ($tipoParentescos as $item) : ?>
                                        <option value="<?php echo $item['id_tipo_parentesco'] ?>" <?php if($conviviente['id_tipo_parentesco'] == $item['id_tipo_parentesco']) echo 'selected="selected"'; ?>><?php echo $item['parentesco'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-form-label"></label>
                            <button type="button" class="btn btn-danger" id="eliminar-persona" data-id="divPersona-<?php echo $index; ?>" onclick="eliminar(this, '<?php echo $conviviente['id_persona'] ?>');">
                                <span class="oi oi-delete"></span> Eliminar Persona
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div id="divAgregarPersonas" class="form-group row" <?php if(empty($tipo_convivencia)) echo 'style="display: none;"'; ?>>
                <div class="col-lg-12 text-center">
                    <br />
                    <button class="btn btn-primary" type="button" id="btnAgregarPersona" onclick="agregarPersona();">
                        <span class="oi oi-plus"></span> Agregar Persona
                    </button>
                </div>
            </div>

            <div class="form-group row" style="display: none;">
                <label class="col-lg-12 control-label fs-title"><b>Tutor solicitante e interesado (opcional)</b></label>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Nombre del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" name="nombre_tutor" id="nombre_tutor" class="form-control mayuscula" value="<?php if (isset($nombre_tutor)) echo $nombre_tutor; ?>" placeholder="Nombre del tutor" spellcheck="false" />
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Apellido del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" name="apellido_tutor" id="apellido_tutor" class="form-control mayuscula" value="<?php if (isset($apellido_tutor)) echo $apellido_tutor; ?>" placeholder="Apellido del tutor" spellcheck="false" />
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 control-label" for="id_tipo_documento_tutor">Tipo documento del tutor :</label>
                <div class="col-lg-9">
                    <select name="id_tipo_documento_tutor" id="id_tipo_documento_tutor" class="form-control" data-toggle="tooltip" data-placement="bottom">
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($tipoDocumentos as $item) : ?>
                            <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($id_tipo_documento_tutor) && $id_tipo_documento_tutor == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Documento del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" name="documento_tutor" id="documento_tutor" class="form-control mayuscula" value="<?php if (isset($documento_tutor)) echo $documento_tutor; ?>" placeholder="N° DE DOCUMENTO DEL TUTOR" maxlength="15" spellcheck="false" />
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
                    <label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
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
                    <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($email)) echo $email; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" required spellcheck="false" />
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
            
            <?php if (empty($id_tramite))  { ?>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">
                        <input id="documentoFrente" name="documentoFrente" type="file" class="form-control-file" onchange="return validarArchivoFrente()" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <input id="documentoDorso" name="documentoDorso" type="file" class="form-control-file" onchange="return validarArchivoDorso()"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Factura de servicio</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Archivo o foto :</label>
                    <div class="col-lg-9">
                        <input id="facturaServicio" name="facturaServicio" type="file" class="form-control-file" />
                    </div>
                </div>
                <?php } else { ?>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">
                        
                        <?php if(isset($fotoFrente) && $fotoFrente != "" && $fotoFrente != null ){ ?>
                           <div id="FotoFrente-<?php echo $fotoFrenteId; ?>">
                           <img  src="<?php echo $fotoFrente; ?>" width="300" height="300"/>    
                           <button  type="button" class="btn-danger" onclick="eliminarFoto('Foto Frente-<?php echo $fotoFrenteId; ?>')">
                           Eliminar
                           </button>
                           </div>
                         <?php }?>
                        <input id="documentoFrente" name="documentoFrente" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <?php if(isset($fotoDorso) && $fotoDorso != "" && $fotoDorso != null ){ ?>
                            <div id="FotoDorso-<?php echo $fotoDorsoId; ?>">
                              <img src="<?php echo $fotoDorso; ?>" width="300" height="300"/>   
                              <button type="button" class="btn-danger" onclick="eliminarFoto('Foto Dorso-<?php echo $fotoDorsoId; ?>')">
                                Eliminar
                              </button> 
                            </div>
                        <?php }?>
                        <input id="documentoDorso" name="documentoDorso" type="file" class="form-control-file" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Factura de servicio</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Archivo o foto :</label>
                    <div class="col-lg-9" >
                        <?php if(isset($fotoFacturaServicio) && $fotoFacturaServicio != "" && $fotoFacturaServicio != null ){ ?>
                            <div id="FotoFacturaServicio-<?php echo $fotoFacturaServicioId; ?>">
                               <img id="fotoFacturaServicio<?php echo $fotoFacturaServicioId; ?>" src="<?php echo $fotoFacturaServicio; ?>" width="300" height="300"/>   
                               <button type="button" class="btn-danger" onclick="eliminarFoto('Foto Factura Servicio-<?php echo $fotoFacturaServicioId; ?>')">
                               Eliminar
                               </button> 
                            </div>
                        <?php }?>
                        <input id="facturaServicio" name="facturaServicio" type="file" class="form-control-file" />
                    </div>
                </div>
               
            <?php } ?>
           
        </div>
    </div>
</div>
<script type="text/javascript">

function eliminarFoto(parameter) {
       var values = parameter.split("-");
       var resp = confirm("Desea eliminar la imagen : "+values[0]);
       if ( resp) {
        $.blockUI({ message: '<h2><img src="<?php echo base_url();?>/assets/img/loading.gif" /> Eliminando...</h2>' });
        $.ajax({
          url: '/RenderImage/deleteImage/'+values[1],
          method: 'GET',
          contentType: 'application/json',
          global: false, //
          type: 'json',
          success: function(data) {
            $.unblockUI();   
            if ( data.status === 'ERROR') {
               showAlert(data.message);
            } else {
              showAlert("Imagen Eliminada");
              parameter = parameter.replace(/\s/g, '');
              $("#"+parameter).hide();

            }
            $("#tramites").unblock();
          }, error : function(error) {
            $.unblockUI();   
             alert("Se produjo un error , contacte al operador"); 
          }
          
       })
       }
   }
    $("#conviveSolo").click(function() {
        $("#divPersona-0").hide();

        var cntPersonasAgregadas = $('.persona:visible').length;
        if (cntPersonasAgregadas > 0) {
            //      alert("cnt="+cntPersonasAgregadas);
            for (let i = 1; i <= cntPersonasAgregadas; i++) {
                //          alert(i);
                $("#divPersona-" + i).remove();
            }
        }

        $("#divAgregarPersonas").hide();
    });
    $("#conviveConOtrasPersonas").click(function() {
        $("#divAgregarPersonas").show();
    });

    function agregarPersona() {
        var divPersonaCeroIsVisible = $("#divPersona-0").is(":visible");
        if (divPersonaCeroIsVisible) {
            $("#linea").show();
            var elementos = document.getElementsByClassName("persona");
            var elemento = elementos[elementos.length - 1];
            var idElemento = elemento.id;
            var array = idElemento.split("-");
            var clonePersona = $('#divPersona-0').clone();
            /*var e = document.getElementById("id_tipo_documentos-0");
            var estipodni = e.value;*
            console.log(estipodni);
            if (estipodni != 1) {
                clonePersona.find('select').val("");
            }*/
            clonePersona.find('input').val("");
            var valor = "divPersona-" + (parseInt(array[1]) + 1);
            clonePersona.attr("id", valor);
            clonePersona.find("button#eliminar-persona").css("display", "");
            clonePersona.find("button#eliminar-persona").attr("data-id", valor);

            var index = parseInt(array[1]) + 1;
            clonePersona.find("input#operaciones-0").attr("id", "operaciones-" + index);
            clonePersona.find("input#operaciones-"+index).val('insert');
            clonePersona.find("input#id_personas-0").attr("id", "id_personas-" + index);
            
            clonePersona.find("select#id_tipo_documentos-0").attr("id", "id_tipo_documentos-" + index);
            clonePersona.find("input#documentos-0").attr("id", "documentos-" + index);
            clonePersona.find("input#apellidos-0").attr("id", "apellidos-" + index);
            clonePersona.find("input#nombres-0").attr("id", "nombres-" + index);
            clonePersona.find("input#cuiles-0").attr("id", "cuiles-" + index);
            clonePersona.find("select#parentescos-0").attr("id", "parentescos-" + index);

            $(elemento).after(clonePersona);

            setColorearDiv();
        } else {
            $("#divPersona-0").show();
        }
    }

    function eliminar(ev, id_persona) {
        let idDiv = ev.getAttribute("data-id");
//         console.log(idDiv);
        if (idDiv == 'divPersona-0') {
            $("#divPersona-0").hide();
    		eliminarPersona(id_persona);
        } else {

        	eliminarPersona(id_persona);
            $("#" + idDiv).fadeOut("slow", function() {
                $("#" + idDiv).remove();
                setColorearDiv();
            });
        }
    }

    function eliminarPersona(id_persona) {
    	<?php if (!empty($id_tramite)) : ?>
        	if(id_persona != '') {
    			$.getJSON('<?php echo base_url(); ?>/certificadoResidenciaConvivencia/eliminarPersona/'+id_persona+'/'+'<?php echo $id_tramite; ?>', function (data) {
    				if(data.error) {
    					var box = bootbox.alert({
    		        	    message: data.message,
    		        	    size: 'small',
    		        	    title: "Alerta",
    		        	    locale: 'es'
    		        	});
    				}
    		     });
    		}
    	<?php endif; ?>
    }    

    function setColorearDiv() {
        elementos = null;
        elementos = document.getElementsByClassName("persona");
        if (elementos != null && elementos.length > 0) {
            for (let i = 0; i < elementos.length; i++) {
                console.log(elementos[i]);
                var elemento = elementos[i];
                var idElemento = elemento.id;
                var array = idElemento.split("-");
                var numero = parseInt(array[1]) + 1;
                var resultado = i % 2;
                console.log("resultad =", resultado);
                if (resultado == 0) {
                    elemento.setAttribute("style", "background: #f4e1d2");
                } else {
                    elemento.setAttribute("style", "background: #fff");
                }
            }
        }
    }

    function validar() {
        if ($("#nombre").val().trim() == '') {
            showAlert("Debe ingresar su nombre", "nombre");
            return;
        }
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
//         if (id_tipo_documento == 1) { // Si el tipo doc es Dni
//             if ($("#nro_tramite_dni").val().trim() == '') {
//                 showAlert("Debe ingresar el N° de trámite que figura en tu DNI", "nro_tramite_dni");
//                 return;
//             }
//         }

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

        if (!$('input[name="tipo_convivencia"]').is(':checked')) {
            showAlert("Debe seleccionar ¿Convive solo o con otras personas?", "conviveSolo");
            return;
        }

        var cntPersonasAgregadas = $('.persona:visible').length;
        $("#cntPersonasAgregadas").val(cntPersonasAgregadas);

        if (cntPersonasAgregadas > 0) {
            var errorTipoDocs = false;
            var errorTipoDocsId = false;
            $(".tipo-documentos").each(function() {
                if ($(this).val().trim() == '') {
                    errorTipoDocs = true;
                    errorTipoDocsId = $(this).attr('id');
                    return false;
                }
            });

            if (errorTipoDocs) {
                showAlert("¡Debe ingresar los Tipo de documentos de todas las personas con las cuales convive!", errorTipoDocsId);
                return;
            }

            var errorDoc1 = false;
            var errorDoc2 = false;
            var errorDocId = false;
            $(".documentos").each(function() {
                if ($(this).val().trim() == '') {
                    errorDoc1 = true;
                    errorDocId = $(this).attr('id');
                    return false;
                } else if ($(this).val().length < 6) {
                    errorDoc2 = true;
                    errorDocId = $(this).attr('id');
                    return false;
                }
            });
            if (errorDoc1) {
                showAlert("¡Debe ingresar los Números de documentos de todas las personas con las cuales convive!", errorDocId);
                return;
            } else if (errorDoc2) {
                showAlert("¡El número de documento ingresado no es válido!", errorDocId);
                return;
            }

            var errorApellido = false;
            var errorApellidoId = false;
            $(".apellidos").each(function() {
                if ($(this).val().trim() == '') {
                    errorApellido = true;
                    errorApellidoId = $(this).attr('id');
                    return false;
                }
            });
            if (errorApellido) {
                showAlert("¡Debe ingresar los Apellidos de todas las personas con las cuales convive!", errorApellidoId);
                return;
            }

            var errorNombre = false;
            var errorNombreId = false;
            $(".nombres").each(function() {
                if ($(this).val().trim() == '') {
                    errorNombre = true;
                    errorNombreId = $(this).attr('id');
                    return false;
                }
            });
            if (errorNombre) {
                showAlert("¡Debe ingresar los Nombres de todas las personas con las cuales convive!", errorNombreId);
                return;
            }

//             var errorCuil = false;
//             var errorCuilId = false;
//             $(".cuiles").each(function() {
//                 if ($(this).val().trim() == '') {
//                     errorCuil = true;
//                     errorCuilId = $(this).attr('id');
//                     return false;
//                 }
//             });
//             if (errorCuil) {
//                 showAlert("¡Debe ingresar el Cuil de todas las personas con las cuales convive!", errorCuilId);
//                 return;
//             }

            var errorParentesco = false;
            var errorParentescoId = false;
            $(".parentescos").each(function() {
                if ($(this).val().trim() == '') {
                    errorParentesco = true;
                    errorParentescoId = $(this).attr('id');
                    return false;
                }
            });
            if (errorParentesco) {
                showAlert("¡Debe ingresar el Parentesco de todas las personas con las cuales convive!", errorParentescoId);
                return;
            }
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
                    showAlert("El tamaño del archivo no debe superar los 5MB");
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
                    showAlert("El tamaño del archivo no debe superar los 5MB");
                    fileInput1.value = '';
                    return;
                }
            }
        }
    }

    function generar(form){
        if ( validar()) {
             
         $("#loading").show();
          form.submit();  
        }
    }

    estilo = "\
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

    $("div[id^='FotoFrente']").click(function(){

        var newTab = window.open();
        frente = '';
        <?php if (!empty($fotoFrente)){ ?>
        frente = "<?php echo $fotoFrente; ?>"
        <?php } ?>
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="dni-frente.jpeg" href= "'+ frente +'" ><img style="display: block; margin: auto;" src= "'+ frente +'" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

        

    });

    $("div[id^='FotoDorso']").click(function(){

        var newTab = window.open();
        dorso = '';
        <?php if (!empty($fotoDorso)){ ?>
        dorso = "<?php echo $fotoDorso; ?>"
        <?php } ?>        
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="dni-dorso.jpeg" href= "'+ dorso +'" ><img style="display: block; margin: auto;" src= "'+ dorso +'" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

    });

    $("div[id^='FotoFacturaServicio']").click(function(){

        var newTab = window.open();
        factura = '';
        <?php if (!empty($fotoFacturaServicio)){ ?>
        factura = "<?php echo $fotoFacturaServicio; ?>"
        <?php } ?>                
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="factura.jpeg" href= "'+ factura +'" ><img style="display: block; margin: auto;" src= "'+ factura +'" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';


    });                
</script>
