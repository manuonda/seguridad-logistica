<?php

use App\Controllers\BaseController;
use App\Models\MovilModel;
use App\Models\TalonarioModel;
use App\Models\TipoCombustibleModel;
use App\Models\ValesModel;

class Talonario extends BaseController {

    protected $talonarioModel;
    protected $valesModel;
    protected $tipoCombustibleModel;
    protected $movilModel;

    public function __construct() {
      $this->talonarioModel =new TalonarioModel();
      $this->valesModel = new ValesModel();
      $this->tipoCombustibleModel = new TipoCombustibleModel();
      $this->movilModel = new MovilModel(); 
    }

    public function index() {
            $data['fecha_desde'] = date('Y-m-d');
            $data['fecha_hasta'] = date('Y-m-d');

            $data['listado'] = $this->talonarioModel->buscar($data);
            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();

            $data['contenido'] = "talonario_lista_view";
            echo view('frontend', $data);
       
    }

    public function agregar() {
        $data['oper'] = OPER_INSERTAR;
        $data['titulo'] = 'Registrar';
        $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
        $data['contenido'] = "talonario_abm_view";
        echo view('frontend', $data);
    }

    public function editar($id=null) {
        $data['oper'] = OPER_EDITAR;
        if($id != null && !empty($id)) {
            $talonario = $this->talonarioModel->get($id);
            $data['titulo'] = '';
            $data['id_tipo_combustible'] = $talonario->id_tipo_combustible;
            $data['fecha_alta'] = $talonario->fecha_alta;
            $data['inicio'] = $talonario->inicio;
            $data['fin'] = $talonario->fin;

            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
            $data['listado'] = $this->valesModel->getAll($id);
            $data['contenido'] = "talonario_abm_view";
            echo view('frontend', $data);
        }
    }

    public function guardar() {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'id_tipo_combustible' => ['label' => 'Tipo de combustible', 'rules' => 'required'],
            'inicio' => ['label' => 'Inicio', 'rules' => 'required'],
            'fin' => ['label' => 'Fin', 'rules' => 'required'],
        ]);

        $data['id'] =  $this->request->getVar('id');
        $data['id_tipo_combustible'] =  $this->request->getVar('id_tipo_combustible');
        $data['inicio'] =  $this->request->getVar('inicio');
        $data['fin'] =  $this->request->getVar('fin');

        if(empty($data['id'])) {
            $data['titulo'] = 'Registrar';
            $data['oper'] = OPER_INSERTAR;
        }else {
            $data['titulo'] = 'Editar';
            $data['oper'] = OPER_EDITAR;
        }

        if ($validation->withRequest($this->request)->run()) {
            if($this->talonarioModel->existe($data['id_tipo_combustible'], $data['inicio'])) {
                $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
                $data['error'] = '¡El Talonario ya existe!';
                $data['contenido'] = "talonario_abm_view";
                echo view('frontend', $data);
                return;
            }

            if(empty($data['id'])) {
                $data['id'] = $this->talonarioModel->insert($data);
            }else {
                $this->talonarioModel->update($data);
            }

            $this->index();
        }else {
            echo view("talonario_abm_view", $data);
            return;
        }
    }

    public function buscar() {
       
            $data['id_tipo_combustible'] =   $this->request->getVar('id_tipo_combustible');
            $data['numero'] =  $this->request->getVar('numero');

            $fecha_desde =  $this->request->getVar('fecha_desde');
            if(!empty($fecha_desde)) {
                list($dia_desde, $mes_desde, $anio_desde) = explode("/", $fecha_desde);
                $fecha_desde = $anio_desde . "-" . $mes_desde . "-" . $dia_desde;
                $data['fecha_desde'] = date('Y-m-d', strtotime($fecha_desde));
            }

            $fecha_hasta =  $this->request->getVar('fecha_hasta');
            if(!empty($fecha_hasta)) {
                list($dia_hasta, $mes_hasta, $anio_hasta) = explode("/", $fecha_hasta);
                $fecha_hasta = $anio_hasta . "-" . $mes_hasta . "-" . $dia_hasta;
                $data['fecha_hasta'] = date('Y-m-d', strtotime($fecha_hasta));
            }

            $data['listado'] = $this->talonarioModel->buscar($data);
            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
            $data['contenido'] = "talonario_lista_view";
            echo view('frontend', $data);
        
    }

    public function get_datos_json($legajo) {
            $movil = $this->movilModel->findByLegajo($legajo);
            if(empty($movil)) {
                $data['isError'] = true;
                $data['mensaje'] = "El movil de Legajo=$legajo no existe.";
            }else {
                $data['isError'] = false;
                $data['unidadPolicial'] = $movil->unidadPolicial;
                $data['dependencia'] = $movil->dependencia;
                $data['tipoMarcaModelo'] = $movil->tipo . ' ' . $movil->marca . ' ' . $movil->modelo;
                $data['dominio'] = $movil->dominio;
            }

            echo json_encode($data);
//        }
    }

    public function get_talonarios_vales() {
        $data['talonarios'] = $this->talonarioModel->findTalonariosConVales();
        echo view('modal_vales_view', $data);
    }

    public function get_talonario($nro_vale=null) {
        $ids_talonarios = $this->request->getVar('ids_talonarios');
        $array_ids_talonarios = null;
        if(!empty($ids_talonarios)) {
            $array_ids_talonarios = explode(",", $ids_talonarios);
        }

        $data['talonario'] = $this->talonarioModel->findByNroVale($nro_vale, $array_ids_talonarios);
        if(empty($data['talonario'])) {
            $vale = $this->valesModel->findByNumero($nro_vale);
            if(empty($vale)) {
                $data['code_error'] = 1; // El vale no existe
            }else if ($vale->asignado == 1) {
                $data['code_error'] = 2; // El vale ya fue asigando
            }
        }
        echo view('modal/include/talonario_vales_view', $data);
    }
}