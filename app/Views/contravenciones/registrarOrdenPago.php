<div class="container" style="padding-top: 70px">
    <!--     <div class="col-md-10 offset-md-1"> -->
    <div class="col-md-12">
        <!-- form user info -->
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Carga de datos para Orden de Pago Contravencional</b></h5>
            </div>
            <form action="<?php echo base_url().'/pagoContravencion/guardar'; ?>" method="post" id="form">
                <div class="card-body">
                    <!-- *************************************** -->
                    <?php if ( isset($status) && $status == 'ERROR') { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <h6><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Información </h6>
                        <ul id="list_message_error">
                            <li> <?php echo $message; ?></li>
                        </ul>
                    </div>
                    <?php }?>
                    <!-- *************************************** -->
                    <?= \Config\Services::validation()->listErrors('my_errors'); ?>
                    <?php if (isset($error) and !empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    <!-- *************************************** -->
                    <input type="hidden" name="id_tramite" id="id_tramite"
                        value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
                    <input type="hidden" name="id_persona_titular" id="id_persona_titular"
                        value="<?php if (isset($id_persona_titular)) echo $id_persona_titular; ?>" />
                    <input type="hidden" name="id_tramite_reba" id="id_tramite_reba"
                        value="<?php if( isset($id_tramite_reba)) echo $id_tramite_reba; ?>" />

                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <div class="form-group row">
                        <label class="col-lg-12 control-label"><b>Datos personales</b></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="nombre" id="nombre" class="form-control mayuscula"
                                value="<?php if(isset($nombre)) echo $nombre; ?>" placeholder="Ingrese nombre" required
                                spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="apellido" id="apellido" class="form-control mayuscula"
                                value="<?php if(isset($apellido)) echo $apellido; ?>" placeholder="Ingrese apellido"
                                required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Telefono *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="telefono" id="telefono" class="form-control mayuscula"
                                value="<?php if(isset($telefono)) echo $telefono; ?>" placeholder="Ingrese telefono"
                                required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="email" id="email" class="form-control"
                                value="<?php if(isset($email)) echo $email; ?>" placeholder="Ingrese email" required
                                spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="id_tipo_documento">Tipo documento *:</label>
                        <div class="col-lg-9">
                            <select name="id_tipo_documento" id="id_tipo_documento" class="form-control"
                                data-toggle="tooltip" data-placement="bottom" required>
                                <option value="">-- SELECCIONAR --</option>
                                <?php foreach ($tipoDocumentos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_documento'] ?>"
                                    <?php if (isset($id_tipo_documento) && $id_tipo_documento == $item['id_tipo_documento']) echo 'selected="selected"'; ?>>
                                    <?php echo $item['tipo_documento'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento" id="documento" class="form-control mayuscula"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($documento)) echo $documento; ?>" placeholder="Ingrese documento"
                                required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">CUIL *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="cuil" id="cuil" class="form-control mayuscula"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($cuil)) echo $cuil; ?>" placeholder="cuil" required
                                spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento*:</label>
                        <div class="col-lg-9">
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                class="form-control mayuscula"
                                value="<?php if(isset($fecha_nacimiento)) echo $fecha_nacimiento; ?>"
                                placeholder="Ingrese Fecha Nacimiento" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="en_concepto_de">Observacion: </label>
                        <div class="col-lg-9">
                            <input type="text" name="en_concepto_de" id="en_concepto_de" class="form-control mayuscula"
                                value="<?php if(isset($en_concepto_de)) echo $en_concepto_de; ?>"
                                placeholder="Observacion" required spellcheck="false" />
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-lg-3 control-label" for="categoria_reba">Denominación Negocio :</label>
                        <div class="col-lg-9">
                            <input type="text" name="denominacion_negocio" id="denominacion_negocio"
                                class="form-control mayuscula"
                                value="<?php if(isset($denominacion_negocio)) echo $denominacion_negocio; ?>"
                                placeholder="Ingrese Nombre Fantasia" required spellcheck="false" />
                        </div>
                    </div> -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- Parte de Pago Uno -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" control-label " for="categoria_reba">Concepto de Pago :</label>
                            <input type="text" name="concepto_uno" id="concepto_uno" class="form-control mayuscula"
                                value="<?php if(isset($concepto_uno)) echo $concepto_uno; ?>"
                                placeholder="Concepto de Pago" required spellcheck="false" />
                        </div>
                        <!-- <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Cantidad Pago Uno :</label>
                            <input type="text" name="cantidad_uno" id="cantidad_uno" class="form-control  "
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($cantidad_uno)) echo $cantidad_uno; ?>"
                                placeholder="Cantidad de Pago" required spellcheck="false" />
                        </div> -->
                        <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Importe a Pagar :</label>
                            <input type="text" name="precio_uno" id="precio_uno" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($precio_uno)) echo $precio_uno; ?>" placeholder="IMPORTE" required
                                spellcheck="false" />
                        </div>
                    </div>
                    <!-- Parte de Pago Dos -->
                    <!-- <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" control-label " for="categoria_reba">Concepto Pago Dos :</label>
                            <input type="text" name="concepto_dos" id="concepto_dos" class="form-control mayuscula"
                                value="<?php if(isset($concepto_dos)) echo $concepto_dos; ?>"
                                placeholder="Concepto Pago" required spellcheck="false" />
                        </div>
                        <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Cantidad Pago Dos :</label>
                            <input type="text" name="cantidad_dos" id="cantidad_dos" class="form-control  "
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($cantidad_dos)) echo $cantidad_dos; ?>"
                                placeholder="Cantidad de Pago" required spellcheck="false" />
                        </div>
                        <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Precio Pago Uno :</label>
                            <input type="text" name="precio_dos"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                id="precio_dos" class="form-control"
                                value="<?php if(isset($precio_dos)) echo $precio_dos; ?>" placeholder="Precio" required
                                spellcheck="false" />
                        </div>
                    </div> -->
                    <!-- Parte de Pago tres -->
                    <!-- <div class="row">
                        <div class="form-group col-md-6">
                            <label class=" control-label " for="categoria_reba">Concepto Pago Tres :</label>
                            <input type="text" name="concepto_tres" id="concepto_tres" class="form-control mayuscula"
                                value="<?php if(isset($concepto_tres)) echo $concepto_tres; ?>"
                                placeholder="Concepto Pago" required spellcheck="false" />
                        </div>
                        <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Cantidad Pago Tres :</label>
                            <input type="text"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                name="cantidad_tres" id="cantidad_tres" class="form-control  "
                                value="<?php if(isset($cantidad_tres)) echo $cantidad_tres; ?>"
                                placeholder="Cantidad de Pago" required spellcheck="false" />
                        </div>
                        <div class="form-group col-md-3">
                            <label class=" control-label " for="categoria_reba">Precio Pago Tres :</label>
                            <input type="text" name="precio_tres" id="precio_tres" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($precio_tres)) echo $precio_tres; ?>" placeholder="Precio"
                                required spellcheck="false" />
                        </div>
                    </div> -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                    <!-- *************************************** -->
                    <div class="form-group row">
                        <div class="col-lg-12 text-center">
                            <a href="<?php echo base_url().'/pagoContravencion/cargarOrdenPago';?>" class="btn btn-primary"><span class="oi oi-home"></span>Volver</a>
                            <button type="button" class="btn btn-primary" onclick="limpiar()"><span class="oi oi-brush"></span> Limpiar</button>
                            <button class="btn btn-primary" type="button" id="btnReiniciar" onclick="enviar()"><span class="oi oi-reload"></span> Guardar</button>
                        </div>
                    </div>
                </div>
                <!-- <input type="hidden" name="recaptcha_response" id="recaptchaResponse" /> -->
                <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- *************************************** -->
