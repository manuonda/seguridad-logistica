<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary mb-3">
            <div class="card-header">Formulario de Rendición</div>
            <div class="card-body">

                <form action="<?php echo base_url() . '/rendicion/buscar' ?>" method="POST" id="form-buscar" enctype="multipart/form-data">
                    <fieldset>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="row">
                                    <!-- <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Fecha desde:</label>
                                        <input type="date" id="fechaDesde" name="fechaDesde" class="form-control" value="<?php if (isset($filter['fechaDesde'])) echo $filter['fechaDesde']; ?>" aria-describedby="emailHelp" placeholder="">
                                    </div> -->

                                   <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Fecha hasta * :</label>
                                        <input type="date" id="fechaHasta" name="fechaHasta" class="form-control" value="<?php if (isset($filter['fechaHasta'])) echo $filter['fechaHasta']; ?>" aria-describedby="emailHelp" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                        <a href="<?php echo base_url() . '/rendicion/limpiar' ?>" class="btn btn-secondary">Limpiar</a>
                                    </div>
                                    <div class="col-md-6" style="horizontal-align: right;">
                                        <button type="button" class="btn btn-primary" onclick="module_rendicion.verificarRendicion()">$ Realizar Rendición</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-6">
                                    <div class="alert alert-success" role="alert">
                                        <h4 class="alert-heading">Información</h4>
                                        <p>Para realizar la <strong>Rendición</strong> debe establecer la <strong>Fecha Hasta.</strong></p>
                                    </div>
                                </div>
                            </div>


                        </div>
            </div>

        </div>
        </fieldset>
        </form>
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

                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Nro.</th>
                                <th scope="col">Tipo Trámite</th>
                                <!-- <th scope="col">Fecha Alta</th> -->
                                <th scope="col">Fecha Pago</th>
                                <th scope="col">Tipo Pago</th>
                                <th scope="col">Referencia Pago</th>
                                <th scope="col">Cuil</th>
                                <th scope="col">Nombre y Apellido</th>
                                <th scope="col">Estado del Trámite</th>
                                <th scope="col">Estado del Pago</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Rendido</th>
                            </tr>
                        </thead>
                        <tbody id="table_tramites_row">

                        </tbody>
                    </table>

                    <?php if($totalImporte!=0) {
                    ?>
                    <div class="alert alert-success" id="cuadro_total" style="float: right; margin-right:100px;">
                        <h8 class="alert-heading"><strong>TOTAL: </strong> $</h8>      
                        <span id="total"><?php echo $totalImporte; ?></span> 
                    </div> 
                    <?php }
                    ?>
                    
                    <div id='pagination'></div>
                    
                </div>
            </div>
        </div>
    </div>

</form>

<!-- <script>
    $(document).ready(function() {
        document.getElementById("fechaHasta").valueAsDate = new Date();
       
    });
</script> -->
<!-- 
<?php
$var = date('d/m/Y');
?>

<script>
    $(document).ready(function() {
        <?php
       echo "var jsvar ='$var';";
   ?>
   document.getElementById("fechaHasta").valueAsDate = jsvar;
    });
</script> -->

