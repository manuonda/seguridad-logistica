
<div class="container" style="margin-top:56px">

<form action="<?php echo base_url().'/aws/upload2' ;?>" method="POST" enctype="multipart/form-data">
<div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Archivo o foto :</label>
                    <div class="col-lg-9">
                    <input id="facturaServicio" name="facturaServicio" type="file" class="form-control-file" />
                    </div>
                </div>

                <button type="submit">Enviar</button>

                <?php if(!empty($base64) && isset($base64)) { ?>
                    <img src="<?php echo $base64; ?>" width="300" height="300"/>   

                <?php } ?>
</form>
</div>
