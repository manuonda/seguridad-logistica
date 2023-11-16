<?php
namespace App\Controllers;

use App\Models\TurnoModel;
use App\Models\TramiteModel;
use App\Models\MovimientoPago;
use App\Libraries\Util;
use App\Models\TipoTramiteModel;
use Exception;
use App\Models\Central\PersonaCentralModel;

class TurnoPlanillaProntuarial extends BaseController {
    
    protected $session;
    protected $userInSession;
    
    public function __construct() {
        $this->session = session();
        $this->userInSession = $this->session->get('user');
    }

    public function index() {
        if(!empty($this->userInSession) && $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA) {
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
        if(!empty($this->userInSession) && $this->userInSession['id_rol']== ROL_UNIDAD_ADMINISTRATIVA) {
            $fecha_turno = $this->request->getVar('fecha_turno');
            $filter['fecha_turno'] = $fecha_turno;
            $this->session->set('filter', $filter);
            $this->listado($fecha_turno);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function listado($fechaTurno) {
        if(!empty($this->userInSession) && $this->userInSession['id_rol']== ROL_UNIDAD_ADMINISTRATIVA) {
            $turnoModel = new TurnoModel();
            $id_dependencia = $this->session->get('id_dependencia');
            $turnoModel->select('tramite_online.turnos.id_turno, tramite_online.turnos.id_tramite, tramite_online.turnos.fecha, tramite_online.turnos.hora, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite,
                                tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador, tramite_online.tramites.contiene_firma_digital,
                                tramite_online.tramites.tipo_planilla, tramite_online.tramites_planilla_detalle.num_prontuario, tramite_online.tramites_planilla_detalle.letra_prontuario, tramite_online.tramites.estado_verificacion,
                                tramite_online.tramite_personas.cuil')
                                ->join('tramite_online.tramites', 'tramite_online.tramites.id_tramite = tramite_online.turnos.id_tramite')
                                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                                ->join('tramite_online.tramites_planilla_detalle', 'tramite_online.tramites_planilla_detalle.id_tramite = tramite_online.tramites.id_tramite', 'left')
                                ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO)
                                ->where('tramite_online.tipo_tramites.id_tipo_tramite ', TIPO_TRAMITE_PLANILLA_PRONTUARIAL)
                                ->where('tramite_online.tramites.id_dependencia', $id_dependencia);
             
                      
            $turnoModel->where('tramite_online.turnos.fecha', $fechaTurno);
            $listado = $turnoModel->orderBy('tramite_online.turnos.fecha', 'ASC')->orderBy('tramite_online.turnos.hora', 'ASC')->findAll();
            $personaCentralModel = new PersonaCentralModel();
            foreach ($listado as $key => $item) {
                if(empty($listado[$key]['num_prontuario'])) {
                    $personaCentral = $personaCentralModel->where('cuil_ciudadano', $listado[$key]['cuil'])->first();
                    if(!empty($personaCentral)) {
                        $listado[$key]['num_prontuario'] = $personaCentral['num_prontuario'];
                        $listado[$key]['letra_prontuario'] = $personaCentral['letra_prontuario'];
                    }
                }
            }
            
            $data['listado'] = $listado;
            $data['fecha_turno'] = $fechaTurno;
            $data['contenido'] = "lista_turno_planilla";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /***
     * Pago Tramite Planilal
     */
	public function pago_tramite_planilla($idTramite)
	{
	    if(empty($this->userInSession)) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
        $tramiteModel = new TramiteModel();
        $tipoTramiteModel = new TipoTramiteModel();

		if (session()->get('isLoggedIn') == NULL) {
// 			return redirect()->to('/caducado');
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$tramite = $tramiteModel->find($idTramite);
      	$tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$status = "";
     
		try {
			$tramite['estado_pago'] = ESTADO_PAGO_PAGADO;
			$tramite['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$tramite['referencia_pago'] = COMISARIA_PAGO;
			$tramite['fecha_pago'] = date('Y-m-d H:i:s');
			$tramite['fecha_modificacion'] = date('Y-m-d H:i:s');
			$tramite['usuario_modificacion'] = session()->get('id');
			$tramiteModel->update($idTramite, $tramite);

			// creo un movimiento
			$movimientoPagoModel = new MovimientoPago();
			$movimientoPago['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$movimientoPago['site_id'] = SITE_POLICIA;
			$movimientoPago['collection_status'] = MP_APPROVED;
			$movimientoPago['id_tramite'] = $idTramite;
			$movimientoPago['monto_recibido'] = $tipoTramite['precio'];
			$movimientoPago['fecha_alta'] = date('Y-m-d H:i:s');
			$movimientoPago['usuario_alta'] = session()->get('id');
			$idMovimientoPago = $movimientoPagoModel->insert($movimientoPago, true);
			$tramite['idMovimientoPago'] = $idMovimientoPago;

			$status = "OK";
		} catch (Exception $e) {
			$status = "ERROR";
		}

		$data['status'] = $status;
		$data['tramite'] = $tramite;
		echo json_encode($data);
		return;
	}

}
