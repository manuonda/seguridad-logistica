<div class="modal" id="modal-estado-pago" data-backdrop="static" data-keyboard="false" role="dialog" style="background: rgba(0,0,0,0.7);">
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
                <label><strong>Id Tramite</strong></label> : <label id="nro_tramite"></label>
                <br>
                <label><strong>Tipo Tramite</strong></label>: <label id="tipo_tramite"></label>
                <br>
                <label><strong>Estado Pago</strong></label>: <label id="estado_pago"></label> 
                <br>
                <label><strong>Importe</strong></label>: $ <label id="precio"></label>
                <div id="divComprobante" style="display: none">
                	<hr/>
                	<label><strong>Fecha y hora de pago</strong></label> : <label id="fechaHoraPago"></label>
                	<br>
                	<a class="btn btn-primary" target="_blank" href="#" id="linkModalDescargarComprobante"><span class="oi oi-cloud-download"></span>  Descargar comprobante</a>
                	<span id="spanNroReciboManual" style="display: none"><br/><br/>N° de recibo emitido manualmente: <b id="nroReciboManual"></b></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-modal-cobrar" onClick="module_pago.realizarCobroEnComisaria()">Cobrar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="module_pago.closeModalPago()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