<!-- *************************************** -->
<script>
$(document).ready(function() {
    <?php if ( isset($status) && $status == 'OK') { ?>
    showAlert("EL TRAMITE SE GENERO CORRECTAMENTE");
    limpiar();
    <?php } ?>

    $('.numberonly').keypress(function(e) {
        var numbers = /^[0-9]+$/;
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(numbers))
            return false;

    });

});

//****limpia los campos */
function limpiar() {
    $("#nombre").val("");
    $("#apellido").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#id_tipo_documento").val("");
    $("#documento").val("");
    $("#cuil").val("");
    $("#fecha_nacimiento").val("");
    $("#en_concepto_de").val("");
    //$("#denominacion_negocio").val("");
    //
    $("#concepto_uno").val("");
    //$("#cantidad_uno").val("");
    $("#precio_uno").val("");
    //$("#concepto_dos").val("");
    //$("#cantidad_dos").val("");
    //$("#precio_dos").val("");
    //$("#concepto_tres").val("");
    //$("#cantidad_tres").val("");
    //$("#precio_tres").val("");
    //
    //$("#tipo_documento").val("");
    //$("#numero_tramite").val("");
}

//****valida los campos */
function validar() {
    var nombre = $("#nombre").val().trim();
    if (nombre == '') {
        showAlert("Debe ingresar el nombre");
        return;
    }
    var apellido = $("#apellido").val().trim();
    if (apellido == '') {
        showAlert("Debe ingresar el apellido");
        return;
    }
     var telefono = $("#telefono").val().trim();
     if (telefono == '') {
         showAlert("Debe ingresar el telefono");
         return;
     }
     var email = $("#email").val().trim();
     if (email == '') {
         showAlert("Debe ingresar el email");
         return;
     }
    var id_tipo_documento = $("#id_tipo_documento").val().trim();
    if (id_tipo_documento == '') {
        showAlert("Debe ingresar el Tipo de documento", "id_tipo_documento");
        return;
    }
    if ($("#documento").val().trim() == '') {
        showAlert("Debe ingresar el Documento", "documento");
        return;
    }
     if ($("#cuil").val().trim() == '') {
         showAlert("Debe ingresar el Cuil", "cuil");
         return;
     }
    if ($("#fecha_nacimiento").val().trim() == '') {
        showAlert("Debe ingresar la Fecha de nacimiento", "fecha_nacimiento");
        return;
    }
    if ($("#en_concepto_de").val().trim() == '') {
        showAlert("Debe ingresar una Observacion de Pago", "en_concepto_de");
        return;
    }
    // if ($("#denominacion_negocio").val().trim() == '') {
    //     showAlert("Debe ingresar una Denominacion", "denominacion_negocio");
    //     return;
    // }
    //para el pago 1
    if ($("#concepto_uno").val().trim() == '') {
        showAlert("Debe ingresar un Concepto de Pago", "concepto_uno");
        return;
    }
    // if ($("#cantidad_uno").val().trim() == '') {
    //     showAlert("Debe ingresar la Cantidad de Pagos", "cantidad_uno");
    //     return;
    // }
    if ($("#precio_uno").val().trim() == '') {
        showAlert("Debe ingresar el Importe a Pagar", "precio_uno");
        return;
    }
    return true;
}

