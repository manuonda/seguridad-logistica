<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <a class="navbar-brand" href="<?php echo base_url();?>">Publico</a>
               
                <div class="collapse navbar-collapse" id="navbarColor01">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                      <a class="nav-link" href="#">Inicio
                        <span class="sr-only">(current)</span>
                      </a>
                    </li>

                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                    <ul>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <?= session()->get('username') ?></a>
                      <div class="dropdown-menu">
                        <!--<a class="dropdown-item" href="#">Perfil</a>-->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url().'/logout';?>">Salir</a>
                      </div>
                    </li>
                    </ul>
                  </form>
                </div>
              </nav>