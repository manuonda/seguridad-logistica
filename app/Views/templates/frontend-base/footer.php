
    <footer class="footer bg-primary">
        <div class="container-fluid row justify-content-center bg-primary">
            <a href="http://jujuy.gob.ar/home/"><img class="ui mini rounded image" src="<?php echo base_url('assets/img/logo.png'); ?>" style="width: 170px; height: 45px; padding-top: 5px;" alt="Logo" /></a>
            <a style="padding-top: 9px; padding-bottom: 5px;" href="http://seguridad.jujuy.gob.ar/">
                <h6 style="color:#fff">Ministerio de Seguridad</h6>
                <!-- <span style="color:#fff">Area Informática</span> -->
            </a>
        </div>
    </footer>

    <script src="<?php echo base_url() ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/moment.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/es.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/select2.full.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootbox.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootbox.locales.min.js"></script>
    <script src="<?php echo base_url() ?>/assetsback/js/jquery.blockUI.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <script src="<?php echo base_url() ?>/assetsback/js/popper.min.js" ></script>
    <script src="<?php echo base_url() ?>/assets/js/main.js?v=1"></script>
    <script src="<?php echo base_url() ?>/assetsback/js/download.js"></script>


    <!-- jquery datatable -->
    <script src="<?php echo base_url() ?>/assets/js/jquery.dataTables.min.js?v=1"></script>
    
     <script type="text/javascript">
      $(document).ready(function(){
        $('.dataTable').DataTable({
        	"language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "pageLength": 50,
            "order": [0, 'desc' ],
        });
      });
     </script>
</body>