<?php
namespace App\Controllers;

use App\Models\TramiteModel;

class CiacDenuncia extends BaseController {
    
    protected $session;
    
    public function __construct() {
        $this->session = session();
    }

    public function index() {
        if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_CIAC) {
            $fechaDesde = null;
            $fechaHasta = null;
            $documento = null;
            $id_dependencia = null;
            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['documento'])) {
                $documento = $filter['documento'];
            }
            
            $this->listado($fechaDesde, $fechaHasta, $documento, $id_dependencia);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function buscar() {
        if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_CIAC) {
            $fechaDesde = $this->request->getVar('fechaDesde');
            $fechaHasta = $this->request->getVar('fechaHasta');
            $documento = $this->request->getVar('documento');
            $id_dependencia = $this->request->getVar('id_dependencia');
            
            $filter['documento'] = $documento;
            session()->set('filter', $filter);
            
            $this->listado($fechaDesde, $fechaHasta, $documento, $id_dependencia);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function listado($fechaDesde, $fechaHasta, $documento, $id_dependencia) {
        $tramiteModel = new TramiteModel();
        $tramiteModel->select('tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite, tramite_online.tramites.fecha_alta, tramite_online.tramite_personas.cuil,
                            tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                            tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador')
                            ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                            ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                            ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                            ->where('tramite_online.tramites.id_tipo_tramite', TIPO_TRAMITE_CONSTANCIA_DENUNCIA)
                         //   ->where('tramite_online.tramites.estado_pago', ESTADO_PAGO_PENDIENTE)
                            ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);
                            if(!empty($documento)) {
                                $tramiteModel->where('tramite_online.tramite_personas.documento', $documento);
                            }
                            if(!empty($id_dependencia)) {
                                $tramiteModel->where('tramite_online.tramites.id_dependencia', $id_dependencia);
                            }
                                                
        $data['listado'] = $tramiteModel->orderBy('tramite_online.tramites.fecha_alta', 'desc')->findAll();
        
        $data['fechaDesde'] = $fechaDesde;
        $data['fechaHasta'] = $fechaHasta;
        $data['documento'] = $documento;
        $data['id_dependencia'] = $id_dependencia;
        $data['contenido'] = "ciac/listado_denuncias";
        echo view("frontend", $data);
    }
}
