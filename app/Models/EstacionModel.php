<?php
namespace App\Models;

use CodeIgniter\Model;


class EstacionModel extends Model {

    protected $table      = 'logistica.estaciones';
    protected $primaryKey = 'id';

    public function get_all() {
        $model = new EstacionModel();
        $model->select('id, nombre, id_unidad_policial');
        return $model->get('logistica.estaciones')->result();
    }

    public function get($id) {
        $model = new EstacionModel();
        $model->select('id, nombre');
        $model->where('id', $id);
        return $model->get('logistica.estaciones')->row();
    }

}