<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->post('/sucursal/save','Sucursal::save');
$routes->post('/sucursal/edit/(:any)/save', 'Sucursal::save');


$routes->add('/sucursal/(:any)/cajas','Caja::index/$1');
$routes->add('/sucursal/(:any)/caja/create','Caja::create/$1');
$routes->add('/sucursal/(:any)/caja/save','Caja::save');

/*
$routes->group('', ['filter' => 'login'] ,function($routes){
   $routes->get('home', 'Login::index');
});
*/


// no require authentication
// $routes->get('/unidadAdmin987Gestion-2021', 'Users::index', ['filter' => 'noauth']);
$routes->get('/unidadAdmin987Gestion-2021', 'Users::unidadAdmin987Gestion2021', ['filter' => 'noauth']);
$routes->get('logout', 'Users::logout');
$routes->get('logoutUnidadAdmin987Gestion2021', 'Users::logoutUnidadAdmin987Gestion2021');
$routes->get('/', 'Users::index', ['filter' => 'noauth']);

// requiere authentication
$routes->match(['get','post'],'register', 'Users::register', ['filter' => 'noauth']);
$routes->match(['get','post'],'profile', 'Users::profile',['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index',['filter' => 'auth']);
$routes->post('dashboard/*', 'Dashboard::index',['filter' => 'auth']);
$routes->get('rendicion', 'Rendicion::index',['filter' => 'auth']);
$routes->get('turnoDependencia', 'TurnoDependencia::index',['filter' => 'auth']);
$routes->get('buscarTramitePersona', 'BuscarTramitePersona::index',['filter' => 'auth']);
$routes->get('tramite/crear', 'Tramite::crear',['filter' => 'auth']);
$routes->get('dashboard', 'Tramite::pago_tramite_comisaria',['filter' => 'auth']);



/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

// Custom 404 view with error message.
$routes->set404Override(function( $message = null )
{
    $data = [
        'title' => 'No existe la pagina.',
        'message' => '¡Lo siento! no se puede encontrar la página que está buscando.'
    ];
    echo view('error_404', $data);
});

