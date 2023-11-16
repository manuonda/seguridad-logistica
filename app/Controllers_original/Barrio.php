<?php
namespace App\Controllers;

use App\Models\BarrioModel;

class Barrio extends BaseController {
    
    function autocomplete() {
        $term = $_GET['term'];
        $id_localidad = $_GET['id_localidad'];
        if (isset($term) && !empty($term) && isset($id_localidad) && !empty($id_localidad)) {
            $barrioModel = new BarrioModel();
            $query = $barrioModel->select('id_barrio as value, barrio as label')
            ->where('id_localidad', $id_localidad)
            //                                  ->like('barrio', strtoupper($_GET['term']), 'both')->get();
            ->like('barrio', strtoupper($_GET['term']), 'after')->get();
            echo json_encode($query->getResult());
        }
    }
}