<?php
namespace App\Models;

use CodeIgniter\Model;


class ProvisionPuestoOneModel extends Model {

   

    public function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.provision_puesto1');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('*');
        $this->db->order_by("fecha_alta");
        return $this->db->get('logistica.provision_puesto1')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("id");
        return $this->db->get('logistica.provision_puesto1')->result();
    }
    public function cuenta($sincro_envio) {
//        $query = $this->db->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio or $sincro_envio is null");
        $query = $this->db->query("select count(*) from logistica.provision_puesto1 where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    
    public function guardar($data) {
        if(empty($data['observacion'])) $data['observacion'] = null;

        $this->db->trans_begin();
        $provision_puesto1 = array(
            'legajo_personal' => $data['legajo_personal'],
            'observacion' => $data['observacion'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.provision_puesto1', $provision_puesto1);
        $id_provision_puesto1 = $this->db->insert_id();

//        echo 'idVales='.$data['idVales'];
        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $provision_puesto1_vale = array(
                    'id_provision_puesto1' => $id_provision_puesto1,
                    'id_vale' => $vales[$i],
                    'usuario_alta' => $this->session->userdata('user_id'),
                    'fecha_alta' => date('Y-m-d H:i:s'),
                    'sincro_envio' => 0
                );
                $this->db->insert('logistica.provision_puesto1_vales', $provision_puesto1_vale);

                $vale = array(
                    'asignado' => 1,
                    'usuario_modificacion' => $this->session->userdata('user_id'),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'sincro_envio' => 2
                );
                $this->db->where('id', $vales[$i]);
                $this->db->update('logistica.vales', $vale);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        }else {
            $this->db->trans_commit();
            return $id_provision_puesto1;
        }
    }

    public function update_sincro($id){
            $query = $this->db->query("update logistica.provision_puesto1 set sincro_envio = 1 where id = $id");
       
            return $query;            
    }     
    
    
    public function actualizar($data) {
        if(empty($data['observacion'])) $data['observacion'] = null;

        $this->db->trans_begin();
        $carga = array(
            'observacion' => $data['observacion'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update('logistica.cargas', $carga);

        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga' => $data['id'],
                    'id_vale' => $vales[$i],
                    'usuario_alta' => $this->session->userdata('user_id'),
                    'fecha_alta' => date('Y-m-d H:i:s'),
                    'sincro_envio' => 0
                );
                $this->db->insert('logistica.cargas_vales', $carga_vale);

                $vale = array(
                    'asignado' => 1,
                    'usuario_modificacion' => $this->session->userdata('user_id'),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'sincro_envio' => 2
                );
                $this->db->where('id', $vales[$i]);
                $this->db->update('logistica.vales', $vale);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        }else {
            $this->db->trans_commit();
            return $data['id'];
        }
    }

    function buscar($searchParam) {
        $this->db->select("pp1.id, pp1.fecha_alta, pp1.legajo_personal, j.nombre as jerarquia, p.apellido, p.nombre");
        $this->db->from('logistica.provision_puesto1 pp1');
        $this->db->join('personal.personal ps', 'ps.legajo = pp1.legajo_personal');
        $this->db->join('personas p', 'p.cuil = ps.cuil');
        $this->db->join('personal.jerarquias j', 'j.id_jerarquia = ps.id_jerarquia');

        if(!empty($searchParam['legajo_personal'])) {
            $this->db->where('pp1.legajo_personal', $searchParam['legajo_personal']);
        }
        if(!empty($searchParam['fecha_desde']) && !empty($searchParam['fecha_hasta']) && $searchParam['fecha_desde']==$searchParam['fecha_hasta']) {
            $this->db->where('date(pp1.fecha_alta) =', $searchParam['fecha_desde']);
        }else {
            if(!empty($searchParam['fecha_desde'])) {
//                $this->db->where('pp1.fecha_alta >=', $searchParam['fecha_desde']);
                $this->db->group_start();
                $this->db->where('pp1.fecha_alta >', $searchParam['fecha_desde']);
                $this->db->or_where('date(pp1.fecha_alta) =', $searchParam['fecha_desde']);
                $this->db->group_end();
            }
            if(!empty($searchParam['fecha_hasta'])) {
//                $this->db->where('pp1.fecha_alta <=', $searchParam['fecha_hasta']);
                $this->db->group_start();
                $this->db->where('pp1.fecha_alta <', $searchParam['fecha_hasta']);
                $this->db->or_where('date(pp1.fecha_alta) =', $searchParam['fecha_hasta']);
                $this->db->group_end();
            }
        }

        $this->db->order_by("pp1.fecha_alta","desc");
        $query = $this->db->get();
        return $query->result();
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.provision_puesto1');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}