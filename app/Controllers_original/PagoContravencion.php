<?php
namespace App\Controllers;

//****LIBRERIAS */
use App\Libraries\PagoMercadoPago;
use App\Libraries\PaymentMercadoPago;
use App\Libraries\UtilBancoMacro;
use App\Libraries\Util;
use App\Libraries\Pdf;

//****MODELOS */
use App\Models\TipoTramiteModel;
use App\Models\TramiteContravencionModel;
use App\Models\TramitePersonaContravencionModel;
use App\Models\TramiteContravencionDetalleModel;
use App\Models\TipoDocumentoModel;
use App\Models\Central\PersonaCentralModel;
//
use App\Models\MovimientoPago;
//use App\Models\TramitePersonaModel;
//use App\Models\TramiteModel;

class PagoContravencion extends BaseController {    
  protected $tipoTramiteModel;
  protected $tramiteContravencionModel;
  protected $tramitePersonaContravencionModel;
  protected $tramiteContravencionDetalleModel;
  protected $tipoDocumentoModel;  
  //
  protected $resultPaymentModel;        
  protected $resultadoPagoOnlineModel;
  protected $pagoMercadoPago;  
  protected $paymentMercadoPagoLib;
  protected $utilBancoMacro;
  //
  protected $fiveDates;
  //protected $tramiteModel;
  //protected $tramitePersonaModel;
  //paginacion
  protected $pager;

  public function __construct() {
    $this->tipoTramiteModel = new TipoTramiteModel();
    $this->tramiteContravencionModel  = new TramiteContravencionModel();
    $this->tramitePersonaContravencionModel = new TramitePersonaContravencionModel();
    $this->tramiteContravencionDetalleModel = new TramiteContravencionDetalleModel();
    $this->tipoDocumentoModel = new TipoDocumentoModel();    
    //
    $this->resultPaymentModel = new MovimientoPago();                    
    $this->resultadoPagoOnlineModel = new MovimientoPago();
    $this->pagoMercadoPago = new PagoMercadoPago();    
    $this->paymentMercadoPagoLib = new PaymentMercadoPago();       
    $this->utilBancoMacro = new UtilBancoMacro();
    //paginacion
    $this->pager = \Config\Services::pager();
    //
    $this->util = new Util();        
    $this->session = session();
    //
    $date = date('Y/m/d');
		$this->fiveDates = date( "Y-m-d", strtotime( $date . "-5 day"));
    //$this->tramiteModel  = new TramiteModel();       
    //$this->tramitePersonaModel = new TramitePersonaModel(); 
  }
  
