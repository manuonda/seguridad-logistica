<?= $this->extend('templates/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Sucursales</h3>
            <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
              <li class="nav-item">
                <a href="<?php echo base_url().'/sucursal/create'?>" class="nav-link active btnTest">CREAR</a>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-6"></div>
                    <div class="col-sm-12 col-md-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Nombre">Nombre</th>
                                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Cajas">Cajas</th>
                                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Acciones">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sucursales as $sucursal) : ?>
                                    <tr>
                                        <td> <?php echo $sucursal['id'];  ?></td>
                                        <td> <?php echo $sucursal['name']; ?></td>
                                        <td> <a href="<?php echo base_url() .'/sucursal/'. $sucursal['id'] . '/cajas'; ?>"> Cajas</a>
                                        <td> <a href="<?php echo base_url().'/sucursal/edit/'.$sucursal['id'] ?>" 
                                              class="btn btn-warning btn-xs btn-flat">
                                              <i class="fas fa-edit"></i>
                                             </a>
                                            <a href="<?php echo base_url().'/sucursal/delete/'.$sucursal['id'] ?>"
                                                class="btn btn-danger btn-xs btn-flat">
                                            <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('script') ;?>
<script type="text/javascript">

 $(document).ready(function() {

 });
     
</script>

<?= $this->endSection('script') ;?>
