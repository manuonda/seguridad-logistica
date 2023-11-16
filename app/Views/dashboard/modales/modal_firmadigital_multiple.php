<div class="modal" id="modal-firma-digital-multiple">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir archivos multiples de Firma Digital</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="module_pago.closeModalPago()">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="form-group mt-3">
                  <input id="files" type="file" name='images[]' multiple="" class="form-control">
              </div>
              <div id="selectedFiles"></div>

     
              <!-- mostrar message de tramite no existe -->
              <div id="mostrarMessageNoExisteTramite" style="display: none">
                 <div class="alert alert-danger alert-dismissible">
                 <h4><i class="icon fa fa-info"></i> Información!</h4>
                    EL TRAMITE NO EXISTE.
                </div>
              </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="module_fileupload_multiple.uploadFirmaDigitalMultiple()">Subir Archivo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="module_fileupload_multiple.closeModalUploadFirmaDigitalMultiple()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

