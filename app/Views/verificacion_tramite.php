<link href="<?php echo base_url() ?>/assets/css/wizard.css?v=1" rel="stylesheet">
<div class="container-fluid" id="grad1" style="padding-top: 70px">
    <div class="row justify-content-center mt-0">
        <div class="col-9 col-sm-9 col-md-9 col-lg-9 p-0 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2 class="text-center"><strong><span class="oi oi-pencil"></span> Verificación</strong></h2>
                <div class="row">
                    <div class="col-md-12 mx-0">
                    <form action="<?php echo base_url().'/'.$controller.'/guardarVerificacion'; ?>" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="col-md-12">
    <!-- form user info -->
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
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                <div class="col-lg-9">
                    <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if (isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                <div class="col-lg-9">
                    <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if (isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
                <div class="col-lg-9">
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control mayuscula" value="<?php if (isset($fecha_nacimiento)) echo $fecha_nacimiento; ?>" placeholder="Fecha Nacimiento" required spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                <div class="col-lg-9">
                    <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" required disabled>
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
                    <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                <div class="col-lg-9">
                    <input type="number" name="cuil" id="cuil" class="form-control mayuscula" value="<?php if (isset($cuil)) echo $cuil; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" required spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Domicilio</b></label>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label" for="id_departamento">Departamento *:</label>
                <div class="col-lg-9">
                    <select name="id_departamento" id="id_departamento" class="form-control" data-toggle="tooltip" data-placement="bottom" required disabled>
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
                 <select name="id_localidad" id="id_localidad" class="form-control" data-toggle="tooltip" data-placement="bottom" required disabled>
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
                    <input type="text" name="barrio" id="barrio" class="form-control mayuscula" value="<?php if (isset($barrio)) echo $barrio; ?>" placeholder="Barrio" required spellcheck="false" disabled />
                </div>
            </div>            
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Calle *:</label>
                <div class="col-lg-6">
                    <input type="text" name="calle" id="calle" class="form-control mayuscula" value="<?php if (isset($calle)) echo $calle; ?>" placeholder="Calle / Finca / Ruta" required spellcheck="false" disabled/>
                </div>  
                
                <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Número *:</label>
                <div class="col-lg-1">
                    <input type="text" name="numero" id="numero" class="form-control" value="<?php if (isset($numero)) echo $numero; ?>" placeholder="Número" maxlength="10" required onkeypress="return isNumber(event)" spellcheck="false" disabled />
                </div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Manzana :</label>
                <div class="col-lg-1">
                    <input type="text" name="manzana" id="manzana" class="form-control mayuscula" value="<?php if (isset($manzana)) echo $manzana; ?>" placeholder="Manzana" maxlength="10" spellcheck="false" disabled />
                </div>
                <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Lote :</label>
                <div class="col-lg-1">
                    <input type="text" name="lote" id="lote" class="form-control mayuscula" value="<?php if (isset($lote)) echo $lote; ?>" placeholder="Lote" maxlength="10" spellcheck="false" disabled/>
                </div>
                
                <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0px;">Piso :</label>
                <div class="col-lg-1">
                    <input type="text" name="piso" id="piso" class="form-control mayuscula" value="<?php if (isset($piso)) echo $piso; ?>" placeholder="Piso" maxlength="3" spellcheck="false" disabled/>
                </div>

                <label class="col-lg-1 col-form-label form-control-label text-right" style="padding-right: 0;">Dpto. :</label>
                <div class="col-lg-1">
                    <input type="text" name="dpto" id="dpto" class="form-control mayuscula" value="<?php if (isset($dpto)) echo $dpto; ?>" placeholder="Dpto." maxlength="5" spellcheck="false" disabled/>
                </div>
            </div>            
            <div class="form-group row">
                <label class="col-lg-12 control-label fs-title"><b>Verificado</b></label>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-9">
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" required name="estado" id="estado" <?php if (isset($verificado) && $verificado == 'SI') {
                                                                                                                    echo "checked";
                                                                                                                 }; ?> value="<?php echo TRAMITE_VALIDADO_VERIFICADO; ?>">SI
                        </label>
                    </div>
                    <div class="form-check-inline p-3 mb-2 bg-light border rounded">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" required name="estado" id="estado" <?php if (isset($verificado) && $verificado == 'NO') {
                                                                                                                    echo "checked";
                                                                                                                 }; ?> value="<?php echo TRAMITE_NO_VERIFICADO; ?>">NO
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label form-control-label">Observaciones :</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones"><?php if(isset($observaciones)) echo $observaciones; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Verificador *:</label>
                <div class="col-lg-9">
                    <input type="text" name="verificador" id="verificador" class="form-control mayuscula" value="<?php if (isset($verificador)) echo $verificador; ?>" placeholder="Ingrese gracia del verificador" required spellcheck="false" />
                </div>
            </div>
         </div>
      </div>
   </div>
            
                        
                        <input type="hidden" name="tipoForm" value="edit"/>
                        <div class="form-group row">
                            <div class="col-lg-12 text-center">
                            	<a href="<?php echo base_url().'/'.$controller.'/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                            	<button class="btn btn-primary" type="submit"><span class="oi oi-document"></span> Guardar</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?php echo view('util_javascript.php'); ?>