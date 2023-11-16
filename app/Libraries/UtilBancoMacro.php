<?php
namespace App\Libraries;

use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\TramitePersonaModel;
use App\Libraries\AESEncrypter;
use App\Libraries\SHA256Encript;
use App\Models\TramiteRebaModel;
use App\Models\CategoriaRebaModel;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
//***************PAGO FALTA CONTRAVENCIONAL POR MACROCLICK */
use App\Models\TramiteContravencionModel;
use App\Models\TramitePersonaContravencionModel;
use App\Models\TramiteContravencionDetalleModel;

class UtilBancoMacro {


    protected $session;

    public function __construct() {
        $this->session = session();
    }
        

    /**
     * Funcion que permite obtener la url base de Banco Macro
     * en function del API 
     */
    public function getApiBaseURLBancoMacro(){
        $entorno = getenv("ENTORNO");
        $baseUrl = "";
        if ($entorno == "DEV") {
          $baseUrl =getenv("API_BANCO_MACRO_DEV");
        } else if($entorno == "PROD") {
          $baseUrl = getenv("API_BANCO_MACRO_PROD");  
        }
        return $baseUrl;
    }


    /**
     * Funcion que permite generar el pago de Banco Macro 
     * retornando los datos en un array de la informacion del mismo
     */
    public function generarPagoBancoMacro($idTramite, $isPersonaValidada ,$fromUrl) {

        $entorno = getenv("ENTORNO");
        $tramiteModel = new  TramiteModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $tipoTramiteModel = new TipoTramiteModel();
        $tramiteRebaModel = new TramiteRebaModel(); 
        $categoriaRebaModel = new CategoriaRebaModel();
        $tramite = $tramiteModel->find($idTramite);
         
        // Actualizo el tramite indicando el tipo de operacion en este caso 
        // Banco Macro  
        $tramite['id_tipo_pago'] = TIPO_PAGO_ONLINE;
        $tramite['referencia_pago'] = BANCO_MACRO;
        $titular = $tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', INT_UNO)->first();
        $tramiteModel->update($idTramite, $tramite);
        //
        $hash = new SHA256Encript();
        $ipAddress = $this->getRealIpAddr();
        $sucursal = ""; //enviar vacío si no se tiene una sucursal configurada en PlusPagos
        $secretKey =  $this->getSecretKey(); 
        $comercio  =  $this->getIdentificadorComercio(); 
        $price = 0;

        // Obtenemos el tramite para obtener el valor del tramite 
        $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
      

       $productos = [];
        /**
         * NOta 1 : se multiplica por 100 puesto que Banco Macro utiliza los 2 ultimos registros, como valor decimal 
         */

        // Tipo de Tramite Categoria Reba
       if ($tipoTramite!= null && $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {

             $tramiteReba = $tramiteRebaModel->where('id_tramite',$idTramite)->first();
             $categoriaReba = $categoriaRebaModel->find($tramiteReba['id_categoria_reba']);
             $price = floatVal($categoriaReba['precio']) ;
             $categoriaUnoPrecio = floatVal($tramiteReba['precio_uno']);
             $categoriaDosPrecio = floatVal($tramiteReba['precio_dos']);
             $categoriaTresPrecio = floatVal($tramiteReba['precio_tres']);
             
             $price = ($categoriaUnoPrecio +  $categoriaDosPrecio + $categoriaTresPrecio)  *  100; 
             $productos[] = $tramiteReba['concepto_uno'];
             $productos[] = $tramiteReba['concepto_dos'];
             $productos[] = $tramiteReba['concepto_tres'];
             
             // pago reba desde el dashboard
             if ($fromUrl == "dashboard") {
                $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/resultadobancomacroreba/';  
             }else if ($fromUrl == "wizard") {
                $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/resultadobancomacroreba/';
             }
             
        } else if ($tipoTramite!= null && $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
            $price = 0;
          
            if ( $tipoTramite['precio'] != null && $tipoTramite['precio'] != 0) {
                $price = $price  +  $tipoTramite['precio'];
            }
            if ($tipoTramite['importe_adicional'] != null &&  $tipoTramite['importe_adicional'] != "") {
                $price  = $price + $tipoTramite['importe_adicional'] ;
            }
            $price = floatval($price) *  100;
            $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/resultadobancomacro/';

            $productos[] ='Certificado Planilla Prontuarial';
            $productos[] ='Certificaciòn de Copia';

        } else if($tipoTramite != null &&  $tipoTramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA &&  $tipoTramite['precio'] != null && $tipoTramite['precio'] != "") {
            // OTRO TIPO DE TRAMITE
            $price = floatval($tipoTramite['precio']) *  100;
            $productos[]= $tipoTramite['tipo_tramite'];
            //  establecer el usuario redireccionar
            $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/resultadobancomacro/';        
        } 

      
        $amount = $price;
        $hashGenerate = $hash->Generate($ipAddress, $secretKey , $comercio , $sucursal , $amount);
         

         // Back URLS
        $aes = new AESEncrypter();
        $callbackSuccess= $base_url . 'success?idTramite='.$idTramite.'&isPersonaValidada='.$isPersonaValidada;
        $callbackCancel =  $base_url . 'failure?idTramite='.$idTramite.'&isPersonaValidada='.$isPersonaValidada;

     
        $monto = $price;
        $sucursalComercio = "";
        $callbackEncriptada = $aes -> EncryptString($callbackSuccess, $secretKey);
        $cancelEncriptada = $aes -> EncryptString($callbackCancel, $secretKey);

       
        $montoEncriptado = $aes -> EncryptString($monto, $secretKey);
        $sucursalEncriptada = $aes -> EncryptString($sucursalComercio, $secretKey);
        $data= [ 

            "ipAddress" => $ipAddress,
            "comercio"  => $comercio,
            "hash_generate" => $hashGenerate,
            "transaction_comercio_id" => $idTramite,
            "call_back"     => $callbackEncriptada,
            "call_cancel"        => $cancelEncriptada,
            // "call_back_alternativa"     => $callbackEncriptadaAlternativa,
            // "call_cancel_alternativa"        => $cancelEncriptadaAlternativa,            
            "monto"         => $montoEncriptado,
            "productos"      => $productos, 
            "titular_cuit"       => $titular['documento'],
            "titular_nombre_apellido" => $titular['nombre']."-".$titular["apellido"] ,
            "sucursal_comercio" => $sucursalEncriptada,
            "precio" => $price
        ];
        return $data;
    }

    /**
     * Funcion que permite generar el pago de Banco Macro para Contravenciones
     * retornando los datos en un array de la informacion del mismo
     */
    public function generarPagoBancoMacroContravencion($idTramite, $isPersonaValidada ,$fromUrl){
            $entorno = getenv("ENTORNO");
            //
            //
            $hash = new SHA256Encript();
            $ipAddress = $this->getRealIpAddr();
            $sucursal = ""; //enviar vacío si no se tiene una sucursal configurada en PlusPagos
            $secretKey =  $this->getSecretContravencionKey(); //PEDIR DATOS DE LA CUENTA CORRIENTE PROPIA PARA CONTRAVENCIONES
            $comercio  =  $this->getIdentificadorContravencionComercio(); //PEDIR DATOS DE LA CUENTA CORRIENTE PROPIA PARA CONTRAVENCIONES
            $price = 0;
            //
            //
            $tramiteContravencionModel = new  TramiteContravencionModel();//pago_contravencion.tramites
            $tramitePersonaContravencionModel = new TramitePersonaContravencionModel();//pago_contravencion.tramite_personas
            $tramiteContravencionDetalleModel = new TramiteContravencionDetalleModel();//pago_contravencion.tramites_contravencion
            //
            $tramite = $tramiteContravencionModel->find($idTramite);
            //var_dump($tramite);
            $tramite['id_tipo_pago'] = TIPO_PAGO_ONLINE;//2-PAGO ONLINE 1-PAGO CONTADO
            $tramite['referencia_pago'] = BANCO_MACRO;//TEXTO QUE DICE POR BANCO
            
            $tramiteContravencionModel->update($idTramite, $tramite);
            //
            $titular = $tramitePersonaContravencionModel->where('id_tramite', $idTramite)->where('es_titular_tramite', INT_UNO)->first();//datos del titular del tramite
            //var_dump($tramiteContravencionModel);            

            $tramiteContravencion = $tramiteContravencionDetalleModel->where('id_tramite',$idTramite)->first();//trae datos del detalle del pago
            //var_dump($tramiteContravencion);
            $preciocontravencion = floatVal($tramiteContravencion['precio_uno']);//monto                         
            $price = $preciocontravencion   *  100; 
            $productos[] = $tramiteContravencion['concepto_uno'];//concepto
            //var_dump($price);
             // 
             $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/resultadobancomacrocontravencion/'; 
             //var_dump('0');
            //#######################################################################################
            $amount = $price;
            //var_dump(0.1);
            
            $hashGenerate = $hash->Generate($ipAddress, $secretKey , $comercio , $sucursal , $amount);
            //var_dump('1');

            // Back URLS
            $aes = new AESEncrypter();
            $callbackSuccess= $base_url . 'success?idTramite='.$idTramite.'&isPersonaValidada='.$isPersonaValidada;
            $callbackCancel =  $base_url . 'failure?idTramite='.$idTramite.'&isPersonaValidada='.$isPersonaValidada;                           
            $monto = $price;
            $sucursalComercio = "";

            $callbackEncriptada = $aes -> EncryptString($callbackSuccess, $secretKey);
            $cancelEncriptada = $aes -> EncryptString($callbackCancel, $secretKey);                    
            $montoEncriptado = $aes -> EncryptString($monto, $secretKey);
            $sucursalEncriptada = $aes -> EncryptString($sucursalComercio, $secretKey);
            //var_dump('4'); 
            $data= [ 

                "ipAddress" => $ipAddress,
                "comercio"  => $comercio,
                "hash_generate" => $hashGenerate,
                "transaction_comercio_id" => $idTramite,
                "call_back"     => $callbackEncriptada,
                "call_cancel"        => $cancelEncriptada,
                // "call_back_alternativa"     => $callbackEncriptadaAlternativa,
                // "call_cancel_alternativa"        => $cancelEncriptadaAlternativa,            
                "monto"         => $montoEncriptado,
                "productos"      => $productos, 
                "titular_cuit"       => $titular['documento'],
                "titular_nombre_apellido" => $titular['nombre']."-".$titular["apellido"] ,
                "sucursal_comercio" => $sucursalEncriptada,
                "precio" => $price
            ];
            //var_dump('5');
            return $data;            
    }

    public function getSecretContravencionKey(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("SECRET_KEY_DEV");
        } else if($entorno == "PROD"){
            return getenv("SECRET_KEY_CONTRAVENCION_PROD");
        }
    }

