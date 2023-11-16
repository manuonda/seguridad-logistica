<div class="row" style="align-items: center;
    align-self: center;
    justify-content: center;">
    <div class="col-md-6">
        <div class="alert alert-info alert-dismissible">
            <h4><i class="icon fa fa-info"></i> Información!</h4>
            PAGO REALIZADO.
        </div>
    </div>
</div>

<div class="card-body text-center">
    <input type="hidden" name="id_tramite" id="id_tramite" value="<?php if (isset($id_tramite)) echo $id_tramite; ?>" />
    <div class="form-group row">
        <label class="col-lg-12 control-label">
            <b>La solicitud se ha generado para:</b>
        </label>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            Apellido y nombre: <b>&nbsp;&nbsp;<?php if (isset($apellido)) echo $apellido; ?>
                <?php if (isset($nombre)) echo $nombre; ?></b><br />
            Documento:  <b>&nbsp;&nbsp;<?php if (isset($documento)) echo $documento; ?></b><br>
            N° de trámite: <b>&nbsp;&nbsp;<?php echo $id_tramite; ?></b>
        </div>
    </div>
	<?php if (isset($turno) && !empty($turno)) { ?>
		<div id="divDescargarTurno" class="col-sm-12 text-center">
        	<div class="col-sm-2"></div>
        	<div class="col-sm-12 p-3 mb-2 border border-success">
        		<h5 class="mb-0"><b>Estimado ciudadano: debe presentarse a validar sus datos, para eso tiene turno para el día <?php echo $util->fechaCastellano($turno['fecha']);?> a las <?php echo substr($turno['hora'],0,5);?> hs. en <?php echo $dependencia; ?>. </b></h5><br/>
        		<h5 class="mb-0">El comprobante de turno fue enviado a su email y además lo puede descargar haciendo click en el siguiente botón:</h5><br/>
        		<button class="btn btn-primary" type="button" id="btnDescargarTurno2"><span class="oi oi-data-transfer-download"></span> Descargar turno</button>
        	</div>
        	<div class="col-sm-2"></div>	
        </div>
	<?php }else { ?>
	
	<?php } ?>
</div>

<?php if (isset($id_tipo_tramite) && $id_tipo_tramite != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) { ?>
    <div class="alert alert-secondary" role="alert">
    	Una vez validado sus datos el trámite le llegará al número por whatsapp y al email indicado en el paso 1. Muchas gracias!!!
    	<!-- 
        Se verificaran sus datos, para descargar el certificado.<br>
         Visite la pagina en la seccion <b><a href="<?php //echo base_url()."/descargarCertificado";?>">Descargar Certificado</a></b> 
          -->
       <br />
    </div>
<?php } ?>
