<div class="modal" id="modal-tipo-tramite">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tipo Tramite</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                 <label> Tipo Tramite </label>
                 <select  class="select form-control" id="idTipoTramiteModal">
                  <option value="">Seleccionar</option>
                   <?php 
                   foreach($tipoTramites as $option ) { ?>
                      <option value="<?php echo $option['id_tipo_tramite'] ;?>" controlador="<?php echo $option['controlador'];?>">
                      <?php echo $option['tipo_tramite'];?> 
                      </option>

                   <?php }?>

                 </select>
              </div>
              <p class="text-danger" id="error-tipo-tramite" style="display: none">Debe Seleccionar el Tipo de Tramite.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="btnActionAceptarModal()">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="btnCloseTipoTramiteModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var baseUrl = "<?php echo base_url(); ?>";
console.log("baseUrl :", baseUrl);

/** btn Action Aceptar */
function  btnActionAceptarModal() {
    let selected = document.getElementById("idTipoTramiteModal");
    let value    = document.getElementById("idTipoTramiteModal").value;
    if ( value === "") {
       $("#error-tipo-tramite").css("display","");
    }else {
      $("#error-tipo-tramite").css("display","none"); 
      let controlador =  document.getElementById("idTipoTramiteModal").options[selected.selectedIndex].getAttribute("controlador");
      let newUrl= baseUrl +"/"+ controlador + "/new";
      console.log(newUrl);
      window.location.href = newUrl;
    } 

}
/** btn Close Modal Tipo Tramite */
function btnCloseTipoTramiteModal(){
    $("#error-tipo-tramite").css("display","none");
    $("#idTipoTramiteModal").val("");
    $("#modal-tipo-tramite").hide();
}

</script>