<?php
namespace App\Controllers;


//use App\Models\TramiteModel;
use App\Models\TramiteContravencionModel;
use App\Models\TipoTramiteModel;
//use App\Models\TramitePersonaModel;
use App\Models\TramitePersonaContravencionModel;
use App\Libraries\PagoBancoMacro;

class ResultadoBancoMacroContravencion extends BaseController
{

  protected $tramiteContravencionModel;
  protected $tipoTramiteModel;

  public function __construct()
  {
    $this->tramiteContravencionModel  = new TramiteContravencionModel();
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
    $data = $this->loadInformation(ESTADO_PAGO_PAGADO, "contravenciones/payment/success", $idTramite, $isPersonaValidada);
    $data['contenido'] ="contravenciones/payment/success";
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
    $data = $this->loadInformation(ESTADO_PAGO_IMPAGO, "contravenciones/payment/failure", $idTramite, $isPersonaValidada);
    $data['contenido'] ="contravenciones/payment/failure";
    $data['id_tramite'] = $idTramite;    
    //var_dump($data);
    echo view("frontend", $data);
  }

  function loadInformation($status, $redirectView, $idTramite, $isPersonaValidada){
    $tramiteContravencionModel = new TramiteContravencionModel();
    $tramitePersonaContravencionModel = new TramitePersonaContravencionModel();
    
    $tramite = $this->tramiteContravencionModel->find($idTramite);
    $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
    //
    //############################################################################
    //
    $pagoBancoMacro = new PagoBancoMacro();
    
    $data=[];

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
        $tramite['fecha_pago'] =  date('Y-m-d H:i:s');
        // $tramite['rendido'] = ; // para la rendicion        
        //$tramite['importe']   = $tipoTramite['precio'];

        //actualiza datos en la tabla pago_contravencion.tramites
        $tramiteContravencionModel->set($tramite);
        $tramiteContravencionModel->where('id_tramite', $idTramite);
        $tramiteContravencionModel->update();
      }
    //$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);

    // if ($tipoTramite != null) {
    //   $data = $this->getDataSegunController($tipoTramite["controlador"], $idTramite);
    //   $data['title']          = $tipoTramite["controlador_title"];
    //   $data['contenidopaso1'] = $tipoTramite['controlador_view'];
    // }

    // $url = base_url();     
    // $data['url'] = $url.'/pagoContravencion';
    
    // asigno el status para ver que operacion cargar de resultado a mostrar
//     $data['status'] = $status;
//     $data['id_tramite'] = $idTramite;
// //     var_dump($idTramite);
//     $data['isPersonaValidada'] = $isPersonaValidada;

//     $data['codigo_operacion'] =  $this->request->getVar('collection_id');;
//     $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
//     $data['tramite'] = $tramite;
//     $data['ua'] = $this->request->getUserAgent();


//     $titular = $TramitePersonaContravencionModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();

//     $data['nombre'] = $titular['nombre'];
//     $data['apellido'] = $titular['apellido'];
//     $data['documento'] = $titular['documento'];

//     $data['contenidopaso4'] = $redirectView;
//     $data['contenido'] = "certificado/resultado_macro";
    return $data;
   
  }
 }
}

?>