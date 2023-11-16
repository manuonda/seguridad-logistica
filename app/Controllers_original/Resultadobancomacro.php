<?php
namespace App\Controllers;

use App\Models\TramitePersonaModel;
use App\Models\MovimientoPago;
use App\Models\TramiteModel;
use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\DependenciaModel;
use App\Models\TipoDocumentoModel;
use App\Models\TipoTramiteModel;
use App\Models\TurnoModel;
use App\Libraries\Util;
use App\Libraries\PagoBancoMacro;

class ResultadoBancoMacro extends BaseController
{

  protected $movimientoPago;
  protected $tramiteModel;
  protected $tramitePersonaModel;
  protected $tipoDocumentoModel;
  protected $departamentoModel;
  protected $dependenciaModel;
  protected $localidadModel;
  protected $tipoTramiteModel;

  public function __construct()
  {
    $this->movimientoPago = new MovimientoPago();
    $this->tramiteModel  = new TramiteModel();
    $this->tramitePersonaModel = new TramitePersonaModel();
    $this->tipoDocumentoModel = new TipoDocumentoModel();
    $this->departamentoModel  = new DepartamentoModel();
    $this->dependenciaModel   = new DependenciaModel();
    $this->localidadModel     = new LocalidadModel();
    $this->tipoTramiteModel   = new TipoTramiteModel();
  }

  public function index()
  {
    return view('result');
  }

  /**
   * Funcion que se accede de respuesta 
   * del guardado de mercado pago
   */
  public function success()
  {
    $idTramite = $this->request->getVar('idTramite');
    $isPersonaValidada = $this->request->getVar('isPersonaValidada');
    $data = $this->loadInformation(ESTADO_PAGO_PAGADO, "payment/success", $idTramite, $isPersonaValidada);
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
    $data = $this->loadInformation(ESTADO_PAGO_IMPAGO, "payment/failure", $idTramite, $isPersonaValidada);
    $data['id_tramite'] = $idTramite;
    echo view("frontend", $data);
  }

 

  /**
   * Funcion encargada de cargar la vista del controllador de la operacion , 
   * title y los valores de personas referenter al tramite
   */
  function loadInformation($status, $redirectView, $idTramite, $isPersonaValidada){
    $pagoBancoMacro = new PagoBancoMacro();
    $tramiteModel = new TramiteModel();
    $dependenciaModel = new DependenciaModel();

    $tramite = $this->tramiteModel->find($idTramite);
    $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
    $resultadoBancoMacro = $pagoBancoMacro->login();
    $transaction = null ;
    if ($resultadoBancoMacro->status) {
        $token = 	$resultadoBancoMacro->data;        
        $transaction = $pagoBancoMacro->getWithToken($idTramite, $token);
        //var_dump($transaction);
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

      $turnoModel = new TurnoModel();
      $data['turno'] = $turnoModel->where('id_tramite', $idTramite)->first();
      $data['util'] = new Util();
      $data['contenidopaso2'] = "turno";
      $data['contenido'] = "wizard/wizard";
    }

    $url = "";
    if ( $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
       $url = base_url().'/tramiteReba/buscar';
    } else {
       $url = base_url(); 
    }
   
    $data['url'] = $url;

    $data['dependencia'] = null;
    if(!empty($tramite['id_dependencia'])) {
        $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
        $data['dependencia'] = $dependencia['dependencia'];
    } // FIXME: si no hay comisaria da error
    
    // asigno el status para ver que operacion cargar de resultado a mostrar
    $data['status'] = $status;
    $data['id_tramite'] = $idTramite;
//     var_dump($idTramite);
    $data['isPersonaValidada'] = $isPersonaValidada;

    $data['codigo_operacion'] =  $this->request->getVar('collection_id');;
    $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
    $data['tramite'] = $tramite;
    $data['ua'] = $this->request->getUserAgent();

    $data['contenidopaso4'] = $redirectView;
    $data['contenido'] = "wizard/wizard_view";
    //var_dump($data);
    return $data;
  }
 }
}
