
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Crear trámite</b></h3><br/>
            </div>
            <?php echo form_open('tramite/cargarDatos'); ?>
            	<div class="form-group row">
            		<div class="col-lg-2 col-form-label"></div>
                    <label class="col-lg-2 col-form-label form-control-label">Tipo de trámite :</label>
                    <div class="col-lg-6">
                        <select name="id_tipo_tramite" id="id_tipo_tramite" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($listaTipoTramites as $item) : ?>
                            	<?php if ($item['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA && $item['id_tipo_tramite'] != TIPO_TRAMITE_TRAMITAR_REBA): ?>
                                	<option value="<?php echo $item['id_tipo_tramite'] ?>"><?php echo $item['tipo_tramite'] ?></option>
                                <?php endif;  ?>	
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                </div>
                <div class="form-group row">
                	<div class="col-lg-2 col-form-label"></div>
                    <label class="col-lg-2 control-label" for="id_tipo_documento">Tipo de documento *:</label>
                    <div class="col-lg-2">
                        <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" data-toggle="tooltip" data-placement="bottom" required>
                            <option value="">-- SELECCIONAR --</option>
                            <?php foreach ($tipoDocumentos as $item) : ?>
                                <option value="<?php echo $item['id_tipo_documento'] ?>" <?php if (isset($id_tipo_documento) && $id_tipo_documento == $item['id_tipo_documento']) echo 'selected="selected"'; ?>><?php echo $item['tipo_documento'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-lg-2 col-form-label form-control-label">Nro. de Documento *:</label>
                    <div class="col-lg-2">
                        <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if (isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false" />
                    </div>
                </div>                
                <div class="col-md-12 text-center"><br/>
                	<button class="btn btn-primary" type="submit"> Crear trámite</button>
                </div>
                <div class="modal" tabindex="-1" role="dialog">
        
  <div id="bootstrap-modal" class="modalito modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
            <?php echo form_close(); ?>
            <br/>
            
        </div>
    </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    documento = document.getElementById("documento");
    documento.type = "number";
    //validar();
    $("#id_tipo_tramite").select2({ width: '100%' });
  });

  $("#id_tipo_documento").change(function() {
	  if(this.value == 1) {
		  documento.type = "number";
	  }else {
		  documento.type = "text";
	  }	
  });

  /*function validar() {
    var id_tipo_tramite = $("#id_tipo_tramite").val().trim(); 
    if (id_tipo_tramite == '') {
        showAlert("Debe seleccionar un tipo de tramite", "id_tipo_tramite");
        return;
    }
	  if($("#documento").val().trim()=='') {
		  showAlert("Debe ingresar el Documento", "documento");
		  return;
	  }
  }*/
</script>
                    

<?= $this->include('dashboard/modales/modal_comisaria_pago.php') ?>
<?php echo view('templates/frontend-base/footer.php'); ?>
<?= $this->include('js/module_pago.php') ?>