<?php
namespace App\Controllers;

use App\Models\TurnoFeriadoModel;

class TurnoFeriado extends BaseController {
    
    public function __construct() {
        
    }
    
    public function index() {
        $dateInicio = date('Y-01-01');
        $dateFin = date('Y-12-31');
        $turnoFeriadoModel = new TurnoFeriadoModel();

        //*ABAJO FUNCIONA
        $data['listado'] = $turnoFeriadoModel->where('fecha >=', $dateInicio)->where('fecha <=', $dateFin)->orderBy('fecha', 'ASC')->findAll();
        $data['contenido'] = "turno_feriado_lista";
        echo view("frontend", $data);
    }
    
    public function agregar() {
        $data['contenido'] = "turno_feriado_abm";
        echo view("frontend", $data);
    }
    
    public function modificar($id_turno_feriado) {
        $turnoFeriadoModel = new TurnoFeriadoModel();
        $turnoFeriado = $turnoFeriadoModel->where('id_turno_feriado', $id_turno_feriado)->first();
        $data['id_turno_feriado'] = $turnoFeriado['id_turno_feriado'];
        $data['fecha'] = $turnoFeriado['fecha'];
        $data['descripcion'] = $turnoFeriado['descripcion'];
        $data['contenido'] = "turno_feriado_abm";
        echo view("frontend", $data);
    }
    
    public function guardar() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'fecha' => ['label' => 'Fecha', 'rules' => 'required|exact_length[10]'],
            'descripcion' => ['label' => 'Descripción', 'rules' => 'required|min_length[2]'],
        ]);
        
        $data['id_turno_feriado'] = $this->request->getVar('id_turno_feriado');
        $data['fecha'] = $this->request->getVar('fecha');
        $data['descripcion'] = $this->request->getVar('descripcion');
        
        if ($validation->withRequest($this->request)->run()) {
            $turnoFeriadoModel = new TurnoFeriadoModel();
            if(empty($data['id_turno_feriado'])) { // insert
                $turnoFeriadoModel = new TurnoFeriadoModel();
                $feriadoExiste = $turnoFeriadoModel->where('fecha', $data['fecha'])->first();
                if(empty($feriadoExiste)) {
                    $data['usuario_alta'] = session()->get('id');
                    $data['fecha_alta'] = date('Y-m-d H:i:s');
                    $turnoFeriadoModel->insert($data, false);
                }else {
                    list($anio, $mes, $dia) = explode("-", $data['fecha']);
                    $data['error'] = "La fecha $dia/$mes/$anio ya fue agregado en el listado de feriados.";
                    $data['contenido'] = "turno_feriado_abm";
                    echo view("frontend", $data);
                    return;
                }
            }else { // update
                $data['usuario_modificacion'] = session()->get('id');
                $data['fecha_modificacion'] = date('Y-m-d H:i:s');
                $turnoFeriadoModel->update($data['id_turno_feriado'], $data);
            }
            
            $this->index();
        }else {
            $data['contenido'] = "turno_feriado_abm";
            echo view("frontend", $data);
        }        
    }
}