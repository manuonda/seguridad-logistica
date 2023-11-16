<div class="col-md-12">
    <div class="card card-outline-secondary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-5">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio"  class="form-check-input inputRadio" style="transform: scale(1.3);" 
                                   id="tipo_planilla" <?php if (isset($dataInformation['tipo_planilla']) && $dataInformation['tipo_planilla'] == PRIMERA_VEZ) {
                                                                                                                                                                echo "checked";
                                   }; ?> value="<?php echo PRIMERA_VEZ; ?>"><b>Primera vez</b>
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);"  id="tipo_planilla" 
                            <?php if (isset($dataInformation['tipo_planilla']) && $dataInformation['tipo_planilla'] == RENOVACION) {
                                 echo "checked";
                             }; ?> value="<?php echo RENOVACION; ?>"><b>Renovación</b>
                        </label>
                    </div>
                </div>
                <label class="col-lg-2 col-form-label form-control-label">ID TRAMITE *:</label>
                <div class="col-lg-2">
                    <input type="text" readonly class="form-control mayuscula" value="<?php if (isset($dataInformation['id_tramite'])) echo $dataInformation['id_tramite']; ?>" readonly />
                </div>
            </div>
           
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                <div class="col-lg-9">
                    <input type="text" id="nombre" disabled class="form-control mayuscula" value="<?php if (isset($dataInformation['nombre'])) echo $dataInformation['nombre']; ?>" 
                       spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" disabled  id="apellido" class="form-control mayuscula" 
                      value="<?php if (isset($dataInformation['apellido'])) echo $dataInformation['apellido']; ?>" spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" disabled id="fecha_nacimiento" class="form-control mayuscula" 
                      value="<?php if (isset($dataInformation['fecha_nacimiento'])) echo $dataInformation['fecha_nacimiento']; ?>" spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nro. de DNI *:</label>
                <div class="col-lg-9">
                    <input type="text"  id="documento"
                    disabled  
                    class="form-control mayuscula" value="<?php if (isset($dataInformation['documento'])) echo $dataInformation['documento']; ?>"
                     spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                <div class="col-lg-9">
                    <input type="number"  id="cuil" class="form-control mayuscula" value="<?php if (isset($dataInformation['cuil'])) echo $dataInformation['cuil']; ?>" 
                         disabled spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select id="id_departamento" class="form-control" data-toggle="tooltip" data-placement="bottom" disabled>
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
                    <select  id="id_localidad" class="form-control" data-toggle="tooltip" data-placement="bottom" diabled>
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
                    <input type="hidden"  id="id_barrio" value="<?php if (isset($id_barrio)) echo $id_barrio; ?>" />
                    <input type="text"  disabled class="form-control mayuscula" 
                    value="<?php if (isset($dataInformation['barrio'])) echo $dataInformation['barrio']; ?>" spellcheck="false" <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio'] === SIN_BARRIO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinBarrio" <?php if (!empty($dataInformation['barrio']) && $dataInformation['barrio'] === SIN_BARRIO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinBarrio">Sin barrio</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-9">
                    <input type="text"  id="calle" class="form-control mayuscula" value="<?php if (isset($dataInformation['calle'])) echo $dataInformation['calle']; ?>" 
                      disabled spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Número *:</label>
                <div class="col-lg-2">
                    <input type="text"  id="numero" class="form-control" 
                     value="<?php if (isset($dataInformation['numero'])) echo $dataInformation['numero']; ?>" 
                     disabled onkeypress="return isNumber(event)" spellcheck="false" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero'] === SIN_NUMERO) echo 'readonly'; ?> />
                </div>
                <div class="col-lg-1 col-form-label form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkSinNumero" <?php if (!empty($dataInformation['numero']) && $dataInformation['numero'] === SIN_NUMERO) echo 'checked'; ?>>
                    <label class="form-check-label" for="checkSinNumero">Sin número</label>
                </div>
                <div class="col-lg-1 col-form-label"></div>
                <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0px;">Piso :</label>
                <div class="col-lg-1" style="padding-left: 0px;">
                    <input type="text"  id="piso" class="form-control mayuscula" value="<?php if (isset($dataInformation['piso'])) echo $dataInformation['piso']; ?>" 
                     disabled  spellcheck="false" />
                </div>
                <div class="col-lg-1 col-form-label"></div>
                <label class="col-lg-1 col-form-label form-control-label" style="padding-right: 0;">Dpto. :</label>
                <div class="col-lg-1" style="padding-left: 0px;">
                    <input type="text"  id="dpto" class="form-control mayuscula" value="<?php if (isset($dataInformation['dpto'])) echo $dataInformation['dpto']; ?>" 
                    disabled  spellcheck="false" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Para ser presentado en</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Autoridad a Presentar *:</label>
                <div class="col-lg-9">
                    <input type="text"  id="autoridad_presentar" class="form-control mayuscula" value="<?php if (isset($dataInformation['autoridad_presentar'])) echo $dataInformation['autoridad_presentar']; ?>" 
                    disabled spellcheck="false" />
                </div>
            </div>
         

            <?php if (empty($id_tramite) && empty($userInSession)) { ?>
                <div id="div_dependencia" class="form-group row">
                    <label class="col-lg-3 control-label" for="id_dependencia">Validar en *:</label>
                    <div class="col-lg-9">
                        <select  id="id_dependencia" class="form-control dependencia" data-toggle="tooltip" data-placement="bottom" disabled >
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($dependencias as $item) : ?>
                                <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($dataInformation['id_dependencia']) && $dataInformation['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else if(!empty($userInSession) && ($userInSession['id_rol']== ROL_COMISARIA_SECCIONAL || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5
                  || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL  || $userInSession['id_rol']==ROL_ANTECEDENTE || $userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA )) { ?>
            	<input type="hidden"  id="id_dependencia" value="<?php if(isset($dataInformation['id_dependencia'])) echo $dataInformation['id_dependencia']; ?>" />
            	
           
            <?php } ?>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos de contacto</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Tel&eacute;fono *:</label>
                <div class="col-lg-9">
                    <input type="number" disabled  class="form-control mayuscula" value="<?php if (isset($dataInformation['telefono'])) echo $dataInformation['telefono']; ?>" 
                    placeholder="Tel&eacute;fono, solo numeros" maxlength="20" didsabled  spellcheck="false" />
                    <input type="hidden"  value="<?php if (isset($porque_motivo)) echo $porque_motivo; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Email *:</label>
                <div class="col-lg-9">
                    <input type="text" id="email" class="form-control" value="<?php if (isset($dataInformation['email'])) echo $dataInformation['email']; ?>" 
                    disabled spellcheck="false" />
                </div>
            </div>
            <?php if (isset($action) && $action == "edit" && isset($estados)) { ?>
                <div class="card p-4 mt-5 mb-5" style="background: #AED6F1">
                    <div class="form-group row">
                        <label class="col-lg-12 control-label fs-title"><b>Estado del trámite</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="estados">Estados *:</label>
                        <div class="col-lg-9">
                            <select  disabled class="form-control" data-toggle="tooltip" data-placement="bottom">
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
                            <textarea class="form-control" disabled ><?php if(isset($dataInformation['observaciones'])) echo $dataInformation['observaciones']; ?></textarea>
                        </div>
                    </div>
                </div>
            <?php }else if (empty($action)) { ?>
            		<input type="hidden"  id="estado" value="<?php if(isset($dataInformation['estado'])) echo $dataInformation['estado']; ?>" /> 
            <?php } ?>

                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotografía 4x4 color </b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Archivo o foto :</label>
                    <div class="col-lg-9">
                        <?php if (isset($dataInformation['fotoColor']) && $dataInformation['fotoColor'] != "" && $dataInformation['fotoColor'] != null) { ?>
                            <div id="FotoColor-<?php echo $dataInformation['fotoColorId']; ?>">
                                <img id="FotoColor<?php echo $dataInformation['fotoColorId']; ?>" src="<?php echo $dataInformation['fotoColor']; ?>" width="300" height="300" />
                               
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label fs-title"><b>Fotos del Documento (Tomá fotos de tu documento por el frente y el dorso y adjuntalos a continuación)</b></label>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Frente :</label>
                    <div class="col-lg-9">

                        <?php if (isset($dataInformation['fotoFrente']) && $dataInformation['fotoFrente'] != "" && $dataInformation['fotoFrente'] != null) { ?>
                            <div">
                                <img id="dni-frente" src="<?php echo $dataInformation['fotoFrente']; ?>" width="300" height="300" />
                            </div>
                        <?php } ?>
                       
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Dorso :</label>
                    <div class="col-lg-9">
                        <?php if (isset($dataInformation['fotoDorso']) && $dataInformation['fotoDorso'] != "" && $dataInformation['fotoDorso'] != null) { ?>
                            <div id="FotoDorso-<?php echo $dataInformation['fotoDorsoId']; ?>">
                                <img src="<?php echo $dataInformation['fotoDorso']; ?>" width="300" height="300" />
                              
                            </div>
                        <?php } ?>
                    </div>
                </div>


        </div>
    </div>
</div>
<script type="text/javascript">
    $(".inputRadio").click(function() {
        //         alert(this.value);
        if (this.value == '<?php echo PRIMERA_VEZ; ?>') {
            $("#divRequisitosRenovacion").hide();
            $("#divRequisitosPrimeraVez").show();
        } else if (this.value == '<?php echo RENOVACION; ?>') {
            $("#divRequisitosPrimeraVez").hide();
            $("#divRequisitosRenovacion").show();
        }
    });

   

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
            factura = "<?php echo $fotoColor; ?>"
        <?php } ?>
        newTab.document.body.innerHTML = '<style>' + estilo + '</style><div class="imageWrapper"><a download="factura.jpeg" href= "' + factura + '" ><img style="display: block; margin: auto;" src= "' + factura + '" width="100%"></a><p class="cornerLink">CLICK PARA DESCARGAR LA IMAGEN</p></div>';

    });

   
</script>