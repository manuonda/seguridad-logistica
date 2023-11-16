<?php
namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\DependenciaModel;
use App\Models\TramitePersonaModel;
use App\Libraries\Util;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
use App\Libraries\UtilBancoMacro;
use App\Models\Central\InculpadoModel;
use App\Models\TipoTramiteModel;
use App\Models\LocalidadModel;
// use App\Models\HuellaModel;
use App\Models\TramiteArchivoModel;
// use App\Models\Central\PersonaCentralModel;
use TCPDF;
use App\Models\TramitePlanillaDetalleModel;
use App\Models\PersonaModel;
use Exception;


ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

class PlanillaProntuarial extends BaseController
{

    protected $tramiteModel;
    protected $util;
    protected $fechaUtil;
    protected $session;


    public function __construct()
    {
        $this->tramiteModel = new TramiteModel();
        $this->util = new Util();
        $this->fechaUtil = new FechaUtil();
        $this->session = session();
    }

    public function index()
    {
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL;
        $tipoTramiteModel = new TipoTramiteModel();
        $tramitePlanilla = $tipoTramiteModel->find(TIPO_TRAMITE_PLANILLA_PRONTUARIAL);
        $data['importe'] = $tramitePlanilla['precio']+$tramitePlanilla['importe_adicional'];
        
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['id_departamento'] = 291; // Dr manuel belgrano
        $data['id_localidad'] = 12794; // San salvador de jujuy

        // Identificador de la pagina para el websocket de turnos
        $time = time();
        $data['turno_user_id'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL . date("dmYHis", $time) . rand(1, 10000);
        $this->cargarForm($data, "wizard");
    }

    public function nuevaPlanilla($urlRedirec)
    {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);
        $data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA;
        $data['estado'] = TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['estado_verificacion'] = TRAMITE_PENDIENTE_VERIFICACION;
        $data['action'] = "crear";

        $userInSession = $this->session->get('user');
        if (!empty($userInSession)) {
            $data['id_dependencia'] = $userInSession['id_dependencia'];
        }

        $this->cargarForm($data, "new");
    }

    public function nuevo($id_tipo_documento, $documento, $urlRedirec)
    {
        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);

        $data['id_tipo_tramite'] = TIPO_TRAMITE_CONSTANCIA_EXTRAVIO;
        $data['estado'] = TRAMITE_PENDIENTE_VALIDACION;
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data['estado_verificacion'] = TRAMITE_PENDIENTE_VERIFICACION;
        $data['id_tipo_documento'] = $id_tipo_documento;
        $data['documento'] = $documento;

        if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5) {
            $data['id_departamento'] = 292; // Yavi
            $data['id_localidad'] = 2779; // La quiaca
        } else if (!empty(session()->get('id_dependencia')) && session()->get('id_dependencia') == ID_DEP_SECCIONAL_23) {
            $data['id_departamento'] = 302; // Palpala
            $data['id_localidad'] = 5820; // Palpala
        } else {
            $data['id_departamento'] = 291; // Dr manuel belgrano
            $data['id_localidad'] = 12794; // San salvador de jujuy
        }

        $data = $this->getDatosPersona($data);
        $userInSession = $this->session->get('user');
        if (!empty($userInSession)) {
            $data['id_dependencia'] = $userInSession['id_dependencia'];
        }
        $this->cargarForm($data, "new");
    }

    public function edit($idTramite)
    {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = $this->loadData($idTramite);
        $this->cargarForm($data, "edit");
    }

    public function validar($idTramite, $urlRedirec)
    {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $urlRedirec;
        session()->set('filter', $filter);

        $data =  $this->loadData($idTramite);
        $this->cargarForm($data, 'validar');
    }

    public function verificar($idTramite, $urlRedirec)
    {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filter = $this->session->get('filter');
        $redirect = "";
        if ($urlRedirec == "dapTurno") {
            $redirect = "dap";
        } else if ($urlRedirec == "dapBuscarPersona") {
            $redirect = "/dap/buscarTramitePersona";
        } else {
            $redirect = $urlRedirec;
        }

        // Valores pulgares y dedos
        $valores_pulgares = ["A","I","E","V","O","X",""];
        $valores_dedos= ["1","2","3","4","O","X",""];   
        
        


        $filter['urlRedirec'] = $redirect;
        session()->set('filter', $filter);

        $tramitePlanillaDetalleModel = new TramitePlanillaDetalleModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
//         $inculpadoModel = new InculpadoModel();

        $tramite = $this->tramiteModel->find($idTramite);
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        //         var_dump($titular['cuil']);
        $tramitePlanillaDetalle = $tramitePlanillaDetalleModel->where('id_tramite', $tramite['id_tramite'])->first();
        $isTramitePlanillaDetalleNull = false;
        if ($tramitePlanillaDetalle == null) {
            $isTramitePlanillaDetalleNull = true;
            $personaModel = new PersonaModel();
            $persona = $personaModel->where('cuil_ciudadano', $titular['cuil'])->first();
//             if(empty($persona)) {
//                 log_message('info','NO existe persona cuil: '.$titular['cuil'].' en base local.');
//                 $personaCentralModel = new PersonaCentralModel();
//                 $personaCentral = $personaCentralModel->where('cuil_ciudadano', $titular['cuil'])->first();
//                 if(!empty($personaCentral)) {
//                     log_message('info','SI existe persona cuil: '.$titular['cuil'].' en base central.');
//                     $id_persona = $personaModel->insert($personaCentral, true);
//                     if($id_persona > 0) {
//                         $persona = $personaModel->where('cuil_ciudadano', $titular['cuil'])->first();
//                         log_message('info','Se ha insertado persona cuil: '.$titular['cuil'].' en base local con id_persona: '.$id_persona);
//                     }
//                 }
//             }

            $planillaDetalle['id_tramite'] = $tramite['id_tramite'];
            $planillaDetalle['antecedentes_penales'] = "NO REGISTRA";
            $planillaDetalle['antecedentes_policiales'] = "NO REGISTRA";
            if ($persona != null && !empty($persona)) {
                $planillaDetalle['num_prontuario'] = $persona['num_prontuario'];
                $planillaDetalle['letra_prontuario'] = $persona['letra_prontuario'];
            }
            $planillaDetalle['usuario_alta'] = $this->session->get('id');
            $planillaDetalle['fecha_alta'] = date('Y-m-d H:i:s');
            $id_tramite_planilla_detalle = $tramitePlanillaDetalleModel->insert($planillaDetalle, true);
            $tramitePlanillaDetalle = $tramitePlanillaDetalleModel->find($id_tramite_planilla_detalle);
        }

        $data['id_tramite'] = $idTramite;
        $data['id_tramite_planilla_detalle'] = $tramitePlanillaDetalle['id_tramite_planilla_detalle'];
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL;
        $data['tipo_planilla'] = $tramite['tipo_planilla']; // primera vez o renovacion
        $data['id_dependencia'] = $tramite['id_dependencia'];
        $data['estado'] = $tramite['estado'];
        $data['estado_verificacion'] = $tramite['estado_verificacion'];
        $data['observaciones'] = $tramitePlanillaDetalle['observaciones'];
//         if($isTramitePlanillaDetalleNull) {
//             $data['antecedentes_penales']  = $inculpadoModel->penales($titular['cuil']);
//         }else {
            $data['antecedentes_penales']  = $tramitePlanillaDetalle['antecedentes_penales'];
//         }
        
        $data['antecedentes_policiales']  = $tramitePlanillaDetalle['antecedentes_policiales'];

        $data['id_persona_titular'] = $titular['id_persona'];
        $data['nombre'] = $titular['nombre'];
        $data['apellido'] = $titular['apellido'];
        $data['fecha_nacimiento'] = $titular['fecha_nacimiento'];
        $data['documento'] = $titular['documento'];
        $data['cuil'] = $titular['cuil'];
        $data['num_prontuario'] = $tramitePlanillaDetalle['num_prontuario'];
        $data['letra_prontuario'] = $tramitePlanillaDetalle['letra_prontuario'];

        if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_ANTECEDENTE) {
            $data['verificador'] = $this->getDataUserSession();
            $data['disabled'] = "";
        } else {
            $data['verificador'] = $tramite['verificador'];
            $data['disabled'] = "disabled";
        }

        
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data["action"] = "verificar";
        $data["estados"] = $this->get_estados($data["estado"]);
        $data['contenido'] = "verificacion_planilla";

        // imagenes fotos  
        $tramiteArchivoModel = new TramiteArchivoModel();
        $tramiteArchivo     = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, PLANILLA_ARCHIVO_INFORME);


        if ($tramiteArchivo != null && $tramiteArchivo[0]->ruta != null && isset($tramiteArchivo[0]->ruta) && file_exists($tramiteArchivo[0]->ruta . "/" . $tramiteArchivo[0]->nombre)) {
            $image  =  file_get_contents($tramiteArchivo[0]->ruta . "/" . $tramiteArchivo[0]->nombre);
            $base64 = 'data:image/' . $tramiteArchivo[0]->tipo . ';base64,' . base64_encode($image);
            $data['tramiteArchivo'] = $base64;
            $data['tramiteArchivoId'] = $tramiteArchivo[0]->id_tramite_archivo;
            $data['nombreArchivo']  = $tramiteArchivo[0]->nombre;
        } else {
            $data['tramiteArchivo'] = "";
            $data['tramiteArchivoId'] = "";
            $data['nombreArchivo']  = "";
        }

