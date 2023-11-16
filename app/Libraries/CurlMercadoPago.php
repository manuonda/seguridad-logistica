<?php namespace App\Libraries;


/**
 * CurlMercadoPago : Funciones base correspondiente a generar las peticiones 
 *                   de tipo curl, a los servidores de MercadoPago
 * @author   : dgarcia
 * @version : 1.0
 */
class CurlMercadoPago {

      public function get($url){
        $accessToken = getenv('ACCESS_TOKEN');
        $url_get = BASE_URL_MP.$url.'?access_token='.$accessToken;
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

      /**
       *  Funcion que permite realizar envio de datos 
       *  para guardar la informacion en MercadoPago
       *  */ 
      public function post($url, $data){
        $accessToken = getenv('ACCESS_TOKEN');
        $url_post = BASE_URL_MP.$url.'?access_token='.$accessToken;
        $ch = curl_init($url_post);
        $data_json = json_encode($data);
        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
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

     /**
      * Funcion de Actualizacion de los datos 
      * mediante el method PUT.
      */
     public function put($url, $data){
      $accessToken = getenv('ACCESS_TOKEN');
      $url_post = BASE_URL_MP.$url.'?access_token='.$accessToken;
      $ch = curl_init($url_post);
      $data_json = json_encode($data);
      // method PUT
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      /* pass encoded JSON string to the POST fields */
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
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

     /**
      * Function de operacion de tipo delete
      * @param: url 
      */
     public function delete($url) {
        $accessToken = getenv('ACCESS_TOKEN');
        $url = BASE_URL_MP.$url.'?access_token='.$accessToken;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* execute request */
        $result = curl_exec($ch);
        /* close cURL resource */
        curl_close($ch);
        return $result;
     }

     /**
      * Search url en donde la url tiene parametros establecidos */ 
     public function search($url) {
      $accessToken = getenv('ACCESS_TOKEN');
      $headers = array(
         'Content-Type: application/json',
         sprintf('Authorization: Bearer %s', $accessToken)
       );
      $url = BASE_URL_MP.$url;
      $ch = curl_init($url);
     
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      /* execute request */
      $result = curl_exec($ch);
      /* close cURL resource */
      curl_close($ch);
      return $result;
     }

}

?>