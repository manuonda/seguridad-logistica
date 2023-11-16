<?php
namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Libraries\PaymentMercadoPago;
use App\Libraries\UtilBancoMacro;

use App\Models\TramitePersonaModel;
use App\Models\MovimientoPago;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;

class DescargarCertificadoReba extends BaseController {
  
  protected $resultPaymentModel;
  protected $tramiteModel;
  protected $tramitePersonaModel;
  protected $tipoTramiteModel;
  protected $resultadoPagoOnlineModel;
  protected $pagoMercadoPago;
  protected $tipoDocumentoModel;

  protected $paymentMercadoPagoLib;

  protected $utilBancoMacro;

  public function __construct() {
       $this->resultPaymentModel = new MovimientoPago();
       $this->tramiteModel  = new TramiteModel();
       $this->tramitePersonaModel = new TramitePersonaModel();
       $this->tipoTramiteModel = new TipoTramiteModel();
       $this->resultadoPagoOnlineModel = new MovimientoPago();
       $this->paymentMercadoPagoLib = new PaymentMercadoPago();
       $this->pagoMercadoPago = new PagoMercadoPago();
       $this->tipoDocumentoModel = new TipoDocumentoModel();
       $this->utilBancoMacro = new UtilBancoMacro();
  }
  
  public function index() {
    $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
    $data['contenido'] = "certificado/descargarReba";
    $data['urlBancoMacro'] = $this->utilBancoMacro->getUrlBancoMacro();
    echo view("frontend", $data);
  }

public function verificar(){
   $validation =  \Config\Services::validation();
//    helper(['form', 'url']);
   $validation = $this->validate([
       'id_tipo_documento'       => ['rules' => 'required'], 
       'documento'               => ['rules' => 'required'],
       'id_tramite'              => ['rules' => 'required']
   ]);
   $data['tramites'] = [];
  
   if (!$validation)  {
     $data['validation'] = $this->validator;
     $data['documento'] = $this->request->getVar('documento');
     $data['id_tramite'] = $this->request->getVar('id_tramite');
     $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
     $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
     $data['contenido'] = "certificado/descargarReba";
     echo view("frontend", $data);
     return;
    
    } else {
        
       $data['documento'] = $this->request->getVar('documento');
       $data['id_tramite'] = $this->request->getVar('id_tramite');
       $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
       $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
       $data['urlBancoMacro'] = $this->utilBancoMacro->getUrlBancoMacro();
       
       $spambot = $this->request->getVar('porque_motivo');
       if (!empty($spambot)) { // si es un spambot
           log_message('error', 'spambot: documento=' . $data['documento'] . ', id_tramite=' . $data['id_tramite']);
           $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
           $data['porque_motivo'] = $spambot;
           $data['contenido'] ="certificado/descargarReba";
           echo view("frontend", $data);
           return;
       }

       $filter['documento'] = $this->request->getVar('documento');
       $filter['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
       $filter['id_tramite'] =$this->request->getVar('id_tramite');
       
       
       $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
       $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
       $recaptcha_response = $this->request->getVar('recaptcha_response');
       //             echo 'recaptcha_response=='.$recaptcha_response;
       $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
       //             echo 'fff=='.$recaptcha;
       $recaptcha_json = json_decode($recaptcha);
       // Miramos si se considera humano o robot:
//        var_dump($recaptcha_json);
       if(empty($recaptcha_json) || (!empty($recaptcha_json) && $recaptcha_json->success && $recaptcha_json->score >= 0.6)) {

       $personas = $this->tramitePersonaModel->findByTramitePagoReba($filter);
       $tramites = [];
       if ($personas) {
           $personaShow = $personas[0];
           foreach($personas as $persona) {
             $tramite_online = [];
             if ($persona && $persona['id_tramite'] != null ) {
                // cada persona tiene un id_tramite
                $tramite = $this->tramiteModel->find($persona['id_tramite']);
              
                if ($tramite != null) {
                    $tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
                    $tramite_online['id_tramite']  = $tramite['id_tramite'];
                    $tramite_online['tipoTramite'] = $tipoTramite['tipo_tramite'];
                    $tramite_online['fecha_alta']  = date_format(date_create($tramite['fecha_alta']), 'd/m/Y H:i');
                    $tramite_online['estado_pago'] = $tramite['estado_pago'];
                    $tramite_online['contiene_firma_digital'] = $tramite['contiene_firma_digital'];

                    // VERFICACION DE DATOS DE APROBACION
                     if ($tramite['estado'] == TRAMITE_VALIDADO ) {
                         $tramite_online['estado'] = TRAMITE_VALIDADO;
                         $tramite_online= $this->completarEtiquetaAprobacion($tramite_online, $tipoTramite);
                     }else if ($tramite['estado'] == TRAMITE_VALIDADO_VERIFICADO ) {
                         $tramite_online['estado'] = TRAMITE_VALIDADO_VERIFICADO;
                         $tramite_online= $this->completarEtiquetaAprobacion($tramite_online, $tipoTramite);
                     } else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
                        $tramite_online['estado'] = TRAMITE_PENDIENTE_VALIDACION;
                        $tramite_online = $this->completarEtiquetaAprobacion($tramite_online, $tipoTramite);    
                    } else if ($tramite['estado'] == TRAMITE_INVALIDADO) {
                         $tramite_online['estado'] =  TRAMITE_INVALIDADO;
                         $tramite_online = $this->completarEtiquetaAprobacion($tramite_online, $tipoTramite);
                    } else {
                      $tramite_online['estado'] = TRAMITE_PENDIENTE_VALIDACION;
                      $tramite_online['estado_aprobado_label'] = '<span class="badge badge-secondary"><h6>PENDIENTE</h6></span>'; 
                      $tramite_online['estado_aprobado_message'] = '<span class="badge badge-secondary"><h6>Todavia sus datos se encuentran en revision</h6></span>'; 
                    }

                    $tramite_online =  $this->completarEtiquetPagos($tramite_online);

                    // Obtenemos la action a partir del estado de los tramites
                    $tramite_online['action'] = $this->getActionTramite($tramite_online ,$tipoTramite);
                    $tramites[]= $tramite_online; 
                    $data['tramites'] = $tramites;   
                    $data['persona'] = $personaShow;
                }

           } //end for             
          } 
        } else {
         $data['cuil'] = $this->request->getVar('cuil');
         $data['status'] = "ERROR";
         $data['message'] = 'No hay trámite de reba a pagar para los datos ingresados.';
        }
        
       } else {
           log_message('error', 'ROBOT: recaptcha='.$recaptcha.', recaptcha_response='.$recaptcha_response.', documento=' . $data['documento'] . ', id_tramite=' . $data['id_tramite']);
           $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
       }
       
       $data['contenido'] ="certificado/descargarReba";
       echo view("frontend", $data);
    } 
  }  


