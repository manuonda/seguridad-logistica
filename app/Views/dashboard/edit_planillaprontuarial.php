<div class="container">
  <div class="card card-outline-secondary">
    <div class="form-group row">
      <label class="col-lg-12 control-label fs-title"><b>Datos personales</b></label>
    </div>
    <div class="form-group row">
      <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
      <div class="col-lg-9">
        <input type="text" readonly name="nombre" id="nombre" class="form-control mayuscula" value="<?php if (isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" />
      </div>
    </div>
    <div class="form-group row">
      <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
      <div class="col-lg-9">
        <input type="text" readonly name="apellido" id="apellido" class="form-control mayuscula" value="<?php if (isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" />
      </div>
    </div>
    <div class="form-group row">
      <label class="col-lg-3 col-form-label form-control-label">Fecha Nacimiento *:</label>
      <div class="col-lg-9">
        <input type="date" readonly name="fecha_nacimiento" id="fecha_nacimiento" class="form-control mayuscula" value="<?php if (isset($fecha_nacimiento)) echo $fecha_nacimiento; ?>" required spellcheck="false" />
      </div>
    </div>
  </div>


  <hr>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="tab" href="#datos">Datos personales</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#domicilios">Domicilios</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#huellas">Huellas</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#fotos">Fotos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#procesos">Procesos</a>
    </li>
  </ul>
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active show" id="datos">
      <?= $this->include('dashboard/planilla_prontuarial/datos.php') ?>
    </div>
    <div class="tab-pane fade active show" id="domicilios">
      <?= $this->include('dashboard/planilla_prontuarial/domicilios.php') ?>
    </div>
    <div class="tab-pane fade" id="huellas">

    </div>
    <div class="tab-pane fade" id="fotos">
      <?= $this->include('dashboard/planilla_prontuarial/fotos.php') ?>
    </div>
    <div class="tab-pane fade" id="procesos">
      <?= $this->include('dashboard/planilla_prontuarial/procesos.php') ?>
    </div>
  </div>




</div>

<script>
  async function mostrarPago(idTramite) {
    console.log('idTramite : ', idTramite);
    $("#tramites").block({
      message: 'Cargando...'
    });
    $.ajax({
      url: '/dashboard/get_pago_tramite/' + idTramite,
      method: 'GET',
      contentType: 'application/json',
      type: 'json',
      success: function(response) {
        const data = JSON.parse(response);
        if (data.status === 'ERROR') {
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
      },
      error: function(error) {
        $("#tramites").unblock();
        alert("Se produjo un error , contacte al operador");
      }

    })

  }
</script>