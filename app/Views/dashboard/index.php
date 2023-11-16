<div class="col-md-12">

  <div class="bs-docs-section" style="margin-top:13px">
    <div class="row">
      <div class="col-lg-6">
        <div class="bs-component">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">Gestión de tramites</li>
          </ol>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card border-primary mb-3">
          <div class="card-header">Formulario de Búsqueda</div>
          <div class="card-body">

            <form action="<?php echo base_url() . '/dashboard/buscar' ?>" method="POST" id="form-buscar" enctype="multipart/form-data">
              <fieldset>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Nro Trámite</label>
                    <input type="number" id="idTramite" name="idTramite" class="form-control" value="<?php if (isset($filter['idTramite'])) echo $filter['idTramite']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="tipo_pago">Tipo Pago</label>
                    <select name="idTipoPago" id="idTipoPago" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($tipoPagos as $item) : ?>
                        <option value="<?php echo $item['id_tipo_pago'] ?>" <?php if (isset($filter['idTipoPago']) && $filter['idTipoPago'] == $item['id_tipo_pago']) echo 'selected="selected"'; ?>><?php echo $item['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="tipo_pago">Tipo Trámite</label>
                    <select onclick="selectTipoTramite()" class="form-control select2" name="idTipoTramite[]" id="idTipoTramite" <?php if ($rol == ROL_JEFE_DAP || $rol == ROL_UAD_REBA_CENTRAL) echo "disabled"; ?> class="form-control" data-toggle="tooltip" data-placement="bottom" multiple="multiple">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($tipoTramites as $item) : ?>
                        <?php if ($item['id_tipo_tramite'] != TIPO_TRAMITE_TRAMITAR_REBA) : ?>
                          <option value="<?php echo $item['id_tipo_tramite'] ?>" <?php if (isset($filter['idTipoTramite']) && in_array($item['id_tipo_tramite'], $filter['idTipoTramite'])) echo 'selected="selected"'; ?>><?php echo $item['tipo_tramite'] ?></option>
                          <?php $item['id_tipo_tramite']; ?>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fecha desde:</label>
                    <input type="date" id="fechaDesde" name="fechaDesde" class="form-control" value="<?php if (isset($filter['fechaDesde'])) echo date($filter['fechaDesde']); ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fecha hasta:</label>
                    <input type="date" id="fechaHasta" name="fechaHasta" class="form-control" value="<?php if (isset($filter['fechaHasta'])) echo date($filter['fechaHasta']); ?>" aria-describedby="emailHelp" placeholder="">
                  </div>


                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Dependencia:</label>
                    <select name="idDependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom" 
                    <?php if ($rol == ROL_UAD_UNIDAD_REGIONAL_UR5) echo "disabled"; ?>
                    >
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($dependencias as $item) : ?>
                        <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($filter['idDependencia']) && $filter['idDependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                </div>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control mayuscula" value="<?php if (isset($filter['nombre'])) echo $filter['nombre']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control mayuscula" value="<?php if (isset($filter['apellido'])) echo $filter['apellido']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Cuil</label>
                    <input type="text" id="cuil" name="cuil" class="form-control" value="<?php if (isset($filter['cuil'])) echo $filter['cuil']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                </div>

                <div class="row">

                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Documento</label>
                    <input type="text" id="documento" name="documento" class="form-control" value="<?php if (isset($filter['documento'])) echo $filter['documento']; ?>" aria-describedby="Documento" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Estado del Pago:</label>
                    <select id="estadoPago" name="estadoPago" id="estadoPago" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($estadoPagos as $item) : ?>
                        <option value="<?php echo $item ?>" <?php if (isset($filter['estadoPago']) && $filter['estadoPago'] == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Estado del Trámite:</label>
                    <select name="estadoTramite" id="estadoTramite" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($estadoTramites as $item) : ?>
                        <option value="<?php echo $item ?>" <?php if (isset($filter['estadoTramite']) && $filter['estadoTramite'] == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>


                  <br>

                </div>
                <div class="row">
                <div class="form-group col-md-4">
                </div>
                <div class="form-group col-md-4">
                </div>
                <div class="form-group col-md-4" id="div-estado-verificacion">
                    <label for="exampleInputEmail1">Estado de Verificaciòn de Antecedente:</label>
                        <select name="estado_verificacion" id="estado_verificacion" class="form-control" data-toggle="tooltip" data-placement="bottom">
                          <option value="">-- SELECCIONAR --</option>
                          <option value="<?php echo TRAMITE_PENDIENTE_VERIFICACION; ?>" <?php if (isset($filter['estado_verificacion']) && $filter['estado_verificacion'] == TRAMITE_PENDIENTE_VERIFICACION) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_PENDIENTE_VERIFICACION . '  '; ?></option>
                          <option value="<?php echo TRAMITE_VERIFICADO; ?>" <?php if (isset($filter['estado_verificacion']) && $filter['estado_verificacion'] == TRAMITE_VERIFICADO) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO . '  '; ?></option>
                          <option value="<?php echo TRAMITE_VERIFICADO_CON_OBSERVACION; ?>" <?php if (isset($filter['estado_verificacion']) && $filter['estado_verificacion'] == TRAMITE_VERIFICADO_CON_OBSERVACION) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO_CON_OBSERVACION . '  '; ?></option>
                          <option value="<?php echo TRAMITE_VERIFICADO_CON_INFORME; ?>" <?php if (isset($filter['estado_verificacion']) && $filter['estado_verificacion'] == TRAMITE_VERIFICADO_CON_INFORME) echo 'selected="selected"'; ?>><?php echo '  ' . TRAMITE_VERIFICADO_CON_INFORME . '  '; ?></option>
                        </select>
                      </div>
                    </div>
               
                <!-- <div>
                    <button type="submit" class="btn btn-primary btn-lg mr-1">Buscar</button>
                    <a href="<?php //echo base_url() . '/dashboard/limpiar' 
                              ?>" class="btn btn-secondary btn-lg">Limpiar</a>
                    <button type="button" class="btn btn-primary btn-lg" onClick="module_fileupload_multiple.mostrarModalFirmaDigitalMultiple();" style="float: right;">Subir Archivos Digitales</button>
                    <button type="button" class="btn btn-primary mr-1 btn-lg" onClick="descargar();" style="float: right;">Descargar Archivos Digitales</button>                    
                </div> -->

                <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start">
                  <div class="">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="<?php echo base_url() . '/dashboard/limpiar' ?>" class="btn btn-secondary">Limpiar</a>
                  </div>
                  <?php if ($rol != ROL_UAD_REBA_CENTRAL && $rol != ROL_UAD_UNIDAD_REGIONAL_UR5) { ?>
                    <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start; gap: 10px;">
                      <!-- <button type="button" class="btn btn-primary" onClick="descargar();">Descargar Archivos Digitales</button>
                        <button type="button" class="btn btn-primary" onClick="module_fileupload_multiple.mostrarModalFirmaDigitalMultiple();">Subir Archivos Digitales</button> -->
                      <a class="btn btn-primary" href="<?php echo base_url() . '/planillaProntuarial/nuevaPlanilla/dashboard'; ?>"><span class="oi oi-new-document"></span> Crear Planilla Prontuarial</a>

                      <a href="<?php echo base_url() . '/dashboard/listado_verificacion_domicilio' ?>" class="btn btn-primary">Listado tramites verificacion domicilio</a>

                    </div>
                  <?php } ?>
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
                    Listado de trámites
                  </div>

                </div>
                <div class="col-md-6"></div>

                <!-- <div class="col-md-3 text-align-center">
                  <div style="align:right">
                    <a href="#" class="btn btn-primary" onclick="mostrarTipoTramite()">Agregar</a>
                  </div>
                </div> -->

              </div>
            </div>
            <div class="card-body">

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Nro.</th>
                    <th scope="col">Tipo Trámite</th>
                    <th scope="col">Fecha Alta</th>
                    <th scope="col">Dependencia</th>
                    <th scope="col">Tipo Pago</th>
                    <th scope="col">Referencia Pago</th>
                    <th scope="col">N° Documento</th>
                    <th scope="col">Nombre y Apellido</th>
                    <th scope="col">Estado del Trámite</th>
                    <th scope="col">Estado del Pago</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody id="table_tramites_row">

                </tbody>
              </table>

              <div class="row">
                <div class="col-md-6">
                  <div id='pagination'></div>
                </div>
                <div class="col-md-6" id="cantidad">

                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
      <?= $this->include('dashboard/modales/modal_estado_pago.php')  ?>
      <?= $this->include('dashboard/modales/modal_tipo_tramite.php') ?>
      <?= $this->include('dashboard/modales/modal_firmadigital.php') ?>
      <?= $this->include('dashboard/modales/modal_firmadigital_multiple.php') ?>
      <?= $this->include('dashboard/modales/modal_pago_reba.php') ?>
    </form>
  </div>


  <!-- modules -->
  <?= $this->include('js/module_pago.php') ?>
  <?= $this->include('js/module_util.php') ?>
  <?= $this->include('js/module_fileupload_multiple.php') ?>


  <?= $this->include('dashboard/modales/modal_pago_planilla_prontuarial.php')  ?>
  <?php echo view('templates/frontend-base/footer.php'); ?>
</div>

<script>
  document.addEventListener("DOMContentLoaded", init, false);

  function init() {
    document.querySelector('#files').addEventListener('change', module_fileupload_multiple.handleFileSelect, false);
    selDiv = document.querySelector("#selectedFiles");
  }

  var base_url = "<?php echo base_url(); ?>";

  async function mostrarPago(idTramite) {
    console.log('idTramite : ', idTramite);
    // $("#tramites").block({
    //   message: 'Cargando...'
    // });


  }

  function mostrarTipoTramite() {
    $("#modal-tipo-tramite").show();
  }

  function descargar() {

    let url = base_url + "/dashboard/descargarcertificados?";
    let idTramite = document.getElementById("idTramite").value;
    let idTipoPago = document.getElementById("idTipoPago").value;
    let idTipoTramite = document.getElementById("idTipoTramite").value;
    let fechaDesde = document.getElementById("fechaDesde").value;
    let fechaHasta = document.getElementById("fechaHasta").value;
    let idDependencia = document.getElementById("id_dependencia").value;
    let nombre = document.getElementById("nombre").value;
    let apellido = document.getElementById("apellido").value;
    let idEstadoPago = document.getElementById("estadoPago").value;
    let estadoTramite = document.getElementById("estadoTramite").value;

    url = url + "idTramite=" + idTramite + "&idTipoPago=" + idTipoPago +
      "&idTipoTramite=" + idTipoTramite + "&fechaDesde=" + fechaDesde +
      "&fechaHasta=" + fechaHasta + "&idDependencia=" + idDependencia +
      "&nombre=" + nombre + "&apellido=" + apellido + "&estadoPago=" + idEstadoPago +
      "&estadoTramite=" + estadoTramite;

    window.open(
      url,
      '_blank' // <- This is what makes it open in a new window.
    );
    //window.location.href = url + "target='_blank' ";

  }

  $(document).ready(function() {

    let idTipoPlanillaProntuarial  ="<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL; ?>";
    let fechaDesde = "<?php echo $filter['fechaDesde']; ?>";
    let fechaHasta = "<?php echo $filter['fechaHasta']; ?>";
    let idsTiposTramites = document.getElementById("idTipoTramite").value;
    console.log('idsTipoTramites : ', idsTiposTramites);

    if (fechaDesde !== "") {
      document.getElementById('fechaDesde').value = new Date(fechaDesde).toISOString().slice(0, 10);
    }

    if (fechaHasta !== "") {
      document.getElementById('fechaHasta').value = new Date(fechaHasta).toISOString().slice(0, 10);
    }


    if ( idsTiposTramites !== "" && idsTiposTramites.length > 0 && idsTiposTramites.includes(idTipoPlanillaProntuarial)) {
     $("#div-estado-verificacion").show();
    } else {
      $("#div-estado-verificacion").hide();
    }
    $("#estadoPago").select2();
    $("#idTipoTramite").select2();
    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPagination(url);
    });

    const url = "<?php echo base_url(); ?>" + '/dashboard/pagination?page=0';
    loadPagination(url);

    function selectTipoTramite(ev){
      console.log(ev);
    }

    $("#idTipoTramite").on('change',function(){
      var ids = $(this).val();
      if ( ids && ids.length > 0 && ids.includes(idTipoPlanillaProntuarial)){
         $("#div-estado-verificacion").show();
      } else {
        $("#div-estado-verificacion").hide();
        $("#estado_verificacion").val("");  
      }
    });

    /**
     * load pagination 
     **/
    function loadPagination(url) {
      //$.blockUI({ message: '<h1><img src="<?php //echo base_url();
                                            ?>/assets/global/img/loading.gif" /> Cargando..</h1>' }); 
      console.log(url);
      $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          $('#pagination').html(response.pagination);
          var cantidadRegistros = "<strong>Cantidad de Registros : </strong>" + response.cantidad;
          $("#table_tramites_row").html(response.tramites);
          $("#cantidad").html(cantidadRegistros);
          $.unblockUI();

        },
        error: function(error) {
          //$.unblockUI();
          alert('Se produjo un error : ', JSON.stringify(error));
        }
      });
    }
  });
</script>