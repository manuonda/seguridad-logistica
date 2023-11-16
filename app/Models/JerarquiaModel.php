<?php
namespace App\Models;

use CodeIgniter\Model;

class JerarquiaModel extends Model {

    protected $table      = 'personal.jerarquias';
    protected $primaryKey = 'id_jerarquia';

    
    public function get_all() {
        $model =new JerarquiaModel();
        $model->select('id_jerarquia as "id", nombre');
        $model->from('jerarquias');
        $model->order_by('id_jerarquia');
        return $model->get()->result();
    }
    
    public function get_nombre_jerarquia($id) {
        $model =new JerarquiaModel();

        $model->select('nombre AS "numrows"');
        $model->where('id_jerarquia', $id);
        $model->from('personal.jerarquias');
        $query = $model->get();
        if ($query->num_rows() == 0) {
            return 0;
        }
        $row = $query->row();
        return  $row->numrows;
    }

    public function get_id_tipo_jerarquia($id) {
        $model =new JerarquiaModel();

        $model->select('id_tipo_jerarquia AS "numrows"');
        $model->where('id_jerarquia', $id);
        $model->from('personal.jerarquias');
        $query = $model->get();
        if ($query->num_rows() == 0) {
            return 0;
        }
        $row = $query->row();
        return  $row->numrows;
    }
}