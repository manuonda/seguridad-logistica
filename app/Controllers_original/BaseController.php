<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */


use CodeIgniter\Controller;


use Exception;
use DateTime;
# definition de Mercado Pago
use MercadoPago;
use App\Models\TipoDocumentoModel;
use App\Models\PersonalModel;
use App\Models\UserModel;
use App\Models\TramiteRebaModel;

MercadoPago\SDK::setAccessToken(getenv('ACCESS_TOKEN'));


class BaseController extends Controller
{

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url'];

    protected $movimientoPago;
    protected $tipoPagoModel;
    protected $tipoTramiteModel;
    protected $utilBancoMacro;
    protected $utilMercadoPago;
    protected $session;
    protected $userInSession;


    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {

        
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.:
         $this->session = \Config\Services::session();
         if(session()->get('isLoggedIn') == NULL ){
            return redirect()->to('/caducado'); // FIXME
//             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
      
    }


    public function __construct()
    {
    
        $this->session = session();
        $this->userInSession = $this->session->get('user');
        if(session()->get('isLoggedIn') == NULL){
            return redirect()->to('/caducado'); // FIXME
//             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


   
}
