<?php
namespace App\Controllers;


use App\Models\TramiteModel;
use App\Models\TipoTramiteModel;
use App\Models\TramitePersonaModel;
use App\Libraries\PagoBancoMacro;

class ResultadoBancoMacroReba extends BaseController
{

  protected $tramiteModel;
  protected $tipoTramiteModel;

  public function __construct()
  {
    $this->tramiteModel  = new TramiteModel();
    $this->tipoTramiteModel   = new TipoTramiteModel();
  }

 
   /**
   * Funcion que se accede de respuesta 
   * del guardado de mercado pago
   */
  public function success()
  {
    $idTramite = $this->request->getVar('idTramite');
    $isPersonaValidada = $this->request->getVar('isPersonaValidada');
    $data = $this->loadInformation(ESTADO_PAGO_PAGADO, "payment_reba/success", $idTramite, $isPersonaValidada);
    $data['id_tramite'] = $idTramite;
    echo view("frontend", $data);
  }
  /* Se cancelo el pago realizado por la persona
   * o por la tarjeta de credito
  */
  public function failure()
  {
    
    $idTramite = $this->request->getVar('idTramite');
    $isPersonaValidada = $this->request->getVar('isPersonaValidada');
    $data = $this->loadInformation(ESTADO_PAGO_IMPAGO, "payment_reba/failure", $idTramite, $isPersonaValidada);
    $data['id_tramite'] = $idTramite;
    echo view("frontend", $data);
  }


  function loadInformation($status, $redirectView, $idTramite, $isPersonaValidada){
    $pagoBancoMacro = new PagoBancoMacro();
    $tramiteModel = new TramiteModel();
    $tramitePersonaModel = new TramitePersonaModel();
    $data=[];

    $tramite = $this->tramiteModel->find($idTramite);
    $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
    $resultadoBancoMacro = $pagoBancoMacro->login();
    $transaction = null ;
    if ($resultadoBancoMacro->status) {
        $token = 	$resultadoBancoMacro->data;        
        $transaction = $pagoBancoMacro->getWithToken($idTramite, $token);
       // var_dump($transaction);
    }
    
    if ($transaction && $transaction != null) {
      if ($transaction->status) {
      
        $tramite['estado_pago'] = $status;
        $tramite['estado_pago_entidad'] = $transaction->data->estado;
        $tramite['mensaje_estado_pago_entidad'] = $transaction->message;
         
        // $tramite['rendido'] = ; // para la rendicion
        $tramite['fecha_pago'] =  date('Y-m-d H:i:s');
        $tramite['importe']   = $tipoTramite['precio'];

        $tramiteModel->set($tramite);
        $tramiteModel->where('id_tramite', $idTramite);
        $tramiteModel->update();
      }
    $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);

    if ($tipoTramite != null) {
      $data = $this->getDataSegunController($tipoTramite["controlador"], $idTramite);
      $data['title']          = $tipoTramite["controlador_title"];
      $data['contenidopaso1'] = $tipoTramite['controlador_view'];
    }

    $url = base_url(); 
    
    $data['url'] = $url.'/descargarCertificadoReba';
    
    // asigno el status para ver que operacion cargar de resultado a mostrar
    $data['status'] = $status;
    $data['id_tramite'] = $idTramite;
//     var_dump($idTramite);
    $data['isPersonaValidada'] = $isPersonaValidada;

    $data['codigo_operacion'] =  $this->request->getVar('collection_id');;
    $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
    $data['tramite'] = $tramite;
    $data['ua'] = $this->request->getUserAgent();


    $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();

    $data['nombre'] = $titular['nombre'];
    $data['apellido'] = $titular['apellido'];
    $data['documento'] = $titular['documento'];

    $data['contenidopaso4'] = $redirectView;
    $data['contenido'] = "certificado/resultado_macro";
    return $data;
   
  }


 }


}

?>
