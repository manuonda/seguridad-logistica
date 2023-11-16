<link href="<?php echo base_url() ?>/assets/css/wizard.css?v=1" rel="stylesheet">
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
<div class="container-fluid" id="grad1" style="padding-top: 70px">
	<?php if (!$ua->isMobile()) : ?>
    <div class="row justify-content-center mt-0">
        <div class="col-9 col-sm-9 col-md-9 col-lg-9 p-0 mb-2">
    <?php endif; ?>
            <div class="card px-0 pt-4 pb-0 mb-3">
                <h2 class="text-center"><strong><?php echo $title; ?></strong></h2>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="form-certificado">
                            <input type="hidden" id="certificado_controller" value="<?php if (isset($action)) echo $action; ?>" />
                            <input type="hidden" id="isPersonaValidada" value="<?php if (isset($isPersonaValidada)) echo $isPersonaValidada; ?>" />
                            <input type="hidden" name="<?= csrf_token() ?>" id="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                            <!-- progressbar -->
                            <?php if ((isset($id_tipo_tramite) && $id_tipo_tramite==TIPO_TRAMITE_CONSTANCIA_DENUNCIA) || (isset($isPersonaValidada) && $isPersonaValidada == 'true')) { ?>
                            <ul id="progressbar">
                                <li  id="personal" class="active text-center" style="width: 33.33%;"><strong>Informacion</strong></li>
                                <li  id="payment"  class="active text-center" style="width: 33.33%;"><strong>Pago</strong></li>
                                <li  id="confirm"  class="active text-center" style="width: 33.33%;"><strong>Finalizar</strong></li>
                            </ul>
                            <?php }else { ?>
                            <ul id="progressbar">
                                <li  id="personal" class="active text-center"><strong>Informacion</strong></li>
                                <li  id="calendar" class="active text-center"><strong>Turno</strong></li>
                                <li  id="payment"  class="active text-center"><strong>Pago</strong></li>
                                <li  id="confirm"  class="active text-center"><strong>Finalizar</strong></li>
                            </ul>
                            <?php } ?>
                            
                            <!-- fieldsets -->
                            <fieldset id="data_information" style="display:none">
                                <div class="form-card">
                                    <?php echo view($contenidopaso1); ?>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Inicio</button>
                                    <button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button"><span class="oi oi-arrow-right"></span> Adelante</button>
                                </div>
                            </fieldset>

							<?php if ((isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CONSTANCIA_DENUNCIA) && (isset($isPersonaValidada) && $isPersonaValidada != 'true')) { ?>
                                <fieldset id="turno_wizard" style="display:none">
                                    <div class="form-card" id="personal_information">
                                        <div class="col-md-12">
                                            <h2 class="fs-subtitle text-center">Turno</h2>
                                        </div>
                                        <?php echo view($contenidopaso2); ?>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary previous action-button-previous" type="button" id="btnVolver"><span class="oi oi-arrow-left"></span> Atras</button>
                                        <button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button"><span class="oi oi-arrow-right"></span> Adelante</button>
                                    </div>
                                </fieldset>
                            <?php } ?>

                            <fieldset id="pago_wizard" style="display:none">
                                <div class="form-card text-center">
                                    <div class="col-md-12">
                                        <h2 class="fs-subtitle text-center">Informacion del Pago</h2>
                                    </div>
                                    <div class="row" style="align-items: center; align-self: center;justify-content: center;">
                                        <div class="col-md-6">
                                            <div class="alert alert-info alert-dismissible">
                                                <h4><i class="icon fa fa-warning"></i> Información!</h4>
                                                OPERACION REALIZADA.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary previous action-button-previous" type="button" id="btnVolver">
                                        <span class="oi oi-arrow-left"></span>
                                        Atras
                                    </button>
                                    <button id="btnEnvioDatoPersonales" class="btn btn-primary next action-button" type="button">
                                    <span class="oi oi-arrow-right"></span> Adelante</button>
                                </div>
                            </fieldset>
                            <fieldset id="resultado_wizard">
                                <div class="form-card text-center">
                                	<?php echo view($contenidopaso4); ?>
                                </div>
                                <div class="text-center">
                                <!-- 
                                    <button class="btn btn-primary previous action-button-previous" type="button" id="btnVolver">
                                        <span class="oi oi-arrow-left"></span>
                                        Atras
                                    </button>  -->
                                    <a href="<?php echo base_url().'/' ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Inicio</a>
                                    <br>
                                <br>
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
    $("#id_localidad").select2({ width: '100%' });
    $(".dependencia").select2({ width: '100%' });

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
    var pase = 0;

    $(document).ready(function() {

        // disabled all inputs
        var all_inputs = Array.from(document.querySelectorAll("input"));
        for (var element of all_inputs) {
            element.disabled = true;
            element.readonly = true;
        }
        var all_selects = document.querySelectorAll("select");
        for( var element of all_selects) {
            element.disabled = true;
            element.readonly = true;
        }
        
         // disabled all inputs
         var all_inputs = Array.from(document.querySelectorAll("textarea"));
        for (var element of all_inputs) {
            element.disabled = true;
            element.readonly = true;
        }
        var all_selects = document.querySelectorAll("select");
        for( var element of all_selects) {
            element.disabled = true;
            element.readonly = true;
        }
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        // Next Operation
        $(".next").click(function() {
            current_fs = $(this).parent().parent();
            next_fs = $(this).parent().parent().next();
            current_id = current_fs[0].id;
            
            console.log(current_fs)
            console.log(next_fs)
            console.log(current_id)
            nextFs(next_fs, current_fs);
            

        });

        // Previous Operation
        $(".previous").click(function() {

            current_fs = $(this).parent().parent();
            previous_fs = $(this).parent().parent().prev();
          
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
</script>