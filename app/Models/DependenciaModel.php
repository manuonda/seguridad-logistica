<?php
namespace App\Models;

use CodeIgniter\Model;


class DependenciaModel extends Model {

    protected $table      = 'personal.dependencias';
    protected $primaryKey = 'id_dependencia';

    public function get_all() {
        $model = new DependenciaModel();
        $model->select('id_dependencia, dependencia');
        return $model->get('personal.dependencias')->result();
    }

    public function get($id) {
        $model = new DependenciaModel();
        $model->select('id_dependencia, dependencia');
        $model->where('id_dependencia', $id);
        return $model->get('personal.dependencias')->row();
    }

    public function findByUnidadPolicial($id_unidad_policial) {
        $model = new DependenciaModel();

        $model->select('id_dependencia, dependencia');
        $model->from('personal.dependencias');
        $model->where('id_unidad_policial', $id_unidad_policial)->get();
        return $model->result();
    }

}