//***cuando se presional el boton enviar */
function enviar() {
    grecaptcha.ready(function() {
        grecaptcha.execute('6Lf4wOQUAAAAAOazF-mb5Ce8oWwZZsz0plTCMZhU', {
            action: 'form'
        }).then(function(token) {
            document.getElementById("recaptchaResponse").value = token;
            if (validar()) {
                let form = document.getElementById("form");
                form.submit();
            }
        });
    });
}

$("#linkNroTramiteDni").click(function() {
    var box = bootbox.alert({
        message: '<div class="text-center"><img src="<?php echo base_url('assets/img/nro-tramite-dni.jpg'); ?>" class="img-fluid" /></div>',
        locale: 'es'
    });
});

$("#id_tipo_documento").change(function() {
    if (this.value == 1) {
        $("#divNroTramiteDni").show();
    } else {
        $("#divNroTramiteDni").hide();
    }
});

function selectCategoria(ev) {
    console.log(ev);
    let valorId = $("#" + ev.id).val();
    console.log(valorId);
    if (valorId !== "") {

        $.ajax({
            url: '/tramiteReba/getCategoriaReba/' + valorId,
            method: 'GET',
            contentType: 'application/json',
            global: false, //
            type: 'json',
            success: function(data) {
                console.log(data);
                $("#precio_uno").val(data.precio);
            },
            error: function(error) {
                $.unblockUI();
                alert("Se produjo un error , contacte al operador");
            }

        })
    }
}
</script>
<?php echo view('templates/frontend-base/footer.php'); ?>