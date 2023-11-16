<link href="<?php echo base_url() ?>/assets/css/wizard.css?v=1" rel="stylesheet">
<div class="container-fluid" id="grad1" style="padding-top: 70px">
    <div class="row justify-content-center mt-0">
        <div class="col-9 col-sm-9 col-md-9 col-lg-9 p-0 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2 class="text-center"><strong>
                <?php if($action == "edit"){ ?>
                    <span class="oi oi-pencil"></span>
                <?php }?>
                <?php if($action == "new"){ ?>
                    <span class="oi oi-document"></span> Nuevo
                <?php } ?>
 
                <?php echo $title; ?></strong></h2>
                <?php if(isset($contiene_firma_digital) && $contiene_firma_digital=='t') { ?>
                    <!-- 
					<h5 class="text-center" style="color: red;">El trámite no se puede modificar porque ya esta firmado digitalmente.</h5>
					 -->
				<?php } ?>

                <div id="divForm" class="row">
                    <div class="col-md-12 mx-0">
                    <form action="<?php echo base_url().'/'.$controller.'/guardar'; ?>" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                        <?php echo view($contenidoedit); ?>
                        <input type="hidden" name="tipoForm" value="edit"/>
                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                        <div class="form-group row">
                            <div class="col-lg-12 text-center">
                            	<?php if(!empty($userInSession)) : ?>
                                	<?php if($userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5 
                                    || $userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL
                                    || $userInSession['id_rol']==ROL_ANTECEDENTE ) : ?>
                                    	<a href="<?php echo base_url().'/'.$controller.'/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                                    	<button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Guardar</button>
                                    <?php endif; ?>
                                    <?php if($userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) : ?>
                                    	<a href="<?php echo base_url().'/'.$controller.'/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                                    	<button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Guardar</button>
                                    <?php endif; ?>
                                    <?php if($id_tipo_tramite==TIPO_TRAMITE_CONSTANCIA_DENUNCIA && $userInSession['id_rol']==ROL_CIAC) : ?>
                                    	<a href="<?php echo base_url().'/'.$controller.'/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                                    	<button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Guardar</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($action) && $action=="edit") : 
      echo view('templates/backend-base/footer.php');  
endif; ?>

<?php if(empty($edit)) : 
      echo view('templates/frontend-base/footer.php');  
endif; ?>


<?php echo view('util_javascript.php'); ?>
<script type="text/javascript">
$("#id_localidad").select2({ width: '100%' });

<?php if (empty($id_tramite)) { ?>
    $(".dependencia").select2({ width: '100%' });
<?php } ?>

//si el tramite ya esta firmado, se desabilita todos los controles del formulario.
<?php if(isset($contiene_firma_digital) && $contiene_firma_digital=='t') { ?>
	$('#divForm').find('input, textarea, button, select').attr('disabled','disabled');
<?php } ?>
</script>