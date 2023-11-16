<?php
namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\DependenciaModel;
use App\Models\TramitePersonaModel;
use App\Models\TurnoModel;
use App\Libraries\Util;
use App\Libraries\FechaUtil;
use App\Models\TipoTramiteModel;
use App\Libraries\UtilBancoMacro;
use App\Models\EstadoCivilModel;
use App\Models\PaisModel;

use App\Libraries\Pdf;


class ExposicionPorJustificativoLaboralPorFaltaDeTransporte extends BaseController {

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
        $data['id_tipo_tramite'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE;
        $tipoTramiteModel = new TipoTramiteModel();
        $tramite = $tipoTramiteModel->find(TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE);
        $data['importe'] = $tramite['precio'];

        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_departamento'] = 291; // Dr manuel belgrano
        $data['id_localidad'] = 12794; // San salvador de jujuy
        
        // Identificador de la pagina para el websocket de turnos
        $time = time();
        $data['turno_user_id'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE . date("dmYHis", $time) . rand(1,10000);
        $this->cargarForm($data , 'wizard');
    }
    
    public function edit($idTramite) {
        $data =  $this->loadData($idTramite);
        $this->cargarForm($data , 'edit');
    }
    
    public function new(){
        $data['id_tipo_tramite'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE;
        $data['estado']=TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $this->cargarForm($data, "new");   
    }
    
    public function nuevo($id_tipo_documento, $documento, $urlRedirec) {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE;
        $data['estado']=TRAMITE_PENDIENTE_VALIDACION;
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

    /**
     * Funcion que permite cargar los datos 
     * referido al tramite de la persona
     */
    public function loadData($idTramite) {

        $tramite = $this->tramiteModel->find($idTramite);
        $tramitePersonaModel = new TramitePersonaModel();
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tutor = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_parte_interesada', INT_UNO)->first();

        $data['id_tramite'] = $tramite['id_tramite'];
        $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
        $data['autoridad_presentar'] = $tramite['autoridad_presentar'];
        $data['fecha_paro_transporte'] = $tramite['fecha_paro_transporte'];
       
        $data['id_dependencia'] = $tramite['id_dependencia'];
        $data['estado'] = $tramite['estado'];
        $data['codigo'] = $tramite['codigo'];
        $data['observaciones'] = $tramite['observaciones'];
        $data['motivo'] = $tramite['motivo'];
        $data['contiene_firma_digital'] = $tramite['contiene_firma_digital'];
        $data['id_persona_titular'] = $titular['id_persona'];
        $data['nombre'] = $titular['nombre'];
        $data['apellido'] = $titular['apellido'];
        $data['fecha_nacimiento'] = $titular['fecha_nacimiento'];
        $data['id_tipo_documento'] = $titular['id_tipo_documento'];
        $data['documento'] = $titular['documento'];
        $data['cuil'] = $titular['cuil'];
        $data['id_pais'] = $titular['id_pais'];
        $data['id_estado_civil'] = $titular['id_estado_civil'];
        $data['profesion'] = $titular['profesion'];
        $data['lugar_de_trabajo'] = $titular['lugar_de_trabajo'];
        $data['empresa_transporte'] = $titular['empresa_transporte'];
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
        
        if($tutor != null ) {
            $data['id_persona_tutor'] = $tutor['id_persona'];
            $data['nombre_tutor'] = $tutor['nombre'];
            $data['apellido_tutor'] = $tutor['apellido'];
            $data['id_tipo_documento_tutor'] = $tutor['id_tipo_documento'];
            $data['documento_tutor'] = $tutor['documento'];    
        }
       
        $paisModel = new PaisModel();
        $estadoCivilModel = new EstadoCivilModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();
        $localidadModel   = new LocalidadModel();
        $data['paises'] = $paisModel->findAll();
        $data['estadosCiviles'] = $estadoCivilModel->findHabilitados();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
//         $data['departamentos'] = $departamentoModel->where('id_departamento', 291)->findAll();
//         $data['departamentos'] = $departamentoModel->whereIn('id_departamento', [288, 291, 292, 293, 294, 297, 300, 302])->findAll();

        if(empty($data['id_tramite'])) {
            $data['localidades'] = [];
        } else if(!empty($data['id_departamento'])) {
            $data['localidades'] = $localidadModel->findByIdDepartamento($data['id_departamento']);
        }
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE;
        $data['contenidopaso1'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";
        $data['title'] ="Certificado de Residencia";
        $data['action'] = "certificadoResidencia/guardarData";

        return $data;
    }

    /**
     * Funcion que permite cargar el formulario para edicion o vista de wizard.
     * En el caso de edicion se verifica si el tramite se debe verificar su dato o no 
     * a traves de otra pantalla
     */
    public function cargarForm($data = [] , $tipoForm = "wizard") {
        $paisModel = new PaisModel();
        $estadoCivilModel = new EstadoCivilModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $localidadModel = new LocalidadModel();
        $dependenciaModel = new DependenciaModel();
        $tipoTramiteModel  = new TipoTramiteModel();
        $utilBancoMacro = new UtilBancoMacro();
        
        $data['paises'] = $paisModel->findAll();
        $data['estadosCiviles'] = $estadoCivilModel->findHabilitados();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();

        if(isset($data['id_departamento']) && $data['id_departamento'] != null) {
            if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5) {
                $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
            }else {
                $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
            }
        }else {
            $data['localidades'] = [];
        }
       
        $data['id_tipo_tramite'] = TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE;
        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] =$tipoTramite["controlador_title"];
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();


        if ($tipoForm == "wizard") {
            $data["estado"] = TRAMITE_PENDIENTE_VALIDACION;
            $data["action"] = "";
            $data["estados"] = [];
            $data['turnoCantidades'] = [];
            $data['contenidopaso1'] = $tipoTramite['controlador_view'];
            $data['contenidopaso2'] = "turno";
            $data['contenido'] = "wizard/wizard";
            
        }else if ($tipoForm == "verificar") {
            $data["action"] = "verificar";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "verificacion_tramite";
           
        }else if ($tipoForm == "validar" || $tipoForm == "ver" || $tipoForm == "new") {
            $data["action"] = "edit";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "vista";
            $data['contenidoedit'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";

        } else {
            // Verifico si existen tramites anteriores            
            if ( !empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
                $listadoTramites = $this->tramiteModel->getTramiteValidado($data['documento'], $data['id_tramite'], TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE);
                if ( sizeof($listadoTramites) > 0 && $data['estado'] != TRAMITE_VALIDADO ) {
                    $tramiteTmp = $listadoTramites[0];
                    $dataInformation = $this->loadData($tramiteTmp['id_tramite']);
                    $data['dataInformation'] = $dataInformation;
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista_doble";
                    $data['contenidoedit'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";
                    $data['contenidoview'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte_view";
                } else {
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista";
                    $data['contenidoedit'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";
                }
            } else {
                $data["action"] = "edit";
                $data["estados"] = $this->get_estados($data["estado"]);
                $data['contenido'] = "vista";
                $data['contenidoedit'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";
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
            'estado' => ['label' => 'Estado', 'rules' => 'required'],
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            'id_pais' => ['label' => 'Nacionalidad', 'rules' => 'required|numeric'],
            'id_estado_civil' => ['label' => 'Estado civil', 'rules' => 'required|exact_length[1]'],
            'profesion' => ['label' => 'Profesion', 'rules' => 'required|min_length[2]|max_length[100]'],
            'lugar_de_trabajo' => ['label' => 'Lugar de trabajo', 'rules' => 'required|min_length[2]|max_length[100]'],
            'empresa_transporte' => ['label' => 'Empresa de transporte en que viaja', 'rules' => 'required|min_length[2]|max_length[100]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            'fecha_paro_transporte' => ['label' => 'Fecha de paro del transporte', 'rules' => 'required|exact_length[10]'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['estado'] = $this->request->getVar('estado');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor'] = $this->request->getVar('id_persona_tutor');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
        $data['documento'] = strtoupper($this->request->getVar('documento'));
        $data['cuil'] = $this->request->getVar('cuil');
        $data['id_pais'] = $this->request->getVar('id_pais');
        $data['id_estado_civil'] = $this->request->getVar('id_estado_civil');
        $data['profesion'] = strtoupper($this->request->getVar('profesion'));
        $data['lugar_de_trabajo'] = strtoupper($this->request->getVar('lugar_de_trabajo'));
        $data['empresa_transporte'] = strtoupper($this->request->getVar('empresa_transporte'));
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
        $data['estado_pago'] = $this->request->getVar('estado_pago');

        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['fecha_paro_transporte'] = $this->request->getVar('fecha_paro_transporte');
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['observaciones'] = $this->request->getVar('observaciones');
        $data['motivo'] = strtoupper($this->request->getVar('motivo'));

        //tipo form 
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
            if(empty($data['id_tramite'])) { // insert
                $codigo = $this->util->generateRandomString(INT_DIEZ);
                while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                }
                $data['codigo'] = $codigo;
                $data['referencia_pago'] = COMISARIA_PAGO;
                $data['id_tipo_pago'] = TIPO_PAGO_CONTADO;
                $id_tramite = $this->tramiteModel->insertarExposicionPorJustificativoLaboralPorFaltaDeTransporte($data);
            }else { // update
                $id_tramite = $this->tramiteModel->updateExposicionPorJustificativoLaboralPorFaltaDeTransporte($data);
            }
            
            if($id_tramite==INT_MENOS_UNO) { // error al guardar en bbdd
                $data['error'] = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                $this->cargarForm($data);
                return;
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
                    return redirect()->to(base_url());
                }
            }else {
                $data['contenido_paso1'] = "exposicionPorJustificativoLaboralPorFaltaDeTransporte";
                $data['contenido'] = "wizard/wizard";
                $data['action'] = "certificadoResidencia/guardarData";
            }
            return redirect()->to('/dashboard');
        } else {
            $this->cargarForm($data, $tipoForm);
        }
    }

    /**
     * Funcion que permite realizar el guardado de los datos
     * mediante un form por ajax 
     */
    public function guardarData() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_pais' => ['label' => 'Nacionalidad', 'rules' => 'required|numeric'],
            'id_estado_civil' => ['label' => 'Estado civil', 'rules' => 'required|exact_length[1]'],
            'profesion' => ['label' => 'Profesion', 'rules' => 'required|min_length[2]|max_length[100]'],
            'lugar_de_trabajo' => ['label' => 'Lugar de trabajo', 'rules' => 'required|min_length[2]|max_length[100]'],
            'empresa_transporte' => ['label' => 'Empresa de transporte en que viaja', 'rules' => 'required|min_length[2]|max_length[100]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'email' => ['label' => 'Email', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            'fecha_paro_transporte' => ['label' => 'Fecha de paro del transporte', 'rules' => 'required|exact_length[10]'],
        ]);
        
        $data['isPersonaValidada'] = $this->request->getVar('isPersonaValidada');
        if(!$data['isPersonaValidada']) {
            $validation->setRule('id_dependencia', 'Comisaría donde se va a verificar y validar', 'required|numeric');
        }

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor'] = $this->request->getVar('id_persona_tutor');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
        $data['documento'] = strtoupper($this->request->getVar('documento'));
        $data['cuil'] = $this->request->getVar('cuil');
        $data['id_pais'] = $this->request->getVar('id_pais');
        $data['id_estado_civil'] = $this->request->getVar('id_estado_civil');
        $data['profesion'] = strtoupper($this->request->getVar('profesion'));
        $data['lugar_de_trabajo'] = strtoupper($this->request->getVar('lugar_de_trabajo'));
        $data['empresa_transporte'] = strtoupper($this->request->getVar('empresa_transporte'));
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
        $data['estado_pago']=ESTADO_PAGO_PENDIENTE;
        $data["estado"] = $this->request->getVar('estado');
        $data['motivo'] = strtoupper($this->request->getVar('motivo'));

        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['fecha_paro_transporte'] = $this->request->getVar('fecha_paro_transporte');
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['id_tipo_pago'] = null;
        $data['referencia_pago'] = null;

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
                $id_tramite = null;
                if(empty($data['id_tramite'])) { // insert
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                    while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                        $codigo = $this->util->generateRandomString(INT_DIEZ);
                    }
                    $data['codigo'] = $codigo;
                    $id_tramite = $this->tramiteModel->insertarExposicionPorJustificativoLaboralPorFaltaDeTransporte($data);
                }else { // update
                    $id_tramite = $this->tramiteModel->updateExposicionPorJustificativoLaboralPorFaltaDeTransporte($data);
                }
                
                if($id_tramite==INT_MENOS_UNO) {
                    $data['error'] = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                    $data = [
                        'success' => false,
                        'message' => "Error de Validacion 2"
                    ];
                    return $this->response->setJSON($data);
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

    protected function getBodyTramite($html, $tramite, $titularTramite) {
        $paisModel = new PaisModel();
        $pais = $paisModel->find($titularTramite['id_pais']);
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);
        $fechaCastellano = $this->fechaUtil->fechaCastellano(2);
        
        $html = $html . '<table>
            <tr>
                <td width="100%" align="center">
                    <br/><br/>
                    <font size="14"><b>EXPOSICIÓN POR JUSTIFICATIVO LABORAL POR FALTA DE TRANSPORTE</b></font>
                </td>
            </tr>
            <tr>
                <td width="100%" align="justify">
<br/><br/>
----: La DIRECCION ADMINISTRATIVA DIGITAL – POLICIA DE LA PROVINCIA DE JUJUY, mediante el uso de
la plataforma https://tramites.seguridad.jujuy.gob.ar, ha llevado adelante la confección del presente
documento, según datos aportados por el solicitante, se EXPONE: Que NO PUDO ASISTIR A SU LUGAR DE
TRABAJO, por causa de <b>PARO DE TRANSPORTE - INTRANSITABILIDAD</b>.<br/><br/>

DATOS PERSONALES:
<br/>
<table border="0">
    <tr>
        <td width="20%" bgcolor="#c3c3c3">APELLIDO/S:</td>
        <td width="80%">&nbsp;&nbsp;<b>' . $titularTramite['apellido'] . '</b></td>
    </tr>
    <tr>
        <td bgcolor="#c3c3c3">NOMBRE/S:</td>
        <td colspan="3">&nbsp;&nbsp;<b>' . $titularTramite['nombre'] . '</b></td>
    </tr>
    <tr>
        <td width="20%" bgcolor="#c3c3c3">' .$tipoDocumento['tipo_documento']. ':</td>
        <td width="30%">&nbsp;&nbsp;<b>' . $titularTramite['documento'] . '</b></td>
        <td width="20%" bgcolor="#c3c3c3">NACIONALIDAD:</td>
        <td>&nbsp;&nbsp;<b>'. $pais['origen'] . '</b></td>
    </tr>
    <tr>
        <td width="20%" bgcolor="#c3c3c3">PROFESIÓN:</td>
        <td width="80%">&nbsp;&nbsp;<b>' . $titularTramite['profesion'] . '</b></td>
    </tr>
    <tr>
        <td width="20%" bgcolor="#c3c3c3">DOMICILIO:</td>
        <td width="80%">&nbsp;&nbsp;<b>' . $this->util->getDireccion2($titularTramite) . '</b></td>
    </tr>
</table>
<br/><br/>
INFORMACION COMPLEMENTARIA:
<br/>
<table border="0">
    <tr>
        <td width="33%" bgcolor="#c3c3c3">EMPRESA DE TRANSPORTE:</td>
        <td width="67%" colspan="3">&nbsp;&nbsp;<b>' . $titularTramite['empresa_transporte'] . '</b></td>
    </tr>
    <tr>
        <td width="33%" bgcolor="#c3c3c3">MOTIVO:</td>
        <td width="67%" colspan="3">&nbsp;&nbsp;<b>' . $tramite['motivo'] . '</b></td>
    </tr>
    <tr>
        <td bgcolor="#c3c3c3">FECHA A JUSTIFICAR:</td>
        <td colspan="3">&nbsp;&nbsp;<b>' . date_format(date_create($tramite['fecha_paro_transporte']), 'd/m/Y') . '</b></td>
    </tr>
    <tr>
        <td bgcolor="#c3c3c3">LUGAR DE TRABAJO:</td>
        <td colspan="3">&nbsp;&nbsp;<b>'.$titularTramite['lugar_de_trabajo'].'</b></td>
    </tr>
    <tr>
        <td bgcolor="#c3c3c3">PARA SER PRESENTADO:</td>
        <td colspan="3">&nbsp;&nbsp;<b>' . $tramite['autoridad_presentar'] . '</b></td>
    </tr>
</table>
<br/><br/>

Se envía el presente documento con sistema de validación QR en formato PDF por los sistemas de mensajería digital (correo
electrónico y WhatsApp) PLATAFORMA DE TRAMITES ONLINE – DIRECCION ADMINISTRATIVA DIGITAL - POLICIA DE LA PROVINCIA
DE JUJUY, a los ' . $fechaCastellano . '.-
<br/>
DOCUMENTO RESPALDADO MEDIANTE<br/>RESOLUCION MINISTERIAL 318-MS/22

                </td>
            </tr>
            </table>
            <br>
        </body>';
        return $html;
    }

    /**
     * Funcion que permite guardar el archivo en 
     * un disco determinado
     */
    public function guardarFileDisk($id_tramite) {
        $pathFile = WRITEPATH . 'temp/';
        $tramitePersonaModel = new TramitePersonaModel();
        $tramite = $this->tramiteModel->find($id_tramite);
        $titularTramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        
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
//         $html = $pdf->get_header();
        $html = $pdf->get_header_tramite($tramite['id_tramite']);
        $html = $this->getBodyTramite($html, $tramite, $titularTramite);
        if($tramite['id_tipo_tramite'] != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) {
            $html = $pdf->get_footer_tramite($html);
        }
        
        $pdf->writeHTML($html, true, false, true, false, '');
        
        $url_validacion_qr = base_url() . '/tramite/validar/' . $tramite['codigo'];
        // set style for barcode
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        
        // QRCODE,L : QR-CODE Low error correction
        //         $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 40, 40, $style, 'N');
//         $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 28, 28, $style, 'N'); bieen
//         $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 192, 10, 28, 28, $style, 'N');
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 172, 5, 27, 27, $style, 'N');
        
        ob_end_clean();
        $tipoTramiteModel = new TipoTramiteModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);
        $pdf->SetAlpha(0.3);
        $imgdata = base64_decode($pdf->imagenConTexto($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento']));
        $pdf->Image('@'.$imgdata);
        $pdf->SetAlpha(1);
        //$pdf->Output($tipoTramite['tipo_tramite'].'-'.$titularTramite['documento'].'.pdf', 'D');
        $pdf->Output($pathFile.$titularTramite['cuil']."-".$id_tramite.".pdf", 'F');
    }
}
