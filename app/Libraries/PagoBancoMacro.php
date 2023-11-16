<?php namespace App\Libraries;


use App\Libraries\Util;
use App\Libraries\CurlBancoMacro;
use App\Libraries\UtilBancoMacro;
class PagoBancoMacro {
     
    protected $curlBancoMacro;
    protected $urlBase;
    protected $baseURL;
    protected $utilBancoMacro;


    public function __construct(){
        $util = new Util();
        $this->utilBancoMacro =  new UtilBancoMacro();
        $this->curlBancoMacro = new CurlbancoMacro();
        $this->baseURL = $util->getApiBaseURLBancoMacro();
    }

  
   /**
    * Funcion que permite loguearse en el sistema de Banco 
    * Macro 
    * */ 
   public function login(){
       $result = $this->curlBancoMacro->login();
       return json_decode($result); 
   }


   public function list($token) {
       $result = $this->curlBancoMacro->list($token);
       return json_decode($result);
   }
    
    /**
     * Funcion que permite obtener la informacion del pago 
     * en referencia a BancoMacro
     */
    public function get($idTransaction){
      $token = $this->utilBancoMacro->getToken();  
      if ( $token == null ) {
          // obtengo informacion de token de banco macro
		  $resultadoBancoMacro = $this->login();
          if ( $resultadoBancoMacro != null &&  $resultadoBancoMacro->status) {
            $user["token"] = $resultadoBancoMacro->data;
            $user = session()->get('user');
            $user["token"] = $resultadoBancoMacro->data;
            session()->set("user",$user);     
          }
       }

      $result = $this->curlBancoMacro->get($idTransaction, $token);
      return json_decode($result);
    }


    public function getWithToken($idTransaction, $token) {
        $result = $this->curlBancoMacro->get($idTransaction, $token);
        return json_decode($result);
    }



}
?>