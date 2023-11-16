<?php

namespace App\Controllers;


use App\Models\CargaEspecialModel;
use App\Models\CargaValeModel;
use App\Models\dependeciaModel;
use App\Models\DependenciaModel;
use App\Models\EstacionModel;
use App\Models\JerarquiaModel;
use App\Models\PersonalModel;
use App\Models\TipoCombustibleModel;
use App\Models\UnidadPolicialModel;

class CargaEspeical extends BaseController {

    protected $cargaEspecialModel;
    protected $unidadPolicialModel;
    protected $dependeciaModel;
    protected $tipoCombustibleMode;
    protected $cargaValeModel;
    protected $personalModel;
    protected $estacionModel;
    protected $jerarquiaModel;

    public function __construct() {
        $this->cargaEspecialModel = new CargaEspecialModel();
        $this->unidadPolicialModel = new UnidadPolicialModel();
        $this->dependeciaModel = new DependenciaModel();
        $this->tipoCombustibleMode = new TipoCombustibleModel();
        $this->cargaValeModel = new CargaValeModel();
        $this->personalModel = new PersonalModel();
        $this->estacionModel = new EstacionModel();
        $this->jerarquiaModel = new JerarquiaModel();

    }

    public function index($fecha_alta=null) {
      
            if(empty($fecha_alta)) {
                $data['fecha_desde'] = date('Y-m-d');
                $data['fecha_hasta'] = date('Y-m-d');
            }else {
                $data['fecha_desde'] = $fecha_alta;
                $data['fecha_hasta'] = $fecha_alta;
            }
            $data['listado'] = $this->cargaEspecialModel->buscar($data);
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

            $data['contenido'] = "carga/carga_especial_lista_view";
            echo view('frontend', $data);
      
    }

    public function agregar() {
       
            $data['titulo'] = 'Registrar';
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['jerarquias'] = $this->jerarquiaModel->get_all();
            $data['contenido'] = "carga/carga_especial_abm_view";
            echo view('frontend', $data);
       
    }

    public function editar($id=null) {
        if($id != null && !empty($id)) {
            $carga = $this->cargaEspecialModel->get($id);
            $data['titulo'] = 'Modificar';
            $data['id'] = $carga->id;
            $data['fecha_alta'] = $carga->fecha_alta;
            $data['descripcion'] = $carga->descripcion;
            $data['id_unidad_policial'] = $carga->id_unidad_policial;
            $data['id_dependencia'] = $carga->id_dependencia;

            $data['legajo_personal'] = $carga->legajo_personal;
            $chofer = $this->personalModel->findByLegajo($carga->legajo_personal);
            $data['jerarquia'] = $chofer->jerarquia;
            $data['apellido'] = $chofer->apellido;
            $data['nombre'] = $chofer->nombre;

            $data['id_estacion'] = $carga->id_estacion;
            $data['id_tipo'] = $carga->id_tipo;
            $data['id_tipo_combustible'] = $carga->id_tipo_combustible;
            $data['cantidad_litros'] = $carga->cantidad_litros;
            $data['importe'] = $carga->importe;
            $data['vales'] = $this->cargaValeModel->findValesByIdCargaEspecial($carga->id);
            $data['nro_comprobante'] = $carga->nro_comprobante;
            $data['observacion'] = $carga->observacion;

            $data['nro_nota_refuerzo'] = $carga->nro_nota_refuerzo;
            $data['jefe_logistica_autorizante'] = $carga->jefe_logistica_autorizante;
            $data['jefe_unidad_autorizante'] = $carga->jefe_unidad_autorizante;

            if(!empty($data['id_unidad_policial'])) {
                $data['dependencias'] = $this->dependeciaModel->findByUnidadPolicial($data['id_unidad_policial']);
            }
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();
            $data['contenido'] = "carga/carga_especial_abm_view";
            echo view('frontend', $data);
        }
    }

    public function guardar() {
        $data['id'] = $this->request->getVar('id');
        
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'id_estacion' => ['label' => 'Estación de Servicio', 'rules' => 'required'],
            'id_tipo' => ['label' => 'Tipo de Carga', 'rules' => 'required|min_length[2]'],
            'id_tipo_combustible' => ['label' => 'Tipo de Combustible', 'rules' => 'required|exact_length[10]'],
            'descripcion' => ['label' => 'Descripción', 'rules' => 'required|exact_length[10]'],
            'legajo_personal' => ['label' => 'Legajo Personal', 'rules' => 'required|exact_length[10]'],
            'descripcion' => ['label' => 'Descripción', 'rules' => 'required|exact_length[10]'],
            'cantidad_litros' => ['label' => 'Cantidad de Litros', 'rules' => 'required|exact_length[10]'],
            'importe' => ['label' => 'Importe', 'rules' => 'required|exact_length[10]'],


        ]);
   

        $data['descripcion'] = strtoupper($this->request->getVar('descripcion'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
        $data['legajo_personal'] = $this->request->getVar('legajo_personal');
        $data['id_estacion'] = $this->request->getVar('id_estacion');
        $data['id_tipo'] = $this->request->getVar('id_tipo');
        $data['id_tipo_combustible'] = $this->request->getVar('id_tipo_combustible');
        $data['cantidad_litros'] = $this->request->getVar('cantidad_litros');
        $data['importe'] = $this->request->getVar('importe');
        $data['idVales'] = $this->request->getVar('idVales');
        $data['nro_comprobante'] = strtoupper($this->request->getVar('nro_comprobante'));
        $data['nro_nota_refuerzo'] = strtoupper($this->request->getVar('nro_nota_refuerzo'));
        $data['jefe_logistica_autorizante'] = strtoupper($this->request->getVar('jefe_logistica_autorizante'));
        $data['jefe_unidad_autorizante'] = strtoupper($this->request->getVar('jefe_unidad_autorizante'));
        $data['observacion'] = $this->request->getVar('observacion');

        $data['jerarquia'] = $this->request->getVar('jerarquia');
        $data['apellido'] = $this->request->getVar('apellido');
        $data['nombre'] = $this->request->getVar('nombre');

        if(empty($data['id'])) {
            $data['titulo'] = 'Registrar';
        }else {
            $data['titulo'] = 'Modificar';
        }

        if ($validation->withRequest($this->request)->run()) {

            if(empty($data['id'])) {
                $data['id'] = $this->cargaEspecialModel->agregar($data);
                $this->index(null);
            }else {
                $this->cargaEspecialModel->actualizar($data);
                $this->index($this->request->getVar('fecha_alta'));
            }
        }else {
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            if(!empty($data['id_unidad_policial'])) {
                $data['dependencias'] = $this->dependeciaModel->findByUnidadPolicial($data['id_unidad_policial']);
            }
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();
            $data['contenido'] = "carga/carga_especial_abm_view";
            echo view('frontend', $data);
        }
    }

    public function buscar() {
        
            $data['descripcion'] =  $this->request->getVar('descripcion');
            $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
            $data['id_dependencia'] =  $this->request->getVar('id_dependencia');
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

            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            $data['listado'] = $this->cargaEspecialModel->buscar($data);
            $data['contenido'] = "carga/carga_especial_lista_view";
            echo view('frontend', $data);
        }
}