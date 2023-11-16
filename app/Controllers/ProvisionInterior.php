<?php

use App\Controllers\BaseController;
use App\Models\CargaValeModel;
use App\Models\EstacionModel;
use App\Models\PersonalModel;
use App\Models\ProvisionInteriorModel;
use App\Models\TipoCombustibleModel;
use App\Models\UnidadPolicialModel;

class ProvisionInterior extends BaseController{

    protected $provisionInteriorModel;
    protected $unidadPolicialModel;
    protected $tipoCombustibleModel;
    protected $cargaValeModel;
    protected $personalModel;
    protected $estacionModel;
    
    public function __construct() {
        $this->provisionInteriorModel = new ProvisionInteriorModel();
        $this->unidadPolicialModel = new UnidadPolicialModel();
        $this->tipoCombustibleModel = new TipoCombustibleModel();
        $this->cargaValeModel = new CargaValeModel();
        $this->personalModel = new PersonalModel();
        $this->estacionModel = new EstacionModel();
    }

    public function index($fecha_alta=null) {
       
            if(empty($fecha_alta)) {
               $data['fecha_desde'] = date('Y-m-d');
               $data['fecha_hasta'] = date('Y-m-d');
            }else {
               $data['fecha_desde'] = $fecha_alta;
               $data['fecha_hasta'] = $fecha_alta;
            }
           $data['listado'] = $this->provisionInteriorModel->buscar($data);
           $data['contenido'] = "carga/provision_interior_lista_view";
            echo view('frontend',$data);
       
    }

    public function agregar() {
        $data['oper'] = OPER_INSERTAR;
        $data['titulo'] = 'Registrar';
        $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
        $data['estaciones'] = $this->estacionModel->get_all();
        $data['contenido'] = "carga/provision_interior_abm_view";
         echo view('frontend',$data);
    }

    function editar($id=null) {
       $data['oper'] = OPER_EDITAR;
        if($id != null && !empty($id)) {
           $carga = $this->provisionInteriorModel->get($id);
           $data['titulo'] = 'Modificar';
           $data['id'] = $carga->id;
           $data['fecha_alta'] = $carga->fecha_alta;
           $data['destino_1'] = $carga->destino_1;
           $data['destino_2'] = $carga->destino_2;
           $data['legajo_personal'] = $carga->legajo_personal;
           $personal_acargo = $this->personalModel->findByLegajo($carga->legajo_personal);
           $data['jerarquia'] = $personal_acargo->jerarquia;
           $data['apellido'] = $personal_acargo->apellido;
           $data['nombre'] = $personal_acargo->nombre;

           $data['id_estacion'] = $carga->id_estacion;
           $data['id_tipo_combustible_1'] = $carga->id_tipo_combustible_1;
           $data['id_tipo_combustible_2'] = $carga->id_tipo_combustible_2;
           $data['cantidad_litros_1'] = $carga->cantidad_litros_1;
           $data['cantidad_litros_2'] = $carga->cantidad_litros_2;
           $data['importe'] = $carga->importe;
           $data['vales'] = $this->cargaValeModel->findValesByIdProvisionInterior($carga->id);
           $data['nro_comprobante'] = $carga->nro_comprobante;
           $data['observacion'] = $carga->observacion;
           $data['jefe_logistica_autorizante'] = $carga->jefe_logistica_autorizante;
           $data['aclaracion_de_provision'] = $carga->aclaracion_de_provision;

           $data['estaciones'] = $this->estacionModel->get_all();
           $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
           $data['contenido'] = "carga/provision_interior_abm_view";
            echo view('frontend',$data);
        }
    }

    public function guardar() {
       $data['id'] = $this->request->getVar('id');
       
       $validation =  \Config\Services::validation();

        $validation->setRules([
            'destino_1' => ['label' => 'Destino', 'rules' => 'required'],
            'id_estacion' => ['label' => 'Estación de servicio', 'rules' => 'required'],
            'id_tipo_combustible_1' => ['label' => 'Tipo de Combustible 1', 'rules' => 'required'],
            'legajo_personal' => ['label' => 'Legajo Policial', 'rules' => 'required'],
            'cantidad_litros_1' => ['label' => 'Cantidad de litros', 'rules' => 'required'],
            'jefe_logistica_autorizante' => ['label' => '¿Quien Autoriza de Logistica?', 'rules' => 'required'],
            'importe' => ['label' => 'Importe', 'rules' => 'required'],
        ]);

       $data['destino_1'] = $this->request->getVar('destino_1');
       $data['destino_2'] = $this->request->getVar('destino_2');
       $data['legajo_personal'] = $this->request->getVar('legajo_personal');
       $data['id_estacion'] = $this->request->getVar('id_estacion');
       $data['id_tipo_combustible_1'] = $this->request->getVar('id_tipo_combustible_1');
       $data['id_tipo_combustible_2'] = $this->request->getVar('id_tipo_combustible_2');
       $data['cantidad_litros_1'] = $this->request->getVar('cantidad_litros_1');
       $data['cantidad_litros_2'] = $this->request->getVar('cantidad_litros_2');
       $data['importe'] = $this->request->getVar('importe');
       $data['nro_comprobante'] = strtoupper($this->request->getVar('nro_comprobante'));
       $data['idVales'] = $this->request->getVar('idVales');
       $data['jefe_logistica_autorizante'] = strtoupper($this->request->getVar('jefe_logistica_autorizante'));
       $data['aclaracion_de_provision'] = $this->request->getVar('aclaracion_de_provision');
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

            if(!empty($data['destino_2']) &&$data['destino_2'] ==$data['destino_1']) {
               $data['error'] = '¡El Destino 2 no debe ser igual al Destino 1!';
                return $this->irPantallaAbm($data);
            }
            if(!empty($data['id_tipo_combustible_2']) &&$data['id_tipo_combustible_2'] ==$data['id_tipo_combustible_1']) {
               $data['error'] = '¡El Tipo de combustible 2 no debe ser igual al Tipo de combustible 1!';
                return $this->irPantallaAbm($data);
            }
            if(!empty($data['id_tipo_combustible_2']) && empty($data['cantidad_litros_2'])) {
               $data['error'] = '¡Debe ingresar la Cantidad de Litros del El Tipo de combustible 2!';
                return $this->irPantallaAbm($data);
            }

            if(empty($data['id'])) {
               $data['id'] = $this->provisionInteriorModel->insert($data);
                $this->index(null);
            }else {
                $this->provisionInteriorModel->update($data);
                $this->index($this->request->getVar('fecha_alta'));
            }
        }else {
           $data['estaciones'] = $this->estacionModel->get_all();
           $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
           $data['contenido'] = "carga/provision_interior_abm_view";
            echo view('frontend',$data);
        }
    }

    public function irPantallaAbm($data) {
        $data['estaciones'] = $this->estacionModel->get_all();
        $data['tipo_combustibles'] = $this->tipoCombustibleModel->get_all();
        $data['contenido'] = "carga/provision_interior_abm_view";
        echo view('frontend', $data);
    }

    public function buscar() {
           $data['destino'] = $this->request->getVar('destino');
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

           $data['listado'] = $this->provisionInteriorModel->buscar($data);
           $data['contenido'] = "carga/provision_interior_lista_view";
            echo view('frontend',$data);
       
    }
}