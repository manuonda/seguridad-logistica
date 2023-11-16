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

    <div class="mb-3">
      <ul class="nav nav-pills" id="myTab" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="home-tab" data-toggle="tab" href="#home2" role="tab" aria-controls="home" aria-selected="true">Trámites</a></li>
        <li class="nav-item"><a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile2" role="tab" aria-controls="profile" aria-selected="false">Rendiciones Realizadas</a></li>
      </ul>
    </div>
    <div class="tab-content mb-4">
      <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
        <?= $this->include('rendicion/tramites.php') ?>

      </div>
      <div class="tab-pane fade" id="profile2" role="tabpanel" aria-labelledby="profile-tab">
      <?= $this->include('rendicion/rendiciones.php') ?>
      </div>
  
    </div>

    <div class="row">
    <div class="col-lg-3">
       <a href="<?php echo base_url().'/'; ?>" class="btn btn-primary btn-primary" style="margin-bottom: 10px">Volver</a>
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
    $("#estadoPago").select2();
    $("#idTipoTramite").select2();
    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPagination(url);
    });

    $('#pagination_rendiciones').on('click', 'a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');
      loadPaginationRendicion(url);
    });

    const url = "<?php echo base_url(); ?>" + '/rendicion/pagination?page=0';
    loadPagination(url);
    const urlRendicion = "<?php echo base_url(); ?>" + '/rendicion/paginationRendicion?page=0';
    loadPaginationRendicion(urlRendicion);

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
          console.log("response : ",response);
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
</script>