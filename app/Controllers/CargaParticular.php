<?php

namespace App\Controllers;
use App\Models\CargaEspecialModel;
use App\Models\CargaValeModel;
use App\Models\DependenciaModel;
use App\Models\EstacionModel;
use App\Models\JerarquiaModel;
use App\Models\MovilModel;
use App\Models\PersonalModel;
use App\Models\SituacionModel;
use App\Models\TipoCombustibleModel;
use App\Models\TipoMovilModel;
use App\Models\UnidadPolicialModel;
use App\Models\CargaParticularModel;

class CargaParticular extends BaseController {



    protected $cargaEspecialModel;
    protected $unidadPolicialModel;
    protected $dependeciaModel;
    protected $tipoCombustibleMode;
    protected $cargaValeModel;
    protected $personalModel;
    protected $estacionModel;
    protected $jerarquiaModel;
    protected $cargaParticularModel;
    protected $movilModel;
    protected $tipoMovilModel;
    protected $situacionModel;


    public function __construct() {
        $this->cargaEspecialModel = new CargaEspecialModel();
        $this->unidadPolicialModel = new UnidadPolicialModel();
        $this->dependeciaModel = new DependenciaModel();
        $this->tipoCombustibleMode = new TipoCombustibleModel();
        $this->cargaValeModel = new CargaValeModel();
        $this->personalModel = new PersonalModel();
        $this->estacionModel = new EstacionModel();
        $this->jerarquiaModel = new JerarquiaModel();
        $this->cargaParticularModel = new CargaParticularModel();
        $this->movilModel= new MovilModel();
        $this->tipoMovilModel = new TipoMovilModel();
        $this->situacionModel = new SituacionModel();
        $this->unidadPolicialModel = new UnidadPolicialModel();

    }



    public function index($fecha_alta=null) {
            if(empty($fecha_alta)) {
                $data['fecha_desde'] = date('Y-m-d');
                $data['fecha_hasta'] = date('Y-m-d');
            }else {
                $data['fecha_desde'] = $fecha_alta;
                $data['fecha_hasta'] = $fecha_alta;
            }

            $data['listado'] = $this->cargaParticularModel->buscar($data);
            $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

            $data['contenido'] = "carga_particular_lista_view";
            echo view('frontend', $data);
    }
    

    function agregar() {
        if ($this->ion_auth->logged_in()) {
            $data['oper'] = OPER_INSERTAR;
            $data['titulo'] = 'Registrar';
            $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();
            $data['estaciones'] = $this->estacionModel->get_all();

            $data['contenido'] = "carga_particular_abm_view";
            echo view('frontend', $data);
        } else {
            redirect('admin/login');
        }
    }

    public function editar($id=null) {
        $data['oper'] = OPER_EDITAR;
        if($id != null && !empty($id)) {
            $carga = $this->cargaParticularModel->get($id);

            $data['titulo'] = 'Modificar';
            $data['id'] = $carga->id;
            $data['fecha_alta'] = $carga->fecha_alta;
            $data['dominio'] = $carga->dominio;
            $data['marca'] = $carga->marca;
            $data['modelo'] = $carga->modelo;
            $data['anio'] = $carga->anio;
            $data['id_tipo_movil'] = $carga->id_tipo_movil;
            $data['kilometraje'] = $carga->kilometraje;
            $data['id_dependencia'] = $carga->id_dependencia;
            $data['id_unidad_policial'] = $carga->id_unidad_policial;

            $data['dni'] = $carga->dni;
            $data['legajo'] = $carga->legajo;
            $data['apellido'] = $carga->apellido;
            $data['nombre'] = $carga->nombre;
            $data['cargo_funcion'] = $carga->cargo_funcion;
            $data['lugar_de_trabajo'] = $carga->lugar_de_trabajo;

            $data['id_estacion'] = $carga->id_estacion;
            $data['id_tipo'] = $carga->id_tipo;
            $data['id_tipo_combustible'] = $carga->id_tipo_combustible;
            $data['cantidad_litros'] = $carga->cantidad_litros;
            $data['importe'] = $carga->importe;
            $data['nro_resolucion'] = $carga->nro_resolucion;
            $data['vales'] = $this->cargaValeModel->findValesByIdCargaParticular($carga->id);
            $data['nro_comprobante'] = $carga->nro_comprobante;
            $data['observacion'] = $carga->observacion;

            $data['nro_nota_refuerzo'] = $carga->nro_nota_refuerzo;
            $data['jefe_logistica_autorizante'] = $carga->jefe_logistica_autorizante;
            $data['jefe_unidad_autorizante'] = $carga->jefe_unidad_autorizante;

            if(!empty($data['id_unidad_policial'])) {
                $data['dependencias'] = $this->dependeciaModel->findByUnidadPolicial($data['id_unidad_policial']);
            }
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();
            $data['contenido'] = "carga_particular_abm_view";
            echo view('frontend', $data);
        }
    }

