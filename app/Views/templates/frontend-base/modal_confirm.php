<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 id="modal_confirm_titulo" class="modal-title">¡Atención!</h4>
        </div>
        <div id="modal_confirm_body" class="modal-body">
            mensaje de confirmacion
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <button type="button" id="modal_confirm_btn_cancel" class="btn btn-primary" data-dismiss="modal" onclick="modalCancelar();">Cancelar</button>
                <button type="button" id="modal_confirm_btn_confirm" class="btn btn-primary" onclick="modalConfirmar();"> Si </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function modalConfirmar() {
//        alert('Setee su funcion confirmar particular!!');
    }
    function modalCancelar() {
//        alert('funcion cancelar');
    }
</script>