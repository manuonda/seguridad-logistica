<?= $this->extend('templates/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sucursal</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sucursales</label>
                            <select class="form-control" id="sucursal">
                                <option value="">-- Seleccionar Sucursal --</option>
                                <?php
                                if ($sucursales != null) {
                                    foreach ($sucursales as $sucursal) : ?>
                                        <option value="<?php echo $sucursal['id'] ?>">
                                            <?php echo $sucursal['name'] ?></option>
                                <?php endforeach;
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Cajas</label>
                            <select class="form-control" id="caja">
                                <option value="">-- Seleccionar Caja --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                     <img src="" id="img-caja"  class="img-fluid mb-2">
                    </div>
                </div>


                <div class="row">
                    <button type="button" id="btn-buscar" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </div>
        <!-- city_name -->

    </div>
</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Ordenes</h3>
                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <a href="<?php echo base_url() . '/ordenes/create'; ?>" class="nav-link active btnTest">CREAR</a>
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
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Nombre">Title</th>

                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Nombre">Sucursal</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Acciones">Caja</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Acciones">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ordenes as $orden) : ?>
                                        <tr>
                                            <td> <?php echo $orden['id'] ?></td>
                                            <td> <?php echo $orden['title']; ?></td>
                                            <td> <?php echo $orden['sucursal'] ?></td>
                                            <td> <?php echo $orden['caja']; ?></td>
                                            <!--                                             
                                            <td> <a href="<?php echo base_url() . '/ordenes/view/' . $orden['id'] ?>" class="btn btn-warning btn-xs btn-flat">
                                                    <i class="fas fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url() . '/ordenes/delete/' . $orden['id'] ?>" class="btn btn-danger btn-xs btn-flat">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td> -->
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
        <a class="btn btn-block btn-default" href="<?php echo base_url() . '/sucursal'; ?>">
            Volver
        </a>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('script'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#sucursal").change(function() {
            console.log('id : ', $(this).val());
            let idSucursal = $(this).val();
            $("#caja").empty();
            $("#caja").append('<option>Seleccionar Caja</option>')
            $.ajax({
                method: 'GET',
                url: '<?php echo base_url() . '/caja/get_cajas/'; ?>' + idSucursal,
                success: function(response) {
                    if (response && response.length > 0) {
                        var cajas = JSON.parse(response);
                        cajas.forEach((value) => {
                            var option = '<option value="' + value.id + '">' + value.name + '</option>';
                            $("#caja").append(option);
                        });
                    }
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            })
        });

        $("#caja").change(function() {
            console.log('id : ', $(this).val());
            let id = $(this).val();
            $.ajax({
                method: 'GET',
                url: '<?php echo base_url() . '/caja/get_id?idCaja?='; ?>' + id,
                success: function(response) {
                    if (response && response.length > 0) {
                        var caja = JSON.parse(response);
                        if (caja[0] && caja[0].qr_image !== null ) {
                            $("#img-caja").prop("src", caja[0].qr_image);
                        }
                    }
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            })
        });

        $("#btn-buscar").on('click', function(ev) {
            ev.preventDefault();
            var id_sucursal = $("#sucursal").val();
            var id_caja = $("#caja").val();
            console.log('id_caja : ' + id_caja);
            console.log('id_sucursal: ' + id_sucursal);
            $.ajax({
                method: 'GET',
                url: '<?php echo base_url() . '/ordenes/buscar'; ?>?idSucursal=' + id_sucursal + '&idCaja=' + id_caja,
                success: function(response) {
                    if (response && response.length > 0) {
                        var cajas = JSON.parse(response);
                        cajas.forEach((value) => {
                            var option = '<option value="' + value.id + '">' + value.name + '</option>';
                            $("#caja").append(option);
                        });
                    }
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            })
        });
    });
</script>

<?= $this->endSection('script'); ?>