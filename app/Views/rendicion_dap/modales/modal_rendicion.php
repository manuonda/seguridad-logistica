<div class="modal" id="modal_rendicion" data-backdrop="static" data-keyboard="false" role="dialog" style="background: rgba(0,0,0,0.7);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cobrar trámite</h5>
                <!-- 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="module_pago.closeModalPago()">
                    <span aria-hidden="true">×</span>
                </button>  -->
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_tramite"/>
                <label><strong>Cantidad Tramites</strong></label> : <label id="numero_tramites"></label>
                <br>
                <label><strong>Importe Total $:</strong></label>: <label id="importe_total"></label>
                <br>
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-modal-cobrar" onClick="module_rendicion.realizarRendicion()">Realizar Rendición</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="module_rendicion.closeRendicion()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

