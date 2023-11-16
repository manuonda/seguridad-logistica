<div class="container">
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper">
      <div class="container">
        <h3>Acceso al Sistema</h3>
        <hr>
        <?php if (session()->get('success')): ?>
          <div class="alert alert-success" role="alert">
            <?= session()->get('success') ?>
          </div>
        <?php endif; ?>
        <form class="" action="/users" method="post">
          <input type="hidden" name="ip" id="ip"/>
          <input type="hidden" name="ip_information" id="ip_information"/>

          <div class="form-group">
           <label for="email">Nombre de Usuario</label>
           <input type="text" class="form-control" name="username" id="username" value="<?= set_value('username') ?>">
          </div>
          <div class="form-group">
           <label for="password">Clave</label>
           <input type="password" class="form-control" name="password" id="password" value="">
          </div>
          <?php if (isset($validation)): ?>
            <div class="col-12" id="div_list_errors">
              <div class="alert alert-danger" role="alert">
                <?= $validation->listErrors() ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if (isset($error) and !empty($error)): ?>
            <div class="alert alert-danger" id="div_error">
                <?php echo $error; ?>
            </div>
    	  <?php endif; ?>
         <div class="col-12" id="div_bloqueado">
         <?php if (isset($error2) and !empty($error2)): ?>
            <div class="alert alert-danger">
                <?php echo $error2; ?>
            </div>
    	  <?php endif; ?>
         </div>

          <?php if(isset($bloqueado) and !empty($bloqueado)) : ?>
            <div id="div_contador">
            <strong><span id="countdown">30</span></strong><strong> segundo</strong><span id="plural">s</span>
            <br><br> 
            </div>
          <?php endif; ?> 
          
         
         <div class="row">
            <div class="col-12 col-sm-4">
              <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
            <div class="col-12 col-sm-4">
             <button type="reset" class="btn btn-warning">Limpiar</button>
            </div>
            <!--
            <div class="col-12 col-sm-8 text-right">
              <a href="/register">Don't have an account yet?</a>
            </div>
          -->
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="application/javascript">

var ip_cliente   ="";

var bloqueado = "";
$(document).ready(function(){
    
    var bloqueado  = '<?php echo $bloqueado ;?>';
    console.log("bloqueado: ", bloqueado);
    if ( bloqueado != "" ) {
         var seconds = document.getElementById("countdown").textContent;
         var countdown = setInterval(function() {
         seconds--;
         console.log("seconds :",seconds);
         (seconds == 1) ? document.getElementById("plural").textContent = "": document.getElementById("plural").textContent = "s";
         document.getElementById("countdown").textContent = seconds;
         if (seconds <= 0) clearInterval(countdown);
         if (seconds === 0) { 
              $("#div_bloqueado").hide(); 
              $("#countdown").hide(); 
              $("#plural").hide();
              $("#div_contador").hide();
              $("#div_error").hide();
              $("#div_list_errors").hide();
          }
        }, 1000);
    }
    
    $.getJSON('https://json.geoiplookup.io/?callback=?', function(data) {
      //console.log(JSON.stringify(data, null, 2));
      var dataStr = JSON.stringify(data);
      console.log("dataStr: ",dataStr);
      $("#ip").val(data.ip);
      $("#ip_information").val(dataStr);
    });
});
 
</script>

