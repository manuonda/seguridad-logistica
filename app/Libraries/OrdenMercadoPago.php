<?php namespace App\Libraries;

use App\Libraries\CurlMercadoPago;


class OrdenMercadoPago {
     
    protected $curlMercadoPago;
    protected $urlBase;
    protected $userId;

    public function __construct(){
        $this->curlMercadoPago = new CurlMercadoPago();  
        $this->userId = getenv('USER_ID');    
    }

   /**
    * Funciona que permite guardar
    * el registro correspondiente  a la orden generada
    * @param : user_id 
    * @param : external_store_id : 
    * @param: 
    */
   public function save($data ,$external_store_id , $external_pos_id){
  
       $url = '/instore/qr/seller/collectors/'.$this->userId.'/stores/'.$external_store_id.'/pos/'.$external_pos_id.'/orders';
       $result = $this->curlMercadoPago->put($url, $data);
       return json_decode($result); 
   }
   
 
   
   /**
    * Funcion que permite obtener una determinada
    * orden
    * @param: $data
    */
   public function get($data){
      $url = '/instore/qr/seller/collectors/'.$this->userId.'/pos/'.$data['external_post_id'].'/orders';
      $result = $this->curlMercadoPago->get($url);
      return json_decode($result);    
   }

   /**
     * Funcion que permite eliminar una determinada 
     * orden correspondiente a una sucural y caja
     * @param : user_id => correspondiente 
     * @param:  External_id de la caja/punto de venta, definido por el integrador. (Obligatorio)
    */
   public function delete($data){
       $url = 'instore/qr/seller/collectors/'.$this->userId.'/pos/'.$data['external_post_id'].'/orders';
       $result = $this->curlMercadoPago->delete($url);
       return $result;
   }
}
?>