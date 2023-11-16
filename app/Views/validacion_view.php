<div class="container" style="padding-top: 70px">
    <div class="col-md-12" <?php if($ua->isMobile()): ?>style="padding-left: 0px; padding-right: 0px;"<?php endif; ?>>
    	<?php if (empty($error)) { ?>
    		<div class="card card-outline-secondary">
                <div class="card-header text-center">
                    <h4 class="mb-0">
                    	<font color= "blue">
                    	<?php echo strtoupper($tipo_tramite); ?>
                    	</font>
                    </h4>
                    <h5 class="mb-0">
                    	<?php if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION && $estado==TRAMITE_VALIDADO) { ?>
                        	<span class="oi oi-check" style="color:green"></span>&nbsp;&nbsp;<b><font color="green"><?php echo $estado?></font></b>
                    	<?php }else if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE && $estado==TRAMITE_VALIDADO) { ?>
                        	<span class="oi oi-check" style="color:green"></span>&nbsp;&nbsp;<b><font color="green"><?php echo $estado?></font></b>
                    	<?php }else if (isset($id_tipo_tramite) && $id_tipo_tramite == TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA && $estado==TRAMITE_VALIDADO_VERIFICADO) { ?>
                        	<span class="oi oi-check" style="color:green"></span>&nbsp;&nbsp;<b><font color="green"><?php echo $estado?></font></b>
                        <?php }else if (empty($contiene_firma_digital) || !$contiene_firma_digital || $estado==TRAMITE_PENDIENTE_VALIDACION || $estado==TRAMITE_INVALIDADO) {?>
                        	<span class="oi oi-x" style="color:red"></span>&nbsp;&nbsp;<b><font color="red"><?php echo 'INVÁLIDO';?></font></b>
                        <?php }else{?> 
                        	<span class="oi oi-check" style="color:green"></span>&nbsp;&nbsp;<b><font color="green"><?php echo $estado?></font></b>
                        <?php } ?>                    	
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
                        	Apellido y Nombre: <b><?php echo $apellido_nombre; ?></b><br/>
                        	Documento: <b><?php echo $documento; ?></b><br/>
                        	Cuil: <b><?php echo $cuil; ?></b><br/>
                            <?php if($id_tipo_tramite == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                            	Fecha de emisión: <b><?php if(empty($fecha_emision)) { echo date_format(date_create($fecha_envio_email), 'd/m/Y'); } else { echo date_format(date_create($fecha_emision), 'd/m/Y'); } ?></b><br/>
                                Antecedentes Penales: <b><?php echo $antecedentes_penales;?></b><br/>
                                Antecedentes Policiales: <b><?php echo $antecedentes_policiales;?></b><br/>
                            <?php }else { ?>
                            	Fecha de emisión: <b><?php echo date_format(date_create($fecha_envio_email), 'd/m/Y'); ?></b><br/>
                            <?php } ?>
                            
                            <?php if (isset($fotoColor) && $fotoColor != "" && $fotoColor != null) { ?>
                             <img style="    width: 150px;height: 180px;" id="FotoColor<?php echo $fotoColorId; ?>" src="<?php echo $fotoColor; ?>" width="300" height="300" />
                            <?php } ?>

                        </div>
                       
                        <div class="col-sm-2"></div>
                    </div>
                    <h5 class="mb-0">
                    	<?php //echo $estado_pago?>	
                    </h5>
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