<?= $this->extend('templates/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sucursal</h3>
            </div>
            <div class="card-body">
            <dl class="row">
                  <dt class="col-sm-4">Sucursal</dt>
                  <dd class="col-sm-8"><?= $sucursal['name']?></dd>
                  <dt class="col-sm-4">Sucursal External Id</dt>
                  <dd class="col-sm-8"><?= $sucursal['external_id']?></dd>
                  <dt class="col-sm-4">Direccion</dt>
                  <dd class="col-sm-8"><?= 'Numero:'.$sucursal['street_number'].', Calle : '.$sucursal['street_name'].
                                        ', Ciudad: '.$sucursal['city_name'].', Departamento : '.$sucursal['state_name']?></dd>
                  
                </dl>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Cajas</h3>
            <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
              <li class="nav-item">
                <a href="<?php echo base_url().'/sucursal/'.$sucursal['id'].'/caja/create';?>" class="nav-link active btnTest">CREAR</a>
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
                                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Acciones">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cajas as $caja) : ?>
                                    <tr>
                                        <td> <?php echo $caja['id'] ?></td>
                                        <td> <?php echo $caja['name']; ?></td>
                                        <td> <a href="<?php echo base_url().'/caja/edit/'.$caja['id'] ?>" 
                                              class="btn btn-warning btn-xs btn-flat">
                                              <i class="fas fa-edit"></i>
                                             </a>
                                            <a href="<?php echo base_url().'/caja/delete/'.$caja['id'] ?>"
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
<div class="row">
    <div class="form-group">
        <a class="btn btn-block btn-default" href="<?php echo base_url().'/sucursal'; ?>">
         Volver      
        </a>
    </div>
</div>
<?= $this->endSection('content'); ?>