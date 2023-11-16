<?php
namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\TipoParentescoModel;
use App\Models\DependenciaModel;
use App\Models\TramitePersonaModel;
use App\Models\TipoTramiteModel;
use App\Models\TurnoModel;
use App\Libraries\Util;
use App\Libraries\UtilBancoMacro;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
use App\Models\TramiteArchivoModel;

class CertificadoResidenciaConvivencia extends BaseController {

    protected $tramiteModel;
    protected $util;
    protected $fechaUtil;
    protected $session;

    public function __construct() {
        $this->tramiteModel = new TramiteModel();
        $this->util = new Util();
        $this->fechaUtil = new FechaUtil();
        $this->session = session();
    }
    
    public function index() {
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA;
        $data['cntPersonasAgregadas'] = INT_CERO;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_departamento'] = 291; // Dr manuel belgrano
        $data['id_localidad'] = 12794; // San salvador de jujuy
        $data['id_tipo_documento'] = 1;
        
        // Identificador de la pagina para el websocket de turnos
        $time = time();
        $data['turno_user_id'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA . date("dmYHis", $time) . rand(1,10000);
        $this->cargarForm($data,"wizard");
    }
    
    public function edit($idTramite) {
        $data = $this->loadData($idTramite);
        $this->cargarForm($data ,'edit');
    }

    public function new(){
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA;
        $data['estado']=TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $this->cargarForm($data, "new");   
    }
    
    public function nuevo($id_tipo_documento, $documento, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA;
        $data['estado'] = TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_tipo_documento'] = $id_tipo_documento;
        $data['documento'] = $documento;
        
        if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_UAD_SAN_PEDRO_UR2) {
            $data['id_departamento'] = 300; // SAN_PEDRO
            $data['id_localidad'] = 14823; // SAN_PEDRO
        }else if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_UAD_HUMAHUACA_UR3) {
            $data['id_departamento'] = 293; // HUMAHUACA
            $data['id_localidad'] = 2760; // HUMAHUACA
        }else if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_UAD_LGSM_UR4) {
            $data['id_departamento'] = 287; // Ledesma
            $data['id_localidad'] = 13125; // LIBERTADOR GENERAL SAN MARTIN
        }else if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_UAD_LA_QUIACA_UR5) {
            $data['id_departamento'] = 292; // Yavi
            $data['id_localidad'] = 2779; // La quiaca
        }else if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_UAD_PERICO_UR6) {
            $data['id_departamento'] = 289; // El Carmen
            $data['id_localidad'] = 2809; // Perico
        }else if(!empty(session()->get('id_dependencia')) && (session()->get('id_dependencia')==ID_DEP_SECCIONAL_23 || session()->get('id_dependencia')==ID_DEP_SECCIONAL_47)) {
            $data['id_departamento'] = 302; // Palpala
            $data['id_localidad'] = 5820; // Palpala
        }else if(!empty(session()->get('id_dependencia')) && session()->get('id_dependencia')==ID_DEP_SUBCRIA_RIO_BLANCO) {
            $data['id_departamento'] = 302; // Palpala
            $data['id_localidad'] = 11385; // Rio Blanco
        }else {
            $data['id_departamento'] = 291; // Dr manuel belgrano
            $data['id_localidad'] = 12794; // San salvador de jujuy
        }
        
        $data = $this->getDatosPersona($data);
        $userInSession = $this->session->get('user');
        if(!empty($userInSession)) {
            $data['id_dependencia'] = $userInSession['id_dependencia'];
        }
        $this->cargarForm($data, "new");
    }
    
    public function validar($idTramite, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data =  $this->loadData($idTramite);
        $this->cargarForm($data , 'validar');
    }
    
    public function verificar($idTramite, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data =  $this->loadData($idTramite);
        $this->cargarForm($data , 'verificar');
    }
    
    public function ver($idTramite, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data =  $this->loadData($idTramite);
        $this->cargarForm($data , 'ver');
    }

    public function volver() {
        $this->userInSession = $this->session->get('user');
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
           $filter = $this->session->get('filter');
           if ($filter != null && !empty($filter['urlRedirec'])) {
               return redirect()->to(base_url().'/'.$filter['urlRedirec']);
           }else {
              return redirect()->to(base_url());
          }
        }else if (!empty($this->userInSession) && $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) {
            return redirect()->to(base_url()."/dashboard");
        }
    }

    public function loadData($idTramite) {

        $tipoTramiteModel  = new TipoTramiteModel();
        $tramite = $this->tramiteModel->find($idTramite);
        $tramitePersonaModel = new TramitePersonaModel();
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tutor = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_parte_interesada', INT_UNO)->first();
        $convivientes = $tramitePersonaModel->findConvivientes($idTramite);
        $tramiteArchivoModel = new TramiteArchivoModel(); 
        
        $data['id_tramite'] = $tramite['id_tramite'];
        $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
        $data['autoridad_presentar'] = $tramite['autoridad_presentar'];
        $data['id_dependencia'] = $tramite['id_dependencia'];
        $data['estado'] = $tramite['estado'];
        $data['observaciones'] = $tramite['observaciones'];
        $data['contiene_firma_digital'] = $tramite['contiene_firma_digital'];
        
        $data['id_persona_titular'] = $titular['id_persona'];
        $data['nombre'] = $titular['nombre'];
        $data['apellido'] = $titular['apellido'];
        $data['fecha_nacimiento'] = $titular['fecha_nacimiento'];
        $data['id_tipo_documento'] = $titular['id_tipo_documento'];
        $data['documento'] = $titular['documento'];
//         $data['nro_tramite_dni'] = $titular['nro_tramite_dni'];
        $data['cuil'] = $titular['cuil'];
        $data['id_departamento'] = $titular['id_departamento'];
        $data['id_localidad'] = $titular['id_localidad'];
        $data['id_barrio'] = $titular['id_barrio'];
        $data['barrio'] = $titular['barrio'];
        $data['numero'] = $titular['numero'];
        $data['manzana'] = $titular['manzana'];
        $data['lote'] = $titular['lote'];
        $data['piso'] = $titular['piso'];
        $data['dpto'] = $titular['dpto'];
        $data['calle'] = $titular['calle'];
        $data['telefono'] = $titular['telefono'];
        $data['email'] = $titular['email'];
        $data['estado_aprobado']=TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['tipo_convivencia'] = $titular['tipo_convivencia'];
        $data['convivientes'] = $convivientes;
        $data['cntPersonasAgregadas'] = count($convivientes);
        
        if($tutor != null ) {
            $data['id_persona_tutor'] = $tutor['id_persona'];
            $data['nombre_tutor'] = $tutor['nombre'];
            $data['apellido_tutor'] = $tutor['apellido'];
            $data['id_tipo_documento_tutor'] = $tutor['id_tipo_documento'];
            $data['documento_tutor'] = $tutor['documento'];
        }

        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA;
        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] =$tipoTramite["controlador_title"];
        
        $localidadModel = new LocalidadModel();
        if (empty($idTramite)) {
            $data['localidades'] = [];
        } else {
            $data['localidades'] = $localidadModel->where('id_departamento', $titular['id_departamento'])->findAll();
        }
        

          // imagenes fotos  
          $fotoFrente  = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_FRENTE);
          $fotoDorso   = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_DORSO);
          $fotoFacturaServicio = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_FACTURA_SERVICIO);  
          
          if ($fotoFrente != null && 
              $fotoFrente[0]->ruta != null &&
              isset($fotoFrente[0]->ruta) &&
              file_exists($fotoFrente[0]->ruta."/".$fotoFrente[0]->nombre)
          ) {
              $image  =  file_get_contents($fotoFrente[0]->ruta."/".$fotoFrente[0]->nombre);
              $base64 = 'data:image/' . $fotoFrente[0]->tipo . ';base64,' . base64_encode($image);
              $data['fotoFrente'] = $base64;
              $data['fotoFrenteId'] = $fotoFrente[0]->id_tramite_archivo;
          } else {
              $data['fotoFrente'] = "";
              $data['fotoFrenteId'] ="";
          }
  
          if ($fotoDorso != null && 
              $fotoDorso[0]->ruta != null &&
              isset($fotoDorso[0]->ruta) &&
              file_exists($fotoDorso[0]->ruta."/".$fotoDorso[0]->nombre)
          ) {
              $image  =  file_get_contents($fotoDorso[0]->ruta."/".$fotoDorso[0]->nombre);
              $base64 = 'data:image/' . $fotoDorso[0]->tipo . ';base64,' . base64_encode($image);
              $data['fotoDorso'] = $base64;
              $data['fotoDorsoId'] = $fotoDorso[0]->id_tramite_archivo;
          } else {
              $data['fotoDorso'] = "";
              $data['fotoDorsoId'] ="";
          }
  
  
          if ($fotoFacturaServicio != null && 
              $fotoFacturaServicio[0]->ruta != null &&
              isset($fotoFacturaServicio[0]->ruta) &&
              file_exists($fotoFacturaServicio[0]->ruta."/".$fotoFacturaServicio[0]->nombre)
          ) {
              $image  =  file_get_contents($fotoFacturaServicio[0]->ruta."/".$fotoFacturaServicio[0]->nombre);
              $base64 = 'data:image/' . $fotoFacturaServicio[0]->tipo . ';base64,' . base64_encode($image);
              $data['fotoFacturaServicio'] = $base64;
              $data['fotoFacturaServicioId'] = $fotoFacturaServicio[0]->id_tramite_archivo;
          } else {
              $data['fotoFacturaServicio'] = "";
              $data['fotoFacturaServicioId'] ="";
          }
          

        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();
        $tipoParentescoModel = new TipoParentescoModel();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['tipoParentescos'] = $tipoParentescoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
