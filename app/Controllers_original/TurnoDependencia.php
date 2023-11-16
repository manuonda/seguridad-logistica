<?php
namespace App\Controllers;

use App\Models\TurnoModel;

class TurnoDependencia extends BaseController {
    
    protected $session;
    protected $userInSession;
    
    public function __construct() {
        $this->session = session();
        $this->userInSession = $this->session->get('user');
    }

    public function index() {
        if(!empty($this->userInSession) &&
             ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL 
            || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5 
            || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL)) {
            $fechaActual = date('Y-m-d');
            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['fecha_turno'])) {
                $fechaActual = $filter['fecha_turno'];
            }
            
            $this->listado($fechaActual);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function buscar() {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL 
          || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5
          || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL)) {
            $fecha_turno = $this->request->getVar('fecha_turno');
            $filter['fecha_turno'] = $fecha_turno;
            $this->session->set('filter', $filter);
            $this->listado($fecha_turno);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function listado($fechaTurno) {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL 
            || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5 
            || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL)) {
            $turnoModel = new TurnoModel();
            $id_dependencia = $this->session->get('id_dependencia');
            $turnoModel->select('tramite_online.turnos.id_turno, tramite_online.turnos.id_tramite, tramite_online.turnos.fecha, tramite_online.turnos.hora, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite,
                                                    tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                                    tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador, tramite_online.tramites.contiene_firma_digital,
                                                    tramite_online.tramites.estado_verificacion')
                                                    ->join('tramite_online.tramites', 'tramite_online.tramites.id_tramite = tramite_online.turnos.id_tramite')
                                                    ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                                    ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                                    ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                                                    ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);
                                                   
             
            $turnoModel->where('tramite_online.tramites.id_dependencia', $id_dependencia);
             // Solamente se busca los tramites de las dependencias cuando estan con los siguientes 
             // roles  
            //  if ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL 
            //  || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5 
            //  || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL) {
            //     $turnoModel->where('tramite_online.tramites.id_dependencia', $id_dependencia);
            //  } else if ($this->userInSession['id_rol']==ROL_ANTECEDENTE  ) {
            //     $turnoModel->where('tramite_online.tipo_tramites.id_tipo_tramite', TIPO_TRAMITE_PLANILLA_PRONTUARIAL);
            //  }
                      
            $turnoModel->where('tramite_online.turnos.fecha', $fechaTurno);
            $data['listado'] =  $turnoModel->orderBy('tramite_online.turnos.fecha', 'ASC')->orderBy('tramite_online.turnos.hora', 'ASC')->findAll();

            $data['userInSession'] = $this->userInSession;
            $data['fecha_turno'] = $fechaTurno;
            $data['contenido'] = "lista_turno_dependencia";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
