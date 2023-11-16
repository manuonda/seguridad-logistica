<?php
namespace App\Models;

use CodeIgniter\Model;


class SituacionModel extends Model {

   

    function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.situaciones');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('*');
        $this->db->order_by("id");
        return $this->db->get('logistica.situaciones')->result();
    }

    public function findByUnidadRegional($id_unidad_regional) {
        $this->db->select('id, nombre');
        $this->db->from('segvial.situaciones');
        $this->db->where('id_unidad_regional', $id_unidad_regional);
        return $this->db->get()->result();
    }

    public function guardar($data) {
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
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('logistica.situaciones', $datos);
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
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $data['id']);
        return $this->db->update('logistica.situaciones', $datos);
    }

    function buscar($searchParam) {
        $sql = "SELECT pf.id, pf.fecha, pf.dni, pf.nombre, pf.apellido, pf.sexo, pf.id_pais, pf.observacion
    			FROM   segvial.personas_fallecidas pf
        		WHERE  1 = 1 ";

        if(!empty($searchParam)) {
            if(!empty($searchParam['fecha'])) {
                list($dia, $mes, $anio) = explode("/", $searchParam['fecha']);
                $sql .= " and pf.fecha = '" . $anio . "-" . $mes . "-" . $dia . "' ";
            }
            if(!empty($searchParam['dni'])) {
                $sql .= " and pf.dni = " . $searchParam['dni'];
            }
            if(!empty($searchParam['nombre'])) {
                $sql .= " and pf.nombre like '%" . $searchParam['nombre'] . "%' " ;
            }
            if(!empty($searchParam['apellido'])) {
                $sql .= " and pf.apellido like '%" . $searchParam['apellido'] . "%' " ;
            }
//     		if(!empty($searchParam['observacion'])) {
//     			$sql .= " and a.observacion like '%" . $searchParam['observacion'] . "%' " ;
//     		}
        }

        $sql .= " ORDER BY pf.fecha desc ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.situaciones');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}