<?php 
namespace App\Controllers;
use App\Models\PruebaModel;
use App\Models\TipoTramiteModel;
use App\Models\MovimientoPago;


class Tramite extends BaseController {

    protected $movimientoPago;

    public function __construct() {
        $this->movimientoPago = new MovimientoPago();
    }

    

    /**
     * Funcion que permite obtener los movimientos de pagos 
     * del idTramite.
     */
    public function get_movimientos($idTramite) {
       
    }

   
}