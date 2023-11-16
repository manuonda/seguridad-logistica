<?php namespace App\Libraries;

use App\Libraries\CurlMercadoPago;

class SucursalMercadoPago {
     
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
        $url = '/users/'.$this->userId.'/stores'; 
        $result = $this->curlMercadoPago->post($url, $data);
        return json_decode($result); 
    }
    
    /**
     * Funcion que permite actualizar una sucursal
     * @param $id
     * @param $data
     */
    public function update($id = null , $data ){
        $url = '/users/'.$this->userId.'/stores/'.$id; 
        $result = $this->curlMercadoPago->put($url, $data);
        return json_decode($result);
    }
    
    /**
     * Funcion que permite obtener una determinada
     * sucursal por el $idSucursal
     * @param: $idSucursal
     */
    public function get($idSucursal = null ){
       $url = '/stores/'.$idSucursal;
       $result = $this->curlMercadoPago->get($url);
       return json_decode($result);    
    }

    public function find($external_id = null, $user_id = null ) {
        $curlMercadoPago = new CurlMercadoPago();
        $url = '/users/'.$this->userId.'/stores/search';
        $result = $this->curlMercadoPago->get($url);
        return json_decode($result); 
    }

    /**
     * Funcion que permite eliminar una determinada 
     * sucursal a partir del id(correspondiente a id mercado pago)
     */
    public function delete($id = null ){
        $url = '/users/'.$this->userId.'/stores/'.$id; 
        $result = $this->curlMercadoPago->delete($url);
        return $result;
    }
}
?>