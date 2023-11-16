<style>
.box-shadow-hover:hover {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  background: #d9edf7;
}

.pointer {
  cursor: pointer;
}

img {
  width: auto;
  max-height: 100px;
}

a {
    text-decoration: none;
    color: #203040;
}

a:hover{
    text-decoration: none;
    cursor: pointer;
}
</style>
<div class="container" style="padding-top: 70px">
    <div class="col-md-12">
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h3 class="mb-0"><b>TR&Aacute;MITES</b></h3>
            </div>
			<?php echo form_open('registrar/guardar', 'class="form-horizontal" role="form" name="form" id="form"'); ?>
            
            <div class="card-body text-center">
            	<div class="alert alert-dismissible alert-warning">
                  <h4 class="alert-heading">¡Atención!</h4>
                  <p class="mb-0" align="justify">
                  	Si no se encuentra su comisaria seccional en el siguiente listado, por favor dirijase a la misma para reliazar el tramite ya que 
                  	por el momento solo se encuentran habilitadas la Seccional 1 (B° Centro), Seccional 2 (B° Gorriti y B° Lujan), Seccional 3 (B° Chijra), Seccional 4 (B° Cuyaya), Seccional 5 (B° Ciudad de nieva), Seccional 6 (B° ALTE. BROWN),  
                  	Seccional 30 (B° Mariano Moreno), Seccional 31 (B° Cnel. Arias), Seccional 32 (B° MALVINAS), Seccional 33 (ALTO COMEDERO), Seccional 34 (V.J. DE REYES), Seccional 44 (Villa San Martín y B° Belgrano), Seccional 46 (Aeroparque), Seccional 49 (Los Huaicos), Seccional 50 (B° Campo Verde), Seccional 55 (B° Los Perales), Seccional 59 (B° San Cayetano)  
                  	, Seccional 61 (B° El Chingo), Seccional 62 (B° Sgto. Cabral), Seccional 63 (18 Hectareas) y la Subcomisaria San Francisco de Alava 
                  	de la ciudad de San Salvador de Jujuy, ademas la Seccional 23 (B° Belgrano de Palpala), Seccional 47 (Paso de jama) y Seccional 51 (B° 18 de Noviembre) de la ciudad de Palpala, Subcomisaría de Rio Blanco y las U.A.D. de las Unidades Regionales 2 (San Pedro), 3 (Humahuaca), 4 (L.G.S.M.), 5 (La Quiaca) y 6 (Perico), para la solicitud de los siguientes trámites online:
                  </p>
                </div>
                <div class="row">
