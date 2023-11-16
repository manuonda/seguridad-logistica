<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="//cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>

<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary col-lg-10 offset-lg-1">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><?php echo $tipo_tramite; ?></h3><br/>
            </div>
            <?= \Config\Services::validation()->listErrors('my_errors'); ?>
			<?php if (isset($error) and !empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
        	<?php endif; ?>
            <?php echo form_open('tramite/guardarTramiteGeneral'); ?>
            	<input type="hidden" name="id_tipo_tramite" id="id_tipo_tramite" value="<?php if (isset($id_tipo_tramite)) echo $id_tipo_tramite; ?>" />
            	<input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
            	<input type="hidden" name="id_persona" id="id_persona" value="<?php if (isset($id_persona)) echo $id_persona; ?>" />
            	<input type="hidden" name="tipo_tramite" id="tipo_tramite" value="<?php if (isset($tipo_tramite)) echo $tipo_tramite; ?>" />
                <div class="form-group row">
                    <label class="col-lg-2 control-label" for="id_tipo_documento">Tipo documento *:</label>
                    <div class="col-lg-4">
                        <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($tipoDocumentos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($id_tipo_documento) && $id_tipo_documento == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-lg-2 col-form-label form-control-label">Documento *:</label>
                    <div class="col-lg-4">
                        <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label form-control-label">Nombre *:</label>
                    <div class="col-lg-4">
                        <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if (isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" />
                    </div>
                    <label class="col-lg-2 col-form-label form-control-label">Apellido *:</label>
                    <div class="col-lg-4">
                        <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if (isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" />
                    </div>
                </div>
                <div class="form-group row">
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label form-control-label">Tel&eacute;fono *:</label>
                    <div class="col-lg-4">
                        <input type="number" name="telefono" id="telefono" class="form-control mayuscula" value="<?php if (isset($telefono)) echo $telefono; ?>" placeholder="Tel&eacute;fono, solo n&uacute;meros" maxlength="20" required spellcheck="false" />
                    </div>
                    <label class="col-lg-2 col-form-label form-control-label">Email *:</label>
                    <div class="col-lg-4">
                        <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($email)) echo $email; ?>" placeholder="EMAIL DE CONTACTO" maxlength="100" required spellcheck="false" />
                    </div>
                </div>
                <!-- 
                <div class="form-group row">
                	<label class="col-lg-3 col-form-label form-control-label">Texto del trámite :</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones"><?php // if(isset($observaciones)) echo $observaciones; ?></textarea>
                    </div>
                </div>
                 -->
                <div class="form-group row">
                	<label class="col-lg-2 col-form-label form-control-label">Texto del trámite :</label>
                    <div class="col-sm-10">
                        <!-- <textarea id="summernote" name="summernote"></textarea> -->
                            <textarea id="editor1" name="editor1">
                                    <?php if(isset($observaciones)) echo $observaciones; ?>
                            </textarea>
                    </div>
                </div>
  <!-- <script>
    $(document).ready(function() {
        $('#summernote').summernote({
        	height: 350,
        });
    });
  </script>               -->
  <script>
            CKEDITOR.replace( 'editor1' );
  </script>

                <div class="col-md-12 text-center"><br/>
                	<a href="<?php echo base_url().'/tramite/volver'; ?>" class="btn btn-primary" type="button" id="btnVolver"><span class="oi oi-reload"></span> Volver</a>
                	<button class="btn btn-primary" type="submit"><span class="oi oi-magnifying-glass"></span> Guardar</button>
                </div>
            <?php echo form_close(); ?>
            <br/>
            
        </div>
    </div>
</div>
<br>
<?= $this->include('dashboard/modales/modal_comisaria_pago.php') ?>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>