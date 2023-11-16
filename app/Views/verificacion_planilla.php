<link href="<?php echo base_url() ?>/assets/css/wizard.css?v=1" rel="stylesheet">
<div class="container-fluid" id="grad1" style="padding-top: 40px">
    <div class="row justify-content-center mt-0">
        <div class="col-9 col-sm-9 col-md-9 col-lg-9 p-0 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2 class="text-center"><strong><span class="oi oi-pencil"></span> Verificación</strong></h2>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form action="<?php echo base_url() . '/planillaProntuarial/guardarVerificacion'; ?>" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                            <div class="col-md-12">
                                <!-- form user info -->
                                <div class="card card-outline-secondary">
                                    <div class="card-body">
                                        <?= \Config\Services::validation()->listErrors('my_errors'); ?>
                                        <?php if (isset($error) and !empty($error)) : ?>
                                            <div class="alert alert-danger">
                                                <?php echo $error; ?>
                                            </div>
                                        <?php endif; ?>
                                        <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
                                        <input type="hidden" name="id_tramite_planilla_detalle" id="id_tramite_planilla_detalle" value="<?php if (isset($id_tramite_planilla_detalle)) echo $id_tramite_planilla_detalle; ?>" />
                                        <input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
                                        <input type="hidden" name="cuil" id="cuil" value="<?php if (isset($cuil)) echo $cuil; ?>" />
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label fs-title">Planilla</label>
                                            <div class="col-lg-5">
                                                <div class="form-check-inline p-3 mb-2 bg-light border rounded" <?php if (isset($tipo_planilla) && $tipo_planilla == RENOVACION) {
                                                                                                                    echo 'style="display: none"';
                                                                                                                }; ?>>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" name="tipo_planilla" id="tipo_planilla" <?php if (isset($tipo_planilla) && $tipo_planilla == PRIMERA_VEZ) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }; ?> value="<?php echo PRIMERA_VEZ; ?>"><b>Primera vez</b>
                                                    </label>
                                                </div>
                                                <div class="form-check-inline p-3 mb-2 bg-light border rounded" <?php if (isset($tipo_planilla) && $tipo_planilla == PRIMERA_VEZ) {
                                                                                                                    echo 'style="display: none"';
                                                                                                                }; ?>>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input inputRadio" style="transform: scale(1.3);" name="tipo_planilla" id="tipo_planilla" <?php if (isset($tipo_planilla) && $tipo_planilla == RENOVACION) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }; ?> value="<?php echo RENOVACION; ?>"><b>Renovación</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <label class="col-lg-2 col-form-label fs-title">Nro. de Trámite</label>
                                            <div class="col-lg-2">
                                                <input type="text" name="id_tramite" id="id_tramite" class="form-control mayuscula" readonly value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" placeholder="Nro. de Tramite" required spellcheck="false" />
                                            </div>
                                        </div>
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
                                            <label class="col-lg-3 col-form-label form-control-label">Nro. de DNI *:</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false" disabled />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Cuil *:</label>
                                            <div class="col-lg-9">
                                                <input type="number" name="cuil2" id="cuil2" class="form-control mayuscula" value="<?php if (isset($cuil)) echo $cuil; ?>" placeholder="Cuil sin guiones ni puntos, 11 digitos" maxlength="11" required spellcheck="false" disabled />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Estado del trámite *:</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="estado" id="estado" class="form-control mayuscula" value="<?php if (isset($estado)) echo $estado; ?>" placeholder="Estado del trámite" required spellcheck="false" disabled />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-12 control-label fs-title"><b>Verificación de antecedentes</b></label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Prontuario :</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="num_prontuario" id="num_prontuario" class="form-control mayuscula" value="<?php if (isset($num_prontuario)) echo $num_prontuario; ?>" placeholder="Nro. de Prontuario" required spellcheck="false" <?php echo $disabled; ?>  onkeypress="return isNumber(event)"/>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="text" name="letra_prontuario" id="letra_prontuario" class="form-control mayuscula" value="<?php if (isset($letra_prontuario)) echo $letra_prontuario; ?>" placeholder="Nomenclatura de Prontuario" required spellcheck="false" <?php echo $disabled; ?> />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Observaciones :</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" <?php echo $disabled; ?>><?php if (isset($observaciones)) echo $observaciones; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- informacion 1 -->

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Agregar Informe :</label>
                                            <div class="col-lg-9">
                                                <input id="archivoInforme" name="archivoInforme" type="file" class="form-control-file" />
                                                <br>
                                                <?php if (isset($tramiteArchivo) && $tramiteArchivo != "" && $tramiteArchivo != null) { ?>
                                                    <div id="Descargar">
                                                        <a href="<?php echo base_url() . "/planillaProntuarial/descargarTramiteArchivo/" . $tramiteArchivoId ?>" class="btn btn-primary" title="Descargar <?php echo $nombreArchivo; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"></path>
                                                            </svg> <?php echo $nombreArchivo; ?>
                                                        </a>
                                                    </div>

                                                    <!-- <div id="TramiteArchivo-<?php echo $tramiteArchivoId; ?>">
                           <img  id="tramite-archivo" src="<?php echo $tramiteArchivo; ?>" width="300" height="300"/>  
                           <button  type="button" class="btn-danger" onclick="eliminarFoto('Tramite Archivo-<?php echo $tramiteArchivoId; ?>')">
                            De
                           </button>
                           </div> -->
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- informacion 1 /end -->



                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Antecedentes penales *:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="antecedentes_penales" id="antecedentes_penales" placeholder="Antecedentes penales" <?php echo $disabled; ?>><?php if (isset($antecedentes_penales)) echo $antecedentes_penales; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Antecedentes policiales *:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="antecedentes_policiales" id="antecedentes_policiales" placeholder="Antecedentes policiales" <?php echo $disabled; ?>><?php if (isset($antecedentes_policiales)) echo $antecedentes_policiales; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Verificador *:</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="verificador" id="verificador" class="form-control mayuscula" readonly value="<?php if (isset($verificador)) echo $verificador; ?>" placeholder="Ingrese gracia del verificador" required spellcheck="false" />
                                            </div>
                                        </div>
                                        <div class="card p-4 mt-5 mb-5" style="background: #AED6F1">
                                            <div class="form-group row">
                                                <label class="col-lg-12 control-label fs-title"><b>Estado de la verificación de antecedentes</b></label>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-3 control-label" for="estado_verificacion">Estado *:</label>
                                                <div class="col-lg-9">
                                                    <select name="estado_verificacion" id="estado_verificacion" class="form-control" data-toggle="tooltip" data-placement="bottom" <?php echo $disabled; ?>>
                                                        <option value="">-- SELECCIONAR --</option>
                                                        <option value="<?php echo TRAMITE_PENDIENTE_VERIFICACION; ?>" <?php if (isset($estado_verificacion) && $estado_verificacion == TRAMITE_PENDIENTE_VERIFICACION) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_PENDIENTE_VERIFICACION . '  '; ?></option>
                                                        <option value="<?php echo TRAMITE_VERIFICADO; ?>" <?php if (isset($estado_verificacion) && $estado_verificacion == TRAMITE_VERIFICADO) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO . '  '; ?></option>
                                                        <option value="<?php echo TRAMITE_VERIFICADO_CON_OBSERVACION; ?>" <?php if (isset($estado_verificacion) && $estado_verificacion == TRAMITE_VERIFICADO_CON_OBSERVACION) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO_CON_OBSERVACION . '  '; ?></option>
                                                        <option value="<?php echo TRAMITE_VERIFICADO_CON_INFORME; ?>" <?php if (isset($estado_verificacion) && $estado_verificacion == TRAMITE_VERIFICADO_CON_INFORME) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO_CON_INFORME . '  '; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="tipoForm" value="edit" />
                            <div class="form-group row">
                                <div class="col-lg-12 text-center">
                                    <a href="<?php echo base_url() . '/planillaProntuarial/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>

                                    <?php if (!empty($userInSession) && $userInSession['id_rol'] == ROL_ANTECEDENTE) { ?>
                                        <button class="btn btn-primary" type="submit"><span class="oi oi-document"></span> Guardar</button>
                                    <?php } ?>
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

<script>
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
</script>