<!--                     <div class="col-lg-12 text-center"> -->
                        <?php foreach($listaTipoTramites as $item): ?>
                        <!--  
                        https://www.solodev.com/blog/web-design/how-to-create-boxes-with-a-distinct-hover-effect.stml
                        <button class="btn btn-primary" type="button" id="tramite_<?php //echo $item['id_tipo_tramite']?>"><span class="oi oi-document"></span> <?php //echo $item['tipo_tramite']?></button>
                       	<br/><br/> -->
                       	<?php if ($item['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA && $item['id_tipo_tramite'] != TIPO_TRAMITE_TRAMITAR_REBA && $item['id_tipo_tramite'] != TIPO_TRAMITE_PLANILLA_PRONTUARIAL) { ?>
                        	<div class="col-md-4 col-sm-6 col-xl-4 my-3" id="tramite_<?php echo $item['id_tipo_tramite']?>" onclick="solicitar('<?php echo $item['controlador']?>');">
                              <div class="card d-block h-100 box-shadow-hover pointer">
                              	<div class="pt-3 h-75p align-items-center d-flex justify-content-center">
          							<img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/'.$item['controlador'].'.png'); ?>" alt="Imagen">
        						</div>
                                <div class="card-body p-4">
                                  <?php if ($item['tipo_tramite'] == 'REBA') { ?>
                                  	<h3 class="h4"><strong>PAGAR <?php echo $item['tipo_tramite']?></strong></h3>
                                  <?php }else { ?>
                                  	<h3 class="h4"><strong><?php echo $item['tipo_tramite']?></strong></h3>
                                  <?php } ?>
                                  <?php if (!empty($item['precio'])) { ?>
                                  	Importe $ <?php echo $item['precio']; ?>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                        <?php } ?>	
                        <?php endforeach;?>
<!--                     </div> -->
                </div>
            </div>
			<?php echo form_close(); ?>
        </div>
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Para tramitar la Planilla Prontuarial en la Central de Policía puede solicitar turno en el siguiente botón:</b></h5>
            </div>
            <div class="card-body text-center">
            	<div class="row">
            	  <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3" id="tramite_47" onclick="solicitar('planillaProntuarial');">
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/planillaProntuarial.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Planilla Prontuarial</strong></h3>
                        Importe $ <?php echo $tramitePlanilla['precio']+$tramitePlanilla['importe_adicional']; ?>
                      </div>
                    </div>
                  </div>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
              </div>  
            </div>
        </div>
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Para realizar tramite de REBA se encuentra habilitadas la D.A.D. en la Central de Policia y las U.A.D. en la Unidad Regional 2 (San Pedro), Unidad Regional 3 (Humahuaca), 
                					Unidad Regional 4 (L.G.S.M.), Unidad Regional 5 (La Quiaca) y Unidad Regional 6 (Perico):</b></h5>
            </div>
            <div class="card-body text-center">
            	<div class="row">
            	  <div class="col-md-4 col-sm-6 col-xl-4 my-3" id="tramite_47" onclick="solicitar('descargarCertificadoReba');">
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/descargarCertificadoReba.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Pagar Reba</strong></h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3" id="tramite_47" onclick="solicitar('tramitarReba');">
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/tramitarReba.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Tramitar Reba</strong></h3>
                      </div>
                    </div>
                  </div>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
              </div>  
            </div>
        </div>
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0">
                	<b>Para realizar el pago de una infracción contravencional, realizar clic en el siguiente botón:</b>
                </h5>
            </div>
            <div class="card-body text-center">
            	<div class="row">
            	  <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
            	  <div class="col-md-4 col-sm-6 col-xl-4 my-3" id="tramite_47" onclick="solicitar('pagoContravencion');">
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Pagar contravención</strong></h3>
                      </div>
                    </div>
                  </div>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
              </div>  
            </div>
        </div>
        <!-- 
        <div class="card card-outline-secondary">
            <div class="card-header text-center">
                <h5 class="mb-0"><b>Si ya has realizado algún trámite y deseas conocer su estado o descargarlo haga clic en el siguiente botón:</b></h5>
            </div>
            <div class="card-body text-center">
            	<div class="row">
            		<div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
            	  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php // echo base_url().'/descargarCertificado'; ?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php // echo base_url('assets/img/descarga.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Descargar</strong></h3>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3"></div>
              </div>  
            </div>
        </div>
         -->
    </div>            
</div>
<br>
<?php echo view('templates/frontend-base/footer.php'); ?>
<script type="text/javascript">
	function solicitar(controlador) {
		if(controlador=='planillaProntuarial') {
// 			location.href = 'http://turnospolicia.jujuy.gob.ar';
			location.href = '<?php echo base_url(); ?>/planillaProntuarial';
		}else if(controlador=='tramitarReba') {
			location.href = 'http://turnospolicia.jujuy.gob.ar/Auth/login';
		} else if (controlador == 'pagoContravencion') {//para el pago de contravenciones
        	location.href = '<?php echo base_url(); ?>/pagoContravencion';	
		}else {
    	   <?php if (empty($userInSession)) { ?>
    			location.href = '<?php echo base_url(); ?>/'+controlador;
    	   <?php }else { ?>
    	   		location.href = '<?php echo base_url(); ?>/'+controlador+'/nuevo/buscarTramitePersona';
    	   <?php } ?>
		}
	}
</script>