
<div class="col-md-12" style="padding-top: 70px">
    <div class="card card-outline-secondary">
        <div class="card-body">
    		<div class="bs-component text-center">
                <h3>Trámites cobrados</h3><br/>
            </div>
            <?php if(!empty($listado)): ?>
            <div class="col-md-12 text-center"><br/>
            	<a target="_blank" href="<?php echo base_url(); ?>/dafVentanilla/cerrarCaja/" style="cursor:pointer" title="Cerrar caja" class="btn btn-primary">
			 		<span class="oi oi-print"></span>&nbsp;&nbsp;Cerrar caja
			 	</a>
            </div>
            <?php endif;?>
            <div id="no-more-tables" class="table-responsive">
                <table id="tabla" class="dataTable table display compact table-bordered table-striped table-condensed table-hover cf" width="100%">
                    <thead class="cf">
                    <tr>
                        <th>N° de Tramite</th>
                        <th>Fecha de cobro</th>
                        <th>Tipo y Nro Documento</th>
                        <th>Apellido y nombre</th>
                        <th>Tipo de tramite</th>
                        <th>Importe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($listado)): ?>
                        <?php foreach($listado as $index => $item): ?>
                            <tr>
                                <td><?php echo $item['id_tramite']; ?></td>
                                <td><?php echo date_format(date_create($item['fecha_pago']), 'd/m/Y H:i'); ?></td>
                                <td><?php echo $item['tipo_documento'].' '.$item['documento']; ?></td>
                                <td><?php echo $item['apellido'].' '.$item['nombre']; ?></td>
                                <td><?php echo $item['tipo_tramite'];?></td>
                                <td>
                                	<?php 
                                	if($item['id_tipo_tramite']==TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
                                	    if($item['urgente']==INT_UNO) {
                                	        echo ($item['precio']*2)+$item['importe_adicional'];
                                	    }else {
                                	        echo $item['precio']+$item['importe_adicional'];
                                	    }
                                	}else {
                                	    echo $item['precio'];
                                	}
                                	?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
<?php echo view('templates/frontend-base/footer.php'); ?>
