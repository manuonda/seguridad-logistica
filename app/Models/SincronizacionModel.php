<?php
namespace App\Models;

use CodeIgniter\Model;


class SincronizacionModel extends Model {

   

    public function insert_h($data){
        $datos = array(
//            'legajo' => $data['legajo'],
            'registros' => $data['registros'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'tipo_operacion' => $data['tipo_operacion']
        );
        
        $this->db->insert('logistica.sincro_historial', $datos);
//        return $this->db->insert_id();
        return;        
        
    }

    public function get_all() {
        $this->db->select('*');
        $this->db->order_by("legajo");
        return $this->db->get('logistica.moviles')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("legajo");
        return $this->db->get('logistica.moviles')->result();
    }

    public function insert_mov($data) {
        $datos = array(
            'legajo' => $data['legajo'],
            'anio' => $data['anio'],
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'dominio' => $data['dominio'],
            'nro_chasis_o_cuadro' => $data['nro_chasis_o_cuadro'],
            'nro_motor' => $data['nro_motor'],
            'id_situacion' => $data['id_situacion'],
            'id_tipo_movil' => $data['id_tipo_movil'],
            'id_unidad_policial' => $data['id_unidad_policial'],
            'id_dependencia' => $data['id_dependencia'],
            'flag_depositario_judicial' => $data['flag_depositario_judicial'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.moviles', $datos);
//        echo "antes de returnnnnnnn";
//        exit();        
//        return $this->db->insert_id();
        return;
    }

    public function actualizar($data) {
        $datos = array(
            'anio' => $data['anio'],
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'dominio' => $data['dominio'],
            'nro_chasis_o_cuadro' => $data['nro_chasis_o_cuadro'],
            'nro_motor' => $data['nro_motor'],
            'id_situacion' => $data['id_situacion'],
            'id_tipo_movil' => $data['id_tipo_movil'],
            'id_unidad_policial' => $data['id_unidad_policial'],
            'id_dependencia' => $data['id_dependencia'],
            'flag_depositario_judicial' => $data['flag_depositario_judicial'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('legajo', $data['legajo']);
        return $this->db->update('logistica.moviles', $datos);
    }

}