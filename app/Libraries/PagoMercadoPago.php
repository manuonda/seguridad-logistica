<?php namespace App\Libraries;

use App\Libraries\CurlMercadoPago;


class PagoMercadoPago {
     
    protected $curlMercadoPago;
    protected $urlBase;
    protected $userId;

    public function __construct(){
        $this->curlMercadoPago = new CurlMercadoPago();  
        $this->userId = getenv('USER_ID');    
    }

   /**
    * Funcion que permite realizar la busqueda 
    * de pagos por sus parametros
     */ 
   public function search($params){
       $url = 'v1/payments/search?'.$params;
       $result = $this->curlMercadoPago->search($url);
       return json_decode($result); 
   }

    /**
     * Funcion que permite obtener la informacion 
     * del Pago de MercadoPago a partir 
     * del $id_resultado_pago_online, que corresponde al external_reference en 
     * Mercado Pago
     */
    private function getInformacionPago($externalReference)
    {
        $pagoMercadoPago = new PagoMercadoPago();
        $params = 'external_reference=' . $externalReference;
        $pago = $pagoMercadoPago->search($params);
        return $pago;
    }

   /** Obtengo el movimiento de Mercado Pago si existe retorna el 
    *  movimiento, en caso contrario devuelve null
    */
   public function movimientoMP($id) {
       $pago = $this->getInformacionPago($id);
       if ($pago != null && sizeof($pago->results) == 0) {
          return null;
       } else {
           return  (array) $pago->results[0];;
       }
   }


}
?>