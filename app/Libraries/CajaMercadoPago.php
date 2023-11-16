<?php namespace App\Libraries;

use App\Libraries\CurlMercadoPago;

class CajaMercadoPago {
     
     protected $curlMercadoPago;
     protected $urlBase;
     protected $userId;

     public function __construct(){
         $this->curlMercadoPago = new CurlMercadoPago();  
         $this->userId = getenv('USER_ID');    
     }

    /**
     * Funciona que permite guardar
     * el registro
     */
    public function save($data){
        $url = '/pos'; 
        $result = $this->curlMercadoPago->post($url, $data);
        return json_decode($result); 
    }
    
    /**
     * Funcion que permite actualizar una sucursal
     * @param $id
     * @param $data
     */
    public function update($id = null , $data ){
        $url = '/pos/'.$id; 
        $result = $this->curlMercadoPago->put($url, $data);
        return json_decode($result);
    }
    
    /**
     * Funcion que permite obtener una determinada
     * caja por el $idCaja
     * @param: $id
     */
    public function get($id = null ){
       $url = '/pos/'.$id;
       $result = $this->curlMercadoPago->get($url);
       return json_decode($result);    
    }

    public function find($external_id = null, $user_id = null ) {
        $curlMercadoPago = new CurlMercadoPago();
        $url = '/pos';
        $result = $this->curlMercadoPago->get($url);
        return json_decode($result); 
    }

    /**
     * Funcion que permite eliminar una determinada 
     * caja a partir del id(correspondiente a id mercado pago)
     */
    public function delete($id = null ){
        $url = '/pos/'.$id; 
        $result = $this->curlMercadoPago->delete($url);
        return $result;
    }
}
?>