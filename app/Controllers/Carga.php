<?php

namespace App\Controllers;

use App\Models\CargaModel;
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

class Carga extends BaseController {

    protected $cargaModel;
    protected $movilModel;
    protected $tipoMovilModel;
    protected $situacionModel;
    protected $unidadPolicialModel;
    protected $dependenciaModel;
    protected $tipoCombustibleModel;
    protected $cargaValeModel;
    protected $personalModel;
    protected $estacionModel;
    protected $jerarquiaModel;



    public function __construct() {
        $this->cargaModel = new CargaModel();
        $this->movilModel = new MovilModel();
        $this->tipoMovilModel = new TipoMovilModel();
        $this->situacionModel = new SituacionModel();
        $this->unidadPolicialModel = new UnidadPolicialModel();
        $this->dependenciaModel = new DependenciaModel();
        $this->tipoCombustibleModel = new TipoCombustibleModel();
        $this->cargaValeModel = new CargaValeModel();
        $this->personalModel= new PersonalModel();
        $this->estacionModel = new EstacionModel();
        $this->jerarquiaModel = new JerarquiaModel();

        /*
        if (session()->get('isLoggedIn') == NULL) {
			//return redirect()->to('/caducado');
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}*/
       
    }

    public function index($fecha_alta=null) {
       
            if(empty($fecha_alta)) {
                $data['fecha_desde'] = date('Y-m-d');
                $data['fecha_hasta'] = date('Y-m-d');
            }else {
                $data['fecha_desde'] = $fecha_alta;
                $data['fecha_hasta'] = $fecha_alta;
            }
            $data['listado'] = $this->cargaModel->buscar($data);
            $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
            $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

            $data['contenido'] = "carga_lista_view";
            echo view('frontend', $data);
       
    }

    public function agregar() {
        
            $data['titulo'] = 'Registrar';
            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['jerarquias'] = $this->jerarquiaModel->get_all();

            $data['contenido'] = "carga_abm_view";
            echo view('frontend', $data);
        
    }

    public function editar($id=null) {
        if($id != null && !empty($id)) {
            $carga = $this->cargaModel->get($id);

            $data['titulo'] = 'Modificar';
            $data['id'] = $carga->id;
            $data['fecha_alta'] = $carga->fecha_alta;
            $data['legajo_movil'] = $carga->legajo_movil;
            $data['kilometraje'] = $carga->kilometraje;

            $movil = $this->movilModel->findByLegajo($carga->legajo_movil);
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['unidadPolicial'] = $this->unidadPolicialModel->get($carga->id_unidad_policial)->nombre;
            $data['dependencia'] = $this->dependenciaModel->get($carga->id_dependencia)->dependencia;
            $data['tipoMarcaModelo'] = $movil->tipo . ' ' . $movil->marca . ' ' . $movil->modelo;
            $data['dominio'] = $movil->dominio;

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
            $data['vales'] = $this->cargaValeModel->findValesByIdCarga($carga->id);
            $data['nro_comprobante'] = $carga->nro_comprobante;
            $data['observacion'] = $carga->observacion;

            $data['nro_nota_refuerzo'] = $carga->nro_nota_refuerzo;
            $data['jefe_logistica_autorizante'] = $carga->jefe_logistica_autorizante;
            $data['jefe_unidad_autorizante'] = $carga->jefe_unidad_autorizante;

            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
            $data['contenido'] = "carga_abm_view";
            echo view('frontend', $data);
        }
    }

    public function guardar() {
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'id_estacion' => ['label' => 'Estación de Servicio', 'rules' => 'required'],
            'id_tipo' => ['label' => 'Tipo de Carga', 'rules' => 'required|min_length[2]'],
            'id_tipo_combustible' => ['label' => 'Tipo de Combustible', 'rules' => 'required|exact_length[10]'],
            'legajo_movil' => ['label' => 'Apellido', 'rules' => 'required'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required'],
            'legajo_movil' => ['label' => 'Legajo Móvil', 'rules' => 'required'],
            'legajo_personal' => ['label' => 'Legajo Personal', 'rules' => 'required'],
            'kilometraje' => ['label' => 'Kilometraje', 'rules' => 'required'],
            'cantidad_litros' => ['label' => 'Cantidad de Litros', 'rules' => 'required'],
            'importe' => ['label' => 'Importe', 'rules' => 'required']
        ]);