//         $data['departamentos'] = $departamentoModel->where('id_departamento', 291)->findAll();
//         $data['departamentos'] = $departamentoModel->whereIn('id_departamento', [288, 291, 292, 293, 294, 297, 300, 302])->findAll();
        
        return $data;
    }

    public function cargarForm($data = [], $tipoForm ="wizard") {
        $tipoTramiteModel = new TipoTramiteModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoParentescoModel = new TipoParentescoModel();
        $departamentoModel = new DepartamentoModel();
        $localidadModel = new LocalidadModel();
        $dependenciaModel = new DependenciaModel();
        $utilBancoMacro = new UtilBancoMacro();

        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['tipoParentescos'] = $tipoParentescoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
//         $data['departamentos'] = $departamentoModel->where('id_departamento', 291)->findAll();
//         $data['departamentos'] = $departamentoModel->whereIn('id_departamento', [288, 291, 292, 293, 294, 297, 300, 302])->findAll();
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();

        if(isset($data['id_departamento']) && $data['id_departamento'] != null) {
            if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5) {
                $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
            }else {
//                 $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->where('id_localidad', 12794)->findAll();
                $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
            }
        }else {
            $data['localidades'] = [];
        }
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA;
        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] =$tipoTramite["controlador_title"];
        $data['tipoForm'] = $tipoForm;
        