  /**
   * Funcion que muestra los span corresondientes 
   * a los estados de pagos
   */
  private function completarEtiquetPagos($tramite = null) {
    switch($tramite['estado_pago']) {
        case ESTADO_PAGO_PAGADO : {
          $tramite['estado_pago_label'] =  '<span class="badge badge-success"><h8>PAGADO</h8></span>'; 
          $tramite['estado_pago_message'] = '<span class="badge badge-success"></span>'; 
        };break;
        case ESTADO_PAGO_PENDIENTE: {
          $tramite['estado_pago_label'] =  '<span class="badge badge-info"><h8>PENDIENTE DE PAGO </h8></span>'; 
          $tramite['estado_pago_message'] = '<span class="badge badge-info"><h8>Todavia su pago se encuentra en PENDIENTE</h8></span>';    
        };break;
        case ESTADO_PAGO_CANCELADO: {
          $tramite['estado_pago_label'] =  '<span class="badge badge-warning"><h8>PAGO CANCELADO</h8></span>'; 
          $tramite['estado_pago_message'] = '<span class="badge badge-warning"><h8>El pago se cancelo porque se vencio el comprobante</h8></span>';
        };break;
        case ESTADO_PAGO_NO_EXISTE: {
          $tramite['estado_pago_label'] =  '<span class="badge badge-warning"><h8>NO EXISTE INFORMACION DE PAGO</h8></span>'; 
          $tramite['estado_pago_message'] = '<span class="badge badge-warning"><h8>El pago no se realizo</h8></span>';
        };break;
        case ESTADO_PAGO_IMPAGO : {
           $tramite['estado_pago_label'] =  '<span class="badge badge-warning"><h8>IMPAGO</h8></span>'; 
           $tramite['estado_pago_message'] = '';
        };break;
        default: {
          $tramite['estado_pago_label'] =   $tramite['estado_pago']; 
          $tramite['estado_pago_message'] = '';
   
        }
      }
      return $tramite;
  }


