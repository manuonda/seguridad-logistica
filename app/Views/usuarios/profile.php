<div class="container" style="padding-top: 70px">
    <div class="bs-docs-section">


        <div class="col-md-12">
            <div class="card border-dark mb-3">
                <div class="card-header text-center">
                    <h5 class="mb-0">
                        Actualización de Contraseña</h5>

                    <div class="card-body text-center">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Usuario :</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control requerido" readonly value="<?php if (isset($usuario)) echo $usuario['username']; ?>" spellcheck="false" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Nombre y Apellido:</label>
                            <div class="col-lg-3">
                                <input type="text" name="apellido" id="apellido" readonly class="form-control mayuscula" value="<?php if (isset($usuario)) echo $usuario['firstname'] . "," . $usuario['lastname']; ?>" />
                            </div>
                        </div>



                        <form action="<?php echo base_url() . '/users/actualizarUsuario'; ?>" id="formulario">
                            <input type="hidden" name="id" id="id" value="<?php if (isset($usuario)) echo $usuario['id']; ?>" />
                            <input type="hidden" name="identity" id="identity" value="<?php if (isset($usuario)) echo $usuario['username']; ?>">


                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Contraseña Actual * :</label>
                                <div class="col-lg-3">
                                    <input type="password"   class="form-control requerido" id="passwordactual" name="passwordactual" required>
                                </div>
                                <span class="span_none" id="passwordactual-error">Ingrese contraseña actual </span>
                            </div>




                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Nueva Contraseña * :</label>
                                <div class="col-lg-3">
                                    <input type="password"  min="8" minlength="8"class="form-control requerido" id="password" name="password" required>
                                </div>
                                <span class="span_none" id="password-error">Ingrese contraseña </span>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Repetir Contraseña * :</label>
                                <div class="col-lg-3">
                                    <input type="password" min="8" minlength="8" class="form-control requerido" id="repetirpassword" name="repetirpassword" required placeholder="">
                                </div>
                                <span class="span_none" id="repetirpassword-error">Ingrese contraseña </span>
                            </div>



                            <div id="div_message_success" class="alert alert-danger">

                                <div id="message_success">
                                </div>
                            </div>

                            <div id="div_message" class="alert alert-danger">
                                <div id="message_alert">
                                </div>
                            </div>

                          
       
                            <div class="alert alert-danger" id="div_status_error">
                                    <h6><i class="glyphicon glyphicon-alert"></i>Información </h6>
                                    <label id="message_status_error">
                                    </label>
                                    
                                </div>
                                <div class="alert alert-primary" id="div_status_ok">
                                    <h6><i class="glyphicon glyphicon-alert"></i>Información </h6>
                                    <label id="message_status_ok">
                                    </label>
                                </div>
                           


                            <div class="text-center">
                                <a href="<?php echo base_url(); ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Volver</a>
                                <button type="submit" id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button"><span class="oi oi-arrow-right"></span> Guardar</button>
                            </div>

                        </form>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url() ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/moment.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/es.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/select2.full.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootbox.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootbox.locales.min.js"></script>
    <script src="<?php echo base_url() ?>/assetsback/js/jquery.blockUI.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>





    <script type="text/javascript">
        //Para realizar la validacion del Cuil ingresado es correcto 
        var cuilInfractorEncontrado = false;
        //Si ingreso alguna para habilitar
        var cantLey = 0;
        var styleError = "error_input";
        var styleSpanError = "help-block help-block-error";
        var styleSpanNone = "span_none";
        var styleErrorInput = "error_input";
        var has_error = "has-error";

        $(document).ready(function() {
            $(".span_none").hide() ;
            $("#div_message").hide();
            $("#div_message_success").hide();
            $("#div_status_ok").hide();
            $("#div_status_error").hide();
            $("#message_status_ok").empty();
            $("#message_status_error").empty();
            $("#message_alert").empty();
            $("#div_message").hide();   
            $("#message_status_error").empty();



            //Formulario Vial 
            $("#formulario").submit(function(eve) {
                
                eve.preventDefault();
                $("#div_status_ok").hide();
                $("#div_status_error").hide();
                $("#message_status_ok").empty();
                $("#message_status_error").empty();
                $("#message_alert").empty();
                $("#div_message").hide();   
                $("#message_status_error").empty();
                if (validarCreateView()) {
                       var data=new FormData(this);
                     
                      $.blockUI({ message: 'Enviando..'});
                      $.ajax({
                       type: "POST",
                       url: '<?php echo base_url(); ?>/users/actualizarPassword',
                       data: data,
                       cache: false,
                       contentType: false,
                       processData: false,
                       dataType: "JSON",
                       success: function (data) {
                          console.log(data);
                          $.unblockUI();
                          if (data.status == 'OK') {
                             $("#div_status_ok").show();
                             $("#message_status_ok").append(data.message);
                           } else {
                             $("#div_status_error").show();
                             $("#message_status_error").append(data.message);
                          }
                        },
                         error: function (data) {
                           $.unblockUI();
                           showAlert("Se produjo un error en el Servidor");
                           console.log("error => " + data);

                        }
                    });  


                }
            });
        }); //end Document Ready Function


        //Funcion que permite verificar si 
        //el dni es correcto y valida si tiene leyes 
        //asociadas
        function validarCreateView() {

            var msg = "";

            var bandForm = true;
            var bandIguales = true;
            var bandCuilInvolucrado = true;
            var bandCuilPropietario = true;
            var bandCantidad = true;
            var password = $("#password").val();
            var repetirpassword = $("#repetirpassword").val();

            if (validarForm('#formulario')) {
                console.log("validar Form");
                bandForm = true;
            } else {
                msg = msg + "<strong>Debe Completar los campos requeridos.</strong>";
                bandForm = false;
            }


            if (bandForm) {
                if (password === repetirpassword) {
                    return true;
                } else {
                    msg = msg + "<strong>Las contraseñas no son iguales";
                    $("#div_message").show();
                    $("#message_alert").empty();
                    $("#message_alert").append(msg);
                    return false;
                }
            } else {

                $("#div_message").show();
                $("#message_alert").empty();
                $("#message_alert").append(msg);
                return false;
            }

        }

        /**
         * Se valida que los input sean requeridos para mostrar los mensajes de error
         *
         */
        function validarForm(x) {
            console.log("validarForms");
            var isValido = true;
            var form = (x);

            // recorre los input y verifica que son requeridos
            $(form).find('input').each(
                function() {
                    var id = $(this).attr('id');
                    var required = $(this).hasClass('requerido');

                    var stringSpan = "#" + id + "-error";
                    var stringDiv = "#" + id + "-div";
                    if ($(this).val().trim() == '' && required) {



                        /*var idErrorSpan = subStringId(stringSpan);
                         */
                        console.log("234324 : " + stringSpan);

                        $(this).addClass(styleErrorInput);

                        $(stringSpan).removeClass(styleSpanNone);
                        $(stringSpan).addClass(styleSpanError);
                        $(stringSpan).css("display", "");
                        $(stringDiv).addClass(has_error);


                        isValido = false;
                    } else {
                        $(stringSpan).css("display", "none");
                    }
                });
            $(form).find('select').each(
                function() {
                    // obtengo el parent div
                    var id = $(this).attr('id');
                    var required = $(this).hasClass('requerido');
                    var stringSpan = "#" + id + "-error";
                    var idErrorSpan = subStringId(stringSpan);

                    if ($(this).val() == '' && required) {
                        console.log("id : " + id);
                        // agrego el error al input
                        $(this).addClass(styleErrorInput);


                        var stringDiv = "#" + id + "-div";
                        $(stringSpan).removeClass(styleSpanNone);
                        $(stringSpan).addClass(styleSpanError);
                        $(stringSpan).css("display", "");
                        $(stringDiv).addClass(has_error);
                        isValido = false;
                    } else {
                        $(stringSpan).css("display", "none");
                    }
                });
            return isValido;

        }
    </script>
</div>