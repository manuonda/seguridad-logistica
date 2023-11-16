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
      
    </div>
    <div class="alert alert-secondary" role="alert">
    	No se ha realizado el pago a través de la plataforma de Banco Macro. Acerquese a la Unidad Regional para completar el pago en efectivo.
    </div>
</div>
