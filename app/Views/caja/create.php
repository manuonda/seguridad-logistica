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
        <input name="id" type="text" value="<?php if (isset($caja)) echo $caja['id']; ?>" />
        sucursal id
        <input name="id_sucursal" id="id_sucursal" type="text" value="<?php if (isset($sucursal)) echo $sucursal['id']; ?>" />

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
            <div class="form-group">
              <label for=""> Nombre </label>
              <input name="name" id="name" type="text" class="form-control <?php if (isset($validation) && $validation->hasError('name')) echo 'is-invalid'; ?>" value="<?php if (isset($caja)) echo $caja['name']; ?>" />
              <?php if (isset($validation) && $validation->hasError('name')) { ?>
                <span class="error invalid-feedback">Ingrese Nombre</span>
              <?php } ?>
            </div>

            <div class="form-group">
              <label>Cantidad Fija</label>
              <input type="checkbox" class="form-control" name="fixed_amount" id="fixed_amount" onchange="checkInput(this)" 
              value="<?php if (isset($caja)  && isset($caja['fixed_amount'])) {  
                        echo $caja['fixed_amount']; 
                      } else  { echo 'false'; 
                      }?>" 
                      <?php if (isset($caja) && $caja['fixed_amount']) echo 'checked'; ?>>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <label>Categoria</label>
              <input type="text" readonly class="form-control" name="category" value="<?php echo $category ?>">
            </div>
          </div>



          <div class="row">
           
            <div class="form-group">
              <label>Caja Mercado Pago Id</label>
              <input name="id_sucursal_mercado_pago" class="form-control" readonly id="id_sucursal" type="text" 
              value="<?php if (isset($caja)) echo $caja['id_caja_mercado_pago']; ?>" />
            </div>
            <!-- sucursal extennal id -->
            <div class="form-group">
              <label>Sucursal External Store Id</label>
              <input name="external_store_id" id="id_sucursal" type="text" readonly class="form-control" 
               value="<?php if (isset($sucursal)) echo $sucursal['external_id']; ?>" />
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
</script>
<?= $this->endSection('script'); ?>