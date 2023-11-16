<?php

use App\Controllers\BaseController;
use App\Models\DependenciaModel;
use App\Models\MovilModel;
use App\Models\SituacionModel;
use App\Models\TipoMovilModel;
use App\Models\UnidadPolicialModel;

class Movil extends BaseController {
 
    protected $movilModel;
    protected $tipoMovilModel;
    protected $situacionModel;
    protected $unidadPolicialModel;
    protected $dependenciaModel;

    public function __construct() {
        $movilModel = new MovilModel();
        $tipoMovilModel = new TipoMovilModel();
        $situacionModel = new SituacionModel();
        $unidadPolicialModel = new UnidadPolicialModel();
        $dependenciaModel = new DependenciaModel();
       
    }

    public function index() {
           $data['listado'] = $this->movilModel->get_all();
           $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
           $data['situaciones'] = $this->situacionModel->get_all();
           $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

           $data['contenido'] = "movil_lista_view";
            echo view('frontend',$data);
       
    }

    public function agregar() {
       $data['titulo'] = 'Registrar';
       $data['operacion'] = 'alta';
       $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
       $data['situaciones'] = $this->situacionModel->get_all();
       $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
       $data['contenido'] = "movil_abm_view";
        echo view('frontend',$data);
    }

    public function editar($legajo=null) {
       $data['operacion'] = 'edicion';
        if($legajo != null && !empty($legajo)) {
           $movil = $this->movilModel->get($legajo);
           $data['titulo'] = 'Modificar';
           $data['legajo'] = $movil->legajo;
           $data['anio'] = $movil->anio;
           $data['marca'] = $movil->marca;
           $data['modelo'] = $movil->modelo;
           $data['dominio'] = $movil->dominio;
           $data['nro_chasis_o_cuadro'] = $movil->nro_chasis_o_cuadro;
           $data['nro_motor'] = $movil->nro_motor;
           $data['id_tipo_movil'] = $movil->id_tipo_movil;
           $data['id_unidad_policial'] = $movil->id_unidad_policial;
           $data['id_dependencia'] = $movil->id_dependencia;
           $data['id_situacion'] = $movil->id_situacion;
           $data['flag_depositario_judicial'] = $movil->flag_depositario_judicial;

           $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
           $data['situaciones'] = $this->situacionModel->get_all();
           $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            if(!empty($data['id_unidad_policial'])) {
               $data['dependencias'] = $this->dependenciaModel->findByUnidadPolicial($data['id_unidad_policial']);
            }

           $data['contenido'] = "movil_abm_view";
            echo view('frontend',$data);
        }
    }

    public function guardar() {
       $data['operacion'] = $this->request->getVar('operacion');
       $data['legajo'] =  strtoupper($this->request->getVar('legajo'));


        if($data['operacion'] == 'alta') {
           $data['titulo'] = 'Registrar';
        }else {
           $data['titulo'] = 'Modificar';
        }

    
        $validation =  \Config\Services::validation();

        $validation->setRules([
            'legajo' => ['label' => 'Legajo', 'rules' => 'required'],
            'marca' => ['label' => 'Marca', 'rules' => 'required'],
            'anio' => ['label' => 'Año', 'rules' => 'required'],
        ]);
        


       $data['anio'] = $this->request->getVar('anio');
       $data['marca'] = strtoupper($this->request->getVar('marca'));
       $data['modelo'] = strtoupper($this->request->getVar('modelo'));
       $data['dominio'] = strtoupper($this->request->getVar('dominio'));
       $data['nro_chasis_o_cuadro'] = strtoupper($this->request->getVar('nro_chasis_o_cuadro'));
       $data['nro_motor'] = $this->request->getVar('nro_motor');
       $data['id_situacion'] = $this->request->getVar('id_situacion');
       $data['id_tipo_movil'] = $this->request->getVar('id_tipo_movil');
       $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
       $data['id_dependencia'] = $this->request->getVar('id_dependencia');
       $data['flag_depositario_judicial'] = $this->request->getVar('flag_depositario_judicial');

       if ($validation->withRequest($this->request)->run()) {
            if($data['operacion'] == 'alta') {
                $this->movilModel->insert($data);
            }else {
                $this->movilModel->update($data);
            }

            $this->index();
        }else {
           $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
           $data['situaciones'] = $this->situacionModel->get_all();
           $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();
            if(!empty($data['id_unidad_policial'])) {
               $data['dependencias'] = $this->dependenciaModel->findByUnidadPolicial($data['id_unidad_policial']);
            }

           $data['contenido'] = "movil_abm_view";
            echo view('frontend',$data);
        }
    }

    public function buscar() {
       $data['legajo'] =  strtoupper($this->request->getVar('legajo'));
       $data['anio'] = strtoupper($this->request->getVar('anio'));
       $data['modelo'] = $this->request->getVar('modelo');
       $data['id_tipo_movil'] = $this->request->getVar('id_tipo_movil');
       $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
       $data['id_situacion'] = $this->request->getVar('id_situacion');

       $data['tipo_moviles'] = $this->tipoMovilModel->get_all();
       $data['situaciones'] = $this->situacionModel->get_all();
       $data['unidades_policiales'] = $this->unidadPolicialModel->get_all();

       $data['listado'] = $this->movilModel->buscar($data);
       $data['contenido'] = "movil_lista_view";
        echo view('frontend',$data);
    }

    public function calcKmEstimado($movil, $ultimaCarga) {
        return $ultimaCarga->kilometraje + ($movil->kilometros_por_litro * $ultimaCarga->cantidad_litros);
    }
}