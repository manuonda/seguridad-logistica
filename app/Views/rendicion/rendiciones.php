<!-- 
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary mb-3">
            <div class="card-header">Formulario de Rendición</div>
            <div class="card-body">

                <form action="<?php echo base_url() . '/rendicion/buscar' ?>" method="POST" id="form-buscar" enctype="multipart/form-data">
                    <fieldset>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Fecha desde:</label>
                                <input type="date" id="fechaDesde" name="fechaDesde" class="form-control" value="<?php if (isset($filter['fechaDesde'])) echo $filter['fechaDesde']; ?>" aria-describedby="emailHelp" placeholder="">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Fecha hasta *:</label>
                                <input type="date" id="fechaHasta" name="fechaHasta" class="form-control" value="<?php if (isset($filter['fechaHasta'])) echo $filter['fechaHasta']; ?>" aria-describedby="emailHelp" placeholder="">
                            </div>

                        </div>
                        <div class="row">
                         <div class="col-md-6">
                           <button type="submit" class="btn btn-primary">Buscar</button>
                           <a href="<?php echo base_url() . '/rendicion/limpiar' ?>" class="btn btn-secondary">Limpiar</a>
                          </div>
                         
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>


    </div>

</div> -->

<form id="form-tramite-tabla">
    <input id="idTramiteTmp" type="hidden" value="" />
    <div class="row" id="tramites">
        <div class="col-lg-12">
            <div class="card border-primary mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="">
                                Listado de Rendiciones Realizadas
                            </div>

                        </div>
                        <div class="col-md-6"></div>

                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <!-- <th scope="col">Fecha Rendición Desde</th> -->
                                <!-- <th scope="col">Fecha Alta</th> -->
                                <th scope="col">Fecha Rendición</th>
                                <th scope="col">$Total Rendición</th>
                                <!-- <th scope="col">Usuario Rendicion</th> -->
                                <!-- <th scope="col">Fecha Alta</th> -->
                                <th scope="col">Acción</th>
                               </tr>
                        </thead>
                        <tbody id="table_rendiciones_row">

                        </tbody>
                    </table>


                    <div id='pagination_rendiciones'></div>

                </div>
            </div>
        </div>
    </div>

</form>