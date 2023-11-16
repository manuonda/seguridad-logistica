<div class="container" style="padding-top: 70px">
<!--     <div class="col-md-10 offset-md-1"> -->
    <div class="col-md-12">
        <!-- form user info -->
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Configuración de parámetros para turnos</b></h5>
            </div>
            <?php echo form_open_multipart('turno/guardar', 'id="form"'); ?>
            <div class="card-body">
                <?= \Config\Services::validation()->listErrors('my_errors'); ?>
                <?php if (isset($error) and !empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="aa" id="aa" value="<?php if(isset($result)) echo $result; ?>" />
                <input type="hidden" name="id_turno_parametro" id="id_turno_parametro" value="<?php if(isset($id_turno_parametro)) echo $id_turno_parametro; ?>" />
                <div class="form-group row">
                    <label class="col-lg-4 control-label" for="id_dependencia">Dependencia *:</label>
                    <div class="col-lg-8">
                        <select name="id_dependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach($dependencias as $item): ?>
                                <option value="<?php echo $item['id_dependencia']?>" <?php if(isset($id_dependencia) && $id_dependencia==$item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div id="idDivTipoTramite" class="form-group row" <?php if (empty($tipo_tramite)): ?>style="display: none"<?php endif; ?>>
                    <label class="col-lg-4 control-label" for="tipo_tramite">Tipo de trámite *:</label>
                    <div class="col-lg-8">
                        <select name="tipo_tramite" id="tipo_tramite" class="form-control" data-toggle="tooltip" data-placement="bottom">
                            <option value="">-- SELECCIONAR --</option>
                            <option value="<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL.'-'.PRIMERA_VEZ; ?>" <?php if(isset($tipo_tramite) && $tipo_tramite==TIPO_TRAMITE_PLANILLA_PRONTUARIAL.'-'.PRIMERA_VEZ) echo 'selected="selected"'; ?>>PLANILLA PRONTUARIAL PRIMERA VEZ</option>
                            <option value="<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL.'-'.RENOVACION; ?>" <?php if(isset($tipo_tramite) && $tipo_tramite==TIPO_TRAMITE_PLANILLA_PRONTUARIAL.'-'.RENOVACION) echo 'selected="selected"'; ?>>PLANILLA PRONTUARIAL RENOVACION</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label form-control-label">Hora inicio atención por la mañana *:</label>
                    <div class="col-lg-8">
                        <input type="time" name="hora_inicio_atencion_mañana" id="hora_inicio_atencion_mañana" class="form-control mayuscula" value="<?php if(isset($hora_inicio_atencion_mañana)) echo $hora_inicio_atencion_mañana; ?>" required spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label form-control-label">Hora fin atención por la mañana *:</label>
                    <div class="col-lg-8">
                        <input type="time" name="hora_fin_atencion_mañana" id="hora_fin_atencion_mañana" class="form-control mayuscula" value="<?php if(isset($hora_fin_atencion_mañana)) echo $hora_fin_atencion_mañana; ?>" required spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label form-control-label">Hora inicio atención por la tarde:</label>
                    <div class="col-lg-8">
                        <input type="time" name="hora_inicio_atencion_tarde" id="hora_inicio_atencion_tarde" class="form-control mayuscula" value="<?php if(isset($hora_inicio_atencion_tarde)) echo $hora_inicio_atencion_tarde; ?>" spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label form-control-label">Hora fin atencioó por la tarde:</label>
                    <div class="col-lg-8">
                        <input type="time" name="hora_fin_atencion_tarde" id="hora_fin_atencion_tarde" class="form-control mayuscula" value="<?php if(isset($hora_fin_atencion_tarde)) echo $hora_fin_atencion_tarde; ?>" spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label form-control-label">Cantidad de turnos por hora *:</label>
                    <div class="col-lg-8">
                        <input type="number" name="cantidad_turno_por_hora" id="cantidad_turno_por_hora" class="form-control mayuscula" value="<?php if(isset($cantidad_turno_por_hora)) echo $cantidad_turno_por_hora; ?>" placeholder="Cantidad de turnos por hora" required spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</button>                        	
                        <button class="btn btn-primary" type="submit"><span class="oi oi-document"></span> Guardar</button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Configuración de Excepciones</b></h5>
            </div>
            <div class="card-body">

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="temporal" name="excepcion" class="custom-control-input" value="temporal" checked>
                    <label class="custom-control-label" for="temporal">Excepción Temporal</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="permanente" name="excepcion" class="custom-control-input" value="permanente">
                    <label class="custom-control-label" for="permanente">Excepción Permanente</label>
                </div>

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="atencion_maniana" name="atencion" class="custom-control-input" value="m" checked>
                    <label class="custom-control-label" for="atencion_maniana">Atención Mañana</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="atencion_tarde" name="atencion" class="custom-control-input" value="t">
                    <label class="custom-control-label" for="atencion_tarde">Atención Tarde</label>
                </div>

                <br><br> 

                <div class="form-group row">
                    <div id="excepcion-temporal" style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px; margin:10px;">
                        <label class="col-form-label form-control-label">Fecha:</label>
                        <div class="">
                            <input type="date" name="fecha-excepcion" id="hora_inicio_excepcion" class="form-control mayuscula" spellcheck="false" />
                        </div>
                    </div>
                    <div id="excepcion-permanente" style="display:none; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px; margin:10px;">
                        <label class="col-form-label form-control-label">Dia:</label>
                        <select name="dia" id="dia" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                            <option value="">-- SELECCIONAR --</option>
                            <option value="LUNES">LUNES</option>
                            <option value="MARTES">MARTES</option>
                            <option value="MIERCOLES">MIERCOLES</option>
                            <option value="JUEVES">JUEVES</option>
                            <option value="VIERNES">VIERNES</option>
                            <option value="SABADO">SABADO</option>
                            <option value="DOMINGO">DOMINGO</option>
                        </select>                        
                    </div>                    
                    <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px; margin:10px;">                    
                        <label class="col-form-label form-control-label">Hora inicio:</label>
                        <div class="">
                            <input type="time" name="hora_inicio_excepcion" id="hora_inicio_excepcion" class="form-control mayuscula" spellcheck="false" />
                        </div>
                    </div>
                    <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px; margin:10px;">
                        <label class="col-form-label form-control-label">Hora fin:</label>
                        <div class="">
                            <input type="time" name="hora_fin_excepcion" id="hora_fin_excepcion" class="form-control mayuscula" spellcheck="false" />
                        </div>
                        <!-- <div class="">                        
                            <button name="agregar" class="btn btn-primary" type="button"><span class="oi oi-document"></span> Agregar Excepcion</button>
                        </div> -->
                    </div>                                        
                    <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px; margin:10px;">
                        <div class="">
                            <input type="text" name="motivo_excepcion" id="motivo_excepcion" class="form-control mayuscula" spellcheck="false" placeholder="Motivo excepción"/>
                        </div>
                        <div class="">                        
                            <button name="agregar" class="btn btn-primary" type="button"><span class="oi oi-document"></span> Agregar Excepcion</button>
                        </div>
                    </div>                                                            
                </div>
                <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Fecha/Dia</th>
                    <th scope="col">Hora inicio atención</th>
                    <th scope="col">Hora fin atención</th>
                    <th scope="col">Motivo de la excepción</th>
                    <th scope="col">Acción</th>
                  </tr>
                </thead>
                <tbody id="table_excepciones_row">

                </tbody>
              </table>                
            </div>                                
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>
<div class="modal" id="modalAceptar" data-backdrop="static" data-keyboard="false" role="dialog" style="background: rgba(0,0,0,0.3);">
    <div class="modal-dialog modal-lg" style="width: 30%; margin-top: 40vh;">
        <div class="modal-content">
            <div id="modal_alert_body" class="modal-body text-center">
                <h5 class="mb-0">Se ha guardado los datos correctamente.</h5><br/>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
<div>


<script type="text/javascript">
$("#id_dependencia").select2();

$( "#btnVolver" ).click(function() {
	location.href = '<?php echo base_url(); ?>';
});

$('input[name="excepcion"]').click(function() {
    if($('input[name="excepcion"]:checked').val() == 'temporal'){        
        $('#excepcion-temporal').css("display", "flex");
        $('#excepcion-permanente').css("display", "none");
    }else{
        $('#excepcion-temporal').css("display", "none");
        $('#excepcion-permanente').css("display", "flex");
    }
});




<?php if(isset($guardar_exito) && $guardar_exito): ?>
	$("#modalAceptar").modal("show");
<?php endif; ?>

document.addEventListener("DOMContentLoaded", function(){ 

    const url = "<?php echo base_url(); ?>" + '/turnoExcepcion/get_excepcion_x_dependencia';

    $("select[name=id_dependencia]").change(function () {
        id_dependencia = $(this).val();
        if (id_dependencia === '') {
        	limpiarCampos();
            return false;
        }

        if(id_dependencia == <?php echo ID_DEP_UAD_CENTRAL ?>) {
        	limpiarCampos();
        	$("#idDivTipoTramite").show();
        	return false;
        }else {
        	$("#idDivTipoTramite").hide();   
        }
        
        $("#loading").modal("show");
        loadExcepcion(url);

        $.getJSON('<?php echo base_url(); ?>/turno/getConfigTurno/' + id_dependencia, function (data) {
            if(data == null) {
            	limpiarCampos();
                return;
            }else {
                $("#id_turno_parametro").val(data.id_turno_parametro);
                $("#id_dependencia").val(data.id_dependencia);
                if(data.hora_inicio_atencion_mañana.length > 5) {
                    data.hora_inicio_atencion_mañana = data.hora_inicio_atencion_mañana.substr(0,5);
                }
                if(data.hora_fin_atencion_mañana.length > 5) {
                    data.hora_fin_atencion_mañana = data.hora_fin_atencion_mañana.substr(0,5);
                }
                $("#hora_inicio_atencion_mañana").val(data.hora_inicio_atencion_mañana);
                $("#hora_fin_atencion_mañana").val(data.hora_fin_atencion_mañana);
                $("#hora_inicio_atencion_tarde").val(data.hora_inicio_atencion_tarde);
                $("#hora_fin_atencion_tarde").val(data.hora_fin_atencion_tarde);
                $("#cantidad_turno_por_hora").val(data.cantidad_turno_por_hora);
            }		
        });
    });

    $("select[name=tipo_tramite]").change(function () {
    	id_dependencia = $('#id_dependencia').val();
        if (id_dependencia === '') {
        	limpiarCampos();
            return false;
        }
        
    	tipo_tramite = $(this).val();
        if (tipo_tramite === '') {
        	limpiarCampos();
            return false;
        }
        
        $("#loading").modal("show");
        loadExcepcion(url);

        var tramite = tipo_tramite.split('-');

        $.getJSON('<?php echo base_url(); ?>/turno/getConfigTurnoPorTipoTramite/'+id_dependencia+'/'+tramite[0]+'/'+tramite[1], function (data) {
            if(data == null) {
            	limpiarCampos();
                return;
            }else {
                $("#id_turno_parametro").val(data.id_turno_parametro);
                $("#id_dependencia").val(data.id_dependencia);
                if(data.hora_inicio_atencion_mañana.length > 5) {
                    data.hora_inicio_atencion_mañana = data.hora_inicio_atencion_mañana.substr(0,5);
                }
                if(data.hora_fin_atencion_mañana.length > 5) {
                    data.hora_fin_atencion_mañana = data.hora_fin_atencion_mañana.substr(0,5);
                }
                $("#hora_inicio_atencion_mañana").val(data.hora_inicio_atencion_mañana);
                $("#hora_fin_atencion_mañana").val(data.hora_fin_atencion_mañana);
                $("#hora_inicio_atencion_tarde").val(data.hora_inicio_atencion_tarde);
                $("#hora_fin_atencion_tarde").val(data.hora_fin_atencion_tarde);
                $("#cantidad_turno_por_hora").val(data.cantidad_turno_por_hora);
            }		
        });
    });

    function limpiarCampos() {
    	$("#id_turno_parametro").val('');
    	$("#tipo_tramite").val('');
        $("#hora_inicio_atencion_mañana").val('');
        $("#hora_fin_atencion_mañana").val('');
        $("#hora_inicio_atencion_tarde").val('');
        $("#hora_fin_atencion_tarde").val('');
        $("#cantidad_turno_por_hora").val('');
    }    

    function loadExcepcion(url) {     
        
        $.ajax({
        url: url+'/'+document.querySelector("*[name='id_dependencia']").value,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            $("#table_excepciones_row").html(response.excepciones);

        },
        error: function(error) {

            alert('Se produjo un error : ', JSON.stringify(error));
        }
        });
    }

    $('button[name="agregar"]').click(function() {
        let fecha_dia;
        
        if($('input[name="excepcion"]:checked').val() == 'temporal'){ 
            fecha_dia = document.querySelector("*[name='fecha-excepcion']").value;
        }else{
            fecha_dia = document.querySelector("*[name='dia']").value;
        }
        let hora_inicio = document.querySelector("input[name='hora_inicio_excepcion']").value;
        let hora_fin = document.querySelector("input[name='hora_fin_excepcion']").value;
        objeto = {
            fecha_dia,
            hora_inicio,
            hora_fin,
            motivo : document.querySelector("input[name='motivo_excepcion']").value,
            atencion : $('input[name="atencion"]:checked').val(),
            id_dependencia : document.querySelector("*[name='id_dependencia']").value
        };

        $.ajax({
        url: "<?php echo base_url(); ?>" + '/turnoExcepcion/set_excepcion',
        type: 'post',
        data: objeto,
        dataType: 'json',
        success: function(response) {
            if(response.estado == 1){
                $("#modalAceptar").modal("show");
                $("#table_excepciones_row").append(response.ok);
                $("#modal_alert_body").html('<h5 class="mb-0">Se ha guardado los datos correctamente.</h5><br/>');
            }else{
                $("#modalAceptar").modal("show");
                $("#modal_alert_body").html('<h5 class="mb-0">Error: La excepcion ya se encuentra registrada</h5><br/>');
            }
        },
        error: function(error) {

            alert('Se produjo un error : ', JSON.stringify(error));
        }
        });        
    });
  
});

function quitarexcepcion(obj){
    event.preventDefault();
    
    $.ajax({
        url: obj.href,
        type: 'post',
        dataType: 'json',
        success: function(response) {
            $("#modalAceptar").modal("show");
            $("#modal_alert_body").html('<h5 class="mb-0">La excepcion se ha eliminado correctamente</h5><br/>');
            obj.closest('tr').remove();
        },
        error: function(error) {

            alert('Se produjo un error : ', JSON.stringify(error));
        }
    });    
};

</script>