<div class="col-md-12">
    <!-- form user info -->
    <div class="card card-outline-secondary">
        <div class="card-body">
        <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">ID TRAMITE *:</label>
                <div class="col-lg-9">
                    <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['id_tramite'])) echo $dataInformation['id_tramite']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                <div class="col-lg-9">
                    <input type="text" id="nombre" class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" id="apellido" class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" class="form-control mayuscula" value="<?php if (isset($dataInformation['fecha_nacimiento'])) echo $dataInformation['fecha_nacimiento']; ?>"  readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                <div class="col-lg-9">
                    <select id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>"  readonly/>
                </div>
            </div>
            <!-- 
            <div class="form-group row" id="divNroTramiteDni">
                <label class="col-lg-3 col-form-label form-control-label">N° de trámite que figura en tu DNI *:</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control mayuscula" value="<?php //if (isset($dataInformation['nro_tramite_dni'])) echo $dataInformation['nro_tramite_dni']; ?>"  readonly/>
                </div>
            </div>
             -->
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                <div class="col-lg-9">
                    <input type="number"  class="form-control mayuscula" value="<?php if (isset($dataInformation['cuil'])) echo $dataInformation['cuil']; ?>"  readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select  class="form-control" data-toggle="tooltip" data-placement="bottom" disabled >
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
                 <select  class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                	<input type="hidden" value="<?php if (isset($dataInformation['id_barrio'])) echo $dataInformation['id_barrio']; ?>" readonly/>
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>"  readonly />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" readonly <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio']===SIN_BARRIO) echo 'checked'; ?>>
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
                <div class="col-lg-1 col-form-label form-check">
                  <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero']===SIN_NUMERO) echo 'checked'; ?>>
                  <label class="form-check-label" for="checkSinNumero">Sin número</label>
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
                <label class="col-lg-12 control-label fs-title"><b>¿Convive solo o con otras personas?</b></label>
            </div>
            <div id="divTipoConvivencia" class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="<?php echo CONVIVE_SOLO; ?>" <?php if (isset($dataInformation['tipo_convivencia']) && $dataInformation['tipo_convivencia'] == CONVIVE_SOLO) echo 'checked'; ?> readonly>  
                        <label class="form-check-label" for="conviveSolo"> Convivo solo/a </label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"  value="<?php echo CONVIVE_CON_OTRAS_PERSONAS; ?>" <?php if (isset($dataInformation['tipo_convivencia']) && $dataInformation['tipo_convivencia'] == CONVIVE_CON_OTRAS_PERSONAS) echo 'checked'; ?> readonly>
                        <label class="form-check-label" for="conviveConOtrasPersonas"> Convivo con otras personas </label>
                    </div>
                </div>
            </div>
			
            <?php if (empty($id_tramite) || (!empty($id_tramite) && isset($dataInformation['tipo_convivencia']) && $dataInformation['tipo_convivencia'] == CONVIVE_SOLO)) : ?>
            <div id="divPersona-0" class="persona borde container" style="display: none;">
            	
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 control-label" for="id_tipo_documento">Tipo documento *:</label><?php } ?>
                    <div class="col-sm-3">
                        <select  class="form-control tipo-documentos" disabled >
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($tipoDocumentos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_documento'] ?>"><?php echo $item['tipo_documento'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nro. Documento *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text"  class="form-control mayuscula documentos"  readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Apellido *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text"  class="form-control mayuscula apellidos"  readonly/>
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nombre *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="text" class="form-control mayuscula nombres" readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label form-control-label">Cuil *:</label><?php } ?>
                    <div class="col-sm-3">
                        <input type="number"  id="cuiles-0" class="form-control mayuscula cuiles" readonly  />
                    </div>
                    <div class="col-sm-2 col-form-label"></div>
                    <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Parentesco *:</label><?php } ?>
                    <div class="col-sm-3">
                        <select  class="form-control parentescos" disabled>
                            <?php if ($ua->isMobile()) : ?>
                                <option value="">Parentesco</option>
                            <?php endif; ?>
                            <?php if (!$ua->isMobile()) : ?>
                                <option value="">--Seleccionar--</option>
                            <?php endif; ?>
                            <?php foreach ($tipoParentescos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_parentesco'] ?>" <?php if (isset($dataInformation['id_tipo_parentesco']) && $dataInformation['id_tipo_parentesco'] == $item['id_tipo_parentesco']) echo 'selected="selected"'; ?>><?php echo $item['parentesco'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
             
            </div>
            <?php endif; ?>
            <?php if (!empty($id_tramite) && isset($tipo_convivencia) && $tipo_convivencia == CONVIVE_CON_OTRAS_PERSONAS) : ?>
                <?php foreach($dataInformation['convivientes'] as $index => $conviviente): ?>
                    <div id="divPersona-<?php echo $index; ?>" class="persona borde container">
                    	 <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 control-label" for="id_tipo_documento">Tipo documento *:</label><?php } ?>
                            <div class="col-sm-3">
                                <select  id="id_tipo_documentos-<?php echo $index; ?>" class="form-control tipo-documentos" disabled>
                                    <option value="">-- SELECCIONAR --</option>
                                    <?php foreach ($tipoDocumentos as $item) : ?>
                                        <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if($conviviente['id_tipo_documento'] == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nro. Documento *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text"  id="documentos-<?php echo $index; ?>" class="form-control mayuscula documentos"  value="<?php echo $conviviente['documento'] ?>" readonly/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Apellido *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text"  id="apellidos-<?php echo $index; ?>" class="form-control mayuscula apellidos" placeholder="Apellido" value="<?php echo $conviviente['apellido'] ?>" readonly />
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Nombre *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="text" id="nombres-<?php echo $index; ?>" class="form-control mayuscula nombres" placeholder="Nombre" value="<?php echo $conviviente['nombre'] ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label form-control-label">Cuil *:</label><?php } ?>
                            <div class="col-sm-3">
                                <input type="number"  id="cuiles-<?php echo $index; ?>" class="form-control mayuscula cuiles" placeholder="Cuil sin guiones ni puntos, 11 digitos" value="<?php echo $conviviente['cuil'] ?>" readonly />
                            </div>
                            <div class="col-sm-2 col-form-label"></div>
                            <?php if (!$ua->isMobile()) { ?><label class="col-sm-2 col-form-label">Parentesco *:</label><?php } ?>
                            <div class="col-sm-3">
                                <select  id="parentescos-<?php echo $index; ?>" class="form-control parentescos" disabled>
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
                     
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
          

            <div class="form-group row" style="display: none;">
                <label class="col-lg-12 control-label fs-title"><b>Tutor solicitante e interesado (opcional)</b></label>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Nombre del tutor :</label>
                <div class="col-lg-9">
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre_tutor'])) echo $dataInformation['nombre_tutor']; ?>"  readonly/>
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-lg-3 col-form-label form-control-label">Apellido del tutor :</label>
                <div class="col-lg-9">
                    <input type="text"  class="form-control mayuscula" value="<?php if (isset($dataInformation['apellido_tutor'])) echo $dataInformation['apellido_tutor']; ?>" readonly />
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
                    <input id="documento_tutor" class="form-control mayuscula" value="<?php if (isset($dataInformation['documento_tutor'])) echo $dataInformation['documento_tutor']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control mayuscula" value="<?php if (isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" readonly/>
                </div>
            </div>
            <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                <div id="div_dependencia" class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                        <select  class="form-control dependencia" data-toggle="tooltip" data-placement="bottom" disabled>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_COMISARIA_SECCIONAL) { ?>
                <input type="hidden"  value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" readonly />
                
            <?php } else if(!empty($userInSession) && $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) { ?>
                <div class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Verificar y validar en *:</label>
                    <div class="col-lg-9">
                        <input type="hidden"  value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" />
                        <select id="dep" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <input type="number" class="form-control mayuscula" value="<?php if (isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email *:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="<?php if (isset($dataInformation['email'])) echo $dataInformation['email']; ?>" readonly />
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
                        <textarea  readonly class="form-control"><?php if(isset($dataInformation['observaciones'])) echo $dataInformation['observaciones']; ?></textarea>
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
                    <label class="col-lg-3 control-label fs-title"><b>Factura de servicio</b></label>
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