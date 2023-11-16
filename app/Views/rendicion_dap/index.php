<div class="col-md-12">

  <div class="bs-docs-section" style="margin-top:70px">

    <div class="row">
      <div class="col-lg-6">
        <div class="bs-component">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">Gestión de Rendiciones</li>
          </ol>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-12">
        <div class="card border-primary mb-3">
          <div class="card-header">Formulario de Rendición</div>
          <div class="card-body">

            <form action="<?php echo base_url() . '/daf/buscar' ?>" method="POST" id="form-buscar">
              <fieldset>

                <div class="row">
                  <!-- <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Fecha desde</label>
                    <input type="date" id="fechaDesde" name="fechaDesde" class="form-control" value="<?php if (isset($filter['fechaDesde'])) echo $filter['fechaDesde']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Fecha hasta</label>
                    <input type="date" id="fechaHasta" name="fechaHasta" class="form-control" value="<?php if (isset($filter['fechaHasta'])) echo $filter['fechaHasta']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div> -->
                
                  <div class="form-group col-md-3">
                    <label for="exampleInputEmail1">Nro. Rendición:</label>
                    <input type="number" id="numero" name="numero" class="form-control" value="<?php if (isset($filter['numero'])) echo $filter['numero']; ?>" aria-describedby="emailHelp" placeholder="">
                  </div> 
                  <div class="form-group col-md-6">
                      <label>Dependencia:</label>
                      <div class="col-lg-9">
                        <select class="form-control dependencia" name="id_dependencia" data-toggle="tooltip">
                          <option value="">-- SELECCIONAR --</option>
                          <?php foreach ($dependencias as $item) : ?>
                            <option value="<?php echo $item['id_dependencia'] ?>" <?php if (isset($filter['id_dependencia']) && $filter['id_dependencia'] == $item['id_dependencia']) echo 'selected="selected"'; ?>><?php echo $item['dependencia'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="<?php echo base_url() . '/daf/limpiar' ?>" class="btn btn-secondary">Limpiar</a>
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
                    Listado de Rendiciones Realizadas
                  </div>

                </div>
                <div class="col-md-6"></div>

              </div>
            </div>
            <div class="card-body">

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <!-- <th scope="col">Fecha Rendición Desde</th> -->
                    <!-- <th scope="col">Fecha Alta</th> -->
                    <th scope="col">Fecha Rendición</th>
                    <th scope="col">$Total Rendición</th>
                    <th scope="col">Dependencia</th>
                    <th scope="col">Estado Rendicion</th>
                    <th scope="col"><div class="text-center">Acciones</div></th>
                  </tr>
                </thead>
                <tbody id="table_rendiciones_row">

                </tbody>
              </table>


              <div id='pagination_rendiciones'></div>

            </div>
          </div>
        </div>
      </div>

    </form>

    <div class="row">
      <div class="col-lg-3">
        <a href="<?php echo base_url() . '/'; ?>" class="btn btn-primary btn-primary" style="margin-bottom: 10px">Volver</a>
      </div>
    </div>
  </div>


  <?= $this->include('rendicion/modales/modal_rendicion.php')  ?>
  <!-- modules -->
  <?= $this->include('js/module_rendicion.php') ?>

  <?php echo view('templates/frontend-base/footer.php'); ?>
</div>
<script>
  var base_url = "<?php echo base_url(); ?>";
  $(document).ready(function() {
    $("#id_dependencia").select2();


    $('#pagination_rendiciones').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPaginationRendicion(url);
    });


    const urlRendicion = "<?php echo base_url(); ?>" + '/daf/paginationRendicion?page=0';
    loadPaginationRendicion(urlRendicion);
    /**
     * load pagination 
     **/
    function loadPaginationRendicion(url) {
      //$.blockUI({ message: '<h1><img src="<?php //echo base_url();
                                            ?>/assets/global/img/loading.gif" /> Cargando..</h1>' }); 
      console.log(url);
      $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          console.log("response : ", response);
          $('#pagination_rendiciones').html(response.paginationRendicion);
          $("#table_rendiciones_row").html(response.rendiciones);
          $.unblockUI();

        },
        error: function(error) {
          //$.unblockUI();
          alert('Se produjo un error en la carga de rendiciones: ', JSON.stringify(error));
        }
      });
    }

 
  });

  function aprobar(idEncabezado) {
      //$.blockUI({ message: '<h1>Aprobando Rendicion..</h1>' }); 
      $.blockUI({
                message: '<h2><img src="<?php echo base_url(); ?>/assets/img/loading.gif" /> Aprobando...</h2>'
            });
      const urlRendicion = "<?php echo base_url(); ?>" + '/daf/aprobarRendicion?idEncabezado='+idEncabezado;
      console.log(urlRendicion);
      $.ajax({
        url: urlRendicion,
        type: 'post',
        dataType: 'json',
        global: false, //,
        success: function(response) {
          console.log("response : ", response);
          if ( response.status === "OK") {
             $("#rendicion-aprobada-"+idEncabezado).show();
             $("#rendicion-desaprobada-"+idEncabezado).hide();
             $("#btn-rendicion-"+idEncabezado).hide();
          }
          
          $.unblockUI();

        },
        error: function(error) {
          $.unblockUI();
          alert('Se produjo un error en la carga de rendiciones: ', JSON.stringify(error));
        }
      });
    }
</script>