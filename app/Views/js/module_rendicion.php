<script>
    var module_rendicion = (function() {

        function verificarRendicion() {
            // var fechaDesde = document.getElementById("fechaDesde").value;
            var fechaDesde = "";
            var fechaHasta = document.getElementById("fechaHasta").value;

            if (fechaHasta === "") {
                showAlert("Debe Completar la Fecha Hasta");
                return;
            }
            $.blockUI({
                message: '<h2><img src="<?php echo base_url(); ?>/assets/img/loading.gif" /> Verificando Rendición...</h2>'
            });
            $.ajax({
                url: '/rendicion/operation?fecha_desde=' + fechaDesde + '&fecha_hasta=' + fechaHasta,
                method: 'GET',
                contentType: 'application/json',
                global: false, //
                type: 'json',
                success: function(data) {
                    console.log(data.tramites);
                    $.unblockUI();
                    if (data.tramites === 0) {
                        showAlert("No existen tramites a Rendir en ese rango de Fecha");
                    } else {
                        $("#numero_tramites").empty("");
                        $("#numero_tramites").append(data.tramites);
                        $("#importe_total").append(data.total);
                        $("#modal_rendicion").show();
                    }

                },
                error: function(error) {
                    $.unblockUI();
                    alert("Se produjo un error , contacte al operador");
                }

            })

        }


     function realizarRendicion() {
            //var fechaDesde = document.getElementById("fechaDesde").value;
            fechaDesde ="";
            var fechaHasta = document.getElementById("fechaHasta").value;
            $("#modal_rendicion").hide();
            if (fechaHasta === "") {
                showAlert("Debe Completar la Fechas Hasta");
                return;
            }
            $.blockUI({
                message: '<h2><img src="<?php echo base_url(); ?>/assets/img/loading.gif" /> Realizando Rendición...</h2>'
            });
            $.ajax({
                url: '/rendicion/realizarrendicion?fecha_desde=' + fechaDesde + '&fecha_hasta=' + fechaHasta,
                method: 'GET',
                contentType: 'application/json',
                global: false, //
                type: 'json',
                success: function(data) {
                    $.unblockUI();
                    showAlert("Rendicion Realizada. Se descargará a continuación");
                    var url = '<?php echo base_url(); ?>' + "/rendicion/rendicionpdf?id_encabezado=" + data.id_operation;
                    window.open(url, '_blank');
                    url = "<?php echo base_url(); ?>" + '/rendicion/pagination?page=0';
                    loadPagination(url);
                    var urlRendicion = "<?php echo base_url(); ?>" + '/rendicion/paginationRendicion?page=0';
                    loadPaginationRendicion(urlRendicion);
                    
                    $('#cuadro_total').hide();

                    //window.location.reload();
                },
                error: function(error) {
                    $.unblockUI();
                    alert("Se produjo un error , contacte al operador");
                }

            })
        }

        function closeRendicion() {
            $("#modal_rendicion").hide();
        }

         /**
     * load pagination 
     **/
    function loadPagination(url) {
      //$.blockUI({ message: '<h1><img src="<?php //echo base_url();
                                            ?>/assets/global/img/loading.gif" /> Cargando..</h1>' }); 
      console.log(url);
      $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          $('#pagination').html(response.pagination);
          $("#table_tramites_row").html(response.tramites);
          $.unblockUI();

        },
        error: function(error) {
          //$.unblockUI();
          alert('Se produjo un error : ', JSON.stringify(error));
        }
      });
    }


      /**
     * load pagination 
     **/
    function loadPaginationRendicion(url) {
      //$.blockUI({ message: '<h1><img src="<?php //echo base_url();
                                            ?>/assets/global/img/loading.gif" /> Cargando..</h1>' }); 
      console.log(url);
      $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          console.log("response : ",response);
          $('#pagination_rendiciones').html(response.paginationRendicion);
          $("#table_rendiciones_row").html(response.rendiciones);
          $.unblockUI();

        },
        error: function(error) {
          //$.unblockUI();
          alert('Se produjo un error en la carga de rendiciones: ', JSON.stringify(error));
        }
      });
    }

        return {
            realizarRendicion: realizarRendicion,
            verificarRendicion: verificarRendicion,
            closeRendicion: closeRendicion
        }

    }());
</script>