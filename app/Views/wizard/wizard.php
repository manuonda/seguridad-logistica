<link href="<?php echo base_url() ?>/assets/css/wizard.css?v=1" rel="stylesheet">
<style>
    .box-shadow-hover:hover {
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        background: #d9edf7;
    }
    .pointer {
        cursor: pointer;
    }
    .hora-selected {
        background-color: #33FF83;
    }
</style>
<?php if ($ua->isMobile()): ?>
<style>
	.card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem;
	}
</style>
<?php endif; ?>
<div class="container-fluid" style="padding-top: 70px">
	<?php if (!$ua->isMobile()) : ?>
    <div class="row justify-content-center mt-0">
        <div class="col-9 col-sm-10 col-md-9 col-lg-9 p-0 mb-2">
    <?php endif; ?>    
            <div class="card px-0 pt-4 pb-0 mb-3">
                <h2 class="text-center"><strong><?php echo $title; ?></strong></h2>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="form-certificado">

                            <input type="hidden" id="certificado_controller" value="<?php if (isset($controller)) echo $controller; ?>" />
                            <input type="hidden" id="isPersonaValidada2" value="false" />
                            <input type="hidden" name="<?= csrf_token() ?>" id="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                            <!-- progressbar -->
                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite==TIPO_TRAMITE_CONSTANCIA_DENUNCIA) { ?>
                                <ul id="progressbar">
                                    <li class="active text-center" id="personal" style="width: 33.33%;"><strong>Información</strong></li>
                                    <li id="payment" class="text-center" style="width: 33.33%;"><strong>Pago</strong></li>
                                    <li id="confirm" class="text-center" style="width: 33.33%;"><strong>Finalizar</strong></li>
                                </ul>
                            <?php }else { ?>
                            	<ul id="progressbar">
                                    <li class="active text-center" id="personal"><strong>Información</strong></li>
                                    <li id="calendar" class="text-center"><strong>Turno</strong></li>
                                    <li id="payment" class="text-center"><strong>Pago</strong></li>
                                    <li id="confirm" class="text-center"><strong>Finalizar</strong></li>
                            	</ul>
                            <?php } ?>
                            <!-- fieldsets -->
                            <fieldset id="data_information">
                                <div class="form-card">
                                    <?php echo view($contenidopaso1); ?>
                                    <div class="row"  id="div_message_error" style="display:none; align-items: center;align-self: center;justify-content: center;">
                                        <div class="col-md-6">
                                            <div class="alert alert-danger alert-dismissible">
                                                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                                                <span id="message_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="text-center">
                                	<button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Volver</button>
                                	<?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                                		<button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button"><span class="oi oi-arrow-right"></span> Adelante</button>
                                	<?php } else { ?>
                                		<button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button" style="display: none;"><span class="oi oi-arrow-right"></span> Adelante</button>
                                	<?php } ?>
                                    <br>
                                    <br>
                                </div>
                            </fieldset>
                            
							<?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CONSTANCIA_DENUNCIA) { ?>
                                <fieldset id="turno_wizard" style="display:none">
                                    <div class="form-card" id="personal_information">
                                    	<div class="col-md-12">
                                            <h2 class="fs-title text-center">Turno</h2>
                                        </div>
                                        <?php echo view($contenidopaso2); ?>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarTramite()">
                                            <span class="oi oi-cancel"></span>Cancelar
                                        </button>
                                        <button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button"><span class="oi oi-arrow-right"></span> Adelante</button>
                                        <br>
                                        <br>
                                    </div>
                                </fieldset>
                            <?php } ?>

                            <fieldset id="pago_wizard" style="display:none">
                                <div class="form-card text-center">
                                    <div class="col-md-12 text-center">
                                        <h2 class="fs-title text-center">Pagar</h2>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <b><span id="datos_personales"></span>, su trámite está en proceso de validación.</b><br/><br/>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ 300</h2><br/>
                                            <?php } ?>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ 500</h2><br/>
                                            <?php } ?>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CONSTANCIA_EXTRAVIO) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ 300</h2><br/>
                                            <?php } ?>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ 100</h2><br/>
                                            <?php } ?>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ <?php echo $importe;?></h2><br/>
                                            <?php } ?>
                                            <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) { ?>
                                            	<h2 class="fs-title text-center">Importe: $ <?php echo $importe;?></h2><br/>
                                            <?php } ?>
                                            <h2 class="fs-title text-center">Seleccione la forma de pago:</h2><br/>
                                           
                                           <!--
                                            <div class="row">
                                            <div class="col-md-12">
    
                                             <img src="https://www.dogmaind.com/wp-content/uploads/2016/10/mercadopago-01-1.png" width="120px" heigth="120px">
                                            <button type="button" class="btn btn-primary" onclick="pagarMercadoPago()">
                                               Realizar Pago con Mercado Pago
                                            </button>
                                            
                                            </div>
                                            </div>
                                             -->

                                            <div class="row justify-content-center " >
                                                <div class="col-md-3 col-sm-10 offset-sm-1 card bg-light pb-5 pt-5 m-3" style="text-align:center">
                                                	<img src="<?php echo base_url() ?>/assets/img/policia.png" class="mx-auto d-block pb-2" height="100px">
                                                    <button type="button" id="link_comisaria" class="btn btn-primary p-1" onclick="pagarComisaria()">Realizar Pago en efectivo</button>
                                                </div>
                                                <div class="col-md-3 col-sm-10 offset-sm-1 card bg-light pb-5 pt-5 m-3" style="text-align:center">
                                                	<!-- 
                                                    <img src="<?php // echo base_url() ?>/assets/img/macro.jpeg" class="mx-auto d-block pb-2" height="100px">
                                                     -->
                                                    <img src="<?php echo base_url() ?>/assets/img/macro-clic.png" class="mx-auto d-block pb-2" height="100px">
                                                    <button type="button" id="link_comisaria" class="btn btn-primary p-1" onclick="pagarBancoMacro()">Realizar Pago por Macro clic</button>
                                                </div>
                                            </div>
                                            
                                            <!-- Formulario de pago de Banco Maro -->
                                            <form method="post" id="formBancoMacro" action="https://sandboxpp.asjservicios.com.ar"/>
	                                        <input type="hidden" id="CallbackSuccess" name="CallbackSuccess" value=""/> 
                                            <input type="hidden" id="CallbackCancel" name="CallbackCancel"  value=""/> 
	                                        <input type="hidden" id="Comercio" name="Comercio" value="" />
	                                        <input type="hidden" id="SucursalComercio" name="SucursalComercio" value=""/>
	                                        <input type="hidden" id="Hash" name="Hash" value="" />
	                                        <input type="hidden" id="TransaccionComercioId" name="TransaccionComercioId" value="" />
	                                        <input type="hidden" id="Monto" name="Monto" value=""/>
                                            <input type="hidden" id="Producto"  name="Producto[0]" value="" />
                                            <!-- opcional -->
	                                        <input type="hidden" name= "Informacion" value= "vhOBWNrIATF5r3Td5++2iEPPyoTVO12AZTF2hqC4KRY=" />
	                                        <!-- opcional -->
	                                        <input type="hidden" id="ClientData.CUIT" name="ClientData.CUIT" value="" />
                                            <!--opcional -->
                                            <input type="hidden" id="ClientData.NombreApellido" name="ClientData.NombreApellido" value="" />
                                            </form>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning" role="alert">
                                        <b>Luego de realizar el pago imprima o guarde el comprobante de pago.</b><br />
                                        <?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                                        	<b>El trámite le será enviado a su cuenta de whatsapp y a su cuenta de email, dentro de las 24 hs. de haber realizado el mismo.</b>
                                        <?php }else { ?>
                                        	<b>Una vez realizado el pago y validado sus datos. El certificado le llegará al número por whatsapp o al email indicado en el paso 1. Muchas gracias!!!</b>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- 
                                <div class="text-center">
                                <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarTramite()">
                                    <span class="oi oi-cancel"></span>Cancelar
                                </button>
                                <br>
                                <br>
                                </div>
                                 -->
                                
                            </fieldset>
                            <fieldset id="resultado_wizard" style="display:none">
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Signed Up</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    <?php if (!$ua->isMobile()) : ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php echo view('templates/frontend-base/footer.php'); ?>
<?php echo view('util_javascript.php'); ?>
<script type="text/javascript">
    <?php if (!$ua->isMobile()) : ?>
        $("#id_localidad").select2({ width: '100%' });
        $(".dependencia").select2({ width: '100%' });
    <?php endif; ?>
    
    $("#btnVolver").click(function() {
        location.href = '<?php echo base_url(); ?>/tramite';
    });
    $("#btnReiniciar").click(function() {
        location.href = '<?php echo base_url(); ?>/certificadoResidencia';
    });
</script>

<script type="text/javascript">
    var controllerAction = document.getElementById("certificado_controller").value;
    var base_url = '<?php echo base_url(); ?>';
    var url_banco_macro ='<?php if(isset($urlBancoMacro)) echo $urlBancoMacro; ?>';
    var pase = 0;

    console.log('controller : ', controllerAction);
    $(document).ready(function() {
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        // Next Operation
        $(".next").click(function() {

            current_fs = $(this).parent().parent();
            next_fs = $(this).parent().parent().next();
            current_id = current_fs[0].id;

            switch (current_id) {
                case "data_information": {
                    if (validar()) {
                        $("#loading").show();
                        grecaptcha.ready(function() {
                            grecaptcha.execute('6Lf4wOQUAAAAAOazF-mb5Ce8oWwZZsz0plTCMZhU', {action: 'form'}).then(function(token) {
                               document.getElementById("recaptchaResponse").value= token;

                        //Add Class Active
                        var form = document.getElementById("form-certificado");
                        var formData = new FormData(form);
                        fetch(base_url + '/' + controllerAction + '/guardarData', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('response : ', data);
                                if (data.status === "OK") {
                                    document.getElementById("id_tramite").value = data.id_tramite;

                                    /***************************GUARDO EL TRUNO SI ES QUE TIENE********************************/
//                                     if(data.turno != null){
//                                         document.getElementById("id_turno").value = data.turno.id_tramite;
//                                         if(data.turno.id_tramite){
//                                             $("#divDescargarTurno3").show();
//                                             $("#flujoNormal").hide();
//                                         }
//                                     }
                                    /******************************************************************************************/
                                    
                                    let nombre = document.getElementById("nombre").value.toUpperCase();
                                    let apellido = document.getElementById("apellido").value.toUpperCase();
                                    $("#datos_personales").append(nombre + " " + apellido);

                                    <?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CONSTANCIA_DENUNCIA) { ?>
                                        // aca antes de pasar a pagina de turno
                                        $('#id_dependencia_turno').val($('#id_dependencia').val());
    
                                        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                                        if(data.turnoCantidades.length != 0) {
                                            var option2 = new Array();
                                            $.each(data.turnoCantidades, function (i, obj) {
                                            	option2[i] = document.createElement('option');
                                                $(option2[i]).attr({value: obj.fecha});
    											var fechaTurno = obj.fecha.split("-");
    											var fecha = new Date(parseInt(fechaTurno[0]), parseInt(fechaTurno[1])-1, parseInt(fechaTurno[2]));
                                                $(option2[i]).append(fecha.toLocaleDateString("es-AR", options).toUpperCase());
    //                                             $(option2[i]).append(fechaEnLetras(obj.fecha));
                                                $("select[name=id_turno_fecha]").append(option2[i]);
                                            });
                                        }
                                    <?php } ?>

                                    debugger;

                                    if(data.isPersonaValidada==='true') {
                                    	$("#isPersonaValidada2").val(data.isPersonaValidada);
                                        $("#calendar").hide();
                                        $('#personal').css({'width': '33.33%'});
                                        $('#payment').css({'width': '33.33%'});
                                        $('#confirm').css({'width': '33.33%'});
                                        next_fs = next_fs.next();
                                    }

                                    <?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) { ?>
                                      var date = new Date();
                                      date.setHours(0, 0, 0, 0);
                                      //FORMAT : MM/DD/YYYY
                                      var date2 = new Date('<?php echo FECHA_VOTACION;?>');
                                      debugger;
                                      if(date.getTime()== date2.getTime()) {
                                            setResultadoNoVotacion();
                                       
                                        //showAlert("Se ha creado la Constancia correspondiente de No Votación.Debe dirigirse a la Seccional Correspondiente posteriormente");

                                      }
                                     
                                      
                                    <?php } ?>
                                    setTimeout(function() {
                                        $("#loading").hide();
                                        nextFs(next_fs, current_fs);
                                    },2000);
                                    
                                } else {
                                    $("#loading").hide();
//                                    

									if(data.message === '') {
										var box = bootbox.alert({
	                                	    message: 'Disculpe, ha ocurrido un error inesperado, vuelva a intentar.',
	                                	    size: 'small',
	                                	    title: "Alerta",
	                                	    locale: 'es'
	                                	});
									}else {
										var mensaje = '<div class="alert alert-danger">';
	                                    for (var clave in data.message) {
	                                	  // Controlando que  data.message  realmente tenga esa propiedad
	                                	  if (data.message.hasOwnProperty(clave)) {
	                                	    // Mostrando en pantalla la clave junto a su valor
//	                                 	    alert("La clave es " + clave+ " y el valor es " + data.message[clave]);
	                                	    mensaje = mensaje + '- ' + data.message[clave];
	                                	    mensaje = mensaje + '<br/>';
	                                	  }
	                                    }
	                                    mensaje = mensaje + '</div>';
	                                    
	                                    var box = bootbox.alert({
	                                	    message: mensaje,
	                                	    size: 'small',
	                                	    title: "Alerta",
	                                	    locale: 'es'
	                                	});
									}
                                }

                            })
                            .catch(function(error) {
                                console.log(error);
                                $("#loading").hide();
//                                 alert(error);
                                var box = bootbox.alert({
                            	    message: 'Disculpe, ha ocurrido un error inesperado, vuelva a intentar.',
                            	    size: 'small',
                            	    title: "Alerta",
                            	    locale: 'es'
                            	});
                            });

                            });
                        });

                    }

                };
                break;
            case "turno_wizard": {
                console.log("next turno wizard");
                //validar que alla seleccionado turno
                let seleccionaFecha =$("#sacoTurno").val();
                if(seleccionaFecha === "SI") {
                 nextFs(next_fs, current_fs);
                } else {
                    var box = bootbox.alert({
                            	    message: 'Debe seleccionar la Fecha y hora del Turno',
                            	    size: 'small',
                            	    title: "Alerta",
                            	    locale: 'es'
                            	});
                }
            };
            break;
            case "pago_wizard": {
                console.log("pago_wizard");
                console.log("estoy en pago wizard");
                nextFs(next_fs, current_fs);
            }
            }


        });

        // Previous Operation
        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function() {
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(".submit").click(function() {
            return false;
        })

    });

    function nextFs(next_fs, current_fs) {
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        //show the next fieldset
        next_fs.show();

        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now) {

                console.log("informaicon numeor 1");
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({
                    'opacity': opacity
                });
            },
            duration: 600
        });
    }

    function eliminarTramite() {
        const idTramite = document.getElementById("id_tramite").value;
        const baseUrl = '<?php echo base_url(); ?>';
        console.log("idTramite : ", idTramite);
//         const resultado = confirm('Desea eliminar la operacion del Tramite?');
//         if (resultado) {
            if (idTramite != "") {
                $("#loading").show();
            
                fetch(baseUrl + '/' + controllerAction + '/delete/' + idTramite)
                .then(response => response.json())
                .then(data => {
                    if ( data.status === "OK") {
                        window.location.href = base_url + "/";
                    } else {
                        $("#loading").hide();
                        alert('Error Al eliminar el Tramite, intente de nuevo');
                    }
                 })
                .catch(error => {
                    $("#loading").hide();
                    console.log(error);
                });
            } else {
                window.location.href = base_url + "/";
            }
//         }
    }

    /**
     * Funcion que permite cancelar el tramite 
     */
    function cancelarTramite() {
    	bootbox.confirm({
            message: "¿Deseas cancelar la solicitud?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
//                 console.log('This was logged in the callback: ' + result);
                if(result) {
                	eliminarTramite();
                }    
            }
        });
    }

    /**
     * Funcion pagar en Comisaria
     */
    function pagarComisaria() {
       $("#loading").show();
       const baseUrl ='<?php echo base_url(); ?>';
       const idTramite = document.getElementById('id_tramite').value;
       const isPersonaValidada = document.getElementById('isPersonaValidada2').value;

	   if(isPersonaValidada==='true') {
		   $("#loading").hide();
		   var box = bootbox.alert({
        		closeButton: false,
        	    message: '<select name="idDependenciaPago" id="idDependenciaPago" class="form-control" data-toggle="tooltip" data-placement="bottom">'
                    +'<option value="">-- SELECCIONAR --</option>'
                    +'<option value="920">U.A.D.  MOVIL - SAN SALVADOR DE JUJUY</option>'
                    +'<option value="906">U.A.D.  PERICO  UR6</option>'
                    +'<option value="905">U.A.D.  LA QUIACA  UR5</option>'
                    +'<option value="904">U.A.D.  L.G.S.M.  UR4</option>'
                    +'<option value="903">U.A.D.  HUMAHUACA  UR3</option>'
                    +'<option value="902">U.A.D.  SAN PEDRO  UR2</option>'
                    +'<option value="327">SUBCOMISARIA RIO BLANCO UR-8</option>'
                    +'<option value="314">SUBCOMISARIA SAN FRANCISCO DE ALAVA (UR-1)</option>'
                    +'<option value="62">COMISARIA SECCIONAL 62 SGTO. CABRAL</option>'
                    +'<option value="61">COMISARIA SECCIONAL 61 EL CHINGO</option>'
                    +'<option value="59">COMISARIA SECCIONAL 59 SAN CAYETANO</option>'
                    +'<option value="55">COMISARIA SECCIONAL 55 LOS PERALES</option>'
                    +'<option value="50">COMISARIA SECCIONAL 50 CAMPO VERDE (UR-1)</option>'
                    +'<option value="47">COMISARIA SECCIONAL 47 PASO DE JAMA</option>'
                    +'<option value="46">COMISARIA SECCIONAL 46 AEROPARQUE</option>'
                    +'<option value="44">COMISARIA SECCIONAL 44 SAN MARTIN</option>'
                    +'<option value="34">COMISARIA SECCIONAL 34 V.J. DE REYES</option>'
                    +'<option value="33">COMISARIA SECCIONAL 33 ALTO COMEDERO</option>'
                    +'<option value="32">COMISARIA SECCIONAL 32 MALVINAS (UR-1)</option>'
                    +'<option value="30">COMISARIA SECCIONAL 30 M. MORENO (UR-1)</option>'
                    +'<option value="23">COMISARIA SECCIONAL 23 BELGRANO</option>'
                    +'<option value="6">COMISARIA SECCIONAL 6 ALTE. BROWN (UR-1)</option>'
                    +'<option value="5">COMISARIA SECCIONAL 5 CIUDAD DE NIEVA (UR-1)</option>'
                    +'<option value="4">COMISARIA SECCIONAL 4 CUYAYA (UR-1)</option>'
                    +'<option value="3">COMISARIA SECCIONAL 3 CHIJRA (UR-1)</option>'
                    +'<option value="2">COMISARIA SECCIONAL 2 GORRITI (UR-1)</option>'
                    +'<option value="1">COMISARIA SECCIONAL 1 CENTRO (UR-1)</option>'
                    +'</select>',
        	    size: 'small',
        	    title: "Seleccione el lugar donde va realizar el pago",
        	    locale: 'es',
        	    callback: function () {
        	    	const idDependenciaPago = document.getElementById('idDependenciaPago').value;
                    if (idDependenciaPago == '') {
                        showAlert("Debe seleccionar el lugar donde va a realizar el pago");
                        return false;
                    } else {
                        $("#loading").show();
                    	setPagoEnComisaria(baseUrl, controllerAction, idTramite, isPersonaValidada, idDependenciaPago);
                    }
//         	    	alert('idDependenciaPago='+idDependenciaPago);
//        	    	setPagoEnComisaria(baseUrl, controllerAction, idTramite, isPersonaValidada, idDependenciaPago);
        	    }
        	});
       	
       		return false;
	   }else {
		   setPagoEnComisaria(baseUrl, controllerAction, idTramite, isPersonaValidada, '');
	   }			   	   		   	
    }

    function setPagoEnComisaria(baseUrl, controllerAction, idTramite, isPersonaValidada, idDependenciaPago) {
    	fetch(baseUrl + '/' + controllerAction + '/pagocomisaria/' + idTramite)
        .then(response => response.json())
        .then(data => {
            if ( data.status === "OK") {
                  window.location.href = base_url + "/resultpaymentonline/comisaria?idTramite="+idTramite+"&isPersonaValidada="+isPersonaValidada+"&idDependenciaPago="+idDependenciaPago;
             } else {
                  $("#loading").hide();
                  alert('Error al Realizar Pago en efectivo, intente de nuevo');
              }
         })
         .catch(error => {
               $("#loading").hide();
         });
    }


    function setResultadoNoVotacion(isPersonaValidada){
        $("#loading").show();
        const baseUrl ='<?php echo base_url(); ?>';
        const idTramite = document.getElementById('id_tramite').value;
        const idDependencia = document.getElementById('id_dependencia').value;
        window.location.href = base_url + "/resultpaymentonline/novotacion?idTramite="+idTramite+"&isPersonaValidada="+isPersonaValidada+"&idDependenciaPago="+idDependencia;
        
    }

    /**
     *  Funcion que permite realizar el pago de MercadoPago,
     *  crea el link de MP
     */  
    function pagarMercadoPago(){
        //$("#loading").show();
        const baseUrl ='<?php echo base_url(); ?>';
        const idTramite = document.getElementById('id_tramite').value;
        fetch(baseUrl + '/' + controllerAction + '/pagoMercadoPago/' + idTramite)
        .then(response => response.json())
        .then(data => {
           console.log(data);
           if ( data.status === "OK" && data.link !== "") {
                 window.location.href = data.link;
            } else {
                 $("#loading").hide();
                 alert('Error Al al Realizar Pago, intente de nuevo');
             }
        })
        .catch(error => {
              $("#loading").hide();
        });
     }

     // function que realiza el pago por Banco Macro 
     function pagarBancoMacro(){
        $("#loading").show();
        const baseUrl ='<?php echo base_url(); ?>';
        const idTramite = document.getElementById('id_tramite').value;
        const isPersonaValidada = document.getElementById('isPersonaValidada2').value;
        fetch(baseUrl + '/' + controllerAction + '/pagoBancoMacro/' + idTramite + '/' + isPersonaValidada + '/wizard')
        .then(response => response.json())
        .then(data => {
           console.log(data);
           if ( data.status === "OK" && data.link !== "") {
               $("#loading").hide(); 

               let dataBancoMacro = data[0];
               document.getElementById("CallbackSuccess").value = dataBancoMacro.call_back;
               document.getElementById("CallbackCancel").value = dataBancoMacro.call_cancel;
               document.getElementById("Comercio").value = dataBancoMacro.comercio;
               document.getElementById("Hash").value = dataBancoMacro.hash_generate;
               document.getElementById("TransaccionComercioId").value = dataBancoMacro.transaction_comercio_id;
               document.getElementById("Monto").value = dataBancoMacro.monto;
               document.getElementById("Producto").value = dataBancoMacro.producto;
               document.getElementById("ClientData.CUIT").value = dataBancoMacro.titular_cuit;
               document.getElementById("ClientData.NombreApellido").value = dataBancoMacro.titular_nombre_apellido;
               
               
               let form  =  document.createElement("form");
               form.action = url_banco_macro;
               form.method ="POST";
               
               let callBackSuccess = document.createElement("input");
               callBackSuccess.name = "CallbackSuccess";
               callBackSuccess.value = dataBancoMacro.call_back;
               form.appendChild(callBackSuccess);


               
               let callbackCancell = document.createElement("input");
               callbackCancell.name = "CallbackCancel";
               callbackCancell.value = dataBancoMacro.call_cancel;
               form.appendChild(callbackCancell);

               console.log("3");
               let comercio  = document.createElement("input");
               comercio.name = "Comercio";
               comercio.value = dataBancoMacro.comercio;
               form.appendChild(comercio);

               console.log("4");
               let hash =  document.createElement("input");
               hash.name = "Hash";
               hash.value =  dataBancoMacro.hash_generate;
               form.appendChild(hash);

               let transaccionComercioId = document.createElement("input");
               transaccionComercioId.name = "TransaccionComercioId";
               transaccionComercioId.value =  dataBancoMacro.transaction_comercio_id;
               form.appendChild(transaccionComercioId);


               let monto = document.createElement("input");
               monto.name="Monto";
               monto.value = dataBancoMacro.monto;
               form.appendChild(monto);

               
            //    let producto = document.createElement("input");
            //    producto.name = "Producto[0]";
            //    producto.value = dataBancoMacro.producto;
            //    form.appendChild(producto);

            console.log(dataBancoMacro.productos);
        if ( dataBancoMacro.productos && dataBancoMacro.productos.length > 0 ) {
            for( let i = 0; i < dataBancoMacro.productos.length ; i++) {
                let producto = document.createElement("input");
                     producto.name =  "Producto["+i+"]";
                     producto.value = dataBancoMacro.productos[i];
                     // producto.value = dataBancoMacro.producto;
                     form.appendChild(producto);
              
            }
        }
        
           
               let clienteCuit = document.createElement("input");
               clienteCuit.name = "ClientData.CUIT";
               clienteCuit.value = dataBancoMacro.titular_cuit;
               form.appendChild(clienteCuit); 


               let nombreApellido    = document.createElement("input");
               nombreApellido.name   = "ClientData.NombreApellido";
               nombreApellido.value  = dataBancoMacro.titular_nombre_apellido;
               form.appendChild(nombreApellido);

               let sucursalComercio   = document.createElement("input");
               sucursalComercio.name  = "SucursalComercio";
               sucursalComercio.value = dataBancoMacro.sucursal_comercio;
               form.appendChild(sucursalComercio);

               //console.log(form);

               document.body.appendChild(form);
               
               form.submit();
               // window.location.href = data.link;
            } else {
                 $("#loading").hide();
                 alert('Error al realizar el pago por Banco Macro, intente de nuevo');
             }
        })
        .catch(error => {
              $("#loading").hide();
        });
     }
</script>

