<?php
namespace App\Models;

use CodeIgniter\Model;


class TipoMovilModel extends Model {

   

    function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.tipo_moviles');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('*');
        $this->db->order_by("id", "legajo");
        return $this->db->get('logistica.tipo_moviles')->result();
    }

    public function findByUnidadRegional($id_unidad_regional) {
        $this->db->select('id, nombre');
        $this->db->from('segvial.tipo_moviles');
        $this->db->where('id_unidad_regional', $id_unidad_regional);
        return $this->db->get()->result();
    }

    public function guardar($data) {
        $datos = array(
            'legajo' => $data['legajo'],
            'anio' => $data['anio'],
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'dominio' => $data['dominio'],
            'nro_chasis_o_cuadro' => $data['nro_chasis_o_cuadro'],
            'nro_motor' => $data['nro_motor'],
            'id_situacion' => $data['id_situacion'],
//            'usuario_alta' => $this->tank_auth->get_user_id(),
//            'fecha_alta' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('logistica.tipo_moviles', $datos);
        return $this->db->insert_id();
    }

    public function actualizar($data) {
        if(empty($data['nombre'])) {
            $data['nombre'] = null;
        }
        if(empty($data['ubicacion'])) {
            $data['ubicacion'] = null;
        }

        $datos = array(
            'nombre' => $data['nombre'],
            'ubicacion' => $data['ubicacion'],
            'id_unidad_regional' => $data['id_unidad_regional'],
            'usuario_modificacion' => $this->tank_auth->get_user_id(),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $data['id']);
        return $this->db->update('logistica.tipo_moviles', $datos);
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.tipo_moviles');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}