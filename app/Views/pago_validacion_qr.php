<div class="container" style="padding-top: 70px">
    <div class="col-md-12" <?php if($ua->isMobile()): ?>style="padding-left: 0px; padding-right: 0px;"<?php endif; ?>>
    	<?php if (empty($error)) { ?>
    		<div class="card card-outline-secondary">
                <div class="card-header text-center">
                    <h5 class="mb-0">
                    	<font color="green">
                    	COMPROBANTE DE PAGO VALIDO
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
                        	Nro. de Transacción: <b><?php echo $id_tramite; ?></b><br/>
                        	Documento: <b><?php echo $documento; ?></b><br/>
                        	Nombre y apellido: <b><?php echo $nombre_apellido; ?></b><br/>
                        	Fecha: <b><?php echo date_format(date_create($fecha_pago), 'd/m/Y'); ?></b><br/>
                        	Dependencia: <b><?php echo $dependencia; ?></b><br/>
                        	Tipo trámite: <b><?php echo $tipo_tramite; ?></b><br/>
                        	Importe: <b><?php echo $precio; ?></b><br/>
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