    public function guardar() {
        $data['id'] =  $this->request->getVar('id');
        $data['id_tipo_movil'] =   $this->request->getVar('id_tipo_movil');
        $data['id_dependencia'] =  $this->request->getVar('id_dependencia');
        $data['id_unidad_policial'] =  $this->request->getVar('id_unidad_policial');
        $data['id_estacion'] =  $this->request->getVar('id_estacion');
        $data['id_tipo_combustible'] =  $this->request->getVar('id_tipo_combustible');
        $data['id_tipo'] =  $this->request->getVar('id_tipo');
        
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'id_estacion' => ['label' => 'Estación de Servicio', 'rules' => 'required'],
            'id_tipo_combustible' => ['label' => 'Tipo de Combustible', 'rules' => 'required'],
            'id_tipo' => ['label' => 'Tipo de Carga', 'rules' => 'required'],
            'dominio' => ['label' => 'Dominio', 'rules' => 'required'],
            'cantidad_litros' => ['label' => 'Cantidad de litros', 'rules' => 'required'],
        ]);
        
        $carga = $this->cargaParticularModel->get($data['id']);
        $data['id_tipo_movil'] = $carga->id_tipo_movil;
        $data['id_estacion'] = $carga->id_estacion;
        $data['id_tipo'] = $carga->id_tipo;
        $data['id_tipo_combustible'] = $carga->id_tipo_combustible;
        $data['id_dependencia'] = $carga->id_dependencia;
        $data['id_unidad_policial'] = $carga->id_unidad_policial;
       
       

        $data['dominio'] =  strtoupper( $this->request->getVar('dominio'));
        $data['marca'] =  strtoupper( $this->request->getVar('marca'));
        $data['modelo'] =  strtoupper( $this->request->getVar('modelo'));
        $data['anio'] =   $this->request->getVar('anio');
        $data['kilometraje'] =  $this->request->getVar('kilometraje');

        $data['dni'] =  $this->request->getVar('dni');
        $data['legajo'] =  $this->request->getVar('legajo');
        $data['apellido'] =  strtoupper( $this->request->getVar('apellido'));
        $data['nombre'] =  strtoupper( $this->request->getVar('nombre'));
        $data['cargo_funcion'] =  strtoupper( $this->request->getVar('cargo_funcion'));
        $data['lugar_de_trabajo'] =  strtoupper( $this->request->getVar('lugar_de_trabajo'));

        $data['cantidad_litros'] =  $this->request->getVar('cantidad_litros');
        $data['nro_comprobante'] = strtoupper( $this->request->getVar('nro_comprobante'));
        $data['importe'] =  $this->request->getVar('importe');
        $data['nro_resolucion'] = strtoupper( $this->request->getVar('nro_resolucion'));
        $data['idVales'] =  $this->request->getVar('idVales');
        $data['vales'] =  $this->request->getVar('vales');
        $data['observacion'] =  $this->request->getVar('observacion');
        $data['nro_nota_refuerzo'] = strtoupper( $this->request->getVar('nro_nota_refuerzo'));
        $data['jefe_logistica_autorizante'] = strtoupper( $this->request->getVar('jefe_logistica_autorizante'));
        $data['jefe_unidad_autorizante'] = strtoupper( $this->request->getVar('jefe_unidad_autorizante'));