        $data['id'] = $this->request->getVar('id');

        $data['legajo_movil'] =  strtoupper($this->request->getVar('legajo_movil'));
        $data['kilometraje'] = $this->request->getVar('kilometraje');
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
        $data['legajo_personal'] = $this->request->getVar('legajo_personal');
        $data['id_estacion'] = $this->request->getVar('id_estacion');
        $data['id_tipo'] = $this->request->getVar('id_tipo');
        $data['id_tipo_combustible'] = $this->request->getVar('id_tipo_combustible');
        $data['cantidad_litros'] = $this->request->getVar('cantidad_litros');
        $data['importe'] = $this->request->getVar('importe');
        $data['nro_comprobante'] = strtoupper($this->request->getVar('nro_comprobante'));
        $data['idVales'] = $this->request->getVar('idVales');
        $data['observacion'] = $this->request->getVar('observacion');
        $data['nro_nota_refuerzo'] = strtoupper($this->request->getVar('nro_nota_refuerzo'));
        $data['jefe_logistica_autorizante'] = strtoupper($this->request->getVar('jefe_logistica_autorizante'));
        $data['jefe_unidad_autorizante'] = strtoupper($this->request->getVar('jefe_unidad_autorizante'));

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
                $data['id'] = $this->cargaModel->agregar($data);
                $this->index(null);
            }else {
                $this->cargaModel->actualizar($data);
                $this->index( $this->request->getVar('fecha_alta'));
            }
        }else {
            $data['estaciones'] = $this->estacionModel->get_all();
            $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
            $data['contenido'] = "carga_abm_view";
            echo view('frontend', $data);
        }
    }

    public function buscar() {
      
            $data['legajo_movil'] =  strtoupper( $this->request->getVar('legajo_movil'));
            $data['legajo_personal'] =   $this->request->getVar('legajo_personal');
            $data['id_tipo_movil'] =  $this->request->getVar('id_tipo_movil');
            $data['id_unidad_policial'] =  $this->request->getVar('id_unidad_policial');
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

            $data['listado'] = $this->cargaModel->buscar($data);
            $data['contenido'] = "carga_lista_view";
            echo view('frontend', $data);
       
    }

    
    public function  getDatosMovil($legajo) {
        $movil = $this->movilModel->findByLegajo($legajo);
        $data['legajo'] = $legajo;
        if(empty($movil)) {
            $data['isError'] = true;
            $data['codigoError'] = 1;
            $data['mensaje'] = "El movil de Legajo $legajo no existe.";
        }else {
            if($movil->id_situacion == 1) { // en servicio
                $ultimaCarga = $this->cargaModel->getKilometrajeAnterior($legajo);
                $data['isError'] = false;
                $data['unidadPolicial'] = $movil->unidadPolicial;
                $data['dependencia'] = $movil->dependencia;
                $data['tipoMarcaModelo'] = $movil->tipo . ' ' . $movil->marca . ' ' . $movil->modelo;
                $data['dominio'] = $movil->dominio;
                $data['id_dependencia'] = $movil->id_dependencia;
                $data['id_unidad_policial'] = $movil->id_unidad_policial;

                if(count($ultimaCarga) > 0) {
                    $data['kilometrajeAnterior'] = $ultimaCarga->kilometraje;
                    $data['kilometrajeActualEstimado'] = $ultimaCarga->kilometraje + ($movil->kilometros_por_litro * $ultimaCarga->cantidad_litros);

                    $ultimaFechaDeCarga = date_format(date_create($ultimaCarga->fecha_alta), 'd/m/Y');
                    $fechaActual = date("d/m/Y");
                    if($ultimaFechaDeCarga === $fechaActual) {
                        $data['yaCargoHoy'] = true;
                        $data['horaDeCarga'] = date_format(date_create($ultimaCarga->fecha_alta), 'H:i');
                    }else {
                        $data['yaCargoHoy'] = false;
                        $data['horaDeCarga'] = '';
                    }
                }else {
                    $data['kilometrajeAnterior'] = '';
                    $data['kilometrajeActualEstimado'] = '';
                    $data['yaCargoHoy'] = false;
                }
            }else {
                $data['isError'] = true;
                $data['codigoError'] = 2;
                $data['mensaje'] = "¡El movil de Legajo $legajo no está en servicio!";
            }
        }

        echo json_encode($data);
    
    }

}