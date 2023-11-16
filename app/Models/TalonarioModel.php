<?php
namespace App\Models;

use CodeIgniter\Model;


class TalonarioModel extends Model {

   

    function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.talonarios');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('t.id, t.fecha_alta, tc.descripcion as tipo_combustible, t.inicio, t.fin');
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.tipo_combustibles tc', 'tc.id = t.id_tipo_combustible');
        $this->db->order_by("fecha_alta");
        return $this->db->get()->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("id");
        return $this->db->get('logistica.talonarios')->result();
    }
    public function cuenta($sincro_envio) {
//        $query = $this->db->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio or $sincro_envio is null");
        $query = $this->db->query("select count(*) from logistica.talonarios where sincro_envio = $sincro_envio");        
        return $query->row();  
    }    

    public function guardar($data) {
        $this->db->trans_begin();
        $datos = array(
            'id_tipo_combustible' => $data['id_tipo_combustible'],
            'inicio' => $data['inicio'],
            'fin' => $data['fin'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.talonarios', $datos);
        $id_talonario = $this->db->insert_id();

        for ($i = $data['inicio']; $i <= $data['fin']; $i++) {
            $vale = array(
                'numero' => $i,
                'asignado' => 0,
                'id_talonario' => $id_talonario,
                'usuario_alta' => $this->session->userdata('user_id'),
                'fecha_alta' => date('Y-m-d H:i:s'),
                'sincro_envio' => 0
            );
            $this->db->insert('logistica.vales', $vale);
            $this->db->insert_id();
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        }else {
            $this->db->trans_commit();
            return $id_talonario;
        }
    }

    public function actualizar($data) {
        $datos = array(
            'id_tipo_combustible' => $data['id_tipo_combustible'],
            'inicio' => $data['inicio'],
            'fin' => $data['fin'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        return $this->db->update('logistica.talonarios', $datos);
    }

    public function update_sincro($id){
            $query = $this->db->query("update logistica.talonarios set sincro_envio = 1 where id = $id");
       
            return $query;            
    }
    
    
    
    function findByNroVale($numero, $ids_talonarios) {
        $this->db->select("t.id, t.inicio, t.fin, t.id_tipo_combustible");
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.vales v', 't.id = v.id_talonario');
        $this->db->where("v.numero", $numero);
        $this->db->where("v.asignado", 0);

        if(!empty($ids_talonarios)) {
            $this->db->where_not_in('t.id', $ids_talonarios);
        }

        $query = $this->db->get();
//        return $query->result();
        return $query->row();
    }

    function findTalonariosConVales() {
        $this->db->select("t.id, t.inicio, t.fin, t.id_tipo_combustible");
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.vales v', 't.id = v.id_talonario');
        $this->db->where("v.asignado", 0);
        $this->db->group_by('t.id');

        $query = $this->db->get();
        return $query->result();
    }

    function findTalonariosConVales_222() {
//        select distinct(t.id) from logistica.talonarios t, logistica.vales v
//where t.id = v.id_talonario
//        and to_date(to_char(now(), 'yyyy-mm-dd'), 'yyyy-mm-dd') <= t.fecha_vencimiento
//        and v.asignado = 0;

        $this->db->select("t.id, t.inicio, t.fin, t.fecha_vencimiento, t.id_tipo_combustible");
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.vales v', 't.id = v.id_talonario');
        $this->db->where("to_date(to_char(now(), 'yyyy-mm-dd'), 'yyyy-mm-dd') <= t.fecha_vencimiento");
        $this->db->where("v.asignado", 0);
        $this->db->group_by('t.id');

        $query = $this->db->get();
        return $query->result();
    }

    function existe($id_tipo_combustible, $inicio) {
        $this->db->select('t.id, t.id_tipo_combustible, t.inicio');
        $this->db->from('logistica.talonarios t');
        $this->db->where('id_tipo_combustible', $id_tipo_combustible);
        $this->db->where('inicio', $inicio);
        $talonario = $this->db->get()->result();
        if(count($talonario) == 0) {
            return false;
        }else {
            return true;
        }
    }

    function buscar($searchParam) {
        $this->db->select("t.id, tc.descripcion as tipo_combustible, t.inicio, t.fin, t.fecha_alta");
        $this->db->from('logistica.talonarios t');
        $this->db->join('logistica.tipo_combustibles tc', 'tc.id = t.id_tipo_combustible');

        if(!empty($searchParam['numero'])) {
            $this->db->join('logistica.vales v', 'v.id_talonario = t.id');
            $this->db->where('v.numero', $searchParam['numero']);
        }
        if(!empty($searchParam['id_tipo_combustible'])) {
            $this->db->where('t.id_tipo_combustible', $searchParam['id_tipo_combustible']);
        }
        if(!empty($searchParam['fecha_desde']) && !empty($searchParam['fecha_hasta']) && $searchParam['fecha_desde']==$searchParam['fecha_hasta']) {
            $this->db->where('date(t.fecha_alta) =', $searchParam['fecha_desde']);
        }else {
            if(!empty($searchParam['fecha_desde'])) {
                $this->db->group_start();
                $this->db->where('t.fecha_alta >', $searchParam['fecha_desde']);
                $this->db->or_where('date(t.fecha_alta) =', $searchParam['fecha_desde']);
                $this->db->group_end();
            }
            if(!empty($searchParam['fecha_hasta'])) {
                $this->db->group_start();
                $this->db->where('t.fecha_alta <', $searchParam['fecha_hasta']);
                $this->db->or_where('date(t.fecha_alta) =', $searchParam['fecha_hasta']);
                $this->db->group_end();
            }
        }

        $this->db->order_by("t.fecha_alta","desc");
        $query = $this->db->get();
        return $query->result();
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.talonarios');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}