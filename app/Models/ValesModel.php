<?php
namespace App\Models;

use CodeIgniter\Model;


class ValesModel extends Model {

   

    function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.vales');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('t.id, t.fecha_alta, tc.descripcion as tipo_combustible, t.inicio, t.fin, t.fecha_vencimiento');
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.tipo_combustibles tc', 'tc.id = t.id_tipo_combustible');
        $this->db->order_by("fecha_alta");
        return $this->db->get()->result();
    }

    function getAll($id_talonario) {
        $this->db->select("id, numero, asignado");
        $this->db->from('logistica.vales');
        $this->db->where("id_talonario", $id_talonario);
        $this->db->order_by("numero");
        $query = $this->db->get();
        return $query->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("id");
        return $this->db->get('logistica.vales')->result();
    }
    public function cuenta($sincro_envio) {
//        $query = $this->db->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio or $sincro_envio is null");
        $query = $this->db->query("select count(*) from logistica.vales where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    public function guardar($data) {
        $datos = array(
            'numero' => $data['numero'],
            'id_talonario' => $data['id_talonario'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.vales', $datos);
        return $this->db->insert_id();
    }

    public function actualizar($data) {
        $datos = array(
            'numero' => $data['numero'],
            'id_talonario' => $data['id_talonario'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        return $this->db->update('logistica.vales', $datos);
    }
    
    public function update_sincro($id){
            $query = $this->db->query("update logistica.vales set sincro_envio = 1 where id = $id");
       
            return $query;            
    }    
    

    function findByIdTalonario($id_talonario) {
        $this->db->select("id, numero");
        $this->db->from('logistica.vales');
        $this->db->where("id_talonario", $id_talonario);
        $this->db->where("asignado", 0);

        $query = $this->db->get();
        return $query->result();
    }

    function findByNumero($numero) {
        $this->db->select("id, numero, asignado, id_talonario");
        $this->db->from('logistica.vales');
        $this->db->where("numero", $numero);
        $query = $this->db->get();
        return $query->row();
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.vales');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}