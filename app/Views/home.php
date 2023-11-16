<style>
.box-shadow-hover:hover {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  background: #d9edf7;
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
 <div class="bs-docs-section">

 <br>
  <div class="col-md-12">
    <div class="card border-dark mb-3">
      <div class="card-header text-center">
        <h2 class="mb-0"><b>¡Bienvenido!</b></h2>
        <h4>Seleccione una opción</h4>
      </div>

      <div class="card-body text-center">
        <div class="row">
      	  <?php if(session()->get('isLoggedIn')): ?>
      	  	  <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) { ?>
      	  	  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php echo base_url().'/dashboard';?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/gestionTramites.png'); ?>" alt="Imagen">
                        <!--<img class="img-fluid w-xs-120p" src="https://image.flaticon.com/icons/png/128/3523/3523349.png" alt="Imagen" style="max-height: 50px">-->
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Gestión de trámites</strong></h3>
                      </div>
                    </div>
                  </a>
              </div>
              <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php echo base_url().'/turno/configParametros';?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/configuracion.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Configuración de parámetros para turnos </strong></h3>
                      </div>
                    </div>
                  </a>
              </div>
              <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php echo base_url().'/turnoFeriado';?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/gestion-feriados.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Gestión de Feriados </strong></h3>
                      </div>
                    </div>
                  </a>
              </div>
              <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php echo base_url().'/tramiteReba';?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/descargarCertificado.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Registrar REBA</strong></h3>
                      </div>
                    </div>
                  </a>
              </div> 
              
              <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/turnoPlanillaProntuarial';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Atender turnos Planilla Prontuarial </strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>  
              <?php } ?>
              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_JEFE_UNIDAD_ADMINISTRATIVA) { ?>
              		 <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                          <a href="<?php echo base_url().'/dashboard';?>"> 
                            <div class="card d-block h-100 box-shadow-hover pointer">
                              <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                                <img class="img-fluid w-xs-120p" src="https://image.flaticon.com/icons/png/128/3523/3523349.png" alt="Imagen" style="max-height: 50px">
                              </div>
                              <div class="card-body p-4">
                                <h3 class="h4"><strong>Gestión de trámites</strong></h3>
                              </div>
                            </div>
                          </a>
                      </div>
              <?php } ?>
              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_JEFE_DAP) { ?>
              		 <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                          <a href="<?php echo base_url().'/dashboard';?>"> 
                            <div class="card d-block h-100 box-shadow-hover pointer">
                              <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                                <img class="img-fluid w-xs-120p" src="https://image.flaticon.com/icons/png/128/3523/3523349.png" alt="Imagen" style="max-height: 50px">
                              </div>
                              <div class="card-body p-4">
                                <h3 class="h4"><strong>Gestión de trámites</strong></h3>
                              </div>
                            </div>
                          </a>
                      </div>
              <?php } ?>

              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_ANTECEDENTE) { ?>
               <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                   <a href="<?php echo base_url().'/dap';?>"> 
                     <div class="card d-block h-100 box-shadow-hover pointer">
                       <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                         <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                       </div>
                       <div class="card-body p-4">
                         <h3 class="h4"><strong>Ver turnos</strong></h3>
                       </div>
                     </div>
                   </a>
               </div>
               <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                   <a href="<?php echo base_url().'/dap/buscarTramitePersona';?>"> 
                     <div class="card d-block h-100 box-shadow-hover pointer">
                       <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                         <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                       </div>
                       <div class="card-body p-4">
                         <h3 class="h4"><strong>Buscar trámites</strong></h3>
                       </div>
                     </div>
                   </a>
               </div>
               <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                   <a href="<?php echo base_url().'/dap/buscarContravencion';?>"> 
                     <div class="card d-block h-100 box-shadow-hover pointer">
                       <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                         <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/constanciaPorExtravio.png'); ?>" alt="Imagen">
                       </div>
                       <div class="card-body p-4">
                         <h3 class="h4"><strong>Consultar Contravenciones</strong></h3>
                       </div>
                     </div>
                   </a>
               </div>
           	  <?php } ?>

              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_COMISARIA_SECCIONAL) { ?>
        		  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/turnoDependencia';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Atender turnos </strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/buscarTramitePersona';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Buscar trámites</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <!-- 
                      <a href="<?php //echo base_url().'/tramite';?>">  --> 
                      <a href="<?php echo base_url().'/tramite/crear';?>">
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/realizar-tramite.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Crear trámite</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <!-- 
                      <a href="<?php //echo base_url().'/tramite';?>">  --> 
                      <a href="<?php echo base_url().'/rendicion';?>">
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                          <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>" alt="Imagen">
                          <!--
                            <img class="img-fluid w-xs-120p" src="https://image.flaticon.com/icons/png/128/3523/3523349.png" alt="Imagen" style="max-height: 50px"> -->
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Rendición</strong></strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>

              <?php } ?>
              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_DAF) { ?>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/dafVentanilla';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/certificadoSupervivencia.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Cobrar trámites</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/dafVentanilla/tramitesCobrados';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/constanciaDenuncia.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Ver tramites cobrados</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/daf';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Recibir rendiciones</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>                  
              <?php } ?>
              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_CIAC) { ?>
                    <!-- 
              		<div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php // echo base_url().'/ciacDenuncia';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="https://image.flaticon.com/icons/png/128/3523/3523349.png" alt="Imagen" style="max-height: 50px">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Listado de pedidos de constancias de denuncias</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>  -->
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                       <a href="<?php echo base_url().'/dap';?>"> 
                         <div class="card d-block h-100 box-shadow-hover pointer">
                           <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                             <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                           </div>
                           <div class="card-body p-4">
                             <h3 class="h4"><strong>Ver turnos de planillas</strong></h3>
                           </div>
                         </div>
                       </a>
                   </div>
                   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                       <a href="<?php echo base_url().'/dap/buscarTramitePersona';?>"> 
                         <div class="card d-block h-100 box-shadow-hover pointer">
                           <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                             <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                           </div>
                           <div class="card-body p-4">
                             <h3 class="h4"><strong>Buscar trámites</strong></h3>
                           </div>
                         </div>
                       </a>
                   </div>
              <?php } ?>

              <!-- rendicion -->
              <?php if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==DAP_RENDICION || session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA )) { ?>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/daf';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Rendiciones</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
              <?php } ?>

              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')== ROL_UAD_UNIDAD_REGIONAL) { ?>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/turnoDependencia';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Atender turnos </strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>  
                   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramiteReba/buscar';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Buscar trámites</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>	
              	   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramiteReba';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/descargarCertificado.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Registrar REBA</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div> 
              <?php } ?>

              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_UAD_REBA_CENTRAL) { ?>
              	   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/dashboard';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/gestionTramites.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Gestión de trámites</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>	
              	   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramiteReba';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/descargarCertificado.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Registrar REBA</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div> 
              <?php } ?>
              
              <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5) { ?>
              	<?php if(!empty(session()->get('id_rol')) && session()->get('id_dependencia')==ID_DEP_UAD_LA_QUIACA_UR5) { ?>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                  <a href="<?php echo base_url().'/dashboard';?>"> 
                    <div class="card d-block h-100 box-shadow-hover pointer">
                      <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                        <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/gestionTramites.png'); ?>" alt="Imagen">
                      </div>
                      <div class="card-body p-4">
                        <h3 class="h4"><strong>Gestión de trámites</strong></h3>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } ?>
                <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/turnoDependencia';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/turnos.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Atender turnos tramites de comisaria</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/buscarTramitePersona';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Buscar trámites de comisaria</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramite/crear';?>">
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/realizar-tramite.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Crear trámite de comisaria</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/rendicion';?>">
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                          <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Rendición trámite de comisaria</strong></strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>
				  <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramiteReba/buscar';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/buscar.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Buscar trámites de reba</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div>	
              	   <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                      <a href="<?php echo base_url().'/tramiteReba';?>"> 
                        <div class="card d-block h-100 box-shadow-hover pointer">
                          <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                            <img class="img-fluid w-xs-120p" src="<?php echo base_url('assets/img/descargarCertificado.png'); ?>" alt="Imagen">
                          </div>
                          <div class="card-body p-4">
                            <h3 class="h4"><strong>Registrar REBA</strong></h3>
                          </div>
                        </div>
                      </a>
                  </div> 	
              <?php } ?>
		  <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</div>
</div>
<br>
<?php echo view('templates/frontend-base/footer.php'); ?>