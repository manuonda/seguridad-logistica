<div class="col-md-12">
    <div class="card card-outline-secondary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <?php if (!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA): ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">ID TRAMITE *:</label>
                    <div class="col-lg-9">
                        <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['id_tramite'])) echo $dataInformation['id_tramite']; ?>" readonly />
                    </div>
                </div>
            <?php endif;  ?>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                <div class="col-lg-9">
                    <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" placeholder="Nombre" spellcheck="false" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" placeholder="Apellido" spellcheck="false" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control mayuscula" value="<?php if (isset($dataInformation['fecha_nacimiento'])) echo $dataInformation['fecha_nacimiento']; ?>" spellcheck="false" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                <div class="col-lg-9">
                    <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil <?php if (empty($userInSession)) echo '*'; ?>:</label>
                <div class="col-lg-9">
                    <input type="text" name="cuil" id="cuil" class="form-control mayuscula" value="<?php if (isset($dataInformation['cuil'])) echo $dataInformation['cuil']; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" readonly spellcheck="false" required onkeypress="return isNumber(event)" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_pais">Nacionalidad *:</label>
                <div class="col-lg-9">
                    <select name="id_pais" id="id_pais" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($paises as $item) : ?>
                            <option value="<?php echo $item['id_pais'] ?>" <?php if (isset($dataInformation['id_pais']) && $dataInformation['id_pais'] == $item['id_pais']) echo 'selected="selected"'; ?>><?php echo $item['origen'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_estado_civil">Estado civil *:</label>
                <div class="col-lg-9">
                    <select name="id_estado_civil" id="id_estado_civil" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($estadosCiviles as $item) : ?>
                            <option value="<?php echo $item['id_estado_civil'] ?>" <?php if (isset($dataInformation['id_estado_civil']) && $dataInformation['id_estado_civil'] == $item['id_estado_civil']) echo 'selected="selected"'; ?>><?php echo $item['situacion'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Profesión *:</label>
                <div class="col-lg-9">
                    <input type="text" name="profesion" id="profesion" class="form-control mayuscula" value="<?php if (isset($dataInformation['profesion'])) echo $dataInformation['profesion']; ?>" placeholder="Profesión" maxlength="100" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">¿Donde trabaja? *:</label>
                <div class="col-lg-9">
                    <input type="text" name="lugar_de_trabajo" id="lugar_de_trabajo" class="form-control mayuscula" value="<?php if (isset($dataInformation['lugar_de_trabajo'])) echo $dataInformation['lugar_de_trabajo']; ?>" placeholder="Lugar donde trabaja" maxlength="100" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Empresa de transporte en que viaja *:</label>
                <div class="col-lg-9">
                    <input type="text" name="empresa_transporte" id="empresa_transporte" class="form-control mayuscula" value="<?php if (isset($dataInformation['empresa_transporte'])) echo $dataInformation['empresa_transporte']; ?>" placeholder="Empresa de transporte en que viaja" maxlength="100" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Motivo :</label>
                <div class="col-lg-9">
                    <input type="text" name="motivo" id="motivo" class="form-control mayuscula" value="<?php if (isset($dataInformation['motivo'])) echo $dataInformation['motivo']; ?>" placeholder="por ejemplo: corte de ruta" maxlength="100" spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select name="id_departamento" id="id_departamento" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($departamentos as $item) : ?>
                            <option value="<?php echo $item['id_departamento'] ?>" <?php if (isset($dataInformation['id_departamento']) && $dataInformation['id_departamento'] == $item['id_departamento']) echo 'selected="selected"'; ?>><?php echo $item['depto'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="localidad">Localidad *:</label>
                <div class="col-lg-9">
                 <select name="id_localidad" id="id_localidad" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                	<input type="hidden" name="id_barrio" id="id_barrio" value="<?php if (isset($dataInformation['id_barrio'])) echo $dataInformation['id_barrio']; ?>" />
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>" placeholder="Barrio" spellcheck="false" readonly/>
                </div>
                <div class="col-lg-1 col-form-label form-check responsive-element">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio']===SIN_BARRIO) echo 'checked'; ?> disabled/>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-5">
                    <input type="text" name="calle" id="calle" class="form-control mayuscula" value="<?php if (isset($dataInformation['calle'])) echo $dataInformation['calle']; ?>" placeholder="Calle / Finca / Ruta" readonly spellcheck="false" />
                </div>
                <label class="col-lg-1 col-form-label form-control-label  numero " style="padding-right: 0px;">Número *:</label>
                <div class="col-lg-2">
                    <input type="text" name="numero" id="numero" class="form-control" value="<?php if (isset($dataInformation['numero'])) echo $dataInformation['numero']; ?>" placeholder="Número" maxlength="10" readonly onkeypress="return isNumber(event)" spellcheck="false" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check responsive-element">
                  <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'checked'; ?> disabled/>
                  <label class="form-check-label" for="checkSinNumero">Sin número</label>
                </div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Manzana :</label>
                <div class="col-lg-1">
                    <input type="text" name="manzana" id="manzana" class="form-control mayuscula" value="<?php if (isset($dataInformation['manzana'])) echo $dataInformation['manzana']; ?>" placeholder="Manzana" maxlength="10" spellcheck="false" readonly/>
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Lote :</label>
                <div class="col-lg-1">
                    <input type="text" name="lote" id="lote" class="form-control mayuscula" value="<?php if (isset($dataInformation['lote'])) echo $dataInformation['lote']; ?>" placeholder="Lote" maxlength="10" spellcheck="false" readonly/>
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Piso :</label>
                <div class="col-lg-1">
                    <input type="text" name="piso" id="piso" class="form-control mayuscula" value="<?php if (isset($dataInformation['piso'])) echo $dataInformation['piso']; ?>" placeholder="Piso" maxlength="3" spellcheck="false" readonly/>
                </div>
                <label class="col-lg-1 col-form-label form-control-label responsive-label" style="padding-right: 0px;">Dpto. :</label>
                <div class="col-lg-1">
                    <input type="text" name="dpto" id="dpto" class="form-control mayuscula" value="<?php if (isset($dataInformation['dpto'])) echo $dataInformation['dpto']; ?>" placeholder="Dpto." maxlength="5" spellcheck="false" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text" name="autoridad_presentar" id="autoridad_presentar" class="form-control mayuscula" value="<?php if (isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" placeholder="Autoridad a Presentar" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha del paro de transporte *:</label>
                <div class="col-lg-9">
                    <input type="date" name="fecha_paro_transporte" id="fecha_paro_transporte" class="form-control mayuscula" value="<?php if (isset($fecha_paro_transporte)) echo $fecha_paro_transporte; ?>" required spellcheck="false" />
                </div>
            </div>       
        	<div class="form-group row">
                <label class="col-lg-3 control-label">Validar los datos en *:</label>
                <div class="col-lg-9">
                    <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
                        <option value="">-- SELECCIONAR --</option>
                        <?php foreach ($dependencias as $item) : ?>
                            <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos de contacto</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Tel&eacute;fono *:</label>
                <div class="col-lg-9">
                    <input type="number" name="telefono" id="telefono" class="form-control mayuscula" value="<?php if (isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" placeholder="Tel&eacute;fono, solo numeros" maxlength="20" readonly spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email <?php if (empty($userInSession)) echo '*'; ?>:</label>
                <div class="col-lg-9">
                    <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($dataInformation['email'])) echo $dataInformation['email']; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" spellcheck="false" readonly/>
                </div>
            </div>
            <div class="card p-4 mt-5 mb-5" style="background: #AED6F1">
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="estados">Estados *:</label>
                    <div class="col-lg-9">
                        <select name="estado" id="estado" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                        <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" disabled><?php if(isset($dataInformation['observaciones'])) echo $dataInformation['observaciones']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
