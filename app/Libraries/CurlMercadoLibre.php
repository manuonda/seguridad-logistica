<?php namespace App\Libraries;

class CurlMercadoLibre {

      public function get($url){
        $url_get = BASE_URL_ML.$url;
        $ch = curl_init($url_get);
        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   'Content-Type:application/json'
                ));
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       /* execute request */
        $result = curl_exec($ch);
        /* close cURL resource */
        curl_close($ch);
        return $result;
      }   


}

?>