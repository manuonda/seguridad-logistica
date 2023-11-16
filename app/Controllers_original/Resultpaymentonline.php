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
use App\Libraries\UtilMercadoPago;

use Exception;


class Resultpaymentonline extends BaseController
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
    $data = $this->loadInformation(ESTADO_PAGO_PAGADO,"payment/success");
    echo view("frontend", $data);
  }
  /* Se cancelo el pago realizado por la persona
   * o por la tarjeta de credito
  */
  public function failure()
  {  $data = $this->loadInformation(ESTADO_PAGO_CANCELADO,"payment/failure");
     echo view("frontend", $data);
  }

  /**
   * La operacion se realizo para pagar por rapipago o pagofacil
   */
  public function pending(){
    $data = $this->loadInformation(ESTADO_PAGO_PENDIENTE,"payment/pending");
    echo view("frontend", $data);
  }

  /**
   * La operacion se realizo para pagar en comisaria
   */
  public function comisaria(){
    $data = $this->loadPagoComisaria("PAGO_COMISARIA","payment/comisaria");
    echo view("frontend", $data);   
  }

  public function novotacion(){
    $data = $this->loadNoVotacion("PAGO_COMISARIA","payment/novotacion");
    echo view("frontend", $data);   
  }


  /**
   * Funcion encargada de cargar la vista del controllador de la operacion , 
   * title y los valores de personas referenter al tramite
   */
  function loadInformation($status ,$redirectView)
  {

    $tramiteModel = new TramiteModel();
    $utilMercadoPago = new UtilMercadoPago();
  

    // ID del pago de Mercado Pago
    $data['collection_id'] = $this->request->getVar('collection_id');
    // Estado del pago. Por ejemplo: approved para un pago aprobado o pending para un pago pendiente
    $collection_status  = $this->request->getVar('collection_status');
    $data['collection_status'] = $collection_status;
    // Valor del campo external_reference que hayas enviado a la hora de crear la preferencia de pago
    $data['external_reference'] = $this->request->getVar('external_reference');
    // Tipo de pago. Por ejemplo: credit_card para tarjetas de crédito o ticket para medios de pago en efectivo
    $payment_type       = $this->request->getVar('payment_type');
    $data['payment_type']  = $payment_type;
    // ID de la orden de pago generada en Mercado Pago
    $data['merchant_order_id']  = $this->request->getVar('merchant_order_id');
    // ID de la preferencia de pago de la que se está retornando
    $data['preference_id']      = $this->request->getVar('preference_id');
    // ID del país de la cuenta de Mercado Pago del vendedor. Por ejemplo: MLA para Argentina
    $data['site_id']            = $this->request->getVar('site_id');
    // Valor aggregator
    $data['processing_mode']    = $this->request->getVar('processing_mode');
    // Valor null
    $data['merchant_account_id'] = $this->request->getVar('merchant_account_id');
    // external_reference 
    $data['external_reference']  = $this->request->getVar('external_reference');


    $idMovimientoPago = $this->request->getVar('external_reference');
    $movimientoPago = $this->movimientoPago->find($idMovimientoPago);

    $idTramite = null;
    $tramite = null;
    $tipoTramite = null;

    if ($movimientoPago) {
      // Obtengo el resultado del Pago Online 
      // puesto que es por operacion y no por el $idTramite
      $idTramite = $movimientoPago["id_tramite"];
      $tramite  = $this->tramiteModel->find($idTramite);
      $tipoTramite = $this->tipoTramiteModel->find($tramite["id_tipo_tramite"]);

      $this->movimientoPago->set($data);
      $this->movimientoPago->where('id', $data['external_reference']);
      $this->movimientoPago->update();
    } else {
      $this->resultPaymentModel->save($data);
    }

    if($idTramite != null && $idTramite != "") {
     
      $tramite = $tramiteModel->find($idTramite);
      $tramite['estado_pago'] = $utilMercadoPago->getStatus($collection_status);
      $tramite['estado_pago_entidad'] = $collection_status;
      $tramite['mensaje_estado_pago_entidad'] = $utilMercadoPago->getMessageEstadoPago($collection_status);
      
      $tramiteModel->set($tramite);
      $tramiteModel->where('id_tramite', $idTramite);
      $tramiteModel->update();
    }

    if($tipoTramite != null) {
       $data = $this->getDataSegunController($tipoTramite["controlador"] , $idTramite);
       $data['title']          = $tipoTramite["controlador_title"];
       $data['contenidopaso1'] = $tipoTramite['controlador_view'];
       $data['contenido'] = "wizard/wizard_view";
    }

   
    // asigno el status para ver que operacion cargar de resultado a mostrar
    $data['status'] = $status;
    $data['id_tramite'] = $idTramite;

    $data['codigo_operacion'] =  $this->request->getVar('collection_id');;
    $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
    $data['tramite'] = $tramite;
   
    $data['contenidopaso4'] = $redirectView;
    $data['contenido'] = "wizard/wizard_view";
    return $data;
  }


    /**
   * Funcion encargada de cargar la vista del controllador de la operacion , 
   * title y los valores de personas referenter al tramite
   */
  function loadPagoComisaria($status ,$redirectView) {
      $dependenciaModel = new DependenciaModel();
      $turnoModel = new TurnoModel();
      
        $idTramite = $this->request->getVar('idTramite');
        $isPersonaValidada = $this->request->getVar('isPersonaValidada');
        $idDependenciaPago = $this->request->getVar('idDependenciaPago');
        $tramite = $this->tramiteModel->find($idTramite);
        $tipoTramite = null;
        if ($tramite ) {
          $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
         
//           $tramite['fecha_pago'] =  date('Y-m-d H:i:s');
//           $tramite['importe_contado']   = $tipoTramite['precio'];
          if(!empty($isPersonaValidada) && $isPersonaValidada==="true") {
              if(!empty($idDependenciaPago)) {
                  $tramite['id_dependencia'] = $idDependenciaPago;
              }
          }
          $this->tramiteModel->set($tramite);
          $this->tramiteModel->where('id_tramite', $idTramite);
         
          $this->tramiteModel->update();
        }

        if($tipoTramite != null) {
            $data = $this->getDataSegunController($tipoTramite["controlador"] , $idTramite);
            $data['title']          = $tipoTramite["controlador_title"];
            $data['contenidopaso1'] = $tipoTramite['controlador_view'];
            
            $data['util'] = new Util();
            $data['contenidopaso2'] = "turno";
            $data['contenido'] = "wizard/wizard";
        }
        
        $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
        $data['dependencia'] = $dependencia['dependencia'];
        $data['turno'] = $turnoModel->where('id_tramite', $idTramite)->first();
    
          // asigno el status para ver que operacion cargar de resultado a mostrar
        $data['status'] = $status;
        $data['isPersonaValidada'] = $isPersonaValidada;
    
        $data['codigo_operacion'] =  $this->request->getVar('collection_id');
//         $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
        $data['id_tramite'] = $tramite['id_tramite'];
        $data['ua'] = $this->request->getUserAgent();
       
        $data['contenidopaso4'] = $redirectView;
        $data['contenido'] = "wizard/wizard_view";
        return $data;
  }

  function loadNoVotacion($status ,$redirectView) {
    $dependenciaModel = new DependenciaModel();
    $turnoModel = new TurnoModel();
    
      $idTramite = $this->request->getVar('idTramite');
      $isPersonaValidada = $this->request->getVar('isPersonaValidada');
      $idDependenciaPago = $this->request->getVar('idDependenciaPago');
      $tramite = $this->tramiteModel->find($idTramite);
      $tramite['referencia_pago'] = GRATIS;
      $tipoTramite = null;
      if ($tramite ) {
        $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
       
//           $tramite['fecha_pago'] =  date('Y-m-d H:i:s');
//           $tramite['importe_contado']   = $tipoTramite['precio'];
        if(!empty($isPersonaValidada) && $isPersonaValidada==="true") {
            if(!empty($idDependenciaPago)) {
                $tramite['id_dependencia'] = $idDependenciaPago;
            }
        }
        $this->tramiteModel->set($tramite);
        $this->tramiteModel->where('id_tramite', $idTramite);
       
        $this->tramiteModel->update();
      }

      if($tipoTramite != null) {
          $data = $this->getDataSegunController($tipoTramite["controlador"] , $idTramite);
          $data['title']          = $tipoTramite["controlador_title"];
          $data['contenidopaso1'] = $tipoTramite['controlador_view'];
          
          $data['util'] = new Util();
          $data['contenidopaso2'] = "turno_no_votacion";
          $data['contenido'] = "wizard/wizard";
      }
      
      $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
      $data['dependencia'] = $dependencia['dependencia'];
      //$data['turno'] = $turnoModel->where('id_tramite', $idTramite)->first();
  
        // asigno el status para ver que operacion cargar de resultado a mostrar
      $data['status'] = $status;
      $data['isPersonaValidada'] = $isPersonaValidada;
  
      $data['codigo_operacion'] =  $this->request->getVar('collection_id');
//         $data['id_tramite'] = $this->request->getVar('external_reference'); //correspondiente al id tramite
      $data['id_tramite'] = $tramite['id_tramite'];
      $data['ua'] = $this->request->getUserAgent();
     
      $data['contenidopaso4'] = $redirectView;
      $data['contenido'] = "wizard/wizard_view";
      return $data;
}


}
