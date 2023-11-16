<script>
  var module_fileupload_multiple = (function() {

    var filesToUpload = [];
    var fileIdCounter = 0;
    var fileIdCounterOnload = 0;

    const uploadFirmaDigitalMultiple = async () => {
      var url = baseUrl + '/dashboard/uploadfirmaDigitalSingleFormat';
      var formData = new FormData();
      let results = [];

      if (filesToUpload.length > 0) {

        try {

          $.blockUI({
            message: 'Enviando archivos...'
          });

          $("#modal-firma-digital-multiple").hide();
          for (var i = 0, len = filesToUpload.length; i < len; i++) {
            formData = new FormData();
            formData.append("file", filesToUpload[i].file);
            results.push(await fetch(url, {
              method: 'POST',
              body: formData
            }));
          }

          let informacion = await Promise.all(results);
          console.log("informcion : ", informacion);
          //let data = await informacion.json();
          console.log("data : ", data);
        } catch (error) {

        }
      } else {
        alert("Debe Seleccionar Archivos para enviar datos");
      }
    }

    const closeModalUploadFirmaDigitalMultiple = () => {
      $("#modal-firma-digital-multiple").hide();
      $("#files").val("");
      filesToUpload = [];
    }

    /** 
     * Funcion que permite mostrar el modal firma digital 
     * multiple
     */
    const mostrarModalFirmaDigitalMultiple = () => {
      console.log("mostrarModalFirmaDigital");
      filesToUpload = [];
      $("files").val();
      $("#modal-firma-digital-multiple").show();
      $("#selectedFiles").empty();
    }

    /**
     * Funcion que permite mostrar el handleFileSelect 
     * que permite mostrar el listado de los files 
     */
    const handleFileSelect = (e) => {
      if (!e.target.files) return;
      selDiv.innerHTML = "";
      var files = e.target.files;
      for (var i = 0; i < files.length; i++) {
        fileIdCounter++;
        var f = files[i];
        var file = files[i];
        var fileId = fileIdCounter;
        console.log("name : ", file.name);
        filesToUpload.push({
          id: fileId,
          file: file
        });
        //var removeLink = "<a class=\"removeFile\" href=\"#\" data-fileid=\"" + fileId + "\">Remove</a>";
        selDiv.innerHTML += "<li><strong>" + file.name + "</strong> - " + file.size + " bytes. &nbsp; &nbsp; " + "</li> "; //   removeLink + "</li> ";
        // selDiv.innerHTML += f.name + "<br/>";
      }

      console.log(filesToUpload);
    }


    return {
      mostrarModalFirmaDigitalMultiple: mostrarModalFirmaDigitalMultiple,
      handleFileSelect: handleFileSelect,
      closeModalUploadFirmaDigitalMultiple: closeModalUploadFirmaDigitalMultiple,
      uploadFirmaDigitalMultiple: uploadFirmaDigitalMultiple
    }

  }());
</script>