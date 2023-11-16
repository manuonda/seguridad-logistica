<?php namespace App\Libraries;

use App\Libraries\CurlMercadoLibre;

class ApiPublicMercadoLibre {
    
    /** Funcion que permite obtener 
     *  las citys a partir del State
     * @state: Referencia a la provincia en MercadoLibre
     *  */ 
    public function getCitys($state) {
        $curlML = new CurlMercadoLibre();
        // STATE_ID_MP : Corresponde al state de Jujuy  
       $url_citys = 'states/'.STATE_ID_MP;
       $result = $curlML->get($url_citys); 
       return $result;
    } 

     
    /***
     * Funcion que permite obtener los estates(provincias)
     * a partir del country
     * @param: $country_id
     */
    public function getStates($country_id){

    }

}
?>