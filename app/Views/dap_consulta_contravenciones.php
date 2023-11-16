<div class="container" style="padding-top: 70px">
    <div class="col-md-12">
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>CONSULTAR CONTRAVENCIONES</b></h5>
            </div>
            <form action="<?php echo base_url().'/dap/verificar'; ?>" method="post" id="form">
                <div class="card-body">
                    <!-- *************************************** -->
                    <?php if ( isset($status)) { ?>
                    <?php if ($status == 'ERROR') { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <h6><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Información </h6>
                        <ul id="list_message_error">
                            <li> <?php echo $message; ?></li>
                        </ul>
                    </div>
                    <?php }else{ ?>
                    <div class="alert alert-success alert-dismissible">
                        <h6><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Información </h6>
                        <ul id="list_message_error">
                            <li> <?php echo $message; ?></li>
                        </ul>
                    </div>
                    <?php } ?>
                    <?php } ?>

                    <!-- *************************************** -->
                    <?= \Config\Services::validation()->listErrors('my_errors'); ?>
                    <?php if (isset($error) and !empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <div class="form-group row">
                        <label class="col-lg-12 control-label"><b>Datos personales</b></label>
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
                        <label class="col-lg-3 col-form-label form-control-label">N° de documento *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento" id="documento" class="form-control mayuscula"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                value="<?php if(isset($documento)) echo $documento; ?>"
                                placeholder="Ingrese el nro. de documento" required spellcheck="false" />
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tipo Contravencion *:</label>
                        <div class="col-lg-9">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_contravencion"
                                            id="tipo_contravencion1" value="1" checked>
                                        <label class="form-check-label" for="comercial"> Comercial </label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_contravencion"
                                            id="tipo_contravencion2" value="2">
                                        <label class="form-check-label" for="otros"> Otros </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <!-- *************************************** -->
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                    <!-- *************************************** -->
                    <div class="form-group row">
                        <div class="col-lg-12 text-center">
                            <a href="<?php echo base_url();?>" class="btn btn-primary"><span class="oi oi-home"></span>
                                Volver</a>
                            <button class="btn btn-secondary" type="button" onclick="limpiar()"><span class=""></span>
                                Limpiar</button>
                            <button class="btn btn-primary" type="button" id="btnReiniciar" onclick="enviar()"><span
                                    class="oi oi-magnifying-glass"></span> Buscar</button>
                        </div>
                    </div>

                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
//limpia los campos
function limpiar() {
    $("#id_tipo_documento").val("");
    $("#documento").val("");
}

function validar() {
    var id_tipo_documento = $("#id_tipo_documento").val().trim();
    if (id_tipo_documento == '') {
        showAlert("Debe ingresar el Tipo de documento", "id_tipo_documento");
        return;
    }
    if ($("#documento").val().trim() == '') {
        showAlert("Debe ingresar el N° de Documento", "documento");
        return;
    }
    return true;
}

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
</script>
<?php echo view('templates/frontend-base/footer.php'); ?>