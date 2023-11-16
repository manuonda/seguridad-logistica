<div class="col-md-12">

  <div class="bs-docs-section" style="margin-top:70px">

    <div class="row">
      <div class="col-lg-6">
        <div class="bs-component">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">Mostrar Rendición</li>
          </ol>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-12">
        <div class="card border-primary mb-3">
          <div class="card-header">Informacion</div>
          <div class="card-body">

            <form>
              <fieldset>

                <div class="row">
                <div class="form-group col-md-3">
                 <label>Nro. Tramite</label><input class="form-control" type="text" 
                 value="<?php echo $encabezado['id_rendicion_encabezado'];?>" readonly>
                </div>
                <div class="form-group col-md-3">
                 <label>Dependencia</label>
                 <input class="form-control" type="text" value="<?php echo $dependencia['dependencia'];?>" readonly>
                </div>
                <div class="form-group col-md-3">
                 <label>Total</label>
                 <input class="form-control" type="text" value="<?php echo $encabezado['total'];?>" readonly>
                </div>
                
                

                </div>
              

              </fieldset>
            </form>
          </div>
        </div>


      </div>

    </div>

    <form id="form-tramite-tabla">
      <input id="idTramiteTmp" type="hidden" value="" />
      <div class="row" id="tramites">
        <div class="col-lg-12">
          <div class="card border-primary mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-3">
                  <div class="">
                   Resumen de Tramites
                  </div>

                </div>
                <div class="col-md-6"></div>

              </div>
            </div>
            <div class="card-body">

              <table class="dataTable table table-hover" id="table">
                <thead>
                  <tr>
                    <th width="60"  style="font-size:10px;">CANTIDAD</th>
                    <th width="410" style="font-size:10px;">DETALLE</th>
                    <th width="55"  style="font-size:10px;">IMPORTE</th>
				           
                  </tr>
                </thead>
                <tbody id="table_rendiciones_row">
             
                       <?php
                  $orden = 0; 
                  foreach ($resumenes as $resum) {
                   $orden++; 
               ?>
			           <tr>
			            <td style="font-size:10px;"><?php echo $resum['cantidad'];?></td>
			            <td style="font-size:10px;" align="lefth"><?php echo $resum['tipo_tramite'];?></td>
			            <td style="font-size:10px;" align="right"><?php echo $resum['importe'];?></td>
			            </tr>
                </tr>
			         <?php } ; ?>
          
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>

    </form>

    <form id="form-tramite-tabla">
      <input id="idTramiteTmp" type="hidden" value="" />
      <div class="row" id="tramites">
        <div class="col-lg-12">
          <div class="card border-primary mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-3">
                  <div class="">
                    Listado de Tramites 
                  </div>

                </div>
                <div class="col-md-6"></div>

              </div>
            </div>
            <div class="card-body">

              <table class="dataTable table table-hover" id="table">
                <thead>
                  <tr>
                    <th style="font-size:10px;border: 1px solid #000;" width="40">Orden</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="100">Nro. Tramite</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="228">Detalle</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="55">Importe</th>
                  </tr>
                </thead>
                <tbody id="table_rendiciones_row">
             
                       <?php
                  $orden = 0; 
                  foreach ($detalles as $detalle) {
                   $orden++; 
               ?>
			           <tr>
			           <td style="border:1px solid #000; font-size:10px;"><?php echo $orden;?> </td>
			           <td style="border:1px solid #000; font-size:10px;"><?php echo $detalle['id_tramite'];?></td>
			           <td style="border:1px solid #000; font-size:10px;"><?php echo  $detalle['tipo_tramite'];?></td>
			           <td align="right" style="border:1px solid #000; font-size:10px;"><?php echo "$".$detalle['importe'];?></td>
			           </tr>
			         <?php } ; ?>
          
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>

    </form>

    <div class="row">
      <div class="col-lg-3">
        <a href="<?php echo base_url() . '/daf'; ?>" class="btn btn-primary btn-primary" style="margin-bottom: 10px">Volver</a>
      </div>
    </div>
  </div>


  <?= $this->include('rendicion/modales/modal_rendicion.php')  ?>
  <!-- modules -->
  <?= $this->include('js/module_rendicion.php') ?>

  <?php echo view('templates/frontend-base/footer.php'); ?>
</div>
