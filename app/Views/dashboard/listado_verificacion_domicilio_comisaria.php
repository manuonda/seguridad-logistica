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
    <h3 style="text-align:center;">Listado de tramites que requieren verificación de domicilio</h3>
    <a style="margin-bottom: 1rem;" href="<?php echo base_url() . '/dashboard' ?>" class="btn btn-secondary">volver</a>

    <form id="form-tramite-tabla">
      <input id="idTramiteTmp" type="hidden" value="" />
      <div class="row" id="tramites">
        <div class="col-lg-12">
          <div class="card border-primary mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-12">
                  <div style="display:flex; flex-direction: row; justify-content: flex-start; justify-content: space-between;">
                    Listado de trámites verificación domicilio
                    <button id= "excel" type="button" class="btn btn-outline-primary btn-sm" >Descargar Excel</button>
                    <!-- <a href="<?php echo base_url() . '/DownloadExcel/createExcel' ?>" target="_blank" class="btn btn-outline-primary btn-sm">Descargar Excel</a> -->
                  </div>

                </div>
                <!-- <div class="col-md-6"></div> -->

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
                    <th scope="col">Fecha Alta</th>
                    <th scope="col">Tipo Trámite</th>
                    <th scope="col">Cuil</th>
                    <th scope="col">Nombre y Apellido</th>
                    <th scope="col">Domicilio</th>
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
    </form>
  </div>

  <?php echo view('templates/frontend-base/footer.php'); ?>
</div>

<script>

//  document.addEventListener("DOMContentLoaded", init, false);
//  function init() {
// 		document.querySelector('#files').addEventListener('change', module_fileupload_multiple.handleFileSelect, false);
// 		selDiv = document.querySelector("#selectedFiles");
// 	}

  var base_url = "<?php echo base_url(); ?>";

  var global;
  

  document.addEventListener("DOMContentLoaded", function(){    
    $("#estadoPago").select2();
    $("#idTipoTramite").select2();
    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPagination(url);
    });

    const url = "<?php echo base_url(); ?>" + '/dashboard/pagination_tramites_verificacion_domicilio_comisaria?page=0';
    loadPagination(url);

    /**
     * load pagination 
     **/
    function loadPagination(url) {     
	  
      console.log(url);
      $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          $('#pagination').html(response.pagination);
          $("#table_tramites_row").html(response.tramites);
          global = response.aexcel;

        },
        error: function(error) {

          alert('Se produjo un error : ', JSON.stringify(error));
        }
      });
    }

    document.getElementById("excel").addEventListener("click", function(){
      //console.log(url);
      $.ajax({
        url: '/DownloadExcel/createExcel',
        type: 'post',
        data: {data : global,
               //encabezado : [{id_tramite:'Nro', tipo_tramite:'Tipo Tramite', fecha_alta:'Fecha de Alta', cuil:'CUIL', ayn:'Apellido y Nombre', domicilio:'Domicilio', verificador:'Verificador'}]
               encabezado : [{id_tramite:'Nro', tipo_tramite:'Tipo Tramite', fecha_alta:'Fecha de Alta', cuil:'CUIL', nya:'Titular y Convivientes', domicilio:'Domicilio', verificador:'Verificador'}]
              },
        dataType: 'json',
        success: function(data) {
          let fecha = new Date().toLocaleDateString('es-AR');
          var $a = $("<a>");
          $a.attr("href",data.file);
          $("body").append($a);
          $a.attr("download","tramite_verificacion_domicilio_"+fecha+".xlsx");
          $a[0].click();
          $a.remove();

        },
        error: function(error) {

          alert('Se produjo un error : ', JSON.stringify(error));
        }
      });
    });

    function descargar_excel() {

    }      
  });

  $(document).ready(function() { 

  });
</script>