<div class="container">
 
  <div class="bs-docs-section">

  <div class="row">
    <div class="col-lg-6">
      <div class="bs-component">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">
          <a href="<?php echo base_url().'/dashboard' ;?>">Tramites</a></li>
          <li class="breadcrumb-item active">Editar</li>
        </ol>
      </div>

    </div>
  </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card border-primary mb-3">
          <div class="card-header">Edicion de Datos </div>
          <div class="card-body">

            <form  action="" method="GET">
              <fieldset>
              <div class="row">
              <div class="form-group col-md-3">
                  <label for="exampleInputEmail1">Nro Tramite</label>
                  <input type="number" name="idTramite" class="form-control" value="<?php if(isset($filter['idTramite'])) echo $filter['idTramite']; ?>" aria-describedby="emailHelp" placeholder="">
                </div>
 
              <div class="form-group col-md-3">
                  <label for="exampleInputEmail1">Cuil</label>
                  <input type="text" name="cuil" class="form-control" value="<?php if(isset($filter['cuil'])) echo $filter['cuil']; ?>" aria-describedby="emailHelp" placeholder="">
                </div>
              </div>
              <div class="row">
              <div class="form-group col-md-3">
                  <label for="exampleInputEmail1">Nombre</label>
                  <input type="text" name="nombre" class="form-control" value="<?php if(isset($filter['nombre'])) echo $filter['nombre']; ?>" aria-describedby="emailHelp" placeholder="">
                </div>
                <div class="form-group col-md-3">
                  <label for="exampleInputEmail1">Apellido</label>
                  <input type="text" name="apellido" class="form-control" value="<?php if(isset($filter['apellido'])) echo $filter['apellido']; ?>" aria-describedby="emailHelp" placeholder="">
                </div>
              </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="<?php echo base_url().'/dashboard' ?>"  class="btn btn-secondary">Limpiar</a>
            
              </fieldset>
            </form>
          </div>
        </div>


      </div>

    </div>
  </div>
  
</div>

<script>

 async function mostrarPago(idTramite){
    console.log('idTramite : ', idTramite);
      $("#tramites").block({ message : 'Cargando...'});
      $.ajax({
          url: '/dashboard/get_pago_tramite/'+idTramite,
          method: 'GET',
          contentType: 'application/json',
          type: 'json',
          success: function(response) {
            const data =  JSON.parse(response);
            if ( data.status === 'ERROR') {
               alert(data.message);
            } else {
               $("#modal-pago").modal();
               $("#tipo_tramite").empty();
               $("#tipo_tramite").append(data.pago.tipo_tramite);
               $("#estado_pago").empty();
               $("#estado_pago").append(data.pago.estado_pago);
               $("#fecha_pago").empty();
               $("#fecha_pago").append(data.pago.fecha_pago);
              
            }
            $("#tramites").unblock();
          }, error : function(error) {
             $("#tramites").unblock();
             alert("Se produjo un error , contacte al operador"); 
          }
          
       })
  
 }

</script>
