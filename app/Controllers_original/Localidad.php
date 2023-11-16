<?php
namespace App\Controllers;

use App\Models\LocalidadModel;
use App\Models\DependenciaModel;

class Localidad extends BaseController {
    
    function getLocalidades($idDepartamento) {
        $localidadModel = new LocalidadModel();
        $localidadModel->select('id_localidad, localidad');
        $localidades = $localidadModel->where('id_departamento', $idDepartamento)->findAll();
        
        $dependenciaModel = new DependenciaModel();
        $dependenciaModel->select('id_dependencia, dependencia');
        $dependencias = $dependenciaModel->where('habilitado',true)->where('id_departamento', $idDepartamento)->findAll();
        $data['localidades'] = $localidades;
        $data['dependencias'] = $dependencias;
//         echo json_encode($localidades);
        echo json_encode($data);
    }
}