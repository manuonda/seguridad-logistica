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

                        <form action="<?php echo base_url() . '/pagoContravencion/listarPagoContravencion' ?>"
                            method="POST" id="form-buscar" enctype="multipart/form-data">
                            <fieldset>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Nro Trámite</label>
                                        <input type="number" id="idTramite" name="idTramite" class="form-control"
                                            value="<?php if (isset($filter['idTramite'])) echo $filter['idTramite']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Fecha desde:</label>
                                        <input type="date" id="fechaDesde" name="fechaDesde" class="form-control"
                                            value="<?php if (isset($filter['fechaDesde'])) echo $filter['fechaDesde']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Fecha hasta:</label>
                                        <input type="date" id="fechaHasta" name="fechaHasta" class="form-control"
                                            value="<?php if (isset($filter['fechaHasta'])) echo $filter['fechaHasta']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Nombre</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control mayuscula"
                                            value="<?php if (isset($filter['nombre'])) echo $filter['nombre']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Apellido</label>
                                        <input type="text" id="apellido" name="apellido" class="form-control mayuscula"
                                            value="<?php if (isset($filter['apellido'])) echo $filter['apellido']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Cuil</label>
                                        <input type="text" id="cuil" name="cuil" class="form-control"
                                            value="<?php if (isset($filter['cuil'])) echo $filter['cuil']; ?>"
                                            aria-describedby="emailHelp" placeholder="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Documento</label>
                                        <input type="text" id="documento" name="documento" class="form-control"
                                            value="<?php if (isset($filter['documento'])) echo $filter['documento']; ?>"
                                            aria-describedby="Documento" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Estado del Pago:</label>
                                        <select id="estadoPago" name="estadoPago" id="estadoPago" class="form-control"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">-- SELECCIONAR --</option>
                                            <?php foreach ($estadoPagos as $item) : ?>
                                            <option value="<?php echo $item ?>"
                                                <?php if (isset($filter['estadoPago']) && $filter['estadoPago'] == $item) echo 'selected="selected"'; ?>>
                                                <?php echo $item ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Estado del Trámite:</label>
                                        <select name="estadoTramite" id="estadoTramite" class="form-control"
                                            data-toggle="tooltip" data-placement="bottom">
                                            <option value="">-- SELECCIONAR --</option>
                                            <?php foreach ($estadoTramites as $item) : ?>
                                            <option value="<?php echo $item ?>"
                                                <?php if (isset($filter['estadoTramite']) && $filter['estadoTramite'] == $item) echo 'selected="selected"'; ?>>
                                                <?php echo $item ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                </div>

                                <div
                                    style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between; align-items: flex-start">
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                        <a href="<?php echo base_url() . '/pagoContravencion/limpiar' ?>" class="btn btn-secondary">Limpiar</a>
                                        <a href="<?php echo base_url().'/pagoContravencion/cargarOrdenPago';?>" class="btn btn-primary"><span class="oi oi-home"></span>Volver</a>
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
                                    <!-- <th scope="col">Dependencia</th> -->
                                    <!-- <th scope="col">Tipo Pago</th> -->
                                    <th scope="col">Referencia Pago</th>
                                    <th scope="col">N° Documento</th>
                                    <th scope="col">Nombre y Apellido</th>
                                    <th scope="col">Estado del Trámite</th>
                                    <th scope="col">Estado del Pago</th>
                                    <!-- <th scope="col">Acciones</th> -->
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
    </form>
 </div>



    <?php echo view('templates/frontend-base/footer.php'); ?>
</div>

<script>
    //document.addEventListener("DOMContentLoaded", init, false);

// function init() {
//   document.querySelector('#files').addEventListener('change', module_fileupload_multiple.handleFileSelect, false);
//   selDiv = document.querySelector("#selectedFiles");
// }

var base_url = "<?php echo base_url(); ?>";
//
$(document).ready(function() {

let fechaDesde = "<?php echo $filter['fechaDesde']; ?>";
let fechaHasta = "<?php echo $filter['fechaHasta']; ?>";

if (fechaDesde !== "") {
  document.getElementById('fechaDesde').value = new Date(fechaDesde).toISOString().slice(0, 10);
}

if (fechaHasta !== "") {
  document.getElementById('fechaHasta').value = new Date(fechaHasta).toISOString().slice(0, 10);
}

$("#estadoPago").select2();

$('#pagination').on('click', 'a', function(e) {
  e.preventDefault();
  const url = $(this).attr('href');
  loadPagination(url);
});

const url = "<?php echo base_url(); ?>" + '/pagoContravencion/pagination?page=0';
loadPagination(url);

function selectTipoTramite(ev){
  console.log(ev);
}

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