//         var_dump($data['tipoForm']);
        if ( $tipoForm == "wizard") {
            $data["estado"] = TRAMITE_PENDIENTE_VALIDACION;
            $data["action"] = "";
            $data['turnoCantidades'] = []; // se inicializa las fechas de turnos en vacio, luego se carga por ajax
            $data['util'] = new Util();
            $data['contenidopaso1'] = $tipoTramite['controlador_view'];
            $data['contenido'] = "wizard/wizard";
            $data['contenidopaso2'] = "turno";            
            $data['title'] =$tipoTramite["controlador_title"];
        }else if ($tipoForm == "verificar") {
            $data["action"] = "verificar";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "verificacion_tramite";
        
        }else if ($tipoForm == "validar" || $tipoForm == "ver" || $tipoForm == "new") {
            $data["action"] = "edit";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "vista";
            $data['contenidoedit'] = "certificado_residencia_convivencia";
        } else {

             // Verifico si existen tramites anteriores            
             if ( !empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
                $listadoTramites = $this->tramiteModel->getTramiteValidado($data['documento'],$data['id_tramite'], TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA);
                if ( sizeof($listadoTramites) > 0 && ( $data['estado'] != TRAMITE_VALIDADO && $data['estado'] != TRAMITE_VALIDADO_VERIFICADO) ) {
                    $tramiteTmp = $listadoTramites[0];
                    $dataInformation = $this->loadData($tramiteTmp['id_tramite']);
                    $data['dataInformation'] = $dataInformation;
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista_doble";
                    $data['contenidoedit'] = "certificado_residencia_convivencia";
                    $data['contenidoview'] = "certificado_residencia_convivencia_view";    
                } else {
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista";
                    $data['contenidoedit'] = "certificado_residencia_convivencia";
                }
            } else {
                $data["action"] = "edit";
                $data["estados"] = $this->get_estados($data["estado"]);
                $data['contenido'] = "vista";
                $data['contenidoedit'] = "certificado_residencia_convivencia";
            }
        }
        
        $data['userInSession'] = $this->session->get('user');
        $data['ua'] = $this->request->getUserAgent();
        echo view("frontend", $data);
    }
   
    /**
     * Funcion que permite guardar la informacion
     */
    public function guardar() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
