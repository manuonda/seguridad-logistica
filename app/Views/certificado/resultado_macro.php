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
                        
                            <fieldset id="resultado_wizard">
                                <div class="form-card text-center">
                                	<?php echo view($contenidopaso4); ?>
                                </div>
                                <div class="text-center">
                                   <a href="<?php echo $url?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-home"></span> Inicio</a>
                                  <br>
                                <br>
                                </div>
                                
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
 
</div>
    </div>
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
