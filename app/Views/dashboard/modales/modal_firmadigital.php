<div class="modal" id="modal-firma-digital" style="background: rgba(0,0,0,0.7);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir archivo Firma Digital</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="module_pago.closeModalPago()">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- muuestra div si existe la firma digital -->  
               <div id="mostrar-archivo-firma-digital" style="display: none">
                  <div class="alert alert-info alert-dismissible">
                  <h4><i class="icon fa fa-info"></i> Información!</h4>
                  <button type="button" onclick="module_util.descargarFirmaDigital()">Descargar Firma Digital</button>
                 </div>
                </div>
  
                 
                <input name="id_file_tramite" id="id_file_tramite" type="hidden"/>
              
                <label><strong>Archivo: </strong>
                <input name="file_tramite" type="file" id="file_tramite">
               <!-- muestra el message de Pago aprobado -->  
              <div id="mostrarMessageCorrecto" style="display: none">
                 <div class="alert alert-info alert-dismissible">
                 <h4><i class="icon fa fa-info"></i> Información!</h4>
                   PAGO REALIZADO.
                </div>
              </div>

              <!-- mostrar message de tramite no existe -->
              <div id="mostrarMessageNoExisteTramite" style="display: none">
                 <div class="alert alert-danger alert-dismissible">
                 <h4><i class="icon fa fa-info"></i> Información!</h4>
                    EL TRAMITE NO EXISTE.
                </div>
              </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="module_util.uploadFirmaDigital()">Subir Archivo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="module_util.closeModalUploadFirmaDigital()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

