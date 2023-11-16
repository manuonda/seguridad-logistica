<?php
namespace App\Models;

use CodeIgniter\Model;


class UnidadPolicialModel extends Model {

   

    public function get_all() {
        $this->db->select('id_unidad_policial, nombre');
//        $this->db->order_by("id_unidad_policial", "asc");
        return $this->db->get('personal.unidades_policiales')->result();
    }

    public function get($id) {
        $this->db->select('nombre');
        $this->db->where('id_unidad_policial', $id);
        return $this->db->get('personal.unidades_policiales')->row();
    }

}