  public function index() {
    $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
    $data['contenido'] = "contravenciones/pagoContravencion";
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
   
    if (!$validation)  {//entra aqui si no se valido bien los campos
      $data['validation'] = $this->validator;
      $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');//tipo documento
      $data['documento'] = $this->request->getVar('documento');//numero de documento
      $data['id_tramite'] = $this->request->getVar('id_tramite');//numero de O.P
      //
      $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
      $data['contenido'] = "contravenciones/pagoContravencion";
      echo view("frontend", $data);
      return;     
     } else {//entra aqui si se valido bien los datos
        $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');//tipo documento
        $data['documento'] = $this->request->getVar('documento');//numero de documento
        $data['id_tramite'] = $this->request->getVar('id_tramite');//numero de O.P
        //
        $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();//trae listado de tipos de documentos
        $data['urlBancoMacro'] = $this->utilBancoMacro->getUrlBancoMacro();
        //
        $spambot = $this->request->getVar('porque_motivo');
        if (!empty($spambot)) { // si es un spambot
            log_message('error', 'spambot: documento=' . $data['documento'] . ', id_tramite=' . $data['id_tramite']);
            $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
            $data['porque_motivo'] = $spambot;
            $data['contenido'] ="contravenciones/pagoContravencion";
            echo view("frontend", $data);
            return;
        }
        //
        $filter['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
        $filter['documento'] = $this->request->getVar('documento');        
        $filter['id_tramite'] =$this->request->getVar('id_tramite');
                
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
        $recaptcha_response = $this->request->getVar('recaptcha_response');
        //             echo 'recaptcha_response=='.$recaptcha_response;
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        //             echo 'fff=='.$recaptcha;
        $recaptcha_json = json_decode($recaptcha);
        // Miramos si se considera humano o robot:
        // var_dump($recaptcha_json);
        if(empty($recaptcha_json) || (!empty($recaptcha_json) && $recaptcha_json->success && $recaptcha_json->score >= 0.6)) {
        //trae las orden de pagos para pagar una contravencion de una persona
        $personas = $this->tramitePersonaContravencionModel->findByTramitePagoContravencion($filter);
        //dd($personas);
        $tramites = [];
        if ($personas) {//entra si hay listado de orden de pago de una persona o no esta vacia la consulta
          //
          $personaCentralModel = new PersonaCentralModel(); 
          //datos de la persona a pagar la contravencion                                  
          $personaCentral = $personaCentralModel->findByDni($this->request->getVar('id_tipo_documento'), trim($this->request->getVar('documento')));
          $data['status'] = "EXITO";
          $data['message'] = 'Pago Cuota MACROCLICK por parte de el Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' ';

            $personaShow = $personas[0];
            foreach($personas as $persona) {
              $tramite_online = [];
              if ($persona && $persona['id_tramite'] != null ) {
                 // cada persona tiene un id_tramite
                 $tramite = $this->tramiteContravencionModel->find($persona['id_tramite']);
                 //dd($tramite);
               
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
          $data['message'] = 'No hay trámite de Contravencion a pagar para los datos ingresados.';
         }
         
        } else {
            log_message('error', 'ROBOT: recaptcha='.$recaptcha.', recaptcha_response='.$recaptcha_response.', documento=' . $data['documento'] . ', id_tramite=' . $data['id_tramite']);
            $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
        }
        
        $data['contenido'] ="contravenciones/pagoContravencion";
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

    if($tipoTramite['id_tipo_tramite'] ==  TIPO_TRAMITE_PAGO_CONTAVENCION && $tramite['estado_pago'] ==  ESTADO_PAGO_PENDIENTE){
      //$action = '<span><strong>Pago Banco</strong></span>';
      $action = '<a  data-id-tramite="'.$tramite['id_tramite'].'" class="pagoBancoMacro" href="'.$tramite['id_tramite'].'"><img style="width:30px;height:30px" src="'.base_url('assets/img/descargarCertificado.png').'" alt="Imagen"></a>';
      return $action;
    } else if ($tipoTramite['id_tipo_tramite'] ==  TIPO_TRAMITE_PAGO_CONTAVENCION && 
               ( $tramite['estado_pago'] == ESTADO_PAGO_CANCELADO || 
               $tramite['estado_pago'] == ESTADO_PAGO_IMPAGO)) {
      $action = "<span><strong>Pago No Realizado. Puede acercarse a las Oficinas de Contravencion para realizar el pago.</strong></span>";
      return $action;
    }
    //
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

  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############# PARA ACCEDER A LA VISTA PARA CARGAR EL PAGO DE LAS CONTRAVENCIONES ###########################################################################################################
  
  public function cargarOrdenPago(){
    $data['contenido'] = "contravenciones/cargarOrdenPago";    
    echo view("frontend", $data);
  }

  public function registrarOrdenPago(){
    if (session()->get('isLoggedIn') == NULL) {			
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
    //$tipoDocumentoModel = new TipoDocumentoModel();
    $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();    
    $data['contenido'] = 'contravenciones/registrarOrdenPago';   
    echo view("frontend", $data);
  }
  
  public function guardar() {
    $validation =  \Config\Services::validation();
    //
    helper(['form', 'url']);
    $validation;
    $tipoDni = $this->request->getVar('id_tipo_documento');
    
    $validation = $this->validate([
        'id_tipo_documento'       => ['rules' => 'required'],
        'documento'               => ['rules' => 'required'],
        'fecha_nacimiento'        => ['rules' => 'required'],
        'cuil'                    => ['rules' => 'required']
    ]);

    if (!$validation) {
        $data['validation'] = $this->validator;
        //
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['telefono'] =$this->request->getVar('telefono');
        $data['email'] =$this->request->getVar('email');
        $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
        $data['documento'] = $this->request->getVar('documento');
        $data['cuil'] = $this->request->getVar('cuil');
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['en_concepto_de'] = strtoupper($this->request->getVar('en_concepto_de'));
        //$data['denominacion_negocio'] = strtoupper($this->request->getVar('denominacion_negocio'));
        //                                              
        $data['concepto_uno'] = strtoupper($this->request->getVar('concepto_uno'));
        //$data['cantidad_uno'] = $this->request->getVar('cantidad_uno');
        $data['precio_uno']   = $this->request->getVar('precio_uno');
        //$data['concepto_dos'] = strtoupper($this->request->getVar('concepto_dos'));
        //$data['cantidad_dos'] = $this->request->getVar('cantidad_dos');
        //$data['precio_dos']   = $this->request->getVar('precio_dos');
        //$data['concepto_tres'] = strtoupper($this->request->getVar('concepto_tres'));
        //$data['cantidad_tres'] = $this->request->getVar('cantidad_tres');
        //$data['precio_tres']   = $this->request->getVar('precio_tres');
        //
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PAGO_CONTAVENCION;
        //        
        $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();                                
        //
        $data['contenido'] = "contravenciones/registrarOrdenPago";
        echo view("frontend", $data);
        return;
    } else {
        $data['id_tramite']      = $this->request->getVar('id_tramite');
        $data['id_tramite_reba'] = $this->request->getVar('id_tramite_reba');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular'); 
        //
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['telefono'] =$this->request->getVar('telefono');
        $data['email'] =$this->request->getVar('email');
        $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
        $data['documento'] = $this->request->getVar('documento');
        $data['cuil'] = $this->request->getVar('cuil');
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['en_concepto_de'] = strtoupper($this->request->getVar('en_concepto_de'));
        //$data['denominacion_negocio'] = strtoupper($this->request->getVar('denominacion_negocio'));
        //
        //if ($filter != null && !empty($filter['urlRedirec'])) {        
        $data['concepto_uno'] = strtoupper($this->request->getVar('concepto_uno'));
        //$data['cantidad_uno'] = $this->request->getVar('cantidad_uno');
        $data['precio_uno']   = $this->request->getVar('precio_uno');
        // if (!empty($this->request->getVar('concepto_dos'))) {
        //   $data['concepto_dos'] = strtoupper($this->request->getVar('concepto_dos'));
        // }else {
        //   $data['concepto_dos'] = Null;
        // }
        // if (!empty($this->request->getVar('cantidad_dos'))) {
        //   $data['cantidad_dos'] = $this->request->getVar('cantidad_dos');
        // }else {
        //   $data['cantidad_dos'] = Null;
        // }         
        // if (!empty($this->request->getVar('precio_dos'))) {
        //   $data['precio_dos']   = $this->request->getVar('precio_dos');
        // }else {
        //   $data['precio_dos'] = Null;
        // }
        // if (!empty($this->request->getVar('concepto_tres'))) {
        //   $data['concepto_tres'] = strtoupper($this->request->getVar('concepto_tres'));
        // }else {
        //   $data['concepto_tres'] = Null;
        // }
        // if (!empty($this->request->getVar('cantidad_tres'))) {
        //   $data['cantidad_tres'] = $this->request->getVar('cantidad_tres');
        // }else {
        //   $data['cantidad_tres'] = Null;
        // }
        // if (!empty($this->request->getVar('precio_tres'))) {
        //   $data['precio_tres']   = $this->request->getVar('precio_tres');
        // }else {
        //   $data['precio_tres'] = Null;
        // }                
        //
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PAGO_CONTAVENCION;
        //
        //$data['id_dependencia']   = session()->get('id_dependencia');                       
        //$data['id_categoria_reba'] =$this->request->getVar('id_categoria_reba');
        //$data['numero_tramite'] = $this->request->getVar('numero_tramite');        
        //
        //        
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
        $recaptcha_response = $this->request->getVar('recaptcha_response');
        //             echo 'recaptcha_response=='.$recaptcha_response;
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        //             echo 'fff=='.$recaptcha;
        $recaptcha_json = json_decode($recaptcha);
        //
        //
        //
        $id_tramite = null;
        if (empty($data['id_tramite'])) { // insert
            $codigo = $this->util->generateRandomString(INT_DIEZ);
            while (!empty($this->tramiteContravencionModel->where('codigo', $codigo)->findAll())) {
                $codigo = $this->util->generateRandomString(INT_DIEZ);
            }

            $data['codigo'] = $codigo;
            $id_tramite = $this->tramiteContravencionDetalleModel->insertar($data);
        } else {// update
            $id_tramite = $this->tramiteContravencionDetalleModel->actualizar($data);
        }
        //return redirect()->to(base_url().'/pagoContravencion/cargarOrdenPago');
            
        $this->generaOp($id_tramite);
    }   
}

public function generaOp($id_tramite){
        //$tramiteModel = new TramiteModel();
        //$data = $this->tramiteContravencionModel->find($id_tramite);
        $data = $this->tramitePersonaContravencionModel->buscaOrdenPago($id_tramite);

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Orden de Pago Contravencion');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);

        $pdf->AddPage();
        $html = $pdf->get_header_contravensiones();
        $html = $this->get_body($html, $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        //
        ob_end_clean();
        $nombre = 'ORDEN DE PAGO Nro: ' . str_pad((int) $id_tramite, 8, "0", STR_PAD_LEFT)  . '.pdf';
        $pdf->Output($nombre, 'D');
        //$pdf->Output('orden_pago.pdf', 'I');
    }

    private function get_body($html, $data){
        // $tramitePersonaModel = new TramitePersonaModel();
        // $dependenciaModel = new DependenciaModel();
        // $titular_tramite = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', 1)->first();
        // $parte_interesada = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_parte_interesada', 1)->first();
        // $dependencia = $dependenciaModel->find($data['id_dependencia']);
        // $fechaCastellano = $this->fechaUtil->fechaCastellano(2);
        $html = $html . '<table>
                  <tr>
                      <td width="100%" align="justify">
                          <div align="center">
                              <h1><b><u>ORDEN DE PAGO Nro: ' . str_pad((int) $data[0]['id_tramite'], 8, "0", STR_PAD_LEFT) . '</u></b></h1>
                              <br/><br/>
                          </div>
                      </td>
                  </tr>
                </table>
                <table border="0">
                <tr>
                    <td>NOMBRE: '.$data[0]['nombre'].'</td>                    
                </tr>
                <tr>
                    <td>APELLIDO: '.$data[0]['apellido'].'</td>                    
                </tr>
                <tr>
                    <td>DNI: '.$data[0]['documento'].'</td>                    
                </tr>
                <tr>
                    <td>CONCEPTO DE PAGO: '.$data[0]['concepto_uno'].'</td>                    
                </tr>
                <tr>
                    <td>IMPORTE A PAGAR: $'.$data[0]['precio_uno'].'</td>                    
                </tr>
                <tr>
                    <td>OBSERVACION: '.$data[0]['en_concepto_de'].'</td>                    
                </tr>
                </table>                
                </body>';
        return $html;
    }

  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############################################################################################################################################################################################
  ############# PARA CONSULTAR ALGUN PAGO DE LAS CONTRAVENCIONES #############################################################################################################################

  public function listarPagoContravencion($filter = null){
    
		if (session()->get('isLoggedIn') == NULL) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

    $filter = $this->session->get('filter');

			if ($filter == null) {
				$filter['idTramite'] = null;
        $filter['fechaDesde'] = $this->fiveDates;
				$filter['fechaHasta'] = date('Y/m/d');
        $filter['nombre'] = null;
				$filter['apellido'] = null;
				$filter['cuil'] = null;
        $filter['documento'] = null;
        $filter['estadoPago'] = null;
				$filter['estadoTramite'] = null;								
			} else {
        $idTramite  = $this->request->getVar('idTramite');
        $fechaDesde  = $this->request->getVar('fechaDesde');
        $fechaHasta  = $this->request->getVar('fechaHasta');
				$nombre     = $this->request->getVar('nombre');
        $apellido   = $this->request->getVar('apellido');
        $cuil       = $this->request->getVar('cuil');
        $documento      = $this->request->getVar('documento');
        $estadoPago     = $this->request->getvar('estadoPago');
        $estadoTramite  = $this->request->getVar('estadoTramite');        
    
        if (isset($idTramite)) {
          $filter['idTramite'] = $idTramite;
        }
        if (isset($fechaDesde) && $fechaDesde != "") {
          $filter['fechaDesde'] = $fechaDesde;
        } else {
          $filter['fechaDesde'] = $this->fiveDates;
        }
        if (isset($fechaHasta) && $fechaHasta != "") {
          $filter['fechaHasta'] = $fechaHasta;
        } else {
          $filter['fechaHasta'] =  date('Y/m/d');
        }
        if (isset($nombre)) {
          $filter['nombre']   = $nombre;
        }
        if (isset($apellido)) {
          $filter['apellido'] = $apellido;
        }
        if (isset($cuil)) {
          $filter['cuil']     = $cuil;
        }
        if (isset($documento)) {
          $filter['documento'] = $documento;
        }
        if (isset($estadoPago)) {
          $filter['estadoPago'] = $estadoPago;
        }
        if (isset($estadoTramite)) {
          $filter['estadoTramite'] = $estadoTramite;
        }                        
			}
      session()->set('filter', $filter);
											
			$data['estadoPagos']  = $this->getSelectEstadosPago();
			$data['estadoTramites'] = $this->getSelectEstadosTramite();
      $data['filter'] = $filter;
			$data['contenido'] = "contravenciones/listarPagoContravenciones";			
			echo view("frontend", $data);		      
	}

  /**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de contravenciones
	 **/
	public function pagination()
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
		//$config = $this->get_configuration(); 
		$filter = session()->get('filter');

		//filter vial 
		if ($filter == null) {
        $filter['idTramite'] = null;
        $filter['fechaDesde'] = $this->fiveDates;
				$filter['fechaHasta'] = date('Y/m/d');
        $filter['nombre'] = null;
				$filter['apellido'] = null;
				$filter['cuil'] = null;
        $filter['documento'] = null;
        $filter['estadoPago'] = null;
				$filter['estadoTramite'] = null;

		} else {
			if ($filter['fechaDesde'] == null  || $filter['fechaDesde'] == "") {
				  $filter['fechaDesde'] = $this->fiveDates;
			}

			if ($filter['fechaHasta'] == null || $filter['fechaHasta'] == "") {
				  $filter['fechaHasta'] = date('Y/m/d');
			}
		}
	
  	// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));
    //var_dump('hola mundo');
    //var_dump($filter);

		//obtenemos los tramites
		$rows = $this->tramiteContravencionModel->search($filter, $rowperpage);
    //var_dump($rows);
		$tramites = [];
		foreach ($rows as $tramite) {
			$tramites[] = $this->get_format_row($tramite);
		}
		//obtengo la cantidad sin filtrar para realizar la pagintation
    //$cantidad = sizeof($this->tramiteModel->get_cantidad_rows($filter));
		$cantidad = count($this->tramiteContravencionModel->search($filter));
		
		//var_dump("cantidad : ".$cantidad);
        //$cantidad=537;   
		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['page'] = $page;
		$data['cantidad'] = $cantidad;

		echo json_encode($data);
		return;
	}

  /**
	 * Funcion que permite establecer el format_row
	 */
	private function get_format_row($tramite)
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$util = new Util();
		$estado = "";
		if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
		    if(empty($tramite['estado'])) {
		        $estado .= TRAMITE_PENDIENTE_VALIDACION;
		    }else {
		        $estado .= $tramite['estado'];
		    }
		    
		    $estado .= ' - ';
		    if(isset($tramite['estado_verificacion'])) {
		        $estado .= $tramite['estado_verificacion'];
		    }
		}else {
		    if ($tramite['estado'] == TRAMITE_APROBADO) {
		        $estado = '<span class="badge badge-success"><h8>APROBADO</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_VALIDADO) {
		        $estado = '<span class="badge badge-success"><h8>VALIDADO</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_NO_VERIFICADO) {
		        $estado = '<span class="badge badge-danger"><h8>' . TRAMITE_NO_VERIFICADO . '</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_VALIDADO_VERIFICADO) {
		        $estado = '<span class="badge badge-success"><h8>' . TRAMITE_VALIDADO_VERIFICADO . '</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
		        $estado =  '<strong><span class="badge badge-secondary"><h8>PENDIENTE VALIDACION</h8></span></strong>';
		    } else if ($tramite['estado'] == TRAMITE_INVALIDADO) {
		        $estado = '<span class="badge badge-danger"><h8>INVALIDADO</h8></span>';
		    }
		}

		if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO) {
			$pago = '<span class="badge badge-primary"><h8>PAGADO</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_PENDIENTE || empty($tramite['estado_pago'])) {
			$pago = '<span class="badge badge-secondary"><h8>PENDIENTE</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_CANCELADO || $tramite['estado_pago'] == ESTADO_PAGO_IMPAGO) {
			$pago = '<span class="badge badge-danger"><h8>IMPAGO</h8></span>';
		} else {
			$pago = '<span class="badge badge-danger"><h8>' . $tramite['estado_pago'] . '</h8></span>';
		}

		// Referencia de pago
		$referencia_pago = "";
		if ($tramite['referencia_pago'] == BANCO_MACRO) {
			$referencia_pago = '<span class="badge badge-primary">BANCO MACRO</span>';
		} else if ($tramite['referencia_pago'] == MERCADO_PAGO) {
			$referencia_pago = '<span  class="badge badge-secondary">MERCADO PAGO</span>';
		} else if ($tramite['referencia_pago'] == COMISARIA_PAGO) {
			$referencia_pago = '<span  class="badge badge-warning"> COMISARIA PAGO</span>';
		} else if ($tramite['referencia_pago'] == "") {
			$referencia_pago = "";
		}

    //*********************************************************************************************************************************** */
    //*********************************************************************************************************************************** */
    //*********************************************************************************************************************************** */


        //Comprobamos por cada tramite si existe un registro validado para marcarlo 
	  //   $styleColorValidado = "";
		// $listadoTramites = $this->tramiteModel->getTramiteValidado($tramite['documento'],$tramite['id_tramite'], $tramite['id_tipo_tramite']);
		// //var_dump("size : ".sizeof($listadoTramites));
		// if ( sizeof($listadoTramites) > 0 && ( $tramite['estado'] != TRAMITE_VALIDADO && $tramite['estado'] != TRAMITE_VALIDADO_VERIFICADO) ) {
		// 	$styleColorValidado = "background-color:#90EE90";
		// }

		// if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL && $tramite['urgente'] == INT_UNO) {
		// 	$styleColorValidado = "background-color:#F6CED8";
		// }

		
		// $link = '<span>' .
		// 	'<a class="btn btn-secondary" ' .
		// 	' href="' . base_url() . '/persona/' . $tramite['cuil'] . '" ' .
		// 	' title="Editar Datos de la persona"><span class="oi oi-document" style="color:#3380FF"></span>Editar Datos Personales</a></span>';

    $styleColorValidado = "background-color:#90EE90";//agregue yo

		$row = '<tr style='.$styleColorValidado.'>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] . '</td>' .
			'<td>' . date_format(date_create($tramite['fecha_alta']), 'd/m/Y H:i') . '</td>' .
			//'<td>' . $tramite['dependencia'] . '</td>' .
			//'<td id="col-forma-pago-' . $tramite['id_tramite'] . '">' . $tramite['nombreTipoPago'] . '</td>' .
			'<td>' . $referencia_pago . '</td>' .
			'<td>' . $tramite['documento'] . '</td>' .
			'<td>' . $tramite['apellido'] . ', ' . $tramite['nombre'] . '</td>' .
			'<td>' . $estado . '</td>' .
			'<td id="col-estado-pago-' . $tramite['id_tramite'] . '">' . $pago . '</td>' .
			'<td width="100">' .
			'<div class="text-center">';


		//  if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
		// 	$row = $row . '<span><a href="' . base_url() . '/tramiteReba/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		// } else {
		// 	$row = $row . '<span><a href="' . base_url() . '/' . $tramite['controlador'] . '/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		// } 
      

	

		 


		if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO || empty($tramite['estado_pago'])) {
			$estado  = "";
			if (empty($item['estado_pago'])) {
				$estado = ESTADO_PAGO_PENDIENTE;
			} else {
				$estado =  $tramite['estado_pago'];
			}
		}

		// $actionVerificador = "";
		// $actionVerificador2 = "";
		// if ($tramite['estado'] == TRAMITE_VALIDADO) {
		// 	if ($util->isTramiteOnline($tramite['id_tipo_tramite'])) {
		// 		$actionVerificador = '<a href="' . base_url() . '/' . $tramite['controlador'] . '/ver/' . $tramite['id_tramite'] . '/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>';
		// 		if (
		// 			$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA ||
		// 			$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA ||
		// 			$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA
		// 		) {
		// 			$actionVerificador2 = '<a style="cursor:pointer"  href="' . base_url() . '/' . $tramite['controlador'] . '/verificar/' . $tramite['id_tramite'] . '/buscarTramitePersona" title="Verificar domicilio">' .
		// 				'<span class="oi oi-menu" style="color:red"></span></a>';
		// 			$row = $row . $actionVerificador2;
		// 		}
		// 	}
		// }

		// if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
		// 	if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO) {
		// 		$row = $row . '&nbsp;&nbsp;<a href="' . base_url() . '/tramiteReba/getCuponPagoOnline/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Descargar Cupon de Pago">' .
		// 			'<span class="oi oi-tablet" style="color:brown"></span>' .
		// 			'</a>';
		// 	} else {
		// 		$row = $row . '&nbsp;&nbsp;<a href="' . base_url() . '/tramiteReba/getCuponesPago/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Descargar Cupones de Pago">' .
		// 			'<span class="oi oi-print" style="color:brown"></span>' .
		// 			'</a>';
		// 		$row = $row . '&nbsp;&nbsp;<a href="#" style="cursor:pointer" onclick="module_pago.mostrarFormPagoEfectivoReba(' . $tramite['id_tramite'] . ', \'' . $tramite['estado_pago'] . '\', \'' . $tramite['tipo_tramite'] . '\', -1)" title="Registrar pago">' .
		// 			'<span class="oi oi-check"></span>' .
		// 			'</a>';
		// 	}
		// } else if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
		//     $row = $row .
    // 		    '&nbsp;&nbsp;<a href="'.base_url().'/planillaProntuarial/verificar/'.$tramite['id_tramite'].'/dashboard" title="ver antecedentes"><span class="oi oi-clipboard" style="color: red"></span></a>';
		    
		// 	if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO) {
		// 	  $price = $tramite['precio'] + $tramite['importe_adicional'];
		// 	  $row = $row .
		// 			'&nbsp;&nbsp;<a href="#" style="cursor:pointer" onclick=module_pago.mostrarFormPagoEfectivoPlanillaProntuarial('.$tramite['id_tramite'].',"PENDIENTE","Planilla",'.$price.') >'.
		// 			' <span class="oi oi-check"></span>'.
		// 			'</a>'; 
		// 	}
		// 	$row = $row . '&nbsp;&nbsp;<a target="_blank" href="' . base_url() . '/planillaProntuarial/getConstanciaPlanillaProntuarial/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Cupon de pago">' .
		// 	'<span class="oi oi-data-transfer-download" style="color:green"></span>' .
		// 	'</a>';
		// 	$row = $row . '&nbsp;&nbsp;<a target="_blank" href="' . base_url() . '/planillaProntuarial/getDocumentoPlanillaProntuarial/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Ver Planilla">' .
		// 	 			'<span class="oi oi-print" style="color:blue"></span>' .
		// 	 			'</a>';
		// }  else {
		// 	$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarTramite(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
		// 		'<span class="oi oi-print" style="color:blue"></span>' .
		// 		'</a>';
		// }

		// if ($tramite['id_tipo_tramite'] != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) {
    // 		// upload firma digital				   
    // 		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Subir firma digital" onclick=module_util.mostrarModalFirmaDigital(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
    // 			'<span class="oi oi-data-transfer-upload" style="grey"></span>' .
    // 			'</a>';
		// }

		// if ($tramite['contiene_firma_digital'] == true) {
		// 	$color = "green";
		// 	if ($tramite['estado_envio_email']) {
		// 		$color = "blue";
		// 	}

		// 	if (!empty($tramite['email']) && $tramite['id_tipo_tramite'] != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) {
		// 		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmail(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
		// 			'<span class="oi oi-envelope-closed" style="color:' . $color . '"></span>' .
		// 			'</a>';
		// 	}

		
		// }

		// if (isset($tramite['telefono'])) {
		// 	$row  = $row . '&nbsp;&nbsp;<a href="https://api.whatsapp.com/send?phone=+549' . $tramite['telefono'] . '&text=hola,%20qué%20tal?" style="cursor:pointer" title="Mensaje por wsp Web" target="_blank" >' .
		// 		'<span class="fa fa-whatsapp" style="green"></span>' .
		// 		'</a>';
		// }


		// if ($tramite['referencia_pago'] == BANCO_MACRO  && ($tramite['estado_pago'] === ESTADO_PAGO_PENDIENTE  || empty($tramite['estado_pago']))) {
		// 	$row  = $row  . '<a style="cursor:pointer" title="Sincronizar Pago" onclick="module_pago.mostrarPago(' . $tramite['id_tramite'] . ')">' .
		// 		'<span class="oi oi-loop-circular" style="color:red"></span>' .
		// 		'</a>';
		// }

		$row = $row . '</div></td></tr>';

		return $row;
	}

  /*
	* Funcion que permite realizar 
	* la limpieza de los filtros
   **/
	public function limpiar()
	{
	    if (!empty(session()->get('id_rol'))) {
    		session()->set('filter', null);
    		$this->listarPagoContravencion();
	    } else {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
	}


}