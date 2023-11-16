<?php

namespace App\Controllers;

use App\Models\TramiteModel;
use App\Libraries\Util;

class BuscarTramitePersona extends BaseController
{

    protected $util;
    protected $session;
    protected $userInSession;

    public function __construct()
    {
        $this->util = new Util();
        $this->session = session();
        $this->userInSession = $this->session->get('user');
    }

    public function index()
    {
        if (!empty($this->userInSession) && ($this->userInSession['id_rol'] == ROL_COMISARIA_SECCIONAL
            || session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $documento = null;
            $fechaDesde = date('Y-m-d');
            $fechaAux = strtotime('+1 day', strtotime($fechaDesde));
            $fechaHasta = date('Y-m-d', $fechaAux);
            $filter = $this->session->get('filter');
            if ($filter != null) {
                if (!empty($filter['fechaDesde'])) {
                    $fechaDesde = $filter['fechaDesde'];
                }
                if (!empty($filter['fechaHasta'])) {
                    $fechaHasta = $filter['fechaHasta'];
                }
                if (!empty($filter['documento'])) {
                    $documento = $filter['documento'];
                }
            }

            $this->listado($fechaDesde, $fechaHasta, $documento);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function buscar()
    {
        if (!empty($this->userInSession) && ($this->userInSession['id_rol'] == ROL_COMISARIA_SECCIONAL || session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $fechaDesde = $this->request->getVar('fechaDesde');
            $fechaHasta = $this->request->getVar('fechaHasta');
            $documento = $this->request->getVar('documento');
            $filter['fechaDesde'] = $fechaDesde;
            $filter['fechaHasta'] = $fechaHasta;
            $filter['documento'] = $documento;
            $this->session->set('filter', $filter);
            $this->listado($fechaDesde, $fechaHasta, $documento);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    private function listado($fechaDesde, $fechaHasta, $documento)
    {
        if (!empty($this->userInSession) && ($this->userInSession['id_rol'] == ROL_COMISARIA_SECCIONAL
            ||  session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $tramiteModel = new TramiteModel();
            $id_dependencia = $this->session->get('id_dependencia');
            $tramiteModel->select('tramite_online.tramites.fecha_alta, tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite,
                                                tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                                tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador,
                                                tramite_online.tramites.contiene_firma_digital, tramite_online.tramites.estado_verificacion')       
                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO)
				->where('tramite_online.tipo_tramites.id_tipo_tramite !=',TIPO_TRAMITE_PAGO_REBA)
                ->where('tramite_online.tramites.id_dependencia', intval($id_dependencia));


            
            if (!empty($fechaDesde)) {
                $tramiteModel->where('tramite_online.tramites.fecha_alta>=', $fechaDesde);
            }
            if (!empty($fechaHasta)) {
                $tramiteModel->where('tramite_online.tramites.fecha_alta<=', $fechaHasta);
            }
            if (!empty($documento)) {
                $tramiteModel->where('tramite_online.tramite_personas.documento=', trim($documento));
            }
            $tramiteModel->orderBy('tramite_online.tramites.id_tramite', 'DESC');
            //                                                 $data['listado'] = $tramiteModel->get()->getResultArray();
            $data['listado'] = $tramiteModel->findAll();
            //          var_dump($tramiteModel->getLastQuery());
            //         echo 'count='.count($data['listado']);
            $data['fechaDesde'] = $fechaDesde;
            $data['fechaHasta'] = $fechaHasta;
            $data['documento'] = $documento;
            $data['userInSession'] = $this->userInSession;
            $data['util'] = $this->util;
            $data['contenido'] = "buscar_tramite_persona";
            echo view("frontend", $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
