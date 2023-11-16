<style>
.box-shadow-hover:hover {
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    background: #d9edf7;
}

a {
    text-decoration: none;
    color: #203040;
}

a:hover {
    text-decoration: none;
    cursor: pointer;
}
</style>
<div class="container" style="padding-top: 70px">
    <div class="bs-docs-section">
        <br>
        <div class="col-md-12">
            <div class="card border-dark mb-3">
                <div class="card-header text-center">
                    <h2 class="mb-0"><b>¡Bienvenido!</b></h2>
                    <h4>Seleccione una opción</h4>
                </div>

                <div class="card-body text-center">
                    <div class="row">
                        <?php if(session()->get('isLoggedIn')): ?>
                        <?php if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_DEPARTAMENTO_CONTRAVENCION) { ?>

                        <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                            <a href="<?php echo base_url().'/pagoContravencion/registrarOrdenPago';?>">
                                <div class="card d-block h-100 box-shadow-hover pointer">
                                    <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                                        <img class="img-fluid w-xs-120p"
                                            src="<?php echo base_url('assets/img/comisariaRendicion.png'); ?>"
                                            alt="Imagen">
                                    </div>
                                    <div class="card-body p-4">
                                        <h3 class="h4"><strong>Generar Orden de Pago</strong></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xl-4 my-3">
                            <a href="<?php echo base_url().'/pagoContravencion/listarPagoContravencion';?>">
                                <div class="card d-block h-100 box-shadow-hover pointer">
                                    <div class="pt-3 h-75p align-items-center d-flex justify-content-center">
                                        <img class="img-fluid w-xs-120p"
                                            src="<?php echo base_url('assets/img/constanciaPorExtravio.png'); ?>"
                                            alt="Imagen">
                                    </div>
                                    <div class="card-body p-4">
                                        <h3 class="h4"><strong>Consultar Ordenes de Pago </strong></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<br>
<?php echo view('templates/frontend-base/footer.php'); ?>