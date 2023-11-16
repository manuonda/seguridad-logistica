<div class="row" style="align-items: center;
    align-self: center;
    justify-content: center;">
    <div class="col-md-6">
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-warning"></i> Información!</h4>
            OPERACION DE PAGO NO REALIZADA.
        </div>
    </div>
</div>

<div class="card-body text-center">
    <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
    <div class="form-group row">
    	<label class="col-lg-12 control-label">
            <b>La solicitud se ha generado para:</b>
        </label>
        <div class="col-sm-12"> 
            Apellido y nombre:<b>&nbsp;&nbsp;<?php if (isset($apellido)) echo $apellido; ?>
             <?php if (isset($nombre)) echo $nombre; ?></b><br/>   
            Documento:<b>&nbsp;&nbsp;<?php if (isset($documento)) echo $documento; ?></b><br>
            N° de trámite:<b>&nbsp;&nbsp;<?php if (isset($id_tramite)) echo $id_tramite; ?></b><br><br>
        </div>
        <label class="col-lg-12 control-label">
            <b>Pero debe acercarse a <?php if(isset($dependencia)) echo $dependencia; ?> para realizar el pago del mismo.</b>
        </label>
    </div>
    <?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
        <div class="alert alert-secondary" role="alert">
        	Una vez realizado el pago y validado sus datos. El certificado le llegará al número por whatsapp y al email indicado en el paso 1. Muchas gracias!!!
        </div>
    <?php } ?>
</div>
