<script>
  var module_util = (function() {

    var filesToUpload = [];

    // Funcion que permite realizar la descargar del comprobante
    const descargarComprobante = (idTramite, controllerAction) => {
      var url = baseUrl + '/' + controllerAction + '/descargarcomprobante/' + idTramite;
      console.log(baseUrl);
      window.open(
        url,
        '_blank' // <- This is what makes it open in a new window.
      );
    }

    // Funcion que permite realizar la descarga del tramite
    const descargarTramite = (idTramite, controllerAction) => {
      if(controllerAction==='' || controllerAction==='planillaProntuarial') {
    	  controllerAction = 'tramite';
      }

      var url = baseUrl + '/' + controllerAction + '/descargar/' + idTramite;
      console.log(baseUrl);
      window.open(
        url,
        '_blank' // <- This is what makes it open in a new window.
      );
    }

    // Funcion que permite realizar el envio del email
    const envioEmail = (idTramite) => {
      var url = baseUrl + '/dashboard/sendEmail/' + idTramite;
      $.blockUI({ message: '<h2><img src="<?php echo base_url();?>/assets/img/loading.gif" /> Enviando Email...</h2>' });
      $.ajax({
        url: url,
        method: 'GET',
        contentType: 'application/json',
        type: 'json',
        global: false, // hace que no se muestre el loading 'cargando...'
        success: function(response) {
          $.unblockUI();
          //$("#tramites").unblock();
          var data = JSON.parse(response);

          if (data.status == "OK") {
        	  bootbox.alert({
          	    message: 'Se realizo el envío del Email.',
          	    size: 'small',
          	    title: "Envío realizado",
          	    locale: 'es'
          	  });
          } else {
        	  bootbox.alert({
        	    message: 'Se produjo un error al realizar el envio del Email: '+data.status,
        	    size: 'small',
        	    title: "Alerta",
        	    locale: 'es'
              });
          }
        },
        error: function(error) {
          $.unblockUI();
          //$("#tramites").unblock();
          bootbox.alert({
      	    message: 'Se ha producido un error en el servidor, intente mas tarde de nuevo.',
      	    size: 'small',
      	    title: "Alerta",
      	    locale: 'es'
          });
        }
      });

    }


    const envioEmailDirectoSinFirma = (idTramite) => {
      var url = baseUrl + '/dashboard/sendEmailDirectoSinFirma/' + idTramite;
      $.blockUI({ message: '<h2><img src="<?php echo base_url();?>/assets/img/loading.gif" /> Enviando Email...</h2>' });
      $.ajax({
        url: url,
        method: 'GET',
        contentType: 'application/json',
        type: 'json',
        global: false, // hace que no se muestre el loading 'cargando...'
        success: function(response) {
          $.unblockUI();
          //$("#tramites").unblock();
          var data = JSON.parse(response);

          if (data.status == "OK") {
        	  bootbox.alert({
          	    message: 'Se realizo el envío del Email.',
          	    size: 'small',
          	    title: "Envío realizado",
          	    locale: 'es'
          	  });
          } else {
        	  bootbox.alert({
        	    message: 'Se produjo un error al realizar el envio del Email: '+data.status,
        	    size: 'small',
        	    title: "Alerta",
        	    locale: 'es'
              });
          }
        },
        error: function(error) {
          $.unblockUI();
          //$("#tramites").unblock();
          bootbox.alert({
      	    message: 'Se ha producido un error en el servidor, intente mas tarde de nuevo.',
      	    size: 'small',
      	    title: "Alerta",
      	    locale: 'es'
          });
        }
      });

    }

    const uploadFirmaDigital = (idTramite) => {
      var valor = $("#file_tramite").val();
    
      if( valor  !== "") {
        var url = baseUrl + '/dashboard/uploadFirmaDigitalSingle';
        var form = document.getElementById("form-tramite-tabla");
        var formData = new FormData(form);
        $("#modal-firma-digital").hide();
        $.blockUI({
           message: '<h1> Subiendo Archivo Firma Digital..</h1>'
        });
     

      fetch(url, {
          method: 'POST',
          body: formData
        }).then(response => response.json())
        .then(data => {
           if (data.status === "OK") {
        	   $.unblockUI();
               bootbox.alert({
               	    message: 'Se ha subido el archivo correctamente.',
               	    size: 'small',
               	    title: "Subida exitosa",
               	    locale: 'es'
           		});
            } else {
               $.unblockUI();
               bootbox.alert({
              	    message: 'Se produjo un error al subir el Archivo : ' + data.message,
              	    size: 'small',
              	    title: "Alerta",
              	    locale: 'es'
          		});
            }
            $("#id_file_tramite").val("");
            $("#file_tramite").val("");
            $.unblockUI();
         }).catch(error => {
            bootbox.alert({
        	    message: '¡Disculpe, se ha producido un error al subir el archivo, vuelva a intentar por favor!',
        	    size: 'small',
        	    title: "Alerta",
        	    locale: 'es'
        	});
            $("#tramites").unblock();
         });
      } else{
        bootbox.alert({
    	    message: 'Debe Seleccionar un archivo para la carga digital.',
    	    size: 'small',
    	    title: "Alerta",
    	    locale: 'es'
    	});
      }
      
    }



    const closeModalUploadFirmaDigital = () => {
      $("#id_file_tramite").val("");
      $("#file_tramite").val("");
      $("#modal-firma-digital").hide();
    }


    /** 
     * Mostrar Modal Firma Digital
     */
    const mostrarModalFirmaDigital = (idTramite) => {
      console.log("mostrarModalFirmaDigital", idTramite);
      $("#id_file_tramite").val(idTramite);
      var url = baseUrl + '/dashboard/mostrarFirmaDigital?id_tramite=' + idTramite;

      $.blockUI({
        message: 'Cargando..'
      });
      $.ajax({
        url: url,
        method: 'GET',
        contentType: 'application/json',
        type: 'json',
        global: false, // hace que no se muestre el loading 'cargando...'
        success: function(response) {
          $.unblockUI();

          $("#modal-firma-digital").show();
          console.log(response);
          if (response.status == "OK") {
            $("#mostrar-archivo-firma-digital").show();
          } else {
            $("#mostrar-archivo-firma-digital").hide();
          }
        },
        error: function(error) {
          $.unblockUI();
          //$("#tramites").unblock();
          alert("Error en el Servidor");
        }
      });
    }

    const descargarFirmaDigital = () => {

      var idTramite = document.getElementById('id_file_tramite').value;

      var url = baseUrl + '/dashboard/descargarFirmaDigital?id_tramite='+idTramite;
      console.log(baseUrl);
      window.open(
        url,
        '_blank' // <- This is what makes it open in a new window.
      );
    }

   
    const handleFileSelect = (e) => { 
      if (!e.target.files) return;
          selDiv.innerHTML = "";
          var files = e.target.files;
          for (var i = 0; i < files.length; i++) {
             var f = files[i];
             var file = evt.target.files[i];
             var fileId = fileIdCounter;

             filesToUpload.push({
                id: fileId,
                file: file
             });
             var removeLink = "<a class=\"removeFile\" href=\"#\" data-fileid=\"" + fileId + "\">Remove</a>";
             output.push("<li><strong>", escape(file.name), "</strong> - ", file.size, " bytes. &nbsp; &nbsp; ", removeLink, "</li> ");
             selDiv.innerHTML += f.name + "<br/>";
          }
    }


    return {
      envioEmail: envioEmail,
      descargarComprobante: descargarComprobante,
      mostrarModalFirmaDigital: mostrarModalFirmaDigital,
      uploadFirmaDigital: uploadFirmaDigital,
      descargarFirmaDigital: descargarFirmaDigital,
      closeModalUploadFirmaDigital: closeModalUploadFirmaDigital,
      descargarTramite: descargarTramite,
      envioEmailDirectoSinFirma:envioEmailDirectoSinFirma
    }

  }());
</script>