<?= $this->extend('templates/admin_template'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <?php if ($action == 'create') { ?>
            <i class="fas fa-plus"></i>
          <?php  } else if ($action == 'update') { ?>
            <i class="fas fa-edit"></i>
          <?php  } else if ($action == 'delete') { ?>
            <i class="fas fa-info"></i>
          <?php } ?>
          <?php echo $action_text; ?>

        </h3>
      </div>
      <!-- /.card-header -->

      <!-- Form -->
      <form role="form" method="POST">
        <input name="id_sucursal"  type="hidden" value="<?php if (isset($sucursal)) echo $sucursal['id']; ?>" />
        <input name="id_caja"      type="hidden" value="<?php if (isset($caja)) echo $caja['id']; ?>" />
        <input name="id_categoria" type="hidden" value="<?php if (isset($categoria)) echo $categoria['id']; ?>" 

        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <?php if ($error) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×
                  </button>
                  <h6><i class="icon fas fa-ban"></i> Ha ocurrido un error al guardar el registro</h6>
                  <ul id="list_message_error">
                    <li> <?php echo $msg; ?></li>
                  </ul>
                </div>
              <?php } ?>
            </div>
          </div>
          <div class="row">
            <!-- city_name -->
            <div class="form-group">
                <label>Sucursales</label>
                <select class="form-control" id="sucursal" name="id_sucursal"> 
                <option value="">-- Seleccionar Sucursal --</option>
                <?php 
                 if ($sucursales!=null){  
                     foreach ($sucursales as $sucursal): ?>                                                                        
                        <option value="<?php echo $sucursal['id'] ?>">
                            <?php echo $sucursal['name'] ?></option>
                     <?php endforeach; 
                 }
               ?>
              </select>
              </div>

              <div class="form-group">
                <label>Cajas</label>
                <select class="form-control" id="caja" name="id_caja"> 
                <option value="">-- Seleccionar Caja --</option>
             </select>
              </div> 

         
          </div>
          <div class="row">
            <div class="form-group">
              <label for=""> Titulo </label>
              <input name="title" id="title" type="text" 
              class="form-control <?php if (isset($validation) && $validation->hasError('title')) echo 'is-invalid'; ?>" 
              value="<?php if (isset($orden)) echo $orden['title']; ?>" />
              <?php if (isset($validation) && $validation->hasError('title')) { ?>
                <span class="error invalid-feedback">Ingrese Title</span>
              <?php } ?>
            </div>

            <div class="form-group">
              <label>Descripcion</label>
              <input type="text" class="form-control" name="description"  
              value="<?php if (isset($orden)) echo $orden['description']; ?>" />
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <label>Categoria</label>
              <input type="text" readonly class="form-control" name="category" 
              readonly 
              value="<?php if (isset($categoria)) echo $categoria['nombre']; ?>" />
            
             
            </div>

            <div class="form-group">
              <label>Total Monto</label>
              <input type="number"   name="total_amount"
              class="form-control <?php if (isset($validation) && $validation->hasError('total_amount')) echo 'is-invalid'; ?>" >
              <?php if (isset($validation) && $validation->hasError('total_amount')) { ?>
                <span class="error invalid-feedback">Ingrese Monto</span>
              <?php } ?>
            </div>
          </div>

          <div class="row">
          <div class="form-group">
                  <label>Fecha de Expiración:</label>
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input name="expiration_date" type="text" class="form-control datetimepicker-input" data-target="#reservationdate">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
        
          </div>

        </div>
        <!-- Card Footer -->
        <div class="card-footer">
          <a href="<?php echo base_url() . '/sucursal/' . $sucursal['id'] . '/cajas'; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left "></i>
            Volver
          </a>

          <?php if ($action ==  'create' ||  $action == 'update') { ?>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              Guardar
            </button>
          <?php } ?>

          <?php if ($action == 'delete') { ?>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-trash"></i>
              Eliminar
            </button>
          <?php } ?>

        </div>

      </form>
    </div>
  </div>
</div>

<?= $this->endSection('content'); ?>

<?= $this->section('script'); ?>
<script type="text/javascript">
  function checkInput(event) {
    console.log(event);
    console.log(event.checked);
    console.log(event.id);
    if (event.checked) {
      document.getElementById(event.id).value = true;
    } else {
      document.getElementById(event.id).value = false;
    }
  }

  $(function () {
   
    //Date range picker
    $('#reservationdate').datetimepicker({
      format: 'YYYY-MM-DD hh:mm',
	    formatTime: 'H:i'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })
    
    $("#sucursal").change(function(){
     console.log('id : ', $(this).val());
     let idSucursal = $(this).val();
     $("#caja").empty();
     $("#caja").append('<option>Seleccionar Caja</option>')
     $.ajax({
         method: 'GET',
         url: '<?php echo base_url().'/caja/get_cajas/'; ?>'+idSucursal,
         success: function(response) {
             if ( response && response.length > 0 ) {
                 var cajas = JSON.parse(response);
                 cajas.forEach((value ) => {
                  var option = '<option value="'+value.id+'">'+value.name+'</option>';
                  $("#caja").append(option);
                });
             }
          console.log(response);
         }, error : function(error){
          console.log(error);   
         }
     })
  });
   

  })
</script>
<?= $this->endSection('script'); ?>
