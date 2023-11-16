<?php
namespace App\Models;

use CodeIgniter\Model;


class ProvisionInteriorModel extends Model {

   

    function get($id) {
        $this->db->select('*');
        $this->db->from('logistica.provision_interior');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all() {
        $this->db->select('*');
        $this->db->order_by("fecha_alta");
        return $this->db->get('logistica.provision_interior')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("id");
        return $this->db->get('logistica.provision_interior')->result();
    }
    public function cuenta($sincro_envio) {
//        $query = $this->db->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio or $sincro_envio is null");
        $query = $this->db->query("select count(*) from logistica.provision_interior where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    
    public function guardar($data) {
        if(empty($data['destino_2'])) $data['destino_2'] = null;
        if(empty($data['id_tipo_combustible_2'])) $data['id_tipo_combustible_2'] = null;
        if(empty($data['cantidad_litros_2'])) $data['cantidad_litros_2'] = null;
        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'destino_1' => $data['destino_1'],
            'destino_2' => $data['destino_2'],
            'legajo_personal' => $data['legajo_personal'],
            'id_estacion' => $data['id_estacion'],
            'id_tipo_combustible_1' => $data['id_tipo_combustible_1'],
            'id_tipo_combustible_2' => $data['id_tipo_combustible_2'],
            'cantidad_litros_1' => $data['cantidad_litros_1'],
            'cantidad_litros_2' => $data['cantidad_litros_2'],
            'importe' => $data['importe'],
            'nro_comprobante' => $data['nro_comprobante'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'aclaracion_de_provision' => $data['aclaracion_de_provision'],
            'observacion' => $data['observacion'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.provision_interior', $carga);
        $id_carga = $this->db->insert_id();

//        echo 'idVales='.$data['idVales'];
        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_provision_interior' => $id_carga,
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
            return $id_carga;
        }
    }

    public function update_sincro($id){
            $query = $this->db->query("update logistica.provision_interior set sincro_envio = 1 where id = $id");
       
            return $query;            
    } 
    
    public function actualizar($data) {
        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'importe' => $data['importe'],
            'nro_comprobante' => $data['nro_comprobante'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'aclaracion_de_provision' => $data['aclaracion_de_provision'],
            'observacion' => $data['observacion'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update('logistica.provision_interior', $carga);

        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_provision_interior' => $data['id'],
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
        $this->db->select("pi.id, pi.fecha_alta, pi.destino_1, pi.destino_2, id_tipo_combustible_1, id_tipo_combustible_2, pi.cantidad_litros_1, pi.cantidad_litros_2, pi.legajo_personal, j.nombre as jerarquia, p.apellido, p.nombre, pi.importe");
        $this->db->from('logistica.provision_interior pi');
        $this->db->join('personal.personal ps', 'ps.legajo = pi.legajo_personal');
        $this->db->join('personas p', 'p.cuil = ps.cuil');
        $this->db->join('personal.jerarquias j', 'j.id_jerarquia = ps.id_jerarquia');

        if(!empty($searchParam['legajo_personal'])) {
            $this->db->where('pi.legajo_personal', $searchParam['legajo_personal']);
        }
        if(!empty($searchParam['fecha_desde']) && !empty($searchParam['fecha_hasta']) && $searchParam['fecha_desde']==$searchParam['fecha_hasta']) {
            $this->db->where('date(pi.fecha_alta) =', $searchParam['fecha_desde']);
        }else {
            if(!empty($searchParam['fecha_desde'])) {
                $this->db->group_start();
                $this->db->where('pi.fecha_alta >', $searchParam['fecha_desde']);
                $this->db->or_where('date(pi.fecha_alta) =', $searchParam['fecha_desde']);
                $this->db->group_end();
            }
            if(!empty($searchParam['fecha_hasta'])) {
                $this->db->group_start();
                $this->db->where('pi.fecha_alta <', $searchParam['fecha_hasta']);
                $this->db->or_where('date(pi.fecha_alta) =', $searchParam['fecha_hasta']);
                $this->db->group_end();
            }
        }
        if(!empty($searchParam['destino'])) {
            $this->db->group_start();
            $this->db->where('pi.destino_1', $searchParam['destino']);
            $this->db->or_where('pi.destino_2', $searchParam['destino']);
            $this->db->group_end();
        }
        $this->db->order_by("pi.fecha_alta","desc");
        $query = $this->db->get();
        return $query->result();
    }

    public function eliminar($id) {
        $result = $this->db->where('id', $id)->delete('logistica.provision_interior');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}