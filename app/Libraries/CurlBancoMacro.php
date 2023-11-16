<?php namespace App\Libraries;

 use App\Libraries\Util;
 use App\Libraries\UtilBancoMacro;

 use Exception;

/**
 * CurlBancoMacro : Funciones base correspondiente a generar las peticiones 
 *                   de tipo curl, a los servidores de BancoMacro
 * @author   : dgarcia
 * @version :  1.0
 */
class CurlBancoMacro {

      protected $baseUrl;
      
      public function __construct()
      {
          $util = new Util();
          $this->baseUrl = $util->getApiBaseURLBancoMacro();
      }
      public function login() {

        
        $utilBancoMacro = new UtilBancoMacro();
        $identificadorComercio =  $utilBancoMacro->getIdentificadorComercio() ;//getenv('IDENTIFICADOR_COMERCIO');
        $frase= $utilBancoMacro->getFrase();
        $result = "";
        $data = array(
          "guid" =>  $identificadorComercio,
          "frase" =>  $frase 
        );
          $url_post = $this->baseUrl."sesion";
          $ch = curl_init($url_post);
          $data_json = json_encode($data);
          /* pass encoded JSON string to the POST fields */
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
          /* set the content type json */
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                     'Content-Type:application/json'
                  ));
          curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
          /* set return type json */
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          /* execute request */
          $result = curl_exec($ch);
          $error_msg ="";
          if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
//             var_dump($error_msg);
          }         
          /* close cURL resource */
          curl_close($ch);
      
        return $result;

       
      }

      /**
       * Function get de datos correspondientes al listado o
       */
      public function list($token){
       
        $url_get =  $this->baseUrl.'transactions';
//         var_dump($url_get);
        $ch = curl_init($url_get);
        $authorization = 'Authorization: Bearer '.$token;
        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   'Content-Type:application/json', $authorization
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
       * Get transaction
       */
      public function get($idTransaction, $token) {

        $url_get =  $this->baseUrl.'transaction/'.$idTransaction;
        $ch = curl_init($url_get);
        $authorization = 'Authorization: Bearer '.$token;
        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   'Content-Type:application/json', $authorization
                ));
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       /* execute request */
        $result = curl_exec($ch);
        $error_msg ="";
        if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
        }  
        /* close cURL resource */
        curl_close($ch);
        return $result;
      }

   

}

?>