//             'nro_tramite_dni' => ['label' => 'N° de trámite que figura en tu DNI', 'rules' => 'required'],
//             'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'tipo_convivencia' => ['label' => '¿Convive solo o con otras personas?', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            //'id_dependencia' => ['label' => 'Comisaría donde se va a verificar y validar', 'rules' => 'required|numeric'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
//             'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor'] = $this->request->getVar('id_persona_tutor');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['estado'] = $this->request->getVar('estado');
        $data['cntPersonasAgregadas'] = $this->request->getVar('cntPersonasAgregadas');
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
        $data['documento'] = strtoupper($this->request->getVar('documento'));
//         $data['nro_tramite_dni'] = strtoupper($this->request->getVar('nro_tramite_dni'));
        $data['cuil'] = $this->request->getVar('cuil');
        $data['id_departamento'] = $this->request->getVar('id_departamento');
        $data['id_localidad'] = $this->request->getVar('id_localidad');
        $data['id_barrio'] = $this->request->getVar('id_barrio');
        $data['barrio'] = strtoupper($this->request->getVar('barrio'));
        $data['numero'] = $this->request->getVar('numero');
        $data['manzana'] = strtoupper($this->request->getVar('manzana'));
        $data['lote'] = strtoupper($this->request->getVar('lote'));
        $data['piso'] = strtoupper($this->request->getVar('piso'));
        $data['dpto'] = strtoupper($this->request->getVar('dpto'));
        $data['calle'] = strtoupper($this->request->getVar('calle'));
        $data['tipo_convivencia'] = strtoupper($this->request->getVar('tipo_convivencia'));
        $data['telefono'] = strtoupper($this->request->getVar('telefono'));
        $data['email'] = $this->request->getVar('email');
        $data['estado_pago'] = $this->request->getVar('estado_pago');
        
        $data['nombre_tutor'] = strtoupper($this->request->getVar('nombre_tutor'));
        $data['apellido_tutor'] = strtoupper($this->request->getVar('apellido_tutor'));
        $data['id_tipo_documento_tutor'] = $this->request->getVar('id_tipo_documento_tutor');
        $data['documento_tutor'] = strtoupper($this->request->getVar('documento_tutor'));
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['observaciones'] = $this->request->getVar('observaciones');
        $tipoForm = $this->request->getVar('tipo_form'); 
        if (empty($data['id_tipo_documento_tutor'])) {
            $data['id_tipo_documento_tutor'] = null;
        }

        //tipo form 
        $tipoForm = $this->request->getVar('tipoForm');

        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
                $data['porque_motivo'] = $spambot;
                $this->cargarForm($data, $tipoForm);
                return;
            }
            
            if($data['tipo_convivencia']==CONVIVE_CON_OTRAS_PERSONAS) { // si convive con otras personas
                if($data['cntPersonasAgregadas']==0) {
                    $data['error'] = "¡Debe agregar las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
                
                $data['operaciones'] = $this->request->getVar('operaciones'); // lista de insert, update, delete
                $data['id_personas'] = $this->request->getVar('id_personas');
                $data['id_tipo_documentos'] = $this->request->getVar('id_tipo_documentos');
                $data['documentos'] = $this->request->getVar('documentos');
                $data['apellidos'] = $this->request->getVar('apellidos');
                $data['nombres'] = $this->request->getVar('nombres');
                $data['cuiles'] = $this->request->getVar('cuiles');
                $data['parentescos'] = $this->request->getVar('parentescos');
                
                if($this->hayTipoDocumentosNulos($data)) { // si hay tipo documentos nulo o vacio se tira error
                    $data['error'] = "¡Debe ingresar los Tipo de documentos de todas las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
                if($this->hayDocumentosNulos($data)) { // si hay documentos nulo o vacio se tira error
                    $data['error'] = "¡Debe ingresar los Numeros de documentos de todas las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
                if($this->hayApellidosNulos($data)) { // si hay apellidos nulo o vacio se tira error
                    $data['error'] = "¡Debe ingresar los Apellidos de todas las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
                if($this->hayNombresNulos($data)) { // si hay nombres nulo o vacio se tira error
                    $data['error'] = "¡Debe ingresar los Nombres de todas las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
//                 if($this->hayCuilesNulos($data)) { // si hay cuiles nulo o vacio se tira error
//                     $data['error'] = "¡Debe ingresar el Cuil de todas las personas con las cuales convive!";
//                     $this->cargarForm($data, $tipoForm);
//                     return;
//                 }
                if($this->hayParentescosNulos($data)) { // si hay parentescos nulo o vacio se tira error
                    $data['error'] = "¡Debe ingresar el Parentesco de todas las personas con las cuales convive!";
                    $this->cargarForm($data, $tipoForm);
                    return;
                }
            }
            
            $id_tramite = null;
            if(empty($data['id_tramite'])) {
                $codigo = $this->util->generateRandomString(INT_DIEZ);
                while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                }
                $data['codigo'] = $codigo;
                $data['referencia_pago'] = COMISARIA_PAGO;
                $data['id_tipo_pago'] = TIPO_PAGO_CONTADO;
                $id_tramite = $this->tramiteModel->insertarCertificadoResidenciaConvivencia($data);
            }else {
                $id_tramite = $this->tramiteModel->updateCertificadoResidenciaConvivencia($data);
            }
            
            if($id_tramite==INT_MENOS_UNO) {
                $data['error'] = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                $this->cargarForm($data);
                return;
            }

            if(empty($data['id_tramite'])) { // insert
                // subir archivos
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoFrente', FOTO_FRENTE );
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoDorso', FOTO_DORSO);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'facturaServicio', FOTO_FACTURA_SERVICIO);
            } else {
                // edit 
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoFrente', FOTO_FRENTE );
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoDorso', FOTO_DORSO);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'facturaServicio', FOTO_FACTURA_SERVICIO);
            }
            
            // ---------------------
            $data['id_tramite'] = $id_tramite;
            if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
                return redirect()->to(base_url().'/dashboard');
            }else if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_COMISARIA_SECCIONAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
                $filter = $this->session->get('filter');
                if ($filter != null && !empty($filter['urlRedirec'])) {
                    $filter['documento'] = $data['documento'];
                    session()->set('filter', $filter);
                    return redirect()->to(base_url().'/'.$filter['urlRedirec']);
                }else {
                    return redirect()->to(base_url()."/dashboard");
                }
            }else {
                $data['contenido_paso1'] = "certificado_residencia";
                $data['contenido'] = "wizard/wizard";
                $data['action'] = "certificadoResidencia/guardarData";
            }
            return redirect()->to('/dashboard');
        } else {
//             var_dump("aqui estoy en tipo form");
            $this->cargarForm($data ,$tipoForm);
        }
    }
    
    public function guardarVerificacion() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'estado' => ['label' => 'Estado', 'rules' => 'required'],
            'verificador' => ['label' => 'Verificador', 'rules' => 'required'],
        ]);
        
        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['estado'] = $this->request->getVar('estado');
        $data['verificador'] = strtoupper($this->request->getVar('verificador'));
        $data['observaciones'] = $this->request->getVar('observaciones');
        
        if ($validation->withRequest($this->request)->run()) {
            $data['usuario_modificacion'] = session()->get('id');
            $data['fecha_modificacion'] = date('Y-m-d H:i:s');
            $this->tramiteModel->update($data['id_tramite'], $data);
            
            $this->userInSession = $this->session->get('user');
            if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
               $filter = $this->session->get('filter');
               if ($filter != null && !empty($filter['urlRedirec'])) {
                   return redirect()->to(base_url().'/'.$filter['urlRedirec']);
               }else {
                  return redirect()->to(base_url());
              }
            }else if (!empty($this->userInSession) && $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) {
                return redirect()->to(base_url()."/dashboard");
            }
        }else {
            
        }
    }

     /**
     * Funcion que permite guardar la informacion
     */
    public function guardarData() {
        $status = "ERROR";
        $message = "";
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
//             'nro_tramite_dni' => ['label' => 'N° de trámite que figura en tu DNI', 'rules' => 'required'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'tipo_convivencia' => ['label' => '¿Convive solo o con otras personas?', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
//             'id_dependencia' => ['label' => 'Comisaría donde se va a verificar y validar', 'rules' => 'required|numeric'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);
        
        $data['isPersonaValidada'] = $this->request->getVar('isPersonaValidada');
        if(!$data['isPersonaValidada']) {
            $validation->setRule('id_dependencia', 'Comisaría donde se va a verificar y validar', 'required|numeric');
        }

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor'] = $this->request->getVar('id_persona_tutor');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['cntPersonasAgregadas'] = $this->request->getVar('cntPersonasAgregadas');
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
        $data['documento'] = strtoupper($this->request->getVar('documento'));
//         $data['nro_tramite_dni'] = strtoupper($this->request->getVar('nro_tramite_dni'));
        $data['cuil'] = $this->request->getVar('cuil');
        $data['id_departamento'] = $this->request->getVar('id_departamento');
        $data['id_localidad'] = $this->request->getVar('id_localidad');
        $data['id_barrio'] = $this->request->getVar('id_barrio');
        $data['barrio'] = strtoupper($this->request->getVar('barrio'));
        $data['numero'] = $this->request->getVar('numero');
        $data['manzana'] = strtoupper($this->request->getVar('manzana'));
        $data['lote'] = strtoupper($this->request->getVar('lote'));
        $data['piso'] = strtoupper($this->request->getVar('piso'));
        $data['dpto'] = strtoupper($this->request->getVar('dpto'));
        $data['calle'] = strtoupper($this->request->getVar('calle'));
        $data['tipo_convivencia'] = strtoupper($this->request->getVar('tipo_convivencia'));
        $data['telefono'] = strtoupper($this->request->getVar('telefono'));
        $data['email'] = $this->request->getVar('email');
        $data['estado_pago']=ESTADO_PAGO_PENDIENTE;
        $data["estado"] = $this->request->getVar('estado');
        
        $data['nombre_tutor'] = strtoupper($this->request->getVar('nombre_tutor'));
        $data['apellido_tutor'] = strtoupper($this->request->getVar('apellido_tutor'));
        $data['id_tipo_documento_tutor'] = $this->request->getVar('id_tipo_documento_tutor');
        $data['documento_tutor'] = strtoupper($this->request->getVar('documento_tutor'));
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['id_tipo_pago'] = null;
        $data['referencia_pago'] = null;
        if (empty($data['id_tipo_documento_tutor'])) {
            $data['id_tipo_documento_tutor'] = null;
        }

        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data = [
                    'status' => $status,
                    'success' => false,
//                     'message' => "¡Ha ocurrido un error de validación, vuelva intentar!"
                    'message' => ""
                ];
                return $this->response->setJSON($data);
            }
            
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
            $recaptcha_response = $this->request->getVar('recaptcha_response');
            //             echo 'recaptcha_response=='.$recaptcha_response;
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            //             echo 'fff=='.$recaptcha;
            $recaptcha_json = json_decode($recaptcha);
            // Miramos si se considera humano o robot:
            if(empty($recaptcha_json) || (!empty($recaptcha_json) && $recaptcha_json->success && $recaptcha_json->score >= 0.6)) {
                if($data['tipo_convivencia']==CONVIVE_CON_OTRAS_PERSONAS) { // si convive con otras personas
                    if($data['cntPersonasAgregadas']==0) {
                        $message = "¡Debe agregar las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                        
                    }
                    
                    $data['id_tipo_documentos'] = $this->request->getVar('id_tipo_documentos');
                    $data['documentos'] = $this->request->getVar('documentos');
                    $data['apellidos'] = $this->request->getVar('apellidos');
                    $data['nombres'] = $this->request->getVar('nombres');
                    $data['cuiles'] = $this->request->getVar('cuiles');
                    $data['parentescos'] = $this->request->getVar('parentescos');
                    
                    if($this->hayTipoDocumentosNulos($data)) { // si hay tipo documentos nulo o vacio se tira error
                        $message = "¡Debe ingresar los Tipo de documentos de todas las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                    }
                    if($this->hayDocumentosNulos($data)) { // si hay documentos nulo o vacio se tira error
                        $error = "¡Debe ingresar los Numeros de documentos de todas las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                    }
                    if($this->hayApellidosNulos($data)) { // si hay apellidos nulo o vacio se tira error
                        $error = "¡Debe ingresar los Apellidos de todas las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                    }
                    if($this->hayNombresNulos($data)) { // si hay nombres nulo o vacio se tira error
                        $error = "¡Debe ingresar los Nombres de todas las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                    }
                    //                 if($this->hayCuilesNulos($data)) { // si hay cuiles nulo o vacio se tira error
                    //                     $error = "¡Debe ingresar el Cuil de todas las personas con las cuales convive!";
                    //                     $data = [
                    //                         'status'  => $status,
                    //                         'message' => $message
                    //                     ];
                    //                     return $this->response->setJSON($data);
                    //                 }
                    if($this->hayParentescosNulos($data)) { // si hay parentescos nulo o vacio se tira error
                        $error = "¡Debe ingresar el Parentesco de todas las personas con las cuales convive!";
                        $data = [
                            'status'  => $status,
                            'message' => $message
                        ];
                        return $this->response->setJSON($data);
                    }
                }
                
                $id_tramite = null;
                if(empty($data['id_tramite'])) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                    while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                        $codigo = $this->util->generateRandomString(INT_DIEZ);
                    }
                    $data['codigo'] = $codigo;
                    $id_tramite = $this->tramiteModel->insertarCertificadoResidenciaConvivencia($data);
                }else {
                    $id_tramite = $this->tramiteModel->updateCertificadoResidenciaConvivencia($data);
                }
                
                if($id_tramite==INT_MENOS_UNO) {
                    $error = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                    $data = [
                        'status'  => $status,
                        'message' => $message
                    ];
                    return $this->response->setJSON($data);
                }
                
                if(empty($data['id_tramite'])) {
                    // subir archivos
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoFrente', FOTO_FRENTE);
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'documentoDorso', FOTO_DORSO);
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA, 'facturaServicio', FOTO_FACTURA_SERVICIO);
                }
                
                /****************VERIFICA SI LA PERSONA TIENE ALGUN TRAMITE ASIGNADO PREVIAMENTE************* */

//                 $turnoModel = new TurnoModel();
//                 $turno = $turnoModel->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.turnos.id_tramite')
//                                         ->join('tramite_online.turno_dependencias', 'tramite_online.turno_dependencias.id_turno_cantidad = tramite_online.turnos.id_turno_cantidad')
//                                         ->where('tramite_online.turno_dependencias.id_dependencia', $data['id_dependencia'])
//                                         ->where('tramite_online.tramite_personas.documento', $data['documento'])
//                                         ->where('tramite_online.turnos.fecha >', date('Y-m-d'))
//                                         ->orderBy('tramite_online.turnos.id_turno', 'DESC')->first();

                /******************************************************************************************** */

                // ---------------------
                $data['id_tramite'] = $id_tramite;
                $data2 = [
                    'status' => "OK",
                    'id_tramite' => $id_tramite,
//                     'turno' => $turno,
                    'isPersonaValidada' => $data['isPersonaValidada'],
                    'turnoCantidades' => $this->getFechasDeTurnoPorDependencia($data['id_dependencia'])
                ];
                return $this->response->setJSON($data2);
            }else {
                log_message('error', 'ROBOT: recaptcha='.$recaptcha.', recaptcha_response='.$recaptcha_response.', documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data = [
                    'status' => "ERROR",
                    'success' => false,
                    //                     'message' => "¡Ha ocurrido un error de validación, vuelva intentar!"
                    'message' => ""
                ];
                return $this->response->setJSON($data);
            }            
        } else {
            $data = [
                'message' => $validation->getErrors(),
                'status' => "ERROR",
                'errors'  => true
            ];

            return $this->response->setJSON($data);
        }
    }
    
    public function eliminarPersona($id_persona, $id_tramite) {
        if(!empty($id_persona) && !empty($id_tramite)) {
            $tramitePersonaModel = new TramitePersonaModel();
            $tramitePersonaModel->where('id_tramite', $id_tramite)->where('id_persona', $id_persona)->delete();
            $data = [
                'message' => 'La persona se ha borrado correctamente.',
                'error'  => false
            ];
            return $this->response->setJSON($data);
        }else {
            $data = [
                'message' => 'El id de la persona y el id del tramite son obligatorios.',
                'error'  => true
            ];
            return $this->response->setJSON($data);
        }
    }
    
    private function hayTipoDocumentosNulos($data) {
        foreach ($data['id_tipo_documentos'] as $id => $value) {
//             echo 'valor='.$value;
            if(trim($value)=='') {
                return true;
            }
        }
//         die;
        return false;
    }
    
    private function hayDocumentosNulos($data) {
        foreach ($data['documentos'] as $id => $value) {
            if(trim($value)=='') {
                return true;
            }
        }
        return false;
    }
    
    private function hayApellidosNulos($data) {
        foreach ($data['apellidos'] as $id => $value) {
            if(trim($value)=='') {
                return true;
            }
        }
        return false;
    }
    
    private function hayNombresNulos($data) {
        foreach ($data['nombres'] as $id => $value) {
            if(trim($value)=='') {
                return true;
            }
        }
        return false;
    }
    
    private function hayCuilesNulos($data) {
        foreach ($data['cuiles'] as $id => $value) {
            if(trim($value)=='') {
                return true;
            }
        }
        return false;
    }
    
    private function hayParentescosNulos($data) {
        foreach ($data['parentescos'] as $id => $value) {
            if(trim($value)=='') {
                return true;
            }
        }
        return false;
    }

    protected function getBodyTramite($html, $tramite, $titularTramite) {
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);
//         $dependenciaModel = new DependenciaModel();
        $convivientes = null;
        if($titularTramite['tipo_convivencia'] == CONVIVE_CON_OTRAS_PERSONAS) {
            $tramitePersonaModel = new TramitePersonaModel();
//          $convivientes2 = $personaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', 0)->where('es_parte_interesada', 0)->findAll();
            $convivientes = $tramitePersonaModel->findConvivientes($tramite['id_tramite']);
        }
