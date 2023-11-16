<?php

use App\Controllers\BaseController;
use App\Libraries\Cuil;
use App\Models\PersonalModel;

class Personal extends BaseController {

    protected $personalModel;
    protected $cuil;

    public function __construct() {
        $this->personalModel = new PersonalModel();
        $this->cuil = new Cuil();
    }

    public function get_datos_json($legajo) {
            $chofer = $this->personalModel->findByLegajo($legajo);
            if(empty($chofer)) {
                $data['isError'] = true;
                $data['mensaje'] = "El Chofer de Legajo $legajo no existe.";
            }else {
                $data['isError'] = false;
                $data['jerarquia'] = $chofer->jerarquia;
                $data['apellido'] = $chofer->apellido;
                $data['nombre'] = $chofer->nombre;
            }
            echo json_encode($data);
    }

    public function agregar() {
       
            $data['dni'] = $this->request->getVar('macDni');
            $data['sexo'] = $this->request->getVar('macSexo');
            $data['nombre'] = strtoupper($this->request->getVar('macNombre'));
            $data['apellido'] = strtoupper($this->request->getVar('macApellido'));
            $data['legajo'] =  $this->request->getVar('macLegajo');
            $data['id_jerarquia'] = $this->request->getVar('macIdJerarquia');
            $data['id_dependencia'] = $this->request->getVar('id_dependencia');
            $data['id_unidad_policial'] = $this->request->getVar('id_unidad_policial');
            $nacionalidad = 1; // Argentino por defecto

            $validacion = $this->validar($data);
            if($validacion == 'ok') {
                $data['cuil'] = $this->cuil->generar($data['dni'], $data['sexo'], $nacionalidad);
                if($this->personalModel->insert($data) == 'ok') {
                    $data['isError'] = false;
                }else {
                    $data['isError'] = true;
                }
            }else {
                $data['isError'] = true;
                $data['mensaje'] = $validacion;
            }
         echo json_encode($data);
       
    }

    public function validar($data) {
        if(empty($data['dni'])) {
            return "¡Debe ingresar el Dni!";
        }
        if(empty($data['nombre'])) {
            return "¡Debe ingresar el Nombre!";
        }
        if(empty($data['apellido'])) {
            return "¡Debe ingresar el Apellido!";
        }
        if(empty($data['legajo'])) {
            return "¡Debe ingresar el Legajo!";
        }
        if(empty($data['id_jerarquia'])) {
            return "¡Debe ingresar la Jerarquia!";
        }
        if(empty($data['id_dependencia'])) {
            return "¡Tiene que ingresar un movil!. Cierre esta ventana y luego ingrese un movil";
        }
        if($this->personalModel->existePersonal($data['legajo'])) {
            return "¡El chofer ya existe!";
        }

        return "ok";
    }
}