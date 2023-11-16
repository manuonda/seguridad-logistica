<script>

  var module_pago = (function() {
    var _idTramite = "";
    var _precio = "";
    var isNumber = function (evt){
       var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
    }

    var changeToUpper = function(event) {
      let valor  = event.value.toUpperCase();
      event.value = valor;
    }

    var mostrarPago = function(idTramite){
      _idTramite =  idTramite;
      //$.blockUI({ message: '<h1><img src="<?php //echo base_url();?>/assets/global/img/loading.gif" /> Cargando..</h1>' }); 

      // limpio los datos 
      clearInputEstado();
      
      $.blockUI({
        message: '<h4>Sincronizando datos con el Banco Macro, para ver el estado del Tramite</h4>'
      });
      $.ajax({
        url: '/dashboard/get_pago_tramite/' + idTramite,
        method: 'GET',
        contentType: 'application/json',
        type: 'json',
        global: false, // hace que no se muestre el loading 'cargando...'
        success: function(response) {
          
          if( response !== "") {
            const data = JSON.parse(response);
          const tramite =  data.tramite;
          const tipoTramite =  data.tipo_tramite;
          
        


          $("#nro_tramite_sincronizacion").append(tramite.id_tramite)
          $("#estado_pago_sincronizacion").append(data.estado_pago);
          $("#fecha_sincronizacion").append(data.fecha);
          $("#fecha_pago_sincronizacion").append(tramite.fecha_pago);
          $("#tipo_tramite_sincronizacion").append(tipoTramite.tipo_tramite);
           
          

          $("#precio_sincronizacion").append(data.importe);
          let estado = "";
           if ( data.estado_pago === "PAGADO") {
            estado ="<span class='badge badge-primary'><h8>PAGADO</h8></span>";   
          } else if ( data.estado_pago === "PENDIENTE") {
            estado = "<span class='badge badge-secondary'><h8>PENDIENTE</h8></span>";
          } else if ( data.estado_pago === "IMPAGO") {
            estado = "<span class='badge badge-danger'><h8>IMPAGO</h8></span>";
          }

          if (data.status === 'ERROR') {
            $("#precio_sincronizacion").append(tipoTramite.importe);
            $("#messageErrorPago_sincronizacion").append(data.message);     
            $("#mostarMessageError_sincronizacion").show();
            $("#modal-sincronizacion-pago").show();
            $("#col-estado-pago-"+idTramite).empty();
            $("#col-estado-pago-"+idTramite).append(estado);

          } else if(data.status === 'OK') {
             $("#mostrarMessageCorrecto_sincronizacion").show();
             $("#modal-sincronizacion-pago").show();
             $("#col-estado-pago-"+idTramite).empty();
             $("#col-estado-pago-"+idTramite).append(estado);
	        		    
          }

          //$.unblockUI(); 
          $("#tramites").unblock();
          }
          $.unblockUI();
      }, error: function(error) {
        $("#tramites").unblock();
        $.unblockUI();
        alert("Se produjo un error , contacte al operador");
        //$.unblockUI();
      }

    });
         
   }

   var mostrarFormPagoEfectivo = function(id_tramite, estado_pago, tipo_tramite, precio) {
	    _idTramite =  id_tramite;
        // limpio los datos 
        clearInput();
        $("#divComprobante").hide();
        $("#nro_tramite").append(id_tramite);
        $("#estado_pago").append(estado_pago);
        $("#tipo_tramite").append(tipo_tramite);
        $("#precio").append(precio);
        $("#btn-modal-cobrar").show();
        $("#modal-estado-pago").show();
   }
   
   var mostrarFormPagoEfectivoReba = function(id_tramite, estado_pago, tipo_tramite, precio) {
	    _idTramite =  id_tramite;

        // limpio los datos 
        clearInputReba();
        $("#divComprobanteReba").hide();
        if(precio == -1) {
        	$("#idLabelImporte").hide();    
        }
        $("#nro_tramite_reba").append(id_tramite);
        $("#estado_pago_reba").append(estado_pago);
        $("#tipo_tramite_reba").append(tipo_tramite);
        $("#precio_reba").append(precio);
        $("#btn-modal-cobrar_reba").show();
        $("#modal-estado-pago-reba").show();
   }

      
   var mostrarFormPagoEfectivoPlanillaProntuarial = function(id_tramite, estado_pago, tipo_tramite, precio) {
	    _idTramite =  id_tramite;

        // limpio los datos 
        clearInputPlanilla();
        $("#divComprobantePlanilla").hide();
        if(precio == -1) {
        	$("#idLabelImporte").hide();    
        }
        $("#nro_tramite_planilla").append(id_tramite);
        $("#estado_pago_planilla").append(estado_pago);
        $("#tipo_tramite_planilla").append(tipo_tramite);
        $("#precio_planilla").append(precio);
        $("#btn-modal-cobrar_planilla").show();
        $("#modal-estado-pago-planilla").show();
   }


   var verPagoEfectivo = function(id_tramite, estado_pago, tipo_tramite, precio) {
	    _idTramite =  id_tramite;
		 // limpio los datos 
	    clearInput();
        $.getJSON('<?php echo base_url(); ?>/dashboard/getUltimoMovimiento/' + _idTramite, function (data) {
        	if(data == null) {
        		var box = bootbox.alert({
            	    message: 'Disculpe, ha ocurrido un error inesperado, por favor intente de nuevo.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
        	}else {
        		var fechaHoraPago = data.fecha_alta.split(" ");
        		var fecha = fechaHoraPago[0].split("-");
        		var hora = fechaHoraPago[1].split(":");
    			$("#fechaHoraPago").text(fecha[2]+'/'+fecha[1]+'/'+fecha[0]+'   '+hora[0]+':'+hora[1]+' hs.');
        		if(data.nro_comprobante != null) {
        			$("#spanNroReciboManual").show();
        			$("#nroReciboManual").text(data.nro_comprobante);
        		}else {
        			$("#spanNroReciboManual").hide();
        		}		

        	   $("#nro_tramite").append(id_tramite);
    	       $("#estado_pago").append('<?php echo ESTADO_PAGO_PAGADO; ?>');
    	       $("#tipo_tramite").append(tipo_tramite);
    	       $("#precio").append(precio);

    	       $("#linkModalDescargarComprobante").attr("href", "<?php echo base_url(); ?>/tramite/descargarcomprobante/"+_idTramite);
    	       $("#linkModalDescargarComprobante").html('<span class="oi oi-cloud-download"></span>  Descargar comprobante');
    	       $("#divComprobante").show();
    	       $("#btn-modal-cobrar").hide();
    	       $("#modal-estado-pago").show();
    		}	
        });
   } 

   /** Funcion que permite realizar el pago en la comisaria */
   const realizarPago = async () =>{
      
      $("#modal-estado-pago").hide();
      const url = base_url + '/dashboard/pago_tramite_comisaria/' + _idTramite;
      $.ajax({
        url:  url,
        method: 'GET',
        contentType: 'application/json',
        type: 'json',
        success:function(response) {
         if ( response[0].status === "OK") {
          showAlert("Se realizo el pago del Tramite");
           window.location.reload();
         } else {
           showAlert("Se Produjo un error al realizar el pago");
         }
          
        }, error:function(error){
           alert("Se Produjo un error al realizar el pago");
        }
      });
         
   }

   const realizarCobroEnComisaria = async () =>{
		console.log(_idTramite);
	    const url = '<?php echo base_url();?>/dashboard/pago_tramite_comisaria/' + _idTramite;
	    $("#modal-estado-pago").hide();
	    $.ajax({
	        url:  url,
	        method: 'GET',
	        contentType: 'application/json',
	        type: 'json',
	        success:function(response) {
	        	const data = JSON.parse(response);
	        	bootbox.dialog({
	        		closeButton: false,
	        	    title: '<b>Cobro realizado</b>',
	        	    message: 'Se realizó el cobro del Tramite correctamente, ademas se ha enviado al email de la persona el comprobante de pago.'+
                   	    	 '<br/><br/><a class="btn btn-primary" target="_blank" href="<?php echo base_url(); ?>/tramite/descargarcomprobante/'+_idTramite+'">'+
               	             '<span class="oi oi-cloud-download"></span>  Descargar comprobante</a>'+
               	             '<br/><br/>Si emite el recibo manualmente, ingrese el numero del mismo aqui:'+
               	             '<input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control mayuscula" placeholder="N° DE RECIBO"/>',
	        	    size: 'small',
	        	    onEscape: false,
	        	    backdrop: true,
	        	    buttons: {
	        	        fee: {
	        	            label: 'Aceptar',
	        	            className: 'btn-primary',
	        	            callback: function() {
	        	            	$("#link-cobrar-"+_idTramite).hide();
	        		        	$("#link-pago-"+_idTramite).show();
	        		        	$("#col-estado-pago-"+_idTramite).text('<?php echo ESTADO_PAGO_PAGADO; ?>');
	        		        	if(data.tramite.id_tipo_tramite == '<?php echo TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA; ?>') {
	        		        		$("#link-descargar-"+_idTramite).show();
	        		        	}
	        		        	
		        	            var nro_comprobante = $("#nro_comprobante").val();
		        	            if(nro_comprobante.trim() != '') {
		        	            	const urlSetNroRecibo = '<?php echo base_url();?>/dashboard/setNroComprobantePago/'+data.tramite.idMovimientoPago;
		        	            	$.ajax({
		        	                    url: urlSetNroRecibo,
		        	                    method: 'POST',
		        	                    data: {'nro_comprobante': nro_comprobante},
		        	                    success:function(response) {
		        	         	        	var box = bootbox.alert({
		        	         	        		closeButton: false,
    		        	                 	    message: 'Se ha registrado el Nro. de comprobante de pago correctamente.',
    		        	                 	    size: 'small',
    		        	                 	    title: "Cobro realizado",
    		        	                 	    locale: 'es'
    		        	                 	}).init(function(){
    		        	                 	    $('.btn.btn-primary').text('Aceptar')
    		        	                 	});
		        	                    }, error:function(error) {
		        	                    	var box = bootbox.alert({
		        	                    		closeButton: false,
		        	                    	    message: 'Se ha producido un error al guardar el nro. de comprobante, por favor intente de nuevo.',
		        	                    	    size: 'small',
		        	                    	    title: "Alerta",
		        	                    	    locale: 'es'
		        	                    	}).init(function(){
    		        	                 	    $('.btn.btn-primary').text('Aceptar')
    		        	                 	});
		        	                    }
		        	                });
		        	            }
	        	            }
	        	        }
	        	    }
	        	})
	        }, error:function(error) {
	        	var box = bootbox.alert({
            	    message: 'Se Produjo un error al realizar el cobro, por favor intente de nuevo.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
	        }
	    });
	}


  const cambiarEstadoReba  = async () =>{
		console.log(_idTramite);
	    const url = '<?php echo base_url();?>/dashboard/pago_tramite_comisaria_reba/' + _idTramite;
	    $("#modal-estado-pago-reba").hide();
	    $.ajax({
	        url:  url,
	        method: 'GET',
	        contentType: 'application/json',
	        type: 'json',
	        success:function(response) {
	        	const data = JSON.parse(response);
           
	        	bootbox.dialog({
	        		closeButton: false,
	        	    title: '<b>Registro de pago correcto</b>',
	        	    message: 'Se ha registrado el pago del Tramite correctamente.',
	        	    size: 'small',
	        	    onEscape: false,
	        	    backdrop: true,
	        	    buttons: {
	        	        fee: {
	        	            label: 'Aceptar',
	        	            className: 'btn-primary',
	        	            callback: function() {
	        	            	$("#link-cobrar-"+_idTramite).hide();
	        		        	$("#link-pago-"+_idTramite).show();
	        		        	$("#col-estado-pago-"+_idTramite).html('<?php echo ESTADO_PAGO_PAGADO; ?>');
	        		        	$("#col-forma-pago-"+_idTramite).html('<?php echo 'PAGO EN EFECTIVO'; ?>');
	        	            }
	        	        }
	        	    }
	        	})
	        }, error:function(error) {
	        	var box = bootbox.alert({
            	    message: 'Se Produjo un error al realizar el cobro, por favor intente de nuevo.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
	        }
	    });
	}

  const cambiarEstadoPlanilla  = async () =>{
		console.log(_idTramite);
	    const url = '<?php echo base_url();?>/turnoPlanillaProntuarial/pago_tramite_planilla/' + _idTramite;
	    $("#modal-estado-pago-planilla").hide();
	    $.ajax({
	        url:  url,
	        method: 'GET',
	        contentType: 'application/json',
	        type: 'json',
	        success:function(response) {
	        	const data = JSON.parse(response);
           
	        	bootbox.dialog({
	        		closeButton: false,
	        	    title: '<b>Registro de pago correcto</b>',
	        	    message: 'Se ha registrado el pago del Tramite correctamente.',
	        	    size: 'small',
	        	    onEscape: false,
	        	    backdrop: true,
	        	    buttons: {
	        	        fee: {
	        	            label: 'Aceptar',
	        	            className: 'btn-primary',
	        	            callback: function() {
	        	            	$("#link-cobrar-"+_idTramite).hide();
	        		        	$("#link-pago-"+_idTramite).show();
	        		        	$("#col-estado-pago-"+_idTramite).html('<?php echo ESTADO_PAGO_PAGADO; ?>');
	        		        	$("#col-forma-pago-"+_idTramite).html('<?php echo 'PAGO EN EFECTIVO'; ?>');
	        	            }
	        	        }
	        	    }
	        	})
	        }, error:function(error) {
	        	var box = bootbox.alert({
            	    message: 'Se Produjo un error al realizar el cobro, por favor intente de nuevo.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
	        }
	    });
	}

  const closeModalPagoEstado = () => {
    $("#modal-sincronizacion-pago").hide();
   }

   const closeModalPago = () => {
    $("#modal-estado-pago").hide();
   }

   const closeModalPagoReba = () => {
    $("#modal-estado-pago-reba").hide();
   }

   const closeModalPagoPlanilla = () => {
    $("#modal-estado-pago-planilla").hide();
   }


   /** Funcion que permite limpiar los datos
    *  y los messages
   */
   const clearInput = () => {
      $("#nro_tramite").empty();
      $("#tipo_tramite").empty();
      $("#estado_pago").empty();
      $("#fecha_pago").empty();
      $("#fecha").empty();
      
      $("#mostarMessageError").hide();
      $("#messageErrorPago").empty();
      $("#precio").empty();

      $("#mostrarMessageCorrecto").hide();
      
      $("#btnRealizarPago").prop('disabled',true);  
   }

   const clearInputEstado = () => {
      $("#nro_tramite_sincronizacion").empty();
      $("#tipo_tramite_sincronizacion").empty();
      $("#estado_pago_sincronizacion").empty();
      $("#fecha_pago_sincronizacion").empty();
      $("#fecha_sincronizacion").empty();
      
      $("#mostarMessageError").hide();
      $("#messageErrorPago").empty();
      $("#precio_sincronizacion").empty();

      $("#mostrarMessageCorrecto").hide();
      
   }

   const clearInputReba = () => {
      $("#nro_tramite_reba").empty();
      $("#tipo_tramite_reba").empty();
      $("#estado_pago_reba").empty();
      $("#fecha_pago_reba").empty();
      $("#fecha_reba").empty();
      
      $("#mostarMessageError").hide();
      $("#messageErrorPago").empty();
      $("#precio").empty();

      $("#mostrarMessageCorrecto").hide();
      
      $("#btnRealizarPago").prop('disabled',true);  
   }

   const clearInputPlanilla = () => {
      $("#nro_tramite_planilla").empty();
      $("#tipo_tramite_planilla").empty();
      $("#estado_pago_planilla").empty();
      $("#fecha_pago_planilla").empty();
      $("#fecha_planilla").empty();
      $("#precio_planilla").empty();
      
      $("#mostarMessageError").hide();
      $("#messageErrorPago").empty();
      $("#precio").empty();

      $("#mostrarMessageCorrecto").hide();
      
      $("#btnRealizarPago").prop('disabled',true);  
   }


   const descargarComprobante = (idTramite, controllerAction) => {
     var url = baseUrl + '/' + controllerAction + '/descargar/' + idTramite;
     console.log(baseUrl);
     window.open(
      url,
      '_blank' // <- This is what makes it open in a new window.
     );
   }


    return {
    	isNumber : isNumber,
        changeToUpper : changeToUpper,
        realizarPago : realizarPago,
        realizarCobroEnComisaria : realizarCobroEnComisaria,
        mostrarPago : mostrarPago,
        mostrarFormPagoEfectivo : mostrarFormPagoEfectivo,
        verPagoEfectivo : verPagoEfectivo,
        closeModalPago : closeModalPago,
        descargarComprobante : descargarComprobante,
        mostrarFormPagoEfectivoReba:mostrarFormPagoEfectivoReba,
        closeModalPagoReba: closeModalPagoReba,
        cambiarEstadoReba: cambiarEstadoReba,
        closeModalPagoEstado:closeModalPagoEstado,
        mostrarFormPagoEfectivoPlanillaProntuarial : mostrarFormPagoEfectivoPlanillaProntuarial,
        closeModalPagoPlanilla: closeModalPagoPlanilla,
        cambiarEstadoPlanilla: cambiarEstadoPlanilla,
    }


  }());
</script>