<link href="<?php echo base_url() ?>/public/assets/css/main.css" rel="stylesheet" media="only screen and (max-width: 768px)" >
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary" style="padding: 1px;">
    <a class="navbar-brand d-none d-sm-block" href="http://policia.jujuy.gob.ar/"><img class="ui mini rounded image" src="<?php echo base_url('assets/img/logo-policia.png'); ?>" alt="Logo" /></a>
    <div class="container">
    	<?php if(!session()->get('isLoggedIn')): ?>
    		<a class="navbar-brand responsive-element" href="#">TRAMITES ONLINE</a>
    	<?php endif; ?>
    	<?php if(session()->get('isLoggedIn')): ?>
    		<a class="navbar-brand" href="#">GESTION DE TRAMITES</a>
    		<a class="navbar-brand" href="#">-</a>
        	<a class="navbar-brand" href="#"><?php echo session()->get('dependencia'); ?></a>
        <?php endif; ?>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
            	<li class="nav-item active">
                    <a class="nav-link responsive-element" href="<?php echo base_url(); ?>">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <?php if(session()->get('isLoggedIn')): ?>
                	<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        	
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="<?php echo base_url(); ?>/users/profile"><span class="oi oi-key"></span> Cambiar contrase&ntilde;a</a>
                        </div>
                  	</li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>/logoutUnidadAdmin987Gestion2021"><span class="oi oi-account-login"></span> Salir</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        
    </div>
    <a class="navbar-brand d-none d-sm-block" href="http://jujuy.gob.ar/home/" style="width: 200px;"><img class="ui mini rounded image" src="<?php echo base_url('assets/img/logo.png'); ?>" style="width: 200px; height: 60px; float: right;" alt="Logo" /></a>
</nav>