//        echo 'id='.$data['id'];
        if(empty($data['id'])) {
            $data['oper'] = OPER_INSERTAR;
            $data['titulo'] = 'Registrar';
        }else {
            $data['oper'] = OPER_EDITAR;
            $data['titulo'] = 'Modificar';
        }

        if ($validation->withRequest($this->request)->run()) {

            if( empty($data['id_unidad_policial']) &&
                empty($data['id_dependencia']) && empty($data['lugar_de_trabajo'])) {
                $data['error'] = '¡Debe ingresar el Lugar de Trabajo!';
                return $this->irPantallaAbm($data);
            }

            if(empty($data['id'])) {
                $data['id'] = $this->cargaParticularModel->insert($data);
                $this->index(null);
            }else {
                $this->cargaParticularModel->update($data);
                $this->index( $this->request->getVar('fecha_alta'));
            }
        }else {
            return $this->irPantallaAbm($data);
        }
    }

    private function irPantallaAbm($data) {
        $data['estaciones'] = $this->estacionModel->get_all();
        $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
        $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
        $data['tipo_combustibles'] = $this->tipoCombustibleMode->get_all();

        $data['contenido'] = "carga_particular_abm_view";
        echo view('frontend', $data);
    }

    public function buscar() {
       
            $data['dominio'] =  strtoupper( $this->request->getVar('dominio'));
            $data['id_tipo_movil'] =  $this->request->getVar('id_tipo_movil');
            $data['id_unidad_policial'] =  $this->request->getVar('id_unidad_policial');
            $data['dni'] =   $this->request->getVar('dni');
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

            $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

            $data['listado'] = $this->cargaParticularModel->buscar($data);
            $data['contenido'] = "carga_particular_lista_view";
            echo view('frontend', $data);
        
    }

    public function getDatosMovil($dominio) {
        $data['dominio'] = strtoupper($dominio);
        $movil = $this->cargaParticularModel->findByDominio($data['dominio']);
        if(empty($movil)) {
            $data['isError'] = true;
            //$data['codigoError'] = 1;
            $data['mensaje'] = "El movil de Dominio ".$data['dominio']." no existe.";
        }else {
            $data['isError'] = false;
            $data['marca'] = $movil->marca;
            $data['modelo'] = $movil->modelo;
            $data['anio'] = $movil->anio;
            $data['id_tipo_movil'] = $movil->id_tipo_movil;
            $data['id_dependencia'] = $movil->id_dependencia;
            $data['id_unidad_policial'] = $movil->id_unidad_policial;

            if(!empty($movil->kilometraje)) {
                $data['kilometrajeAnterior'] = $movil->kilometraje;
                $data['kilometrajeActualEstimado'] = $movil->kilometraje + ($movil->kilometros_por_litro * $movil->cantidad_litros);
            }else {
                $data['kilometrajeAnterior'] = '';
                $data['kilometrajeActualEstimado'] = '';
            }

            $ultimaFechaDeCarga = date_format(date_create($movil->fecha_alta), 'd/m/Y');
            $fechaActual = date("d/m/Y");
            if($ultimaFechaDeCarga === $fechaActual) {
                $data['yaCargoHoy'] = true;
                $data['horaDeCarga'] = date_format(date_create($movil->fecha_alta), 'H:i');
            }else {
                $data['yaCargoHoy'] = false;
                $data['horaDeCarga'] = '';
            }
        }

        echo json_encode($data);
    }
}