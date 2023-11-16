<div class="row" style="align-items: center;
    align-self: center;
    justify-content: center;">
<div class="col-md-6">
<div class="alert alert-info alert-dismissible">
     <h4><i class="icon fa fa-info"></i> Información!</h4>
     EXPOSICION POR NO VOTACION, GENERADA CORRECTAMENTE
</div>
</div>
</div>

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
      
        <!-- 
        <div class="col-sm-12">
           <a class="btn btn-primary" target="_blank" href="<?php //echo base_url()."/tramite/descargarcomprobante/".$id_tramite; ?>">
           <span class="oi oi-cloud-download"></span>
            Descargar Comprobante de Pago en Comisaria
           </a>
        </div>
               <!-- 
        Se verificaran sus datos, para descargar el certificado.<br>
         Visite la pagina en la seccion <b><a href="<?php //echo base_url()."/descargarCertificado";?>">Descargar Certificado</a></b> 
          -->

          <div class="col-sm-12">
          <div class="form-group">
            <div id="divDescargarTurno" class="col-sm-12 text-center">
            	<div class="col-sm-2"></div>
            	<div class="col-sm-12 p-3 mb-2 border border-success">
            		<h5 class="mb-0"><b>Estimado ciudadano: Debe presentarse hoy a la <?php echo $dependencia; ?>, para validar sus datos</b></h5><br/>
            	</div>
            	<div class="col-sm-2"></div>	
            </div>
          </div>
          </div>
       
       <br />
    </div>

<?php echo view('util_javascript.php'); ?>
