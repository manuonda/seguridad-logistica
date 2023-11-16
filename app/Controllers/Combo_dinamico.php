<?php

namespace App\Controllers;

use App\Models\DependenciaModel;

class ComboDinamico extends BaseController {

    protected $dependenciaModel;

    public function __construct() {
       $this->dependenciaModel = new DependenciaModel();
    }

    public function get_dependencias($id_unidad_policial) {
        $dependencias = $this->dependenciaModel->findByUnidadPolicial($id_unidad_policial);
        echo json_encode($dependencias);
    }
}