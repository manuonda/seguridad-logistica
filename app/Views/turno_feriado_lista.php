
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3><b>Listado de feriados</b></h3>
            </div>
            <br>
            <div class="col-md-12 text-center"><a href="<?php echo base_url(); ?>/turnoFeriado/agregar" class="btn btn-primary" id="linkAgregar"><span class="oi oi-plus"></span> Agregar nuevo feriado</a></div>
            <br/>
            <div id="no-more-tables" class="table-responsive">
                <table id="tableCntJerarquia" class="table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($listado as $index => $item): ?>
                        <tr>
                            <td data-title="#"><?php echo $index+1; ?></td>
                            <td data-title="Fecha"><?php echo date_format(date_create($item['fecha']), 'd/m/Y');; ?></td>
                            <td data-title="Descripción"><?php echo $item['descripcion']; ?></td>
                            <td data-title="Acción">
                            	<a href="<?php echo base_url(); ?>/turnoFeriado/modificar/<?php echo $item['id_turno_feriado']; ?>" class="btn btn-primary" id="linkModificar"><span class="oi oi-pencil"></span> </a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
 