//         var_dump($tramiteArchivo);

        $data['userInSession'] = $this->session->get('user');
        $data['ua'] = $this->request->getUserAgent();
        echo view("frontend", $data);
    }

    public function descargarTramiteArchivo($idTramiteArchivo)
    {
        // imagenes fotos  
        $tramiteArchivoModel = new TramiteArchivoModel();
        $tramiteArchivo     = $tramiteArchivoModel->getByIdAndReferenciaFoto($idTramiteArchivo, PLANILLA_ARCHIVO_INFORME);

        if (
            $tramiteArchivo != null && $tramiteArchivo[0]->ruta != null && isset($tramiteArchivo[0]->ruta) &&
            file_exists($tramiteArchivo[0]->ruta . "/" . $tramiteArchivo[0]->nombre)
        ) {

            //Define header information
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: 0");
            header('Content-Disposition: attachment; filename="' . basename($tramiteArchivo[0]->nombre) . '"');
            header('Content-Length: ' . filesize($tramiteArchivo[0]->ruta . "/" . $tramiteArchivo[0]->nombre));
            header('Pragma: public');

            //Clear system output buffer
            flush();

            //Read the size of the file
            readfile($tramiteArchivo[0]->ruta . "/" . $tramiteArchivo[0]->nombre);

            //Terminate from the script
            die();
           
        }
    }

    public function ver($idTramite, $urlRedirec)
    {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $redirect = "";
        if ($urlRedirec == "dapTurno") {
            $redirect = "dap";
        } else if ($urlRedirec == "dapBuscarPersona") {
            $redirect = "/dap/buscarTramitePersona";
        } else if ($urlRedirec == "buscarTramitePersona") {
            $redirect = "/buscarTramitePersona";
        }

        $filter = $this->session->get('filter');
        $filter['urlRedirec'] = $redirect;
        session()->set('filter', $filter);

        $data =  $this->loadData($idTramite);
        $this->cargarForm($data, 'ver');
    }

    public function volver()
    {
        $this->userInSession = $this->session->get('user');
        if (!empty($this->userInSession) && ($this->userInSession['id_rol'] == ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol'] == ROL_UAD_UNIDAD_REGIONAL_UR5
            || $this->userInSession['id_rol'] == ROL_UAD_UNIDAD_REGIONAL || $this->userInSession['id_rol'] == ROL_ANTECEDENTE)) {

            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['urlRedirec'])) {
                return redirect()->to(base_url() . '/' . $filter['urlRedirec']);
            } else {
                return redirect()->to(base_url());
            }
        } else if (!empty($this->userInSession) && $this->userInSession['id_rol'] == ROL_UNIDAD_ADMINISTRATIVA) {
            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['urlRedirec'])) {
                return redirect()->to(base_url() . '/' . $filter['urlRedirec']);
            } else {
                return redirect()->to(base_url() . "/dashboard");
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function loadData($idTramite)
    {
        //         $huellasModel = new HuellaModel();
        $tramite = $this->tramiteModel->find($idTramite);
        $tramitePersonaModel = new TramitePersonaModel();
        $tipoTramiteModel  = new TipoTramiteModel();
        $tramiteArchivoModel = new TramiteArchivoModel();
        $tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);

        $data = [];
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();

        $localidadModel = new LocalidadModel();
        if (empty($idTramite)) {
            $data['localidades'] = [];
        } else {
            $data['localidades'] = $localidadModel->where('id_departamento', $titular['id_departamento'])->findAll();
        }

        $data['id_tramite'] = $tramite['id_tramite'];
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL;
        $data['autoridad_presentar'] = $tramite['autoridad_presentar'];
        $data['tipo_planilla'] = $tramite['tipo_planilla']; // primera vez o renovacion
        $data['id_dependencia'] = $tramite['id_dependencia'];
        $data['estado'] = $tramite['estado'];
        $data['observaciones'] = $tramite['observaciones'];
        $data['contiene_firma_digital'] = $tramite['contiene_firma_digital'];
        $data['urgente'] = $tramite['urgente'];
        $data['precio'] = $tramite['precio'];
        
//         var_dump("precio : ".$tramite['precio']);
        if(empty($tramite['id_tramite']) && empty($data['urgente'])){
            $data['precio'] = $tipoTramite['precio'];
            $data['urgente'] = INT_CERO;
        }
        
        $data['id_persona_titular'] = $titular['id_persona'];
        $data['nombre'] = $titular['nombre'];
        $data['apellido'] = $titular['apellido'];
        $data['fecha_nacimiento'] = $titular['fecha_nacimiento'];
        $data['documento'] = $titular['documento'];
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
       
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] = $tipoTramite["controlador_title"];
        $data['contenidopaso1'] = $tipoTramite['controlador_view'];

        // huellas 
        //         $data['huellass'] = $huellasModel->search_huella($titular['cuil']);
        //         $data['huella_all'] = $huellasModel->search_all($titular['cuil']);
        $data['rcuil'] = $titular['cuil'];


        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();


        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();
        $localidadModel   = new LocalidadModel();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        //         $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
        //         $data['departamentos'] = $departamentoModel->where('id_departamento', 291)->findAll();
        $data['departamentos'] = $departamentoModel->whereIn('id_departamento', [291, 292])->findAll();

        if (empty($data['id_tramite'])) {
            $data['localidades'] = [];
        } else if (!empty($data['id_departamento'])) {
            $data['localidades'] = $localidadModel->findByIdDepartamento($data['id_departamento']);
        }


        // imagenes fotos  
        $fotoFrente      = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_FRENTE);
        $fotoDorso       = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_DORSO);
        $fotoColor       = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, FOTO_COLOR);
        $archivoPlanilla = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, ARCHIVO_PLANILLA);
        //         $huellaDigital   = $tramiteArchivoModel->findByIdTramiteByFoto($idTramite, HUELLA_DIGITAL);

        if ($fotoFrente != null && $fotoFrente[0]->ruta != null && isset($fotoFrente[0]->ruta) && file_exists($fotoFrente[0]->ruta . "/" . $fotoFrente[0]->nombre)) {
            $image  =  file_get_contents($fotoFrente[0]->ruta . "/" . $fotoFrente[0]->nombre);
            $base64 = 'data:image/' . $fotoFrente[0]->tipo . ';base64,' . base64_encode($image);
            $data['fotoFrente'] = $base64;
            $data['fotoFrenteId'] = $fotoFrente[0]->id_tramite_archivo;
        } else {
            $data['fotoFrente'] = "";
            $data['fotoFrenteId'] = "";
        }

        if ($fotoDorso != null && $fotoDorso[0]->ruta != null && isset($fotoDorso[0]->ruta) && file_exists($fotoDorso[0]->ruta . "/" . $fotoDorso[0]->nombre)) {
            $image  =  file_get_contents($fotoDorso[0]->ruta . "/" . $fotoDorso[0]->nombre);
            $base64 = 'data:image/' . $fotoDorso[0]->tipo . ';base64,' . base64_encode($image);
            $data['fotoDorso'] = $base64;
            $data['fotoDorsoId'] = $fotoDorso[0]->id_tramite_archivo;
        } else {
            $data['fotoDorso'] = "";
            $data['fotoDorsoId'] = "";
        }

        if ($fotoColor != null && $fotoColor[0]->ruta != null && isset($fotoColor[0]->ruta) && file_exists($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre)) {
            $image  =  file_get_contents($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre);
            $base64 = 'data:image/' . $fotoColor[0]->tipo . ';base64,' . base64_encode($image);
            $data['fotoColor'] = $base64;
            $data['fotoColorId'] = $fotoColor[0]->id_tramite_archivo;
        } else {
            $data['fotoColor'] = "";
            $data['fotoColorId'] = "";
        }

        if ($archivoPlanilla != null && $archivoPlanilla[0]->ruta != null && isset($archivoPlanilla[0]->ruta) && file_exists($archivoPlanilla[0]->ruta . "/" . $archivoPlanilla[0]->nombre)) {
            $image  =  file_get_contents($archivoPlanilla[0]->ruta . "/" . $archivoPlanilla[0]->nombre);
            $base64 = 'data:image/' . $archivoPlanilla[0]->tipo . ';base64,' . base64_encode($image);
            $data['archivoPlanilla'] = $base64;
            $data['archivoPlanillaId'] = $archivoPlanilla[0]->id_tramite_archivo;
        } else {
            $data['archivoPlanilla'] = "";
            $data['archivoPlanillaId'] = "";
        }

        $data['id_tipo_tramite'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL;
        $data['contenidopaso1'] = "planilla_prontuarial";
        $data['title'] = "Certificado de Residencia";
        $data['action'] = "certificadoResidencia/guardarData";

        return $data;
    }

    private function cargarForm($data = [], $tipoForm = "wizard")
    {
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $localidadModel = new LocalidadModel();
        $dependenciaModel = new DependenciaModel();
        $tipoTramiteModel  = new TipoTramiteModel();
        $utilBancoMacro = new UtilBancoMacro();

        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitadoParaPlanilla();
        
        if(isset($data['id_departamento']) && $data['id_departamento'] != null) {
            $data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
        }else {
            $data['localidades'] = [];
        }
        
        $data['id_tipo_tramite'] = TIPO_TRAMITE_PLANILLA_PRONTUARIAL;
        $data['contenido'] = "planilla_prontuarial";

        $tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
        $data['controller'] = $tipoTramite['controlador'];
        $data['title'] = $tipoTramite["controlador_title"];
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();

        if(empty($tramite['id_tramite']) && empty($data['urgente'])){
            $tipoTramite = $tipoTramiteModel->find(TIPO_TRAMITE_PLANILLA_PRONTUARIAL);
            $data['precio'] = $tipoTramite['precio'];
            $data['urgente'] = INT_CERO;
        } 

        if ($tipoForm == "wizard") {
            $data["estado"] = TRAMITE_PENDIENTE_VALIDACION;
            $data['estado_verificacion'] = TRAMITE_PENDIENTE_VERIFICACION;
            $data["action"] = "";
            $data['controller'] = $tipoTramite['controlador'];
            $data['title'] = $tipoTramite["controlador_title"];
            $data['contenidopaso1'] = $tipoTramite['controlador_view'];

            $data['turnoCantidades'] = []; // se inicializa las fechas de turnos en vacio, luego se carga por ajax
            $data['util'] = new Util();
            $data['contenido'] = "wizard/wizard";
            $data['contenidopaso2'] = "turno";
        } else if ($tipoForm == "verificar") {
            $data["action"] = "verificar";
            $data["estados"] = $this->get_estados($data["estado"]);
            $data['contenido'] = "verificacion_planilla";
        } else if ($tipoForm == "validar" || $tipoForm == "ver" || $tipoForm == "new" || $tipoForm == "edit") {

            $data["action"] = "edit";
            $data["estados"] = $this->get_estados($data["estado"]);

            $data['contenido'] = "vista";
            $data['contenidoedit'] = "planilla_prontuarial";
        } else {
            // Verifico si existen tramites anteriores            
            if (!empty(session()->get('id_rol')) &&  session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
                $listadoTramites = $this->tramiteModel->getTramitesByCuilByIdTramite($data['cuil'], $data['id_tramite'], [TIPO_TRAMITE_PLANILLA_PRONTUARIAL]);
                if (sizeof($listadoTramites) > 0 && $data['estado'] != TRAMITE_VALIDADO) {
                    $tramiteTmp = $listadoTramites[0];
                    $dataInformation = $this->loadData($tramiteTmp['id_tramite']);
                    $data['dataInformation'] = $dataInformation;
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista_doble";
                    $data['contenidoedit'] = "planilla_prontuarial";
                    $data['contenidoview'] = "planilla_prontuarial_view";
                } else {
                    $data["action"] = "edit";
                    $data["estados"] = $this->get_estados($data["estado"]);
                    $data['contenido'] = "vista";
                    $data['contenidoedit'] = "planilla_prontuarial";
                }
            } else {
                $data["action"] = "edit";
                $data["estados"] = $this->get_estados($data["estado"]);
                $data['contenido'] = "vista";
                $data['contenidoedit'] = "planilla_prontuarial";
            }
        }

        $data['userInSession'] = $this->session->get('user');
        $data['ua'] = $this->request->getUserAgent();
        echo view("frontend", $data);
    }

    /**
     * Funcion que permite guardar la informacion de la planilla prontuarial
     */
    public function guardar()
    {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation =  \Config\Services::validation();
        $data['tipo_supervivencia'] = $this->request->getVar('tipo_supervivencia');
        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_persona_tutor']   = $this->request->getvar('id_persona_tutor');
        $data['id_tipo_tramite']    = $this->request->getVar('id_tipo_tramite');
        $data['estado']             = $this->request->getVar('estado');
        $data['urgente']            = $this->request->getVar('urgente');
        $data['precio']             = $this->request->getVar('precio');
        $validation->setRules([
            'tipo_planilla' => ['label' => 'Tipo', 'rules' => 'required'],
            'estado' => ['label' => 'Estado', 'rules' => 'required'],
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            //'id_dependencia' => ['label' => 'Comisaría donde se va a verificar y validar', 'rules' => 'required|numeric'],
            //             'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['tipo_planilla'] = strtoupper($this->request->getVar('tipo_planilla'));
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = TIPO_DOC_DNI; // DNI
        $data['documento'] = strtoupper($this->request->getVar('documento'));
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
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['observaciones'] = $this->request->getVar('observaciones');
        $data['estado_pago'] = $this->request->getVar('estado_pago');
        $data['estado_verificacion'] = $this->request->getVar('estado_verificacion');

        $tipoForm = $this->request->getVar('tipoForm');
        if (empty($data['id_tipo_documento_tutor'])) {
            $data['id_tipo_documento_tutor'] = null;
        }
        
        log_message('info', 'tipo_planilla=' . $data['tipo_planilla'] . ', documento=' . $data['documento'] . ', apellido=' . $data['apellido']);
//         var_dump("tipo_planilla : ".$data['tipo_planilla']); return;
        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
                $data['porque_motivo'] = $spambot;
                $this->cargarForm($data, "edit");
                return;
            }

            $id_tramite = null;
            
            if (empty($data['id_tramite'])) {
                $codigo = $this->util->generateRandomString(INT_DIEZ);
                while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                }
                $data['codigo'] = $codigo;
                $data['referencia_pago'] = COMISARIA_PAGO;

//                 var_dump("tipo_planilla : ".$data['tipo_planilla']); return;
                $id_tramite = $this->tramiteModel->insertarPlanillaProntuarial($data);
            } else {
                $id_tramite = $this->tramiteModel->updatePlanillaProntuarial($data);
            }


            if ($id_tramite == INT_MENOS_UNO) {
                log_message('error', 'Planilla, error al guardar persona en local, documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data['error'] = "¡Ha ocurrido un error al guardar en la base de datos, por favor vuelva a intentar!";
                $this->cargarForm($data, "edit");
                return;
            } else {
//                 try {
//                     $personaCentralModel = new PersonaCentralModel();
//                     $resultado = $personaCentralModel->savePersonaDomicilio($data);
//                     if ($resultado == INT_MENOS_UNO) {
//                         log_message('error', 'Planilla, error al guardar persona en central, documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
//                         $data['error'] = "¡Ha ocurrido un error inesperado al guardar, por favor vuelva a intentar!";
//                         $this->cargarForm($data, "edit");
//                         return;
//                     }
//                 } catch (Exception $e) {
//                     log_message('error', 'Planilla, error al guardar persona en central, documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
//                     $data['error'] = "¡Ha ocurrido un error al guardar en la base central, por favor vuelva a intentar!";
//                     $this->cargarForm($data, "edit");
//                     return;
//                 }
            }

            if (empty($data['id_tramite'])) {
                // subir archivos
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoFrente', FOTO_FRENTE);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoDorso', FOTO_DORSO);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'fotoColor', FOTO_COLOR);
                if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_ANTECEDENTE) {
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'archivoPlanilla', ARCHIVO_PLANILLA);
                }
            } else {
                // edit 
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoFrente', FOTO_FRENTE);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoDorso', FOTO_DORSO);
                $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'fotoColor', FOTO_COLOR);
                if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_ANTECEDENTE) {
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'archivoPlanilla', ARCHIVO_PLANILLA);
                }
            }

            $filter = $this->session->get('filter');
            // ---------------------
            $data['id_tramite'] = $id_tramite;
            if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
                if ($filter != null && !empty($filter['urlRedirec'])) {
                    $filter['documento'] = $data['documento'];
                    $filter['idTipoTramite'] = [TIPO_TRAMITE_PLANILLA_PRONTUARIAL];
                    session()->set('filter', $filter);
                    return redirect()->to(base_url() . '/' . $filter['urlRedirec']);
                } else {
                    return redirect()->to('/dashboard');
                }
                //             }else if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_COMISARIA_SECCIONAL) {
            } else if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == ROL_COMISARIA_SECCIONAL || session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5)) {
                $filter = $this->session->get('filter');
                if ($filter != null && !empty($filter['urlRedirec'])) {
                    $filter['documento'] = $data['documento'];
                    $filter['idTipoTramite'] = [TIPO_TRAMITE_PLANILLA_PRONTUARIAL];
                    session()->set('filter', $filter);
                    return redirect()->to(base_url() . '/' . $filter['urlRedirec']);
                } else {
                    return redirect()->to(base_url());
                }
            } else {
                $data['contenido_paso1'] = "planilla_prontuarial";
                $data['contenido'] = "wizard/wizard";
                $data['action'] = "planillaProntuarial/guardarData";
            }

            return redirect()->to('/dashboard');
        } else {

            /*$data = [
                'message' => $validation->getErrors(),
                'status' => "ERROR",
                'errors'  => true
            ];*/
            $this->cargarForm($data, $tipoForm);
        }
    }

    public function guardarVerificacion()
    {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'id_tramite' => ['label' => 'ID tramite', 'rules' => 'required|numeric'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric'],
            'id_tramite_planilla_detalle' => ['label' => 'ID tramite planilla detalle', 'rules' => 'required|numeric'],
            'num_prontuario' => ['label' => 'Nro. de prontuario', 'rules' => 'required|numeric'],
            'letra_prontuario' => ['label' => 'Nomenclatura de prontuario', 'rules' => 'required'],
            'antecedentes_penales' => ['label' => 'Antecedentes penales', 'rules' => 'required'],
            'antecedentes_policiales' => ['label' => 'Antecedentes policiales', 'rules' => 'required'],
            'estado_verificacion' => ['label' => 'Estado', 'rules' => 'required'],
            'verificador' => ['label' => 'Verificador', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_tramite_planilla_detalle'] = $this->request->getVar('id_tramite_planilla_detalle');
        $data['num_prontuario'] = $this->request->getVar('num_prontuario');
        $data['letra_prontuario'] = strtoupper($this->request->getVar('letra_prontuario'));
        $data['observaciones'] = $this->request->getVar('observaciones');
        $data['antecedentes_penales'] = strtoupper($this->request->getVar('antecedentes_penales'));
        $data['antecedentes_policiales'] = strtoupper($this->request->getVar('antecedentes_policiales'));

        if ($validation->withRequest($this->request)->run()) {
            $this->uploadArchivo($data['id_tramite'], TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'archivoInforme', PLANILLA_ARCHIVO_INFORME);

            $tramitePlanillaDetalleModel = new TramitePlanillaDetalleModel();
            $data['usuario_modificacion'] = session()->get('id');
            $data['fecha_modificacion'] = date('Y-m-d H:i:s');
            $tramitePlanillaDetalleModel->update($data['id_tramite_planilla_detalle'], $data);

            $tramite['verificador'] = strtoupper($this->request->getVar('verificador'));
            $tramite['estado_verificacion'] = $this->request->getVar('estado_verificacion');
            //             $tramite['usuario_modificacion'] = session()->get('id');
            //             $tramite['fecha_modificacion'] = date('Y-m-d H:i:s');
            $this->tramiteModel->update($data['id_tramite'], $tramite);

            $cuil = $this->request->getVar('cuil');
            $personaModel = new PersonaModel();
            $persona = $personaModel->where('cuil_ciudadano', $cuil)->first();
//             var_dump($persona); return;
            if(empty($persona)) {
                log_message('error','funcion guardarVerificacion, No existe persona en la base con cuil_ciudadano: '.$cuil);
                // FIXME: me falta crear la persona en base de datos.
            }else {
                $personaAux['num_prontuario'] = $data['num_prontuario'];
                $personaAux['letra_prontuario'] = $data['letra_prontuario'];
                $personaModel->update($persona['id_persona'], $personaAux);
                log_message('error','funcion guardarVerificacion, Se ha actualizado el prontuario de la persona en la base con cuil_ciudadano: '.$cuil);
            }

            // FIXME: agregar clasificacion de huellas

            if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == ROL_ANTECEDENTE || session()->get('id_rol') == ROL_JEFE_DAP)) {
                $filter = $this->session->get('filter');
                if ($filter != null && !empty($filter['urlRedirec'])) {
                    return redirect()->to(base_url() . '/' . $filter['urlRedirec']);
                }
            }
            return redirect()->to(base_url());
        } else {
            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['urlRedirec'])) {
                $this->verificar($data['id_tramite'], $filter['urlRedirec']);
            } else {
                return redirect()->to(base_url());
            }
        }
    }

    public function guardarData()
    {
        $status = "ERROR";
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'tipo_planilla' => ['label' => 'Tipo de planilla', 'rules' => 'required'],
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'id_localidad' => ['label' => 'Localidad', 'rules' => 'required|numeric'],
            'barrio' => ['label' => 'Barrio', 'rules' => 'required'],
            'numero' => ['label' => 'Número', 'rules' => 'required'],
            'calle' => ['label' => 'Calle', 'rules' => 'required'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            'id_dependencia' => ['label' => 'Comisaría donde se va a verificar y validar', 'rules' => 'required|numeric'],
            'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['tipo_planilla'] = strtoupper($this->request->getVar('tipo_planilla'));
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
        $data['id_tipo_documento'] = TIPO_DOC_DNI;
        $data['documento'] = strtoupper($this->request->getVar('documento'));
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
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
        $data["estado"] = $this->request->getVar('estado');
        $data['isPersonaValidada'] = $this->request->getVar('isPersonaValidada');
        $data['observaciones'] = $this->request->getVar("observaciones");
        $data['urgente']            = $this->request->getVar('urgente');
        $data['precio']             = $this->request->getVar('precio');
        $data['estado_verificacion'] = $this->request->getVar('estado_verificacion');

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
            
//             echo 'paso 1';

            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
            $recaptcha_response = $this->request->getVar('recaptcha_response');
            //             echo 'recaptcha_response=='.$recaptcha_response;
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            //             echo 'fff=='.$recaptcha;
            $recaptcha_json = json_decode($recaptcha);

            // Miramos si se considera humano o robot:
            if (empty($recaptcha_json) || (!empty($recaptcha_json) && $recaptcha_json->success && $recaptcha_json->score >= 0.6)) {
                
//                 echo 'paso 2';
                
                $data['id_dependencia'] = ID_DEP_UAD_CENTRAL; // por el momento
                $turnoCantidades = $this->getFechasDeTurnoPorDependenciaYporTramite($data['id_dependencia'], TIPO_TRAMITE_PLANILLA_PRONTUARIAL, $data['tipo_planilla']);
                if(empty($turnoCantidades)) {
                    $errorTurno = [ "turno" => "Disculpe, no hay turnos disponibles." ];
                    $data = [
                        'status' => "ERROR",
                        'success' => false,
                        'message' => $errorTurno
                    ];
                    return $this->response->setJSON($data);
                }
                
                $id_tramite = null;
                if (empty($data['id_tramite'])) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                    while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                        $codigo = $this->util->generateRandomString(INT_DIEZ);
                    }
                    $data['codigo'] = $codigo;

                    $id_tramite = $this->tramiteModel->insertarPlanillaProntuarial($data);
                } else {
                    $id_tramite = $this->tramiteModel->updatePlanillaProntuarial($data);
                }


                if ($id_tramite == INT_MENOS_UNO) {
                    $message = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                    $data = [
                        'status'  => $status,
                        'message' => $message
                    ];
                    return $this->response->setJSON($data);
                }


                if (empty($data['id_tramite'])) {
                    // subir archivos
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoFrente', FOTO_FRENTE);
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'documentoDorso', FOTO_DORSO);
                    $this->uploadArchivo($id_tramite, TIPO_TRAMITE_PLANILLA_PRONTUARIAL, 'fotoColor', FOTO_COLOR);
                }

                //                 $data['id_tramite'] = $id_tramite;
                if ($data['id_dependencia'] == ID_DEP_UAD_CENTRAL) {
                    $dataResp = [
                        'status' => "OK",
                        'id_tramite' => $id_tramite,
                        'isPersonaValidada' => $data['isPersonaValidada'],
                        'turnoCantidades' => $turnoCantidades
                    ];
                } else {
                    $dataResp = [
                        'status' => "OK",
                        'id_tramite' => $id_tramite,
                        'isPersonaValidada' => $data['isPersonaValidada'],
                        'turnoCantidades' => $this->getFechasDeTurnoPorDependenciaPlanilla($data['id_dependencia'])
                    ];
                }

                return $this->response->setJSON($dataResp);
            } else {
                log_message('error', 'ROBOT: recaptcha=' . $recaptcha . ', recaptcha_response=' . $recaptcha_response . ', documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data = [
                    'status' => "ERROR",
                    'success' => false,
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


    /**
     * Funcion que permite obtener los datos de personas 
     */
    public function getDatosPersonas($documento) {

        if (empty(session()->get('id_rol')) || session()->get('id_rol') !=  ROL_UNIDAD_ADMINISTRATIVA) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
         $tramiteModel = new TramiteModel();
         $localidadModel = new LocalidadModel();
         $localidades = [];
         $tramites = $tramiteModel->getTramiteByDocumentoByEstadosPagados($documento,[ TRAMITE_VALIDADO , TRAMITE_VALIDADO_VERIFICADO]);
         $status = "OK";
         $persona  = null;
         if ($tramites && sizeof($tramites)> 0) {
           $persona = $tramites[0];
           $id_departamento = $persona['id_departamento'];
           $localidades = $localidadModel->findByIdDepartamento($id_departamento); 
        } else {
            $persona = null; 
         }

         $data = [
            'status'  => $status,
            'persona' => $persona,
            'localidades' => $localidades
        ];
        return $this->response->setJSON($data);
    }

    public function getDocumentoPlanillaProntuarial($id_tramite)
    {
        if (empty(session()->get('id_rol'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tipoTramiteModel = new TipoTramiteModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $tramite = $this->tramiteModel->find($id_tramite);
        if(empty($tramite['contiene_firma_digital']) || $tramite['contiene_firma_digital'] == false) {
            $dataTramite['usuario_emision'] = session()->get('id');
            $dataTramite['fecha_emision'] = date('Y-m-d H:i:s');
            $this->tramiteModel->update($id_tramite, $dataTramite);
            
            $tramite['usuario_emision'] = $dataTramite['usuario_emision'];
            $tramite['fecha_emision'] = $dataTramite['fecha_emision'];
        }
        
        $tramiteArchivoModel = new TramiteArchivoModel();
//         $inculpadoModel = new InculpadoModel();
        $titularTramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();

        $tramitePersonaModel = new TramitePersonaModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);

        // imagen de foto color   
        $fotoColor       = $tramiteArchivoModel->findByIdTramiteByFoto($tramite['id_tramite'], FOTO_COLOR);

        $tramitePlanillaDetalleModel = new TramitePlanillaDetalleModel();
        $tramitePlanillaDetalle = $tramitePlanillaDetalleModel->getByIdTramite($tramite['id_tramite']);

        $numero_prontuario = "";
        $letra_prontuario = "";
        $antecedentesPenales = "";
        $antecedentesPoliciales = "";
        $prontuario  = "";

        if ($tramitePlanillaDetalle !== NULL && $tramitePlanillaDetalle) {
            $numero_prontuario = $tramitePlanillaDetalle[0]->num_prontuario != NULL ? $tramitePlanillaDetalle[0]->num_prontuario : " ";
            $letra_prontuario  = $tramitePlanillaDetalle[0]->letra_prontuario != NULL ? $tramitePlanillaDetalle[0]->letra_prontuario : " ";
            $prontuario = $numero_prontuario . " - " . $letra_prontuario;
            $antecedentesPenales  = $tramitePlanillaDetalle[0]->antecedentes_penales;
            $antecedentesPoliciales = $tramitePlanillaDetalle[0]->antecedentes_policiales;
        }
        //           var_dump($tramitePlanillaDetalle);

        $image = "";
        $path = "";
        $nameFile = "";
        if (
            $fotoColor != null &&
            $fotoColor[0]->ruta != null &&
            isset($fotoColor[0]->ruta) &&
            file_exists($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre)
        ) {
            $image  =  file_get_contents($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre);
            $nameFile = $fotoColor[0]->nombre;
            $path = "/home/tramites/public_html/public/tmp/";
            file_put_contents($path.$nameFile, $image);
            $resultChmod = chmod($path.$nameFile, 0666);
            var_dump($resultChmod);
        } else {
           // var_dump("fotolo coolor no existe");
        }
        
        if(empty($nameFile)) {
            return redirect()->to('/home/error/2');
        }

//         $personaEncontrada = $inculpadoModel->searchPersonaByCuilCiudadano($titularTramite['cuil']);
        //var_dump($personaEncontrada);

        $huellas_serie_s = " ------";
        $huellas_seccion_s ="------";

//         if ($personaEncontrada && $personaEncontrada != null ) {
//             $huellas = $inculpadoModel->search_huellas($personaEncontrada[0]->cuil);
//             $huellas_serie = [];
       
//             $huellas_seccion = [];
//             if ($huellas && sizeof($huellas) > 0 && isset($huellas)) {
//               for($i = 0 ; $i < 5 ; $i++ ){
//                   $huellas_serie[] = $huellas[$i]["huella"];
//                }
//                $huellas_serie_s = implode("-",$huellas_serie);
        
//                for($i = 5 ; $i < sizeof($huellas) ; $i++ ){
//                    $huellas_seccion[] = $huellas[$i]["huella"];
//                 }
//                 $huellas_seccion_s = implode("-",$huellas_seccion);
                
//             } 
//         }
       
          
   
        $url_validacion_qr = base_url() . '/tramite/validar/' . $tramite['codigo'];
        // set style for barcode
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 0.1, // width of a single module in points
            'module_height' => 0.1 // height of a single module in points
        );

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);
        
        //$antecedentesPenales = "LAS PELEAS QUE HAY POR DEBAJO DE UNA PAZ INTERNA ESCENIFICADA. SANTILLI Y RITONDO LANZADOS A LA MISMA CANDIDATURA PERO CON DISTINTOS PADRINOS. UN EMISARIO SECRETO DEL LARRETISMO PARA UNA GESTIÓN DELICADA";
//         $antecedentesPenales = "NO REGISTRA";
//         $antecedentesPoliciales = "NO REGISTRA";
        $pdf->AddPage();
        $html = $this->getOnline($tramite, $titularTramite, $tipoTramite, $nameFile, $prontuario, $antecedentesPenales, $antecedentesPoliciales ,$huellas_serie_s, $huellas_seccion_s);
        
        $pdf->SetAlpha(0.3);
        $imgdata = base64_decode($pdf->imagenConTextoYcoordenadas($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento'], 540, 420));
        $pdf->Image('@'.$imgdata);
        $pdf->SetAlpha(1);
        
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 33, 107, 40, 40, $style, 'N');
        ob_end_clean();
        $pdf->Output($tipoTramite['tipo_tramite'].'-'.$titularTramite['documento'].'.pdf', 'I');
        ob_end_flush();
    }

    public function getOnline($tramite, $titularTramite, $tipoTramite, $nameFile, $prontuario, $antecedentesPenales, $antecedentesPoliciales, $serie, $seccion)
    {

        $html = '
        <html>
        <body>
            <table border="0">
                <tr>
                    <!-- parte lateral izquierda -->
                    <td width="8%" height="100%">
                        <img src="assets/img/planilla-lateral.png" />
                    </td>
                    <!-- parte lateral derecha -->
                    <td width="90%" height="70%">
                       
                        <table border="1">
                            <tr>
                                <td width="50%" bgcolor="#c3c3c3">
                                    <strong><font size="17">PLANILLA PRONTUARIAL</font><br/><br/><font size="12">&nbsp;&nbsp;N° TRAMITE: ' . $tramite['id_tramite'] . '</font></strong>
                                </td>
                                <td width="50%">
                                    <img  src="assets/img/planilla-header-derecho.png" />
                                </td>
                            </tr>
                        </table>
                        <table style="border: 2px solid #000;">
                            <tr>
                            <td>
                              <br>
                              <table border="0">
                                      <tr>
                                            <td width="30%" style="text-align:left"></td>
                                            <td width="69%" style="text-align:left">
                                            <br>
                                              <br>
                                              <table width="100%" border="0">
                                               <tr>
                                               <td width="60%">
                                                 <font size="9"><strong>SOLICITANTE:</strong></font>
                                               </td>
                                               <td width="40%">
                                                 <font size="9">
                                                 <strong>
                                                  LEG. POL. ' . $prontuario . '
                                                 </strong>
                                                 </font>
                                               </td> 
                                               </tr>
                                              </table>
                                            </td>
                                        </tr>
                    
                                        <!-- fila 3 cuil y datos personales -->
                                        <tr>
                                              <td width="26%" heigth="25%" style="padding:3px; text-align:center;">
                                                <img src="tmp/'.$nameFile.'" style="margin-left:50px; border: 1px solid #000"/>
                                              </td>
                                              <td width="2%">&nbsp;</td>
                                              <td width="80%;">
                                               
                                                <table width="88%" style="size:8; border: 1px solid #000;align:rigth;">
                                                    <tr style="border: 1px solid #000;align:rigth;">
                                                        <td align="left">
                                                            <font size="9">NOMBRE Y APELLIDO: <strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</strong></font>
                                                            <br>
                                                            <font size="9"><strong>Tipo y Nro. de Doc: DNI. ' . $titularTramite['documento'] . '</strong></font>
                                                            <br>
                                                            <font size="9"><strong>Domicilio:</strong>
                                                            ' . $this->util->getDireccion2($titularTramite) . ' 
                                                            </font>
                                                         </td>
                                                    </tr>
                                                 </table>
                                                 <br>
                                                 <br>
                                                 
                                                 <table width="88%" style="border: 1px solid #000;align:rigth;">
                                                     <tr style="border: 1px solid #000;align:rigth;">
                                                     <td>
                                                      <font size="9"><strong>ENTIDAD SOLICITANTE: </strong> '.$tramite['autoridad_presentar'].' </font>
                                                     </td>
                                                      </tr>
                                                 </table>
                                               
                                             </td>
                                        </tr>
                                        <!-- end fila cuil y datos personales -->
                                    </table>
                    
                                    <!-- comienzo la tabla de datos de las 3 columnas -->
                    
                                    <br> 
                                    <!-- table fila cuilt, text, text -->
                                    <table width="100%">
                                        <tr>
                                            <td width="30%" style="border : 1px solid #000">
                                            <table>
                                              <tr>
                                                <td style="margin-left: 25px;">
                                                 <font size="10" style="border: 1px solid #000"><span style="border: 1px solid #000"><strong>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $titularTramite['cuil'] . '
                                                </strong></span></font>
                                                </td>
                                               </tr>
                                             </table>
                                            </td>
                                            <td width="40%">
                                              <font size="10"><span><strong> REGISTRO DE ANTECEDENTES</strong></span></font>                                              
                                            </td>
                                            <td width="30%">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- table valores retributitos , penales --> 
                                    <table border="0">
                                        <tr>
                                            <!-- columna 1 tasas y codigo qr  -->
                                            <td width="30%">
                                                <table>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                <td>
                                                <table border="0" width="80%">
                                                    <tr>
                                                    <td  align="left">&nbsp;
                                                      <!-- <font size="9"><strong>ID DECADACTILAR</strong></font> -->
                                                      <br>
                                                      <!-- <font size="9"><strong>SERIE : '.$serie.' </strong></font> -->
                                                      <br>  
                                                      <!-- <font size="9"><strong>SECCION : '.$seccion.' </strong></font> -->
                                                    </td>
                                                    </tr>
                                                  </table>
                                                </td> 
                                                </tr>
                                                  
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>&nbsp;</td></tr>

                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr>
                                                        <td width="100%">
                                                            &nbsp;
                                                        </td>
                                                    </tr>
                                                  
                                                </table>
                                            </td>
                                            
                                            <!-- columna 2 antecedentes-->
                                            <td width="70%">
                                                <font size="10"> 
                                                    <table border="0">
                                                          <tr><td><ul><li><strong>PENALES:</strong></li></ul></td></tr>
                                                          <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $antecedentesPenales . '</td></tr>
                                                          <tr>
                                                            <td width="62%">&nbsp;</td>
                                                            <td rowspan="5" colspan="1">
                                                                <table border="0" style="align:rigth;" bgcolor="#c3c3c3" width="95%">
                                                                    <tr>
                                                                      <td width="65%" align="left"><font size="9">&nbsp;<strong>TASAS<br>&nbsp;RETRIBUTIVAS</strong></font></td>
                                                                      <td width="33%"><font size="7">&nbsp;&nbsp;&nbsp;Ley 5598<br>&nbsp;&nbsp;&nbsp;Art. 2/11</font></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><font size="8">&nbsp;Certificado Planilla<br>&nbsp;Prontuarial</font></td>
                                                                        <td><font size="7">&nbsp;&nbsp;&nbsp;$' . $tipoTramite['precio'] . '</font></td>
                                                                    </tr>
                                                                    <tr>
                                                                    <td><font size="8">&nbsp;Certificación de<br>&nbsp;copia</font></td>
                                                                    <td><font size="7">&nbsp;&nbsp;&nbsp;$' . $tipoTramite['importe_adicional'] . '</font></td>
                                                                </tr>
                                                                </table>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td ><ul><li><strong>POLICIALES:</strong></li></ul></td>
                                                          </tr>
                                                          <tr>
                                                            <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $antecedentesPoliciales . '</td>
                                                          </tr> 
                                                          <tr>
                                                             <td>&nbsp;</td>
                                                          </tr>
                                                          <tr><td><font size="9"><strong>DATOS VERIFICADOR D.A.P.</strong><br/>' . $tramite['verificador'] . '</font></td></tr>            
                                                          <tr><td><font size="9"><br/><strong>FECHA DE EMISIÓN</strong></font></td></tr> 
                                                          <tr><td><font size="9"><strong>' . date_format(date_create($tramite['fecha_emision']), 'd/m/Y') . '</strong></font></td></tr>                                                           
                                                     </table>  
                                                 </font>
                                            </td>
                                        </tr>
                                        
                                        <tr><td>&nbsp;</td></tr>
                                        <tr><td>&nbsp;</td></tr>
                                                    
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>        
        </body>
        </html>
        ';
        return $html;
    }

    public function getConstanciaPlanillaProntuarial($id_tramite)
    {
        if (empty(session()->get('id_rol'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tipoTramiteModel = new TipoTramiteModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $tramite = $this->tramiteModel->find($id_tramite);
        $titularTramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();

        $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
        $totalImporte=$tipoTramite['precio']+$tipoTramite['importe_adicional'];

        $tramitePersonaModel = new TramitePersonaModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);
        
        $localidadModel = new LocalidadModel();
        $localidad = $localidadModel->find($titularTramite['id_localidad']);

        $tramitePlanillaDetalleModel = new TramitePlanillaDetalleModel();
        $tramitePlanillaDetalle = $tramitePlanillaDetalleModel->getByIdTramite($tramite['id_tramite']);

        $numero_prontuario = "";
        $letra_prontuario = "";
        $prontuario  = "";

        if ($tramitePlanillaDetalle !== NULL && $tramitePlanillaDetalle) {
            $numero_prontuario = $tramitePlanillaDetalle[0]->num_prontuario != NULL ? $tramitePlanillaDetalle[0]->num_prontuario : " ";
            $letra_prontuario  = $tramitePlanillaDetalle[0]->letra_prontuario != NULL ? $tramitePlanillaDetalle[0]->letra_prontuario : " ";
            $prontuario = $numero_prontuario . " - " . $letra_prontuario;
        }

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Constancia Planilla Prontuarial');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetHeaderMargin(10);
        $pdf->SetTopMargin(8);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('helvetica', '', 12);
        
        $pdf->AddPage();
//         $html = $this->getHtmlConstancia($tramite, $tipoTramite, $totalImporte, $titularTramite,$localidad, $prontuario);
        $html = $this->getNewHtmlConstancia($tramite, $tipoTramite, $totalImporte, $titularTramite,$localidad, $prontuario);
        
        $pdf->SetAlpha(0.3);
//         $imgdata = base64_decode($pdf->imagenConTextoYcoordenadas($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento'], 540, 565));
        $imgdata = base64_decode($pdf->imagenConTextoYcoordenadas($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento'], 540, 220));
        $pdf->Image('@'.$imgdata);
        $pdf->SetAlpha(1);
        
        $pdf->writeHTML($html, true, 0, true, 0);
        ob_end_clean();        
        $pdf->Output("Constancia_" . $tipoTramite['tipo_tramite']."-".$titularTramite['documento'].".pdf", 'I');
        ob_end_flush();
    }

    public function getNewHtmlConstancia($tramite, $tipoTramite, $totalImporte, $titularTramite, $localidad, $prontuario){
    $html = '
            <html>
            <body>
                <table border="0">
                    <tr>
                        <td height="70%">
                            <table border="1">
                                <tr>
                                    <td width="50%" bgcolor="#c3c3c3">
                                        <table style="padding-left:3px; padding-top:3px;">
                                            <tr>
                                                <td style="text-align:center;">
                                                    <img src="assets/img/planilla-header-derecho.png" height="28px" width="240px;"/>
                                                    <font style="text-align:center;"><strong><font size="11">PLANILLA PRONTUARIAL EN TRAMITE</font></strong></font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <br><br>
                                                    <font size="15"><strong>CUPON DE PAGO</strong></font><br/>
                                                    <font size="13"><strong>TOTAL: $' . $totalImporte. '</strong></font>
                                                    <div><font size="15"><strong>N° DE TRAMITE: ' . $tramite['id_tramite'] . '</strong></font></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;">
                                                    <font align="center">Fecha: ' . date_format(date_create(date('Y-m-d')), 'd/m/Y') . '</font>
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="98%">
                                                    <font size="9"><strong>NOMBRE Y APELLIDO: </strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</font>
                                                    <br>
                                                    <font size="9" style="text-align:right;"><strong>DNI: </strong>' . $titularTramite['documento'] . '</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <font style="text-align:center;color:#4B4B4B;" size="7px"><strong>SOLICITUD EN PROCESO - LA PRESENTE NO ACREDITA IDENTIDAD</strong></font>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%">
                                        <table style="padding-left:3px; padding-top:3px;">
                                            <tr>
                                                <td style="text-align:center;">
                                                    <img src="assets/img/planilla-header-derecho.png" height="28px" width="240px;"/>
                                                    <font style="text-align:center;"><strong><font size="11">PLANILLA PRONTUARIAL EN TRAMITE</font></strong></font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <br><br>
                                                    <font size="15"><strong>CUPON DE PAGO</strong></font><br/>
                                                    <font size="13"><strong>TOTAL: $' . $totalImporte. '</strong></font>
                                                    <div><font size="15"><strong>N° DE TRAMITE: ' . $tramite['id_tramite'] . '</strong></font></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:center;">
                                                    <font align="center">Fecha: ' . date_format(date_create(date('Y-m-d')), 'd/m/Y') . '</font>
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="98%">
                                                    <font size="9"><strong>NOMBRE Y APELLIDO: </strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</font>
                                                    <br>
                                                    <font size="9" style="text-align:right;"><strong>DNI: </strong>' . $titularTramite['documento'] . '</font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <font style="text-align:center;color:#4B4B4B;" size="7px"><strong>SOLICITUD EN PROCESO - LA PRESENTE NO ACREDITA IDENTIDAD</strong></font>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            ';
            return $html;
    }

    public function getHtmlConstancia($tramite, $tipoTramite, $totalImporte, $titularTramite, $localidad, $prontuario)
    {
        $html = '
        <html>
        <body>
            <table border="0">
                <tr>
                    <td height="70%">
                        <table border="1">
                            <tr>
                                <td width="40%" bgcolor="#c3c3c3">
                                    <table style="padding-left:5px;padding-right:12px;">
                                        <tr>
                                            <td align="center">
                                                <br/><br/>
                                                <font size="15"><strong>CUPON DE PAGO</strong></font><br/>
                                                <div><font size="13"><strong>N° DE TRAMITE: ' . $tramite['id_tramite'] . '</strong></font></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;"><br/><br/>
                                            <font><strong> PLANILLA</strong></font><br/>
                                            <font align="center">Fecha: ' . date_format(date_create(date('Y-m-d')), 'd/m/Y') . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="60%">
                                    <table style="padding-top:3px;">
                                        <tr>
                                            <td style="text-align:center;">
                                                <img src="assets/img/planilla-header-derecho.png" height="28px" width="260px;"/>
                                                <font style="text-align:center;"><strong><font size="11">PLANILLA PRONTUARIAL EN TRAMITE</font></strong></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td width="50%">
                                                <font size="8"><strong>TASAS RETRIBUTIVAS</strong></font><br>
                                                <font size="8">DETALLE:</font><br>
                                                <font size="8">PLANILLA PRONTUARIAL: &nbsp;&nbsp;&nbsp;&nbsp;$' .  $tipoTramite['precio'] . '</font><br>
                                                <font size="8">CERTIFICACION COPIA DNI: $' . $tipoTramite['importe_adicional'] . ' </font>
                                                <div style="text-align:right;">
                                                <font size="9"><strong>TOTAL: $' . $totalImporte. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></font>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="padding-top:0px;">
                                        <tr>
                                            <td width="98%">
                                                <font size="9"><strong>NOMBRE Y APELLIDO: </strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</font>
                                                <br>
                                                <font size="9" style="text-align:right;"><strong>DNI: </strong>' . $titularTramite['documento'] . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                    <font style="text-align:center;color:grey;" size="9px"><strong>SOLICITUD EN PROCESO - LA PRESENTE NO ACREDITA IDENTIDAD</strong></font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br><br>
            <table border="0">
                <tr>
                    <!-- parte lateral derecha -->
                    <td width="100%" height="70%">
                        <table border="1">
                            <tr>
                                <td width="40%" bgcolor="#c3c3c3">
                                    <table style="padding-left:5px;padding-right:12px;">
                                        <tr>
                                            <td align="center">
                                                <br/><br/>
                                                <font size="15"><strong>CUPON DE PAGO</strong></font><br/>
                                                <div><font size="13"><strong>N° DE TRAMITE: ' . $tramite['id_tramite'] . '</strong></font></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;"><br/><br/>
                                            <font><strong> PLANILLA</strong></font><br/>
                                            <font align="center">Fecha: ' . date_format(date_create(date('Y-m-d')), 'd/m/Y') . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="60%">
                                    <table style="padding-top:3px;">
                                        <tr>
                                            <td style="text-align:center;">
                                                <img src="assets/img/planilla-header-derecho.png" height="28px" width="260px;"/>
                                                <font style="text-align:center;"><strong><font size="11">PLANILLA PRONTUARIAL EN TRAMITE</font></strong></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td width="50%">
                                                <font size="8"><strong>TASAS RETRIBUTIVAS</strong></font><br>
                                                <font size="8">DETALLE:</font><br>
                                                <font size="8">PLANILLA PRONTUARIAL: &nbsp;&nbsp;&nbsp;&nbsp;$' .  $tipoTramite['precio'] . '</font><br>
                                                <font size="8">CERTIFICACION COPIA DNI: $' . $tipoTramite['importe_adicional'] . ' </font>
                                                <div style="text-align:right;">
                                                <font size="9"><strong>TOTAL: $' . $totalImporte. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></font>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="padding-top:0px;">
                                        <tr>
                                            <td width="98%">
                                                <font size="9"><strong>NOMBRE Y APELLIDO: </strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</font>
                                                <br>
                                                <font size="9" style="text-align:right;"><strong>DNI: </strong>' . $titularTramite['documento'] . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                    <font style="text-align:center;color:grey;" size="9px"><strong>SOLICITUD EN PROCESO - LA PRESENTE NO ACREDITA IDENTIDAD</strong></font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br><br>
            <table border="0">
                <tr>
                    <!-- parte lateral derecha -->
                    <td width="100%" height="70%">
                        <table border="1">
                            <tr>
                                <td width="40%" bgcolor="#c3c3c3">
                                    <table style="padding-left:5px;padding-right:12px;">
                                        <tr>
                                            <td align="center">
                                                <br/><br/>
                                                <font size="15"><strong>CUPON DE PAGO</strong></font><br/>
                                                <div><font size="13"><strong>N° DE TRAMITE: ' . $tramite['id_tramite'] . '</strong></font></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;"><br/><br/>
                                            <font><strong> PLANILLA</strong></font><br/>
                                            <font align="center">Fecha: ' . date_format(date_create(date('Y-m-d')), 'd/m/Y') . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="60%">
                                    <table style="padding-top:3px;">
                                        <tr>
                                            <td style="text-align:center;">
                                                <img src="assets/img/planilla-header-derecho.png" height="28px" width="260px;"/>
                                                <font style="text-align:center;"><strong><font size="11">PLANILLA PRONTUARIAL EN TRAMITE</font></strong></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td width="50%">
                                                <font size="8"><strong>TASAS RETRIBUTIVAS</strong></font><br>
                                                <font size="8">DETALLE:</font><br>
                                                <font size="8">PLANILLA PRONTUARIAL: &nbsp;&nbsp;&nbsp;&nbsp;$' .  $tipoTramite['precio'] . '</font><br>
                                                <font size="8">CERTIFICACION COPIA DNI: $' . $tipoTramite['importe_adicional'] . ' </font>
                                                <div style="text-align:right;">
                                                <font size="9"><strong>TOTAL: $' . $totalImporte. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></font>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table style="padding-top:0px;">
                                        <tr>
                                            <td width="98%">
                                                <font size="9"><strong>NOMBRE Y APELLIDO: </strong>' . $titularTramite['nombre'] . ' ' . $titularTramite['apellido'] . '</font>
                                                <br>
                                                <font size="9" style="text-align:right;"><strong>DNI: </strong>' . $titularTramite['documento'] . '</font>
                                            </td>
                                        </tr>
                                    </table>
                                    <font style="text-align:center;color:grey;" size="9px"><strong>SOLICITUD EN PROCESO - LA PRESENTE NO ACREDITA IDENTIDAD</strong></font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';
        return $html;
    }

    private function getSolicitanteFoto($html, $tramite, $titularTramite, $dependencia, $pagadoConMacro)
    {
        // <br/><br/><b>ABONAR A TRAVEZ DE MACRO CLIC O EN VENTANILLA DE POLICIA DE JUJUY</b><br/>
        // <img src="assets/img/cupon-titulo.png" width="370" height="27" />
        // <img src="assets/img/cupon-titulo2.png" width="380" height="28" />

        //var_dump($titularTramite);


        $departamentoModel = new DepartamentoModel();
        $departamento = $departamentoModel->find($titularTramite['id_departamento']);
        //var_dump($departamento);
        $localidadModel = new LocalidadModel();
        $localidad = $localidadModel->find($titularTramite['id_localidad']);
        //var_dump($localidad); 

        $html = $html . '
        <table border="0">
        <tr>
           <td height="23%"><img src="assets/img/logo-unidad.png"></td>
           <td><font size="10">DIVISION ANTECEDENTES PERSONALES</font></td>
        </tr>
        <!-- end fila 1 -->
        <tr>
         <td> </td>
         <td>SOLICITANTE 2323</td>
        </tr>
        
        <!-- fila 3 cuil y datos personales -->
        <tr>
           <td width="20%">
             <table width="100%">
               <tr><td>IMAGEN</td></tr>
             </table>
           </td>
          
            <td width="80%">
                    <table style="border: 1px solid #000">
                          <tr>
                            <th  align="left"><font size="10">NOMBRE Y APELLIDO</font>' . strtoupper($titularTramite['apellido']) . ' ' . strtoupper($titularTramite['nombre']) . '</th>
                          </tr>
                          <tr>
                          <th  align="left"><font size="10">Tipo y Nro. de Doc </font>: DNI:' . $titularTramite['documento'] . ' </th>
                          </tr>
                          <tr>
                          <th  align="left"><font size="10">Domicilio: </font>' . $departamento['depto'] . ',' . $localidad['localidad'] . ' </th>
                          </tr>
                          <tr>
                          <th  align="left"><font size="10">Teléfono Celular: </font>' . $titularTramite['telefono'] . ' </th>
                          </tr>
                          <tr>
                          <th  align="left"><font size="10">Correo Electrónico: </font>' . $titularTramite['email'] . ' </th>
                          </tr>
                        
                    </table>
             </td>
                    
        </tr>
        <!-- end fila cuil y datos personales -->             
        </table>
        ';

        return $html;
    }

    // Funcion que arma la columna del Cuil y el codigo QR
    private function getColumna1($titularTramite)
    {
        $html = '
        <table>
        <tr><td>' . $titularTramite['cuil'] . '</td></tr>
        <tr>Codigo QR</tr>
        </table> 
        ';
        return $html;
    }

    private function getColumna2()
    {
        $html = '
           <table>
           <tr> REGISTRO DE ANCEDENTES</tr>
           <tr>
           <ul>
           <li>PENALES</li>
           <li>POLICIALES</li>
           </ul>
           </tr>
           </table>
        ';

        return $html;
    }




    private function antecedentes_penales($cuil)
    {
//         var_dump("cuil : " . $cuil);
        $inculpadoModel = new InculpadoModel();
        $inc = $inculpadoModel->sentencias('20294042890');
//         var_dump("inc : ", $inc);
        $sentencia = NULL;
        $causa = NULL;
        $antecedentes = [];
        if (!empty($inc)) {
            foreach ($inc as $s) {
                $fecha = date('Y-m-d');
                if ($s->caducidad_enplanilla != NULL) {
                    if ($fecha <= $s->caducidad_enplanilla) {
                        $id_resol = $s->id_resolucion;
                        $fecha_resolucion = $s->fecha_oficio;
                        $resolucion = $s->resolucion;
                        //$fecha_causa = $s->fecha_causa;
                        $fecha_causa = NULL;
                        $periodo_fecha = $s->periodo_fecha;
                        //$causa = $s->causa_art;    
                        //                    if($s->articulado != NULL and $s->articulado != ''){
                        $causa = $s->articulado;
                        $informa = $s->informativa;
                        //                    }
                        //$ide_causa = $s->id_causa;
                        $id_involucrado = $s->id_involucrado;
                        $oficio = $s->oficio;
                        $expediente = $s->expediente;
                        $interviniente = $s->interviniente;
                        $antecedentes[] =
                            [
                                "fecha_resolucion" => $fecha_resolucion,
                                "resolucion" => $resolucion,
                                "fecha_causa" => $fecha_causa,
                                "periodo_fecha" => $periodo_fecha,
                                "causa" => $causa,
                                "oficio" => $oficio,
                                "expediente" => $expediente,
                                "interviniente" => $interviniente,
                                "id_involucrado" => $id_involucrado,
                                "informativa" => $informa
                            ];
                    }
                }
            }
            return $antecedentes;
        } else {
            return [];
        }
    }

    // Los antecedentes pentales tienen que ser por periodo_fecha
    private function penales($antecedentes = [])
    {
        $penales_2 = "";
        $contravencionales = "";
        $periodo_fecha = "";
        $causa_a = "";
        $periodo_fecha_a = "";
        $contar_res = "";
        if ($antecedentes && sizeof($antecedentes)) {
            foreach ($antecedentes as $antecedente) {

                echo "informacion de antecedente";
//                 var_dump($antecedente);
                echo "<br>";
                $periodo_fecha = $antecedente['periodo_fecha'];
                $causa = $antecedente['causa'];
                $oficio = "";
                $expte = "";
                $interviniente = "";
                $contar_res = 0;
                if (!empty($antecedente['oficio'])) {
                    $oficio = 'Oficio: ' . $antecedente['oficio'] . '. ';
                }
                if (!empty($antecedente['expediente'])) {
                    $expte = 'Expte: ' . $antecedente['expediente'] . '. ';
                }
                if (!empty($antecedente['interviniente'])) {
                    $interviniente = 'Interviniente: ' . $antecedente['interviniente'] . '. ';
                }
                // PENALES
                if ($antecedente['informativa'] != 2) {
                    if ($periodo_fecha == $periodo_fecha_a and $causa == $causa_a) {
                        $penales_2 = $penales_2 . '&nbsp;/&nbsp;' . date("d-m-Y", strtotime($antecedente['fecha_resolucion'])) .
                            ' Res.: ' . $antecedente['resolucion'] . '. ' . $oficio . $expte . $interviniente;
                        $contar_res = $contar_res + 1;
                    } else {
                        $penales_2 = $penales_2 . '<br>_Fecha: ' . $periodo_fecha . '; Causa: ' . $causa . '; ' . date("d-m-Y", strtotime($antecedente['fecha_resolucion'])) . ' Res.: ' . $antecedente['resolucion'] . '. ' . $oficio . $expte . $interviniente;
                        $band_penal = 1;
                        $contar_res = $contar_res + 1;
                    }
                } else { // CONTRAVENCIONALES
                    if ($periodo_fecha == $periodo_fecha_a and $causa == $causa_a) {
                        $contravencionales = $contravencionales . '&nbsp;/&nbsp;' . date("d-m-Y", strtotime($antecedente['fecha_resolucion'])) .
                            ' Res.: ' . $antecedente['resolucion'] . '. ' . $expte . $interviniente;
                        $band_contra = 1;
                        $contar_res = $contar_res + 1;
                    } else {
                        $contravencionales = $contravencionales . '<br>_Fecha: ' . $periodo_fecha . '; Causa: ' . $causa . '; ' . date("d-m-Y", strtotime($antecedente['fecha_resolucion'])) . ' Res.: ' . $antecedente['resolucion'] . '. ' . $expte . $interviniente;
                        $band_contra = 1;
                        $contar_res = $contar_res + 1;
                    }
                }
                $periodo_fecha_a = $periodo_fecha;
                $causa_a = $causa;

                $salto = '<br>';
            }
        } else {
            return "NO REGISTRA";
        }

        echo "FINALIZA AQUI PENALES =======";
//         var_dump($penales_2);
        echo "FINALIZA AQUI CONTRAVENCIONALES =====";
//         var_dump($contravencionales);
        return $penales_2;
    }

}