//         $parte_interesada = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_parte_interesada', 1)->first();
//         $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
        $fechaCastellano = $this->fechaUtil->fechaCastellano(2);
        $html = $html . '<table>
        <tr>
        <td width="100%" align="justify">
            <div align="center">

                <h1><b><u>CERTIFICADO DE RESIDENCIA Y CONVIVENCIA</u></b></h1>
                <br/><br/>
            </div>

- - - : El Funcionario Policial que suscribe <u><b>CERTIFICA</b></u>: Que ' . $titularTramite['apellido'] . ' ' . $titularTramite['nombre'] . ' 
quien acredita su identidad personal mediante la presentación de su
'.$tipoDocumento['tipo_documento'].' Nº ' . $titularTramite['documento'] . ', actualmente <u><b>VIVE</b></u> y <u><b>RESIDE</b></u> en ' . $this->util->getDireccion2($titularTramite);

        if($titularTramite['tipo_convivencia'] == CONVIVE_SOLO) {
            $html = $html . ' y <u><b>CONVIVE SOLO</b></u>, según verificación efectuada por: '.$tramite['verificador'].' actual numerario de esta unidad.<br/>';
        }else {
            $html = $html . ', <u><b>JUNTO A SU</b></u> ';

            if ( $convivientes != null) {
                $cntConvivientes = count($convivientes);
                //         $html = $html . '$cntConvivientes='.$cntConvivientes;
                foreach($convivientes as $index => $item):
                $html = $html . $item['parentesco'] .' '. $item['apellido'] .' '. $item['nombre'] .' '.$item['tipo_documento'].' N° '. $item['documento'];
                if($cntConvivientes != $index+1) {
                    $html = $html . ', ';
                }
                endforeach;
            }
          
            $html = $html . ', según verificación efectuada por: '.$tramite['verificador'].' actual numerario de esta unidad.<br/>';
        }
        
        $html = $html . '- - - : A solicitud de la parte interesada y al solo efecto de ser presentado ante ' . $tramite['autoridad_presentar'] . '
