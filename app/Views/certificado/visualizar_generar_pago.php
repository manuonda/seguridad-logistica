<div class="container" style="padding-top: 70px">
    <div class="col-md-12">
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><? echo $tipo_tramite ?></h5>
            </div>
            <?php echo form_open('registrar/guardar', 'class="form-horizontal" role="form" name="form" id="form"'); ?>
            <?php if (isset($error) and !empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
        	<?php endif; ?>
            <div id="alert"></div>
            <div class="card-body text-center">
            	<input type="hidden" name="id_tramite" id="id_tramite" value="<?php if(isset($id_tramite)) echo $id_tramite; ?>" />
        		<div class="form-group row">
                    <div class="col-sm-12">
                    	<b><?php if(isset($apellido)) echo $apellido; ?> <?php if(isset($nombre)) echo $nombre; ?>, su trámite está en proceso de validación.</b><br/>
                    	<b>Realice el pago haciendo clic en el siguiente boton:</b><br/><br/>
                    	<a class="btn btn-primary" href="<?php echo $preference->init_point ;?>">Pagar</a>
                    </div>                        
                </div>
            	<div class="alert alert-warning" role="alert">
                	<b>Luego de realizar el pago imprima o guarde el comprobante de pago.</b><br/>
                	<b>El trámite estará disponible para su descarga después de 24 hs. de haber realizado el mismo y será enviado también a su email.</b>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 text-center">
                    <button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>
<script type="text/javascript">
$( "#btnVolver" ).click(function() {
	location.href = '<?php echo base_url(); ?>/tramite';
});
</script>