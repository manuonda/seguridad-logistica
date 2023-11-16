<?php namespace App\Libraries;

use App\Libraries\CurlMercadoPago;

/**
 * Class correspondiente al payment 
 * de Mercado Pago , a partir de algunos metodos 
 * se puede obtener informacion
 */

class PaymentMercadoPago {
     
    protected $curlMercadoPago;
    protected $urlBase;
    protected $userId; 

    public function __construct(){
        $this->curlMercadoPago = new CurlMercadoPago();  
        $this->userId = getenv('USER_ID');  

    }

   
    /**
     * Funcion que permite obtener el pago 
     * a traves del codigo de operacion realizada
     *  */ 
    public function get($codigoOperacion){
      $url = '/v1/payments/'.$codigoOperacion;
      $result = $this->curlMercadoPago->get($url);
      return json_decode($result);    
   }
}
?>