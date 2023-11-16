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
          <div class="card-header">Formulario de Busqueda</div>
          <div class="card-body">

            <form action="<?php echo base_url() . '/dashboard/buscar' ?>" method="POST">
              <fieldset>
                <div class="row">
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Nro Tramite</label>
                    <input type="number" name="idTramite" class="form-control" value="<?php if (isset($filter['idTramite'])) echo $filter['idTramite']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="tipo_pago">Tipo Pago</label>
                    <select name="idTipoPago" id="idTipoPago" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($tipoPagos as $item) : ?>
                        <option value="<?php echo $item['id_tipo_pago'] ?>" <?php if (isset($filter['idTipoPago']) && $filter['idTipoPago'] == $item['id_tipo_pago']) echo 'selected="selected"'; ?>><?php echo $item['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="tipo_pago">Tipo Tramite</label>
                    <select name="idTipoTramite" id="idTipoTramite" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($tipoTramites as $item) : ?>
                        <option value="<?php echo $item['id_tipo_tramite'] ?>" <?php if (isset($filter['idTipoTramite']) && $filter['idTipoTramite'] == $item['id_tipo_tramite']) echo 'selected="selected"'; ?>><?php echo $item['tipo_tramite'] ?></option>
                        <?php $item['id_tipo_tramite']; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Fecha desde:</label>
                    <input type="date" name="fechaDesde" class="form-control" value="<?php if (isset($filter['fechaDesde'])) echo $filter['fechaDesde']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Fecha hasta:</label>
                    <input type="date" name="fechaHasta" class="form-control" value="<?php if (isset($filter['fechaHasta'])) echo $filter['fechaHasta']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>


                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Dependencia:</label>
                    <select name="idDependencia" id="id_dependencia" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($dependencias as $item) : ?>
                        <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($filter['idDependencia']) && $filter['idDependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                </div>
                <div class="row">
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php if (isset($filter['nombre'])) echo $filter['nombre']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="<?php if (isset($filter['apellido'])) echo $filter['apellido']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Cuil</label>
                    <input type="text" name="cuil" class="form-control" value="<?php if (isset($filter['cuil'])) echo $filter['cuil']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Estado del Pago:</label>
                    <select name="estadoPago" id="estadoPago" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($estadoPagos as $item) : ?>
                        <option value="<?php echo $item ?>" <?php if (isset($filter['estadoPago']) && $filter['estadoPago'] == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Estado del Trámite:</label>
                    <select name="estadoTramite" id="estadoTramite" class="form-control" data-toggle="tooltip" data-placement="bottom">
                      <option value="">-- SELECCIONAR --</option>
                      <?php foreach ($estadoTramites as $item) : ?>
                        <option value="<?php echo $item ?>" <?php if (isset($filter['estadoTramite']) && $filter['estadoTramite'] == $item) echo 'selected="selected"'; ?>><?php echo $item ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                </div>

                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="<?php echo base_url() . '/dashboard/limpiar' ?>" class="btn btn-secondary">Limpiar</a>

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
                    Listado de tramites
                  </div>

                </div>
                <div class="col-md-6"></div>

                <div class="col-md-3 text-align-center">
                  <div style="align:right">
                    <a href="#" class="btn btn-primary" onclick="mostrarTipoTramite()">Agregar</a>
                  </div>
                </div>

              </div>
            </div>
            <div class="card-body">

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Nro.</th>
                    <th scope="col">Tipo Tramite</th>
                    <th scope="col">Fecha Alta</th>
                    <th scope="col">Tipo Pago</th>
                    <th scope="col">Referencia Pago</th>
                    <th scope="col">Cuil</th>
                    <th scope="col">Nombre y Apellido</th>
                    <th scope="col">Estado del Trámite</th>
                    <th scope="col">Estado del Pago</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody id="table_tramites_row">

                </tbody>
              </table>


              <div id='pagination'></div>

            </div>
          </div>
        </div>
      </div>
      <?= $this->include('dashboard/modales/modal_estado_pago.php') ?>
      <?= $this->include('dashboard/modales/modal_tipo_tramite.php') ?>
      <?= $this->include('dashboard/modales/modal_firmadigital.php') ?>
    </form>
  </div>


  <!-- modules -->
  <?= $this->include('js/module_pago.php') ?>
  <?= $this->include('js/module_util.php') ?>
  <?php echo view('templates/frontend-base/footer.php'); ?>
</div>

<script>
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


  $(document).ready(function() {
    $("#estadoPago").select2();
    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPagination(url);
    });

    const url = "<?php echo base_url(); ?>" + '/dashboard/pagination?page=0';
    loadPagination(url);

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
          $("#table_tramites_row").html(response.tramites);
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