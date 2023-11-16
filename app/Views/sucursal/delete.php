<?= $this->extend('templates/admin_template'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"> 
           <?php if ($action == 'create') { ?>
             <i class="fas fa-plus"></i>
           <?php  } else if ($action == 'update' ) { ?> 
             <i class="fas fa-edit"></i>
           <?php  } else if ($action == 'delete') { ?>
             <i class="fas fa-info"></i>
            <?php } ?>  
            <?php echo $action_text; ?> 
            
        </h3>
      </div>
      <!-- /.card-header -->
      
 <!-- Form -->
 <form role="form" method="POST">
 <input name="id_sucursal" id="id_sucursal" type="text"  
    value="<?php if(isset($sucursal)) echo $sucursal['id'];?>"/>


<div class="card-body">
  <div class="row">
  <div class="col-md-6">
<?php if ($error) { ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
        ×
      </button>
      <h6><i class="icon fas fa-ban"></i> Ha ocurrido un error al guardar el registro</h6>
     <ul id="list_message_error">
        <li> <?php echo $msg; ?></li>
     </ul>
</div>
 <?php }?>
  </div>
  </div>
 <div class="row">
<div class="col-md-6">
 <div class="row">
 <div class="form-group">
  <label for="name">Nombre</label>
  <input class="form-control <?php if(isset($validation) && $validation->hasError('name')) echo 'is-invalid'; ?>" type="text" name="name"
    value="<?php if(isset($sucursal) && $sucursal['name']) echo $sucursal['name'];?>"
  />
  <?php  if (isset($validation) && $validation->hasError('name')){ ?>
    <span class="error invalid-feedback">Ingrese Nombre</span>
  <?php } ?>

 </div>
 </div>
 <div class="row">
 <div class="form-group">
  <label for="street_number">Numero</label>
  <input class="form-control  <?php if(isset($validation) && $validation->hasError('street_number')) echo 'is-invalid'; ?>" 
     type="number" name="street_number" 
     value="<?php if(isset($sucursal) && $sucursal['street_number']) echo $sucursal['street_number'];?>"/> 
  <?php  if ( isset($validation) && $validation->hasError('street_number')){ ?>
    <span class="error invalid-feedback">Ingrese Number</span>
   <?php } ?>  
</div>
<div class="form-group">
    <label for="street_name">Calle</label>
    <input class="form-control <?php if(isset($validation) && $validation->hasError('street_name')) echo 'is-invalid'; ?>" 
    type="text" name="street_name"
    value="<?php if(isset($sucursal) && $sucursal['street_name']) echo $sucursal['street_name'];?>">
    <?php  if (isset($validation) && $validation->hasError('street_name')){ ?>
    <span class="error invalid-feedback">Ingrese Nombre Calle</span>
   <?php } ?> 
</div>
</div>

<!-- city_name -->
<div class="row">
<div class="form-group">
   <label>Provincia</label> 
   <input class="form-control" type="text" readonly name="state_name" value="<?php echo $state_name;?>"/> 
</div>
<div class="form-group">
 <label>Localidad</label>
  <select class="form-control 
  <?php if(isset($validation) && $validation->hasError('city_name')) echo 'is-invalid'; ?>"  data-toggle="tooltip"  name="city_name" id="localidades">
  <option value="">-- Seleccionar Localidad --</option>
  <?php 
    if ($citys!=null){  
         foreach ($citys as $city): ?>                                                                        
            <option value="<?php echo $city->name ?>"    
            <?php if (isset($sucursal) && $sucursal != null && $sucursal['city_name'] == $city->name) echo 'selected="selected"'; ?>>
              <?php echo $city->name ?></option>
            <?php endforeach; 
        }
    ?>
 </select>
 <?php  if (isset($validation) && $validation->hasError('city_name')){ ?>
    <span class="error invalid-feedback">Seleccione </span>
   <?php } ?> 
 </div>
</div>

<div class="row">
<div class="form-group">
  <label>Sucursal Mercado Pago </label>
<input name="id_sucursal_mercado_pago" class="form-control" readonly id="id_sucursal" type="text"  
    value="<?php if(isset($sucursal)) echo $sucursal['id_sucursal_mercado_pago'];?>"/>

</div>
<div class="form-group">
<label>Sucursal External Id</label>
<input name="external_id" id="id_sucursal" type="text" readonly class="form-control"
    value="<?php if(isset($sucursal)) echo $sucursal['external_id'];?>"/>
</div>

</div>

</div>
<div class="col-md-6">
        <!-- BEGIN GEOLOCATION PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Localización</span>
                </div>

            </div>
            <div class="portlet-body">
                <div class="form-group">
                 <label class="control-label">Latitud</label>
                 <input type="text" 
                  class="form-control <?php if(isset($validation) && $validation->hasError('latitude')) echo 'is-invalid'; ?>" 
                  name="latitude" id="latitude" 
                  value="<?php if(isset($sucursal) && $sucursal['latitude']) echo $sucursal['latitude'];?>"
                  readonly>
                 <?php  if ( isset($validation) && $validation->hasError('latitude')){ ?>
                    <span class="error invalid-feedback">Ingrese Latitude</span>
                <?php } ?> 
                </div> 
                <div class="form-gropup">
                  <label class="control-label">Longitud</label>
                  <input type="text" 
                  class="form-control <?php if(isset($validation) && $validation->hasError('longitude')) echo 'is-invalid'; ?>" 
                  name="longitude" id="longitude" 
                  value="<?php if(isset($sucursal) && $sucursal['longitude']) echo $sucursal['longitude'];?>" readonly>  
                  <?php  if (isset($validation) && $validation->hasError('longitude')){ ?>
                     <span class="error invalid-feedback">Ingrese Longitud</span>
                  <?php } ?>  
                </div>
            <div id="map" style="width: 100%;height: 330px;"> </div>
            </div>
         </div>
</div>
   </div>

        <!-- Card Footer --> 
        <div class="card-footer">
          <a href="<?php echo base_url().'/sucursal';?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left "></i>
            Volver
          </a>
          
           <?php if ($action ==  'create' ||  $action == 'update') { ?>
            <button type="submit" class="btn btn-primary">
             <i class="fas fa-save"></i> 
             Guardar
            </button>    
           <?php } ?>
         
           <?php  if ($action == 'delete' ) { ?>
            <button type="submit" class="btn btn-primary">
            <i class="fas fa-trash"></i>
                Eliminar
            </button> 
            <?php } ?>
         
        </div>  
          
      </form>
    </div>
   </div> 
</div>
</div>
<?= $this->endSection('content'); ?>


<?= $this->section('script') ;?>
<script type="text/javascript">

 $(document).ready(function() {
  console.log('aque qijen ondasfd');
         var latCenterJujuy = -24.1793;
         var lngCenterJujuy = -65.3137;
         var map = L.map('map').setView([latCenterJujuy, lngCenterJujuy], 13);

         var latOrigen =$("#latitude").val();
         var lngOrigen =$("#longitude").val();

         if((latOrigen!=null && latOrigen!="") && (lngOrigen!=null && lngOrigen !="")) {
           latCenterJujuy = latOrigen;
           lngCenterJujuy = lngOrigen; 
         }else{
            alert("NO ESTAN COMPLETAS LAS COORDENADAS  DE LA DENUNCIA, PUEDE MOVER EL MARCADOR PARA ESTABLECER EL PUNTO");
         }

         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(map);

        var marker = new L.marker([latCenterJujuy,lngCenterJujuy],{
            draggable : 'true'
          });
       
       marker.on('dragend', function(event) {
          var position = marker.getLatLng();
          marker.setLatLng(position, {
          draggable: 'true'
        }).bindPopup(position).update();
        $("#latitude").val(position.lat);
        $("#longitude").val(position.lng).keyup();
      });

      // map.addLayer(marker);
     marker.addTo(map)
    .bindPopup("<b>Mover el marcador para establecer un nuevo punto</b>").openPopup();
 });
     
</script>

<?= $this->endSection('script') ;?>