    public function getIdentificadorContravencionComercio(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("IDENTIFICADOR_COMERCIO_DEV");
        } else if($entorno == "PROD"){
            return getenv("IDENTIFICADOR_COMERCIO_CONTRAVENCION_PROD");
        }
    }

    /**
     * Funcion que permite obtener la Ip Real
     */
    private function getRealIpAddr()
    {

        return "54.211.235.146";
        // if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        //     return $_SERVER["HTTP_CLIENT_IP"];
        // if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        //     return $_SERVER["HTTP_X_FORWARDED_FOR"];
        // return $_SERVER["REMOTE_ADDR"];
    }


    /*
     * Funcion que permite obtener el token del usuario 
     * en session
     */
    public function getToken() {
        $user = $this->session->get('user');
        $token = $user['token'];     
        return $token;   
    }

    public function getSecretKey(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("SECRET_KEY_DEV");
        } else if($entorno == "PROD"){
            return getenv("SECRET_KEY_PROD");
        }
    }

    public function getIdentificadorComercio(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("IDENTIFICADOR_COMERCIO_DEV");
        } else if($entorno == "PROD"){
            return getenv("IDENTIFICADOR_COMERCIO_PROD");
        }
    }

    public function getUrlBancoMacro(){        
        $entorno = getenv("ENTORNO");        
        if ( $entorno =="DEV") {
            return getenv("URL_BANCO_MACRO_DEV");
        } else if($entorno == "PROD"){
            return getenv("URL_BANCO_MACRO_PROD");
        }
    }

    public function getApiBancoMacro(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("API_BANCO_MACRO_DEV");
        } else if($entorno == "PROD"){
            return getenv("API_BANCO_MACRO_PROD");
        }
    }

    public function getFrase(){
        $entorno = getenv("ENTORNO");
        if ( $entorno =="DEV") {
            return getenv("FRASE_DEV");
        } else if($entorno == "PROD"){
            return getenv("FRASE_PROD");
        }
    }



    public function getStatus($statusBancoMacro = "") {
        $status ="";
        if( $statusBancoMacro == "REALIZADA") {
            $status = ESTADO_PAGO_PAGADO;
        } else if ($statusBancoMacro == "PENDIENTE" || $statusBancoMacro == "CREADA"  || $statusBancoMacro == "EN_PAGO") {
            $status = ESTADO_PAGO_PENDIENTE;
        } else { // other transactions status
            $status = ESTADO_PAGO_CANCELADO;
        }
        return $status;
    }

    public function getFormatStatus($statusBancoMacro = "") {
        $status ="";
        if( $statusBancoMacro == "REALIZADA") {
            $status = "<span class='badge badge-primary'><h8>APROBADO</h8></span>";
        } else if ($statusBancoMacro == "PENDIENTE" || $statusBancoMacro == "CREADA"  || $statusBancoMacro == "EN_PAGO") {
            $status = "<span class='badge badge-secondary'><h8>PENDIENTE</h8></span>";
        } else { // other transactions status
            $status = "<span class='badge badge-danger'><h8>IMPAGO</h8></span>";
        }
        return $status;
    }


    public function getStatusFromCode($codeBancoMacro) {
        $estado = "";
        switch($codeBancoMacro) {
            case "REALIZADA" : return ESTADO_PAGO_PAGADO;break;
            case "EXPIRADA"  : return ESTADO_PAGO_IMPAGO;break;
            case "RECHAZADA" : return ESTADO_PAGO_IMPAGO;break;
            case "VENCIDA"   : return ESTADO_PAGO_IMPAGO;break;
            case "CANCELADA" : return ESTADO_PAGO_IMPAGO;break;
            case "CREADA"    : return ESTADO_PAGO_PENDIENTE; break;
        }
    }

    
 }
?>