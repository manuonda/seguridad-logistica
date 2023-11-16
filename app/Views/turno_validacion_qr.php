<div class="container" style="padding-top: 70px">
    <div class="col-md-12" <?php if($ua->isMobile()): ?>style="padding-left: 0px; padding-right: 0px;"<?php endif; ?>>
    	<?php if (empty($error)) { ?>
    		<div class="card card-outline-secondary">
                <div class="card-header text-center">
                    <h5 class="mb-0">
                    	<font color="green">
                    	TURNO VALIDO
                    	</font>
                    </h5>
                </div>
                <div class="card-body text-center">
            		<div class="form-group row">
                    	<label class="col-lg-12 control-label">
                  			<b>A nombre de:</b>
                    	</label>
                    </div>
                    <div class="form-group row">
                    	<div class="col-sm-4"></div>
                        <div class="col-sm-6 text-left">
                        	Apellido y nombre: <b><?php echo $apellido_nombre; ?></b><br/>
                        	Documento: <b><?php echo $documento; ?></b><br/>
                        	Cuil: <b><?php echo $cuil; ?></b><br/>
                        	Trámite: <b><?php echo $tipo_tramite; ?></b><br/>
                        	Dependencia: <b><?php echo $dependencia; ?></b><br/>
                        	Fecha: <b><?php echo date_format(date_create($fecha), 'd/m/Y'); ?></b><br/>
                        	Hora: <b><?php echo $hora; ?></b><br/>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
            </div>
    	<?php }else { ?>
            <div class="alert alert-danger text-center">
                <b><?php echo $error; ?></b>
            </div>
        <?php } ?>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>