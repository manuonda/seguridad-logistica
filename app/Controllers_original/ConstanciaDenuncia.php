<?php
namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\DependenciaModel;
use App\Models\TramitePersonaModel;
use App\Libraries\Util;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
use App\Libraries\UtilBancoMacro;
use App\Models\TipoTramiteModel;
use App\Models\TramiteArchivoModel;


class ConstanciaDenuncia extends BaseController {

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
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CONSTANCIA_DENUNCIA;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_departamento'] = 291; // Dr manuel belgrano
        $data['id_localidad'] = 12794; // San salvador de jujuy
        
        // Identificador de la pagina para el websocket de turnos
        $time = time();
        $data['turno_user_id'] = TIPO_TRAMITE_CONSTANCIA_DENUNCIA . date("dmYHis", $time) . rand(1,10000);
        $this->cargarForm($data,"wizard");
    }
    
    public function edit($idTramite) {
        $data = $this->loadData($idTramite);
        $this->cargarForm($data, "edit");
    }

    public function new(){
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CONSTANCIA_DENUNCIA;
        $data['estado_aprobado']=TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $this->cargarForm($data, "new");   
    }
    
    public function nuevo($id_tipo_documento, $documento, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CONSTANCIA_DENUNCIA;
        $data['estado'] = TRAMITE_VALIDADO;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_tipo_documento'] = $id_tipo_documento;
        $data['documento'] = $documento;
        $data['id_departamento']=291;
        $data['id_localidad']=12794;
        $data['autoridad_presentar'] = 'LAS AUTORIDADES QUE LO REQUIERAN';
//         $data['descripcion_denuncia'] = '<p>- - - - -:El Funcionario Policial que suscribe a los efectos legales hace CONSTAR: Que a partir de fecha: dd/mm/aaaa se inician ..................&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>DENUNCIANTE:</b><br><b>VICTIMA / DAMNIFICADO:</b><br><b>INCULPADO / PROTAGONISTAS:</b><br><b>HECHO CONSISTE:</b><br><b>PREVENTIVO DIGITAL:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br></p>';
        $data['descripcion_denuncia'] = '- - - - -:El Funcionario Policial que suscribe a los efectos legales hace CONSTAR: Que a partir de fecha: dd/mm/aaaa se inician ... <b>DENUNCIANTE:</b> ............ <b>VICTIMA / DAMNIFICADO:</b>&nbsp; ........................... <b>INCULPADO / PROTAGONISTAS:</b>&nbsp; ............................ <b>HECHO CONSISTE:</b> .....................<b>PREVENTIVO DIGITAL:</b> ';
        
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
    
    public function ver($idTramite, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data =  $this->loadData($idTramite);
        $this->cargarForm($data , 'ver');
    }
    
    public function volver() {
        $this->userInSession = $this->session->get('user');
        if(!empty($this->userInSession) && $this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL) {
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
        $tramiteArchivoModel = new TramiteArchivoModel();
        // carga de arrays
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();

        $data['tipoDocumentos']  = $tipoDocumentoModel->findAll();
        $data['dependencias']    = $dependenciaModel->findAllHabilitado();
        $data['departamentos']   = $departamentoModel->where('id_provincia', 9)->findAll();
        $data['estado']          = $tramite['estado'];
        $data['observaciones'] = $tramite['observaciones'];
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        
        $data['id_tramite'] = $tramite['id_tramite'];
        $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
        $data['autoridad_presentar'] = $tramite['autoridad_presentar'];
        $data['id_dependencia'] = $tramite['id_dependencia'];
        
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
        $data['fecha_denuncia'] = $tramite['fecha_denuncia'];
        $data['hora_denuncia'] = $tramite['hora_denuncia'];
        $data['id_dependencia'] = $tramite['id_dependencia'];
        $data['oficial_tomo_denuncia'] = $tramite['oficial_tomo_denuncia'];
        $data['telefono'] = $titular['telefono'];
        $data['email'] = $titular['email'];
        $data['descripcion_denuncia'] = $tramite['descripcion_denuncia'];
        
        $localidadModel = new LocalidadModel();
        if($data['id_departamento'] != null && isset($data['id_departamento'])) {
            $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
        }
        

         // imagenes fotos  
         $fotoFrente  = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_FRENTE);
         $fotoDorso   = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_DORSO);
         
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
 

        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] =$tipoTramite["controlador_title"];
        $data['contenidopaso1'] = $tipoTramite['controlador_view'];

        return $data;
    }

    public function cargarForm($data = [],$tipoForm="wizard") {
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $localidadModel = new LocalidadModel();
        $dependenciaModel = new DependenciaModel();
        $tipoTramiteModel = new TipoTramiteModel();
        $utilBancoMacro = new UtilBancoMacro();

        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();
        
        if(isset($data['id_departamento']) && $data['id_departamento'] != null) {
            $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
        }else {
            $data['localidades'] = [];
        }

        $data['id_tipo_tramite'] = TIPO_TRAMITE_CONSTANCIA_DENUNCIA;
        $data['contenido'] = "constancia_denuncia";
        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] =$tipoTramite["controlador_title"];

        if($tipoForm == "wizard") {
            $data["estado"] = TRAMITE_PENDIENTE_VALIDACION;
            $data["action"] = "";
            $data['turnoCantidades'] = []; // se inicializa las fechas de turnos en vacio, luego se carga por ajax
            $data['util'] = new Util();
            $data['contenidopaso1'] = $tipoTramite['controlador_view'];
            $data['contenido'] = "wizard/wizard";
            $data['contenidopaso2'] = "turno";            
            $data['title'] =$tipoTramite["controlador_title"];
            
        }else if ($tipoForm == "validar" || $tipoForm == "ver" || $tipoForm == "new") {
            $data["action"] = "edit";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "vista";
            $data['contenidoedit'] = "constancia_denuncia";
           
        }else {
            // Verifico si existen tramites anteriores            
            if ( !empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
                $listadoTramites = $this->tramiteModel->getTramiteValidado($data['documento'],$data['id_tramite'], TIPO_TRAMITE_CONSTANCIA_DENUNCIA);
                if ( sizeof($listadoTramites) > 0 && ( $data['estado'] != TRAMITE_VALIDADO && $data['estado'] != TRAMITE_VALIDADO_VERIFICADO) ) {
                    $tramiteTmp = $listadoTramites[0];
                    $dataInformation = $this->loadData($tramiteTmp['id_tramite']);
                    $data['dataInformation'] = $dataInformation;
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista_doble";
                    $data['contenidoedit'] = "constancia_denuncia";
                    $data['contenidoview'] = "constancia_denuncia_view";    
                } else {
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista";
                    $data['contenidoedit'] = "constancia_denuncia";
                }
            } else {
                $data["action"] = "edit";
                $data["estados"] = $this->get_estados($data["estado"]);
                $data['contenido'] = "vista";
                $data['contenidoedit'] = "constancia_denuncia";
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
        $data['tipo_supervivencia'] = $this->request->getVar('tipo_supervivencia');
        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor']   = $this->request->getvar('id_persona_tutor');
        $data['id_tipo_tramite']    = $this->request->getVar('id_tipo_tramite');
        $data['estado']             = $this->request->getVar('estado');  

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
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'fecha_denuncia' => ['label' => 'Fecha que realizó la denuncia', 'rules' => 'required|exact_length[10]'],
//             'hora_denuncia' => ['label' => 'Hora aproximada que realizó la denuncia', 'rules' => 'required|exact_length[8]'],
           // 'id_dependencia' => ['label' => 'Comisaría seccional donde realizó la denuncia', 'rules' => 'required|numeric'],
            'oficial_tomo_denuncia' => ['label' => 'Oficial que tomó la denuncia', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
//             'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
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
        $data['telefono'] = strtoupper($this->request->getVar('telefono'));
        $data['email'] = $this->request->getVar('email');
        $data['fecha_denuncia'] = $this->request->getVar('fecha_denuncia');
        $data['hora_denuncia'] = $this->request->getVar('hora_denuncia');
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['oficial_tomo_denuncia'] = strtoupper($this->request->getVar('oficial_tomo_denuncia'));
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['estado_pago'] = $this->request->getVar('estado_pago');
        $data['observaciones'] = $this->request->getVar('observaciones');
        $data['descripcion_denuncia'] = $this->request->getVar('descripcion_denuncia');
        $tipoForm = $this->request->getVar('tipoForm');
        
        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
                $data['porque_motivo'] = $spambot;
                $this->cargarForm($data);
                return;
            }

            $id_tramite = null;
            if(empty($data['id_tramite'])) {
                $codigo = $this->util->generateRandomString(INT_DIEZ);
                while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                }
                $data['codigo'] = $codigo;
                $data['referencia_pago'] = COMISARIA_PAGO;
                $id_tramite = $this->tramiteModel->insertarConstanciaDenuncia($data);
            }else {
                $id_tramite = $this->tramiteModel->updateConstanciaDenuncia($data);
            }
            
            if($id_tramite==INT_MENOS_UNO) {
                $data['error'] = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                $this->cargarForm($data);
                return;
            }
            
            if(empty($data['id_tramite'])) { // insert
                // subir archivos
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoFrente', FOTO_FRENTE );
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoDorso', FOTO_DORSO);
            } else {
                // edit 
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoFrente', FOTO_FRENTE );
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoDorso', FOTO_DORSO);
            }
            
            // ---------------------
            $data['id_tramite'] = $id_tramite;
            if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
                return redirect()->to(base_url().'/dashboard');
            }else if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_COMISARIA_SECCIONAL || session()->get('id_rol')==ROL_CIAC)) {
                $filter = $this->session->get('filter');
                if ($filter != null && !empty($filter['urlRedirec'])) {
                    $filter['documento'] = $data['documento'];
                    session()->set('filter', $filter);
                    return redirect()->to(base_url().'/'.$filter['urlRedirec']);
                }else {
                    return redirect()->to(base_url());
                }
            }else {
                $data['contenido_paso1'] = "constancia_denuncia";
                $data['contenido'] = "wizard/wizard";
                $data['action'] = "constanciaDenuncia/guardarData";
            }
            return redirect()->to('/dashboard');
        } else {
            $this->cargarForm($data, $tipoForm);
        }
    }


    public function guardarData(){
        $status = "ERROR";
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
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'fecha_denuncia' => ['label' => 'Fecha que realizó la denuncia', 'rules' => 'required|exact_length[10]'],
            'hora_denuncia' => ['label' => 'Hora aproximada que realizó la denuncia', 'rules' => 'required|exact_length[5]'],
            'id_dependencia' => ['label' => 'Comisaría seccional donde realizó la denuncia', 'rules' => 'required|numeric'],
            'oficial_tomo_denuncia' => ['label' => 'Oficial que tomó la denuncia', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
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
        $data['telefono'] = strtoupper($this->request->getVar('telefono'));
        $data['email'] = $this->request->getVar('email');
        $data['fecha_denuncia'] = $this->request->getVar('fecha_denuncia');
        $data['hora_denuncia'] = $this->request->getVar('hora_denuncia');
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['oficial_tomo_denuncia'] = strtoupper($this->request->getVar('oficial_tomo_denuncia'));
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['estado_pago']=ESTADO_PAGO_PENDIENTE;
        $data["estado"] = $this->request->getVar('estado');
        
        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data = [
                    'status' => "ERROR",
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
                $data['isPersonaValidada'] = false;
                $id_tramite = null;
                if(empty($data['id_tramite'])) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                    while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                        $codigo = $this->util->generateRandomString(INT_DIEZ);
                    }
                    $data['codigo'] = $codigo;
                    
                    $id_tramite = $this->tramiteModel->insertarConstanciaDenuncia($data);
                }else {
                    $id_tramite = $this->tramiteModel->updateConstanciaDenuncia($data);
                }
                
                if($id_tramite==INT_MENOS_UNO) {
                    $message = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                    $data = [
                        'status'  => $status,
                        'message' => $message
                    ];
                    return $this->response->setJSON($data);
                }
                
                if(empty($data['id_tramite'])) {
                    // subir archivos
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoFrente', FOTO_FRENTE);
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_CONSTANCIA_DENUNCIA, 'documentoDorso', FOTO_DORSO);
                }
                $data['id_tramite'] = $id_tramite;
                $data = [
                    'status' => "OK",
                    'id_tramite' => $id_tramite,
                    'isPersonaValidada' => $data['isPersonaValidada']
                ];
                return $this->response->setJSON($data);
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
    
    protected function getBodyTramite($html, $tramite, $titularTramite) {
        $dependencia['dependencia'] = "Unidad Administrativa de Policía";
        $fechaCastellano = $this->fechaUtil->fechaCastellano(2);
        $html = $html . '<table>
        <tr>
        <td width="100%" align="justify">
            <div align="center">

                <h1><b><u>CONSTANCIA POLICIAL POR DENUNCIA</u></b></h1>
                <br/><br/>
            </div>' . $tramite['descripcion_denuncia'];
        
        $html = $html . '<br/><br/>- - - - -:A solicitud de la parte interesada y al solo efecto de ser presentado ante ' . $tramite['autoridad_presentar'] . '
, se expide, firma y estampa código QR en la UNIDAD ADMINISTRATIVA DIGITAL, con asiento en la ciudad de SAN SALVADOR DE JUJUY, PROVINCIA DE JUJUY, REPUBLICA ARGENTINA a los ' . $fechaCastellano . '.-
    
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
