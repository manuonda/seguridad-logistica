
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Registrar feriado</b></h3>
            </div>
            <br>
            <?php echo form_open_multipart('turnoFeriado/guardar', 'class="form-horizontal" role="form"'); ?>
            	<?= \Config\Services::validation()->listErrors('my_errors'); ?>
				<?php if (isset($error) and !empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
            	<?php endif; ?>
            	<input type="hidden" name="id_turno_feriado" id="id_turno_feriado" value="<?php if (isset($id_turno_feriado)) echo $id_turno_feriado; ?>" />
                <div class="form-group row">
                	<div class="col-lg-4"></div>
                    <label class="col-lg-1 col-form-label form-control-label">Fecha *:</label>
                    <div class="col-lg-3">
                        <input type="date" name="fecha" id="fecha" class="form-control mayuscula" value="<?php if (isset($fecha)) echo $fecha; ?>" placeholder="Fecha" required spellcheck="false" />
                    </div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="form-group row">
                	<div class="col-lg-4"></div>
                    <label class="col-lg-1 col-form-label form-control-label">Descripción *:</label>
                    <div class="col-lg-3">
                        <input type="text" name="descripcion" id="descripcion" class="form-control mayuscula" value="<?php if (isset($descripcion)) echo $descripcion; ?>" placeholder="Descripción"  spellcheck="false" />
                    </div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="col-lg-12 text-center">
                	<button class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</button>
                    <button class="btn btn-primary" type="submit"><span class="oi oi-document"></span> Guardar</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
 <script type="text/javascript">
$( "#btnVolver" ).click(function() {
	location.href = '<?php echo base_url(); ?>/turnoFeriado';
});
 </script>
