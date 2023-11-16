<div class="row" style="align-items: center;
    align-self: center;
    justify-content: center;">
<div class="col-md-6">
<div class="alert alert-info alert-dismissible">
     <h4><i class="icon fa fa-info"></i> Información!</h4>
    PAGO PENDIENTE.
</div>
</div>
</div>

    <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
    <div class="form-group row">
        <label class="col-lg-12 control-label">
            <b>La solicitud se generará para:</b>
        </label>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            Apellido y nombre:<?php if (isset($persona->apellido)) echo $persona->apellido; ?>
             <?php if (isset($nombre)) echo $nombre; ?><br/>   
            Documento:  <?php if (isset($persona->documento)) echo $persona->documento; ?><br>
            Codigo Operación: <?php if(isset($codigo_operacion)) echo $codigo_operacion;?>
        </div>
    </div>

<?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
    <div class="alert alert-secondary" role="alert">
    	Una vez realizado el pago en el lugar seleccionado y validado sus datos. El certificado le llegará al número por whatsapp y al email indicado en el paso 1. Muchas gracias!!!
    <!-- 
        Una vez realizado el pago por algunas de las Sucursales. <br>
        Ingrese en la Pagina Principal en la Seccion <br> <a href="<?php echo base_url()."/descargarCertificado";?>">Descargar Certificado</a></b> para ver el estado de su Pago.
         -->
    </div>
<?php } ?>
