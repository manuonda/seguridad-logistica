<div class="container">
	<div class="bs-docs-section">
		<div class="row">
          <div class="col-lg-12">
            <div class="card border-primary mb-3">
              <div class="card-header">Cambiar de estado</div>
              <div class="card-body">
                <form  action="" method="GET">
                  <fieldset>
                	<div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nombre *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="nombre" id="nombre" class="form-control mayuscula" value="<?php if(isset($nombre)) echo $nombre; ?>" placeholder="Nombre" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Apellido *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="apellido" id="apellido" class="form-control mayuscula" value="<?php if(isset($apellido)) echo $apellido; ?>" placeholder="Apellido" required spellcheck="false" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Documento *:</label>
                        <div class="col-lg-9">
                            <input type="text" name="documento" id="documento" class="form-control mayuscula" value="<?php if(isset($documento)) echo $documento; ?>" placeholder="N° DE DOCUMENTO" maxlength="15" required spellcheck="false"/>
                        </div>
                    </div>
                    
                    
                  
                      <div class="row">
                          <div class="form-group col-md-3">
                              <label for="exampleInputEmail1">Nro Tramite</label>
                              <input type="number" name="idTramite" class="form-control" value="<?php if(isset($filter['idTramite'])) echo $filter['idTramite']; ?>" aria-describedby="emailHelp" placeholder="">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="exampleInputEmail1">Tipo de tramite</label>
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
                      
                      <button type="submit" class="btn btn-primary">Guardar y notificar a la persona</button>
                    
                  </fieldset>
                </form>
              </div>
            </div>
    
          </div>
    
        </div>
	</div>
</div>
