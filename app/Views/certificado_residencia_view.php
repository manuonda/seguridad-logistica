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
                    <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date"  class="form-control mayuscula" value="<?php if (isset($dataInformation['fecha_nacimiento'])) echo $dataInformation['fecha_nacimiento']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                <div class="col-lg-9">
                    <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>" disabled />
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
                <br />
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
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
                	<input type="hidden" value="<?php if (isset($dataInformation['id_barrio'])) echo $dataInformation['id_barrio']; ?>" />
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>" readonly/>
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
            <div class="form-group row" style="display: none;">
                <label class="col-lg-12 control-label fs-title"><b>Tutor solicitante e interesado (opcional)</b></label>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Nombre del tutor :</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre_tutor'])) echo $dataInformation['nombre_tutor']; ?>" readonly />
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
                    <select class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['documento_tutor'])) echo $dataInformation['documento_tutor']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" readonly />
                </div>
            </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Validar los datos en *:</label>
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
                    <input type="number"  class="form-control mayuscula" value="<?php if (isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" readonly/>
                    <input type="hidden" value="<?php if (isset($dataInformation['porque_motivo'])) echo $dataInformation['porque_motivo']; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email *:</label>
                <div class="col-lg-9">
                    <input type="text"class="form-control" value="<?php if (isset($dataInformation['email'])) echo $dataInformation['email']; ?>" readonly />
                </div>
            </div>
          
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
                        <textarea class="form-control" disabled  id="observaciones" placeholder="Observaciones"><?php if(isset($dataInformation['observaciones'])) echo $dataInformation['observaciones']; ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">
                        
                        <?php if(isset($dataInformation['fotoFrente']) && $dataInformation['fotoFrente'] != "" && $dataInformation['fotoFrente'] != null ){ ?>
                           <div>
                           <img  src="<?php echo $dataInformation['fotoFrente']; ?>" width="300" height="300"/>    
                           </div>
                         <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <?php if(isset($dataInformation['fotoDorso']) && $dataInformation['fotoDorso'] != "" && $dataInformation['fotoDorso'] != null ){ ?>
                            <div>
                              <img src="<?php echo $dataInformation['fotoDorso']; ?>" width="300" height="300"/>   
                             </div>
                        <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Factura de servicio</b></label>
                </div>
                <div class="form-group row">
                    <div class="col-lg-9" >
                        <?php if(isset($dataInformation['fotoFacturaServicio']) && $dataInformation['fotoFacturaServicio'] != "" && $dataInformation['fotoFacturaServicio'] != null ){ ?>
                            <div>
                               <img src="<?php echo $dataInformation['fotoFacturaServicio']; ?>" width="300" height="300"/>   
                              
                            </div>
                        <?php }?>
                    </div>
                </div>
         
        </div>

    </div>
</div>
