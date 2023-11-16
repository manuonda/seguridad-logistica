<?php

use App\Controllers\BaseController;
use App\Models\PersonalModel;
use App\Models\ProvisionPuestoOneModel;
use App\Models\ProvisionPuestoOneValeModel;

class ProvisionPuestoOne extends BaseController {

    protected $provisionPuestoOneModel;
    protected $provisionPuestoOneValeModel;
    protected $personalModel;

    public function __construct() {
        $this->provisionPuestoOneModel = new ProvisionPuestoOneModel();
        $this->provisionPuestoOneValeModel = new ProvisionPuestoOneValeModel();
        $this->personalModel = new PersonalModel();

    }

    public function index($fecha_alta=null) {
            if(empty($fecha_alta)) {
                $data['fecha_desde'] = date('Y-m-d');
                $data['fecha_hasta'] = date('Y-m-d');
            }else {
                $data['fecha_desde'] = $fecha_alta;
                $data['fecha_hasta'] = $fecha_alta;
            }
            $data['listado'] = $this->provisionPuestoOneModel->buscar($data);

            $data['contenido'] = "provision_puesto1_lista_view";
            echo view('frontend', $data);
    
        }

    public function agregar() {
            $data['oper'] = OPER_INSERTAR;
            $data['titulo'] = 'Registrar';
            $data['contenido'] = "provision_puesto1_abm_view";
            echo view('frontend', $data);
    }

    public function editar($id=null) {
        if($id != null && !empty($id)) {
            $data['oper'] = OPER_EDITAR;
            $carga = $this->provisionPuestoOneModel->get($id);

            $data['titulo'] = 'Modificar';
            $data['id'] = $carga->id;
            $data['fecha_alta'] = $carga->fecha_alta;
            $data['legajo_personal'] = $carga->legajo_personal;
            $chofer = $this->personalModel->findByLegajo($carga->legajo_personal);
            $data['jerarquia'] = $chofer->jerarquia;
            $data['apellido'] = $chofer->apellido;
            $data['nombre'] = $chofer->nombre;
            $data['vales'] = $this->provisionPuestoOneValeModel->findValesByIdProvision($carga->id);
            $data['observacion'] = $carga->observacion;

            $data['contenido'] = "provision_puesto1_abm_view";
            echo view('frontend', $data);
        }
    }

    public function guardar() {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'legajo_personal' => ['label' => 'Legajo Policial', 'rules' => 'required'],
            'vales' => ['label' => 'Nro. de Vales', 'rules' => 'required'],
        ]);

        $data['id'] = $this->request->getVar('id');
        $data['legajo_personal'] = $this->request->getVar('legajo_personal');
        $data['idVales'] = $this->request->getVar('idVales');
        $data['observacion'] = $this->request->getVar('observacion');
        $data['jerarquia'] = $this->request->getVar('jerarquia');
        $data['apellido'] = $this->request->getVar('apellido');
        $data['nombre'] = $this->request->getVar('nombre');

        if(empty($data['id'])) {
            $data['oper'] = OPER_INSERTAR;
            $data['titulo'] = 'Registrar';
        }else {
            $data['oper'] = OPER_EDITAR;
            $data['titulo'] = 'Modificar';
        }

        if ($validation->withRequest($this->request)->run()) {
            if(empty($data['id'])) {
                $data['id'] = $this->provisionPuestoOneModel->insert($data);
                $this->index(null);
            }else {
                $this->provisionPuestoOneModel->update($data);
                $this->index($this->request->getVar('fecha_alta'));
            }
        }else {
            $data['contenido'] = "provision_puesto1_abm_view";
            echo view('frontend', $data);
           
        }
    }

    public function buscar() {
       
        $fecha_desde = $this->request->getVar('fecha_desde');
        if(!empty($fecha_desde)) {
            list($dia_desde, $mes_desde, $anio_desde) = explode("/", $fecha_desde);
            $fecha_desde = $anio_desde . "-" . $mes_desde . "-" . $dia_desde;
            $data['fecha_desde'] = date('Y-m-d', strtotime($fecha_desde));
        }
        $fecha_hasta = $this->request->getVar('fecha_hasta');
        if(!empty($fecha_hasta)) {
            list($dia_hasta, $mes_hasta, $anio_hasta) = explode("/", $fecha_hasta);
            $fecha_hasta = $anio_hasta . "-" . $mes_hasta . "-" . $dia_hasta;
            $data['fecha_hasta'] = date('Y-m-d', strtotime($fecha_hasta));
        }
        $data['listado'] = $this->provisionPuestoOneModel->buscar($data);
        $data['contenido'] = "provision_puesto1_lista_view";
        echo view('frontend', $data);
       
    }
}