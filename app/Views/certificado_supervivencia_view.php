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
                    <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" readonly  />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" reaonly class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" class="form-control mayuscula" value="<?php if (isset($dataInformation['fecha_nacimiento'])) echo $dataInformation['fecha_nacimiento']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                <div class="col-lg-9">
                    <select disabled class="form-control" data-toggle="tooltip" data-placement="bottom" readonly>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($tipoDocumentos as $item) : ?>
                            <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($dataInformation['id_tipo_documento']) && $dataInformation['id_tipo_documento'] == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>" readonly />
                </div>
            </div>
            <!-- 
            <div class="form-group row" id="divNroTramiteDni">
                <label class="col-lg-3 col-form-label form-control-label">N° de trámite que figura en tu DNI *:</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control mayuscula" value="<?php // if (isset($dataInformation['nro_tramite_dni'])) echo $dataInformation['nro_tramite_dni']; ?>" readonly />
                </div>
            </div>
             -->
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                <div class="col-lg-9">
                    <input type="number" class="form-control mayuscula" value="<?php if (isset($dataInformation['cuil'])) echo $dataInformation['cuil']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>¿Cómo va a certificar la supervivencia?</b></label>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-9">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" <?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "SE HACE PRESENTE") {
                                                                                echo "checked";
                                                                            }; ?>  value="SE HACE PRESENTE">Se hace presente
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" <?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "SE ENCUENTRA EN") {
                                                                                                                                echo "checked";
                                                                                                                            }; ?> value="SE ENCUENTRA EN">Se encuentra en
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" <?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") {
                                                                                                                                echo "checked";
                                                                                                                            }; ?> value="EN DOMICILIO">En domicilio
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row" id="divTituloDomicilio" 
                style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row" id="divDepartamento" style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($departamentos as $item) : ?>
                            <option value="<?php echo $item['id_departamento'] ?>" <?php if (isset($dataInformation['id_departamento']) && $dataInformation['id_departamento'] == $item['id_departamento']) echo 'selected="selected"'; ?>><?php echo $item['depto'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="divLocalidad" style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>">
                <label class="col-lg-3 control-label" for="localidad">Localidad *:</label>
                <div class="col-lg-9">
                     <select disabled class="form-control" data-toggle="tooltip" data-placement="bottom">
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($localidades as $item) : ?>
                            <option value="<?php echo $item['id_localidad'] ?>" <?php if (isset($dataInformation['id_localidad']) && $dataInformation['id_localidad'] == $item['id_localidad']) echo 'selected="selected"'; ?>><?php echo $item['localidad'] ?></option>
                        <?php endforeach; ?>
                     </select>
                </div>
            </div>
            <div class="form-group row" id="divBarrio" style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>">
                <label class="col-lg-3 col-form-label form-control-label">Barrio *:</label>
                <div class="col-lg-8">
                	<input type="hidden"  value="<?php if (isset($dataInformation['id_barrio'])) echo $dataInformation['id_barrio']; ?>" />
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>" readonly/>
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio']===SIN_BARRIO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row" id="divDomicilio" style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>"> 
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-9">
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['calle'])) echo $dataInformation['calle']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row" id="divNumero" style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "EN DOMICILIO") echo ""; else echo "none"; ?>">
            	<label class="col-lg-3 col-form-label form-control-label">Número *:</label>
                <div class="col-lg-2">
                    <input type="text"  class="form-control" value="<?php if (isset($dataInformation['numero'])) echo $dataInformation['numero']; ?>" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'readonly'; ?> readonly />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                  <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'checked'; ?>>
                  <label class="form-check-label" for="checkSinNumero">Sin número</label>
                </div>
                <div class="col-lg-1 col-form-label"></div>
                <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0px;">Piso :</label>
                <div class="col-lg-1" style="padding-left: 0px;">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['piso'])) echo $dataInformation['piso']; ?>"  readonly/>
                </div>
                <div class="col-lg-1 col-form-label"></div>
                <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0;">Dpto. :</label>
                <div class="col-lg-1" style="padding-left: 0px;">
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['dpto'])) echo $dataInformation['dpto']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row" id="divLugarDondeSeEncuentra" 
                style="display:<?php if (isset($dataInformation['tipo_supervivencia']) && $dataInformation['tipo_supervivencia'] == "SE ENCUENTRA EN") echo ""; else echo "none"; ?>">
                <label class="col-lg-3 control-label" for="lugar_donde_se_encuentra">Lugar donde se encuentra *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['lugar_donde_se_encuentra'])) echo $dataInformation['lugar_donde_se_encuentra']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-12 control-label fs-title"><b>Tutor solicitante e interesado (opcional)</b></label>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Nombre del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre_tutor'])) echo $dataInformation['nombre_tutor']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Apellido del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido_tutor'])) echo $dataInformation['apellido_tutor']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 control-label" for="id_tipo_documento_tutor">Tipo documento del tutor :</label>
                <div class="col-lg-9">
                    <select disabled class="form-control" data-toggle="tooltip" data-placement="bottom">
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($tipoDocumentos as $item) : ?>
                            <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($dataInformation['id_tipo_documento_tutor']) && $dataInformation['id_tipo_documento_tutor'] == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Documento del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento_tutor'])) echo $dataInformation['documento_tutor']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" readonly/>
                </div>
            </div>
            <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                <div id="div_dependencia" class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                        <select disabled class="form-control dependencia" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_COMISARIA_SECCIONAL) { ?>
            	<input type="hidden"  value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" />
            	
            <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) { ?>
            	<div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                    	<input type="hidden"  value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" />
                        <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="number"  class="form-control mayuscula" value="<?php if (isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" readonly/>
                    <input type="hidden"  value="<?php if (isset($dataInformation['porque_motivo'])) echo $dataInformation['porque_motivo']; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email :</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="<?php if (isset($dataInformation['email'])) echo $dataInformation['email']; ?>" readonly />
                </div>
            </div>

            <?php if (isset($action) && ($action == "edit" || $action == "new") && isset($estados)) { ?>
            	<div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="estados">Estados *:</label>
                    <div class="col-lg-9">
                        <select disabled class="form-control" data-toggle="tooltip" data-placement="bottom">
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
                        <textarea class="form-control" readonly placeholder="Observaciones"><?php if(isset($dataInformation['observaciones'])) echo $dataInformation['observaciones']; ?></textarea>
                    </div>
                </div>
            <?php }else if (empty($action)) { ?>
            		<input type="hidden" name="estado" id="estado" value="<?php if(isset($dataInformation['estado'])) echo $dataInformation['estado']; ?>" /> 
            <?php } ?>
        </div>
    </div>
</div>