  /**
   * Completa la etiqueta del estado del tramite
   */
  private function completarEtiquetaAprobacion($tramite_online = null, $tipoTramite) {
    switch($tramite_online['estado']) {
      case TRAMITE_VALIDADO :{
        $tramite_online['estado_aprobado_label'] = '<span class="badge badge-success"><h8>'.TRAMITE_VALIDADO.'</h8></span>'; 
        if($tipoTramite==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA || $tipoTramite==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA || $tipoTramite==TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) {
            $tramite_online['estado_aprobado_message'] = '<span class="badge badge-secondary"><h6>Pendiente de verificación.</h6></span>';
        }else {
            $tramite_online['estado_aprobado_message'] = '';
        }
      };break;
      case TRAMITE_VALIDADO_VERIFICADO :{
          $tramite_online['estado_aprobado_label'] = '<span class="badge badge-success"><h8>'.TRAMITE_VALIDADO_VERIFICADO.'</h8></span>';
          $tramite_online['estado_aprobado_message'] = '';
      };break;
      case TRAMITE_PENDIENTE_VALIDACION: {
        $tramite_online['estado_aprobado_label'] = '<span class="badge badge-secondary"><h6>PENDIENTE</h6></span>'; 
        //$tramite_online['estado_aprobado_message'] = '<span class="badge badge-secondary"><h6>Todavia sus datos se encuentran en revision</h6></span>';
        $tramite_online['estado_aprobado_message'] = '';
       
      };break;
      case TRAMITE_INVALIDADO: {
        $tramite_online['estado_aprobado_label'] =  '<span class="badge badge-danger"><h8>CANCELADO</h8></span>'; 
        $tramite_online['estado_aprobado_message'] = '<span class="badge badge-success"><h8>Debe completar los datos correspondientes</h8></span>';
      };break;
    }
    return $tramite_online;
  }

  /**
   * Funcion que permite obtener la action a partir del tramite y el estado 
   * del pago del mismo
   */
  private function getActionTramite($tramite , $tipoTramite) {
    $action = "";

    if($tipoTramite['id_tipo_tramite'] ==  TIPO_TRAMITE_PAGO_REBA && $tramite['estado_pago'] ==  ESTADO_PAGO_PENDIENTE){
      //$action = '<span><strong>Pago Banco</strong></span>';
      $action = '<a  data-id-tramite="'.$tramite['id_tramite'].'" class="pagoBancoMacro" href="'.$tramite['id_tramite'].'"><img style="width:30px;height:30px" src="'.base_url('assets/img/descargarCertificado.png').'" alt="Imagen"></a>';
      return $action;
    } else if ($tipoTramite['id_tipo_tramite'] ==  TIPO_TRAMITE_PAGO_REBA && 
               ( $tramite['estado_pago'] == ESTADO_PAGO_CANCELADO || 
               $tramite['estado_pago'] == ESTADO_PAGO_IMPAGO)) {
      $action = "<span><strong>Pago No Realizado. Puede acercarse a la Unidad Regional para realizar el mismo.</strong></span>";
      return $action;
    }

   

    //bugfix--
    if ($tramite['estado']== TRAMITE_VALIDADO_VERIFICADO && $tramite['estado_pago'] == ESTADO_PAGO_PAGADO  && $tramite['contiene_firma_digital'] == true) { // case 1
        $action = '<a class="btn btn-primary" href="'.base_url().'/'.$tipoTramite['controlador'].'/descargar/'.$tramite['id_tramite'].'">Descargar</a>';
    }else if ($tramite['estado']== TRAMITE_VALIDADO && $tramite['estado_pago'] == ESTADO_PAGO_PAGADO  && $tramite['contiene_firma_digital'] == false) {
      $action = '<span><strong>Espere la subida del Documento.</strong></span>';
    }else if( $tramite['estado'] == TRAMITE_INVALIDADO ) { 
      $action = '<span><strong>Trámite Invalidado.</strong></span>';
    } else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
      $action = '<span>En Proceso de Validacion.</span>';
    }else if ($tramite['estado_pago'] == ESTADO_PAGO_PENDIENTE) { // No Existe un pago 
      $action = '<span><strong>Pago Pendiente. Puede acercarse a la Comisaria para realizar el mismo.</strong></span>';
    } 
    return $action;
  }
  
  
 
}
