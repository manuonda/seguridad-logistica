
    <div class="col-md-12">
        <div class="card card-outline-secondary">
            <div id="alert"></div>
            <div class="card-body text-center">
            	<span style="float: left;" id="token"></span>
        		
                <!-- div id="flujoNormal" style="display: block;" -->
        		<div class="form-group row">
                	<label class="col-lg-2 control-label" for="id_dependencia">Validar y verificar en *:</label>
                    <div class="col-lg-9">
                        <select name="id_dependencia_turno" id="id_dependencia_turno" class="form-control" data-toggle="tooltip" data-placement="bottom" required disabled>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach($dependencias as $item): ?>
                                <option value="<?php echo $item['id_dependencia']?>" <?php if(isset($id_dependencia) && $id_dependencia==$item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                                
                <?php if (!isset($turno) || empty($turno)) { ?>
                    <div class="form-group row"></div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                        	<h5 class="mb-0">Seleccione la fecha</h5>
                        </div>                        
                    </div>
                    <div class="form-group row">
                    	<input type="hidden" name="sacoTurno" id="sacoTurno" value="NO" />
                    	<label class="col-lg-2 control-label" for="id_turno_fecha">Fecha *:</label>
                        <div class="col-lg-9">
                            <select name="id_turno_fecha" id="id_turno_fecha" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach($turnoCantidades as $item): ?>
                                    <option value="<?php echo $item['fecha']?>" <?php if(isset($fecha) && $fecha==$item['fecha']) echo 'selected="selected"'; ?>><?php echo $util->fechaCastellano($item['fecha']);?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row"></div>
                    <div id="divTituloSeleccionarHorario" class="form-group row" style="display: none;">
                        <div class="col-sm-12">
                        	<h5 class="mb-0">Seleccione el horario</h5>
                        </div>                        
                    </div>
                    <div id="divHoras" class="row">               
                    </div>
                    <div id="divDescargarTurno" class="row text-center" style="display: none;">
                    	<div class="col-sm-2"></div>
                    	<div class="col-sm-8 p-3 mb-2 border border-success">
                    		<h5 class="mb-0">Su turno ha sido otorgado correctamente y se ha enviado el comprobante del mismo a su cuenta de email ingresado en el paso anterior. 
                    						 Ademas podrá descargarlo en el ultimo paso o al finalizar la presente solicitud.</h5><br/>
                    	</div>
                    	<div class="col-sm-2"></div>	
                    </div>
                <?php }else { ?>
                	<div id="divDescargarTurno" class="row text-center">
                    	<div class="col-sm-2"></div>
                    	<div class="col-sm-8 p-3 mb-2 border border-success">
                    		<h5 class="mb-0"><b>Tiene turno para el día <?php echo $util->fechaCastellano($turno['fecha']);?> a las <?php echo substr($turno['hora'],0,5);?> hs.</b></h5><br/>
                    		<h5 class="mb-0">Descargue el comprobante del turno desde el siguiente botón:</h5><br/>
                    		<button class="btn btn-primary" type="button" id="btnDescargarTurno"><span class="oi oi-data-transfer-download"></span> Descargar turno</button>
                    	</div>
                    	<div class="col-sm-2"></div>	
                    </div>
                <?php } ?>
                <!-- 
                </div>

                    <div id="divDescargarTurno3" class="row text-center" style="display: none;">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8 p-3 mb-2 border border-success">
                            <h5 class="mb-0">Ud. ya cuenta con un turno asignado con anterioridad en la Dependencia Policial Seleccionada. Descargue el comprobante del mismo desde el siguiente botón:</h5><br/>
                            <button class="btn btn-primary" type="button" id="btnDescargarTurno3"><span class="oi oi-data-transfer-download"></span> Descargar turno</button>
                        </div>
                        <div class="col-sm-2"></div>	
                    </div>                    
                 -->
            </div>
        </div>
    </div>
    
<script>
    <?php if (isset($turno_user_id) && !empty($turno_user_id)) { ?>
    var conn = new WebSocket('ws://localhost:8282');
//     var conn = new WebSocket('ws://tramites.seguridad.jujuy.gob.ar:8282');
    var client = {
//         user_id: $('#cuil').val(),
        user_id: <?php echo $turno_user_id; ?>,
        recipient_id: null,
        type: 'socket',
        token: null,
        message: null
    };

    conn.onopen = function (e) {
        conn.send(JSON.stringify(client));
        $('#messages').append('<span color="green">Successfully connected as user ' + client.user_id + '</span><br>');
    };

    conn.onmessage = function (e) {
        var data = JSON.parse(e.data);
        if (data.message) {
            //$('#messages').append(data.user_id + ' : ' + data.message + '<br>');
//             alert(data.message);
			var mensaje = data.message.split('|');
			var id_dependencia = $('#id_dependencia').val();
			var fecha = $('#id_turno_fecha').val();
			if(id_dependencia == mensaje[0]) { //alert(fecha+'----'+mensaje[1]);  alert(fecha+'----'+mensaje[1]);
    			if(fecha == mensaje[1]) { //alert(fecha+'----'+mensaje[2]);
    				var cntHora = $('#'+mensaje[2]+'-cnt').text();
        			//alert(cntHora);
        			if(cntHora == '0') {
        				$('#'+mensaje[2]).hide();
        			}else {
        				$('#'+mensaje[2]+'-cnt').text(parseInt(cntHora)-1);
        			}
    			}	
			}
        }
        if (data.type === 'token') {
            $('#token').html('JWT Token : ' + data.token);
        }
    };
    <?php } ?>
    
    function enviarMensaje(idHora, hora, id_turno_cantidad) {
    	var id_dependencia = $('#id_dependencia').val();
        var fecha = $('#id_turno_fecha').val();
    	client.message = id_dependencia+'|'+fecha+'|'+idHora;
        client.token = $('#token').text().split(': ')[1]; //alert(client.token);
        client.type = 'chat';
//         if ($('#recipient_id').val()) {
//             client.recipient_id = $('#recipient_id').val();
//         }
        $('#token').empty();
        $('#recipient_id').empty();
        conn.send(JSON.stringify(client));

        guardarTurno(idHora, hora, id_turno_cantidad);
    }

    function guardarTurno(idHora, hora, id_turno_cantidad) {
    	var csrf_test_name = $('#csrf_test_name').val();
    	var id_tramite = $('#id_tramite').val();
    	var fecha = $('#id_turno_fecha').val();
    	
    	$.post('<?php echo base_url(); ?>/turno/guardarTurno', { "csrf_test_name":csrf_test_name, "id_tramite" : id_tramite, "fecha" : fecha, "hora" : hora, "id_turno_cantidad" : id_turno_cantidad }, null, "json" )
            .done(function( data, textStatus, jqXHR ) {
//                 if ( console && console.log ) {
//                     console.log( "La solicitud se ha completado correctamente." );
//                 }
//                 alert(data.resultado);
				if(data.error) {
					//alert(data.mensajes);
					bootbox.alert({
                	    message: 'Disculpe, ha ocurrido un error inesperado, vuelva a intentar mas tarde.',
                	    size: 'small',
                	    title: "Alerta",
                	    locale: 'es'
                	});
				}else {
					$('#sacoTurno').val('SI');
					marcarHora(idHora);
					var cantidad = $('#'+idHora+'-cnt').text();
					cantidad = cantidad - 1;
					$('#'+idHora+'-cnt').text(cantidad);

					$(".cuadro-hora").attr("onclick","yaTieneTurno('"+hora+"')");

					$('#id_turno_fecha').attr('disabled', 'disabled');
					$("#btnCancelar").hide();
					$("#divDescargarTurno").show();
				}
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
//                     console.log( "La solicitud a fallado: " +  textStatus);
                    bootbox.alert({
                	    message: 'Disculpe, ha ocurrido un error inesperado, vuelva a intentar mas tarde.',
                	    size: 'small',
                	    title: "Alerta",
                	    locale: 'es'
                	});
            	}
    		});
   	}

    function marcarHora(idElement) {
        if($("#"+idElement+"-subdiv").hasClass("hora-selected")) {
        	$("#"+idElement+"-subdiv").removeClass("hora-selected");
        	$("#"+idElement+"-h3").find('.oi').remove();
        }else {
        	$("#"+idElement+"-subdiv").addClass("hora-selected");
        	$("#"+idElement+"-h3").append(' <span class="oi oi-check"></span>');
        }    
    }

    function yaTieneTurno(hora) {
    	if(hora.length > 5) {
    		hora = hora.substr(0,5);
		}
    	var fecha = $("#id_turno_fecha option:selected").text();
    	var box = bootbox.alert({
    	    message: 'Disculpe, Ud. ya tiene turno para el dia '+fecha+' a las '+hora,
    	    size: 'small',
    	    title: "Alerta",
    	    buttons: {
    	        ok: {
    	          label: 'Cerrar'
    	        }
    	    }
    	});
    }    

    $('#submit').click(function () {
        client.message = $('#text').val();
        client.token = $('#token').text().split(': ')[1];
        client.type = 'chat';
        if ($('#recipient_id').val()) {
            client.recipient_id = $('#recipient_id').val();
        }
        $('#token').empty();
        $('#recipient_id').empty();
        conn.send(JSON.stringify(client));
    });

    $("select[name=id_turno_fecha]").change(function () {
    	$("#loading").modal("show");
    	var id_turno_fecha = $(this).val();
    	var id_dependencia = $('#id_dependencia').val();
    	var id_tipo_tramite = $('#id_tipo_tramite').val();
//     	alert('id_turno_fecha='+id_turno_fecha);
        if (id_turno_fecha == '') {
        	$("#divTituloSeleccionarHorario").hide();
        	$("#divHoras").empty();
        	$("#loading").modal("hide");
        	return false;
        }

        if (id_tipo_tramite == '<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL ?>') {
        	var tipo_planilla = $("input[name='tipo_planilla']:checked").val();

            $.getJSON('<?php echo base_url(); ?>/turno/getHorasPorTipoTramite/'+id_turno_fecha+'/'+id_dependencia+'/'+id_tipo_tramite+'/'+tipo_planilla, function (data) {
            	$("#divTituloSeleccionarHorario").show();
            	$("#divHoras").empty();
                $.each(data, function (i, obj) {
                    var idHora = obj.hora.substr(0,5).replace(':', '');
                	$("#divHoras").append('<div class="col-md-4 col-sm-6 col-xl-4 my-3 " id="hora-'+idHora+'" >'+
                            				'<div class="card d-block h-100 box-shadow-hover pointer cuadro-hora" id="hora-'+idHora+'-subdiv" style="border: 1px solid rgba(0,0,0,.125);" onclick="enviarMensaje(\'hora-'+idHora+'\', \''+obj.hora+'\', \''+obj.id_turno_cantidad+'\');">'+
                    							'<div class="card-body p-4">'+
                        							'<h3 class="h5" id="hora-'+idHora+'-h3"><strong>'+obj.hora.substr(0,5)+'</strong></h3>'+
                        							'<h3 class="h5"><strong id="hora-'+idHora+'-cnt">'+obj.cantidad+'</strong></h3>'+
                                                    '<h3 class="h5"><strong>disponibles</strong></h3>'+
                    							'</div>'+
                  							'</div>'+
                						   '</div>');
                });
             });
         }else {
        	$.getJSON('<?php echo base_url(); ?>/turno/getHoras/'+id_turno_fecha+'/'+id_dependencia, function (data) {
            	$("#divTituloSeleccionarHorario").show();
            	$("#divHoras").empty();
    //             alert(JSON.stringify(data));
                $.each(data, function (i, obj) {
                    var idHora = obj.hora.substr(0,5).replace(':', '');
                	$("#divHoras").append('<div class="col-md-4 col-sm-6 col-xl-4 my-3 " id="hora-'+idHora+'" >'+
                            				'<div class="card d-block h-100 box-shadow-hover pointer cuadro-hora" id="hora-'+idHora+'-subdiv" style="border: 1px solid rgba(0,0,0,.125);" onclick="enviarMensaje(\'hora-'+idHora+'\', \''+obj.hora+'\', \''+obj.id_turno_cantidad+'\');">'+
                    							'<div class="card-body p-4">'+
                        							'<h3 class="h5" id="hora-'+idHora+'-h3"><strong>'+obj.hora.substr(0,5)+'</strong></h3>'+
                        							'<h3 class="h5"><strong id="hora-'+idHora+'-cnt">'+obj.cantidad+'</strong></h3>'+
                                                    '<h3 class="h5"><strong>disponibles</strong></h3>'+
                    							'</div>'+
                  							'</div>'+
                						   '</div>');
                });
             });
         }        
    });
    
</script>
