<style>
  
   .body{
       font-size: 12px;
   }
   .form-control{
       font-size: 12px;
   }
</style>
<div class="bs-docs-section"" style="margin-top:70px ; font-size:12px">
<div class="row">
    <div class="col-md-6" style="padding-left: 5px;padding-right: 0px;">
            <form action="<?php echo base_url() . '/' . $controller . '/guardar'; ?>" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
          
            <h4 class="text-center"><strong>
                    <?php if ($action == "edit") { ?>
                        <span class="oi oi-pencil"></span>
                    <?php } ?>
                    <?php if ($action == "new") { ?>
                        <span class="oi oi-document"></span> Nuevo
                    <?php } ?>
                </strong>
                <?php echo $title; ?></strong></h4>
            
            <?php echo view($contenidoedit); ?>
                <input type="hidden" name="tipoForm" value="edit" />
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse" />
                <div class="form-group row">
                    <div class="col-lg-12 text-center">
                        <?php if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_COMISARIA_SECCIONAL) : ?>
                            <a href="<?php echo base_url() . '/' . $controller . '/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                            <button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Guardar</button>
                        <?php endif; ?>
                        <?php if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) : ?>
                            <a href="<?php echo base_url() . '/dashboard'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                            <button class="btn btn-primary" type="button" onclick="generar(this.form);"><span class="oi oi-document"></span> Guardar</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
        <h4 class="text-center">
            <strong>
             <span class="oi oi-eye"></span> 
             </strong>
            <?php echo $title; ?> VALIDADO </strong></h4>
            </h2>
            <?php echo view($contenidoview); ?>
        </div>

        </form>
    </div>
</div>

<?php if (isset($action) && $action == "edit") :
    echo view('templates/backend-base/footer.php');
endif; ?>

<?php if (empty($edit)) :
    echo view('templates/frontend-base/footer.php');
endif; ?>


<?php echo view('util_javascript.php'); ?>
<script type="text/javascript">
    $("#id_localidad").select2({
        width: '100%'
    });

    <?php if (empty($id_tramite)) { ?>
        $("#id_dependencia").select2({
            width: '100%'
        });
    <?php } ?>
</script>