, se expide, firma y estampa código QR en la UNIDAD ADMINISTRATIVA DIGITAL, con asiento en la ciudad de SAN SALVADOR DE JUJUY, PROVINCIA DE JUJUY, REPUBLICA ARGENTINA a los ' . $fechaCastellano . '.
    
                        </td>
                    </tr>
                </table>
                </body>';
        return $html;
    }

    /**
     * Funcion que permite guardar el archivo en 
     * un disco determinado
     */
    public function guardarFileDisk($id_tramite) {
        $pathFile = WRITEPATH . 'archivos/';
        $data = $this->tramiteModel->find($id_tramite);
        $tramitePersonaModel = new TramitePersonaModel();
        $titular_tramite = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();


        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);

        $pdf->AddPage();
        $html = $pdf->get_header();
        $html = $this->get_body($html, $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        //         $url_validacion_qr = base_url().'permiso/validar/'.$id_permiso_circulacion;
        $url_validacion_qr = base_url() . 'permiso/validar/' . $data['codigo'];
        //         $url_validacion_qr = base_url().'inicio/desencriptar/123456789123456789123456789146498797897897897878';
        // set style for barcode
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,L : QR-CODE Low error correction
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 40, 40, $style, 'N');
        //         $pdf->write2DBarcode($html_encriptado, 'QRCODE,L', 12, 10, 40, 40, $style, 'N', true);
       // ob_end_clean();
       $date =  date('dmYsiH');
       $pdf->Output($pathFile.$titular_tramite['cuil']."-".$id_tramite."-".$date.".pdf", 'F');   
    }
}
