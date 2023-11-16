<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse" <?php if($this->ua->is_mobile()) { ?> style="background-color: #333;" <?php } ?>>
    <ul class="nav navbar-nav side-nav">
        <li class="active"><a href="<?php echo base_url(); ?>inicio"><i class="fa fa-fw fa-dashboard"></i> Inicio</a></li>
        <li><a href="<?php echo base_url(); ?>movil"><i class="fa fa-taxi"></i> Moviles</a></li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#cargas"><i class="fa fa-quote-left"></i> Cargas </a>
            <ul id="cargas" class="collapse">
                <li>
                    <a href="<?php echo base_url(); ?>carga">Movil Policial</a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>carga_especial">Carga Especiales</a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>provision_interior">Provisión al Interior</a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>carga_particular">Movil Particular</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#talonarios_vales"><i class="fa fa-fw fa-table"></i> Vales </a>
            <ul id="talonarios_vales" class="collapse">
                <li>
                    <a href="<?php echo base_url(); ?>talonario">Listar Talonarios</a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>provision_puesto1">Provisión al Puesto 1</a>
                </li>
            </ul>
        </li>
    </ul>
</div>