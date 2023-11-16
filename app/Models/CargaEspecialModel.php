<?php
namespace App\Models;

use CodeIgniter\Model;

class CargaEspecialModel extends Model {

    protected $table      = 'logistica.cargas_especiales';
    protected $primaryKey = 'id';
   

    public function get($id) {
        $model = new CargaModel();
        $model->select('*');
        $model->from('logistica.cargas_especiales');
        $model->where('id', $id)->get();
        return $model->getRsultObject();
    }

    public function get_all() {
        $model = new CargaModel();
        $model->select('*');
        $model->order_by("fecha_alta");
        return $model->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $model = new CargaModel();
        $model->select('*');
        $model->where('sincro_envio', $sincro_envio);
        $model->order_by("id");
        return$model->get('logistica.cargas_especiales')->result();
    }
    public function cuenta($sincro_envio) {
        $model = new CargaModel();
        $query = $model->query("select count(*) from logistica.cargas_especiales where sincro_envio = $sincro_envio");        
        return $query->row();
    }
    
    public function guardar($data) {
        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['nro_nota_refuerzo'])) $data['nro_nota_refuerzo'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;
        if(empty($data['jefe_unidad_autorizante'])) $data['jefe_unidad_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'descripcion' => $data['descripcion'],
            'id_dependencia' => $data['id_dependencia'],
            'id_unidad_policial' => $data['id_unidad_policial'],
            'legajo_personal' => $data['legajo_personal'],
            'id_estacion' => $data['id_estacion'],
            'id_tipo' => $data['id_tipo'],
            'id_tipo_combustible' => $data['id_tipo_combustible'],
            'cantidad_litros' => $data['cantidad_litros'],
            'importe' => $data['importe'],
            'nro_comprobante' => $data['nro_comprobante'],
            'observacion' => $data['observacion'],
            'nro_nota_refuerzo' => $data['nro_nota_refuerzo'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'jefe_unidad_autorizante' => $data['jefe_unidad_autorizante'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.cargas_especiales', $carga);
        $id_carga = $this->db->insert_id();

//        echo 'idVales='.$data['idVales'];
        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga_especial' => $id_carga,
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

    public function actualizar_sincro($id){
            $query = $this->db->query("update logistica.cargas_especiales set sincro_envio = 1 where id = $id");
       
            return $query;            
    }     
    
    public function actualizar($data) {
        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['nro_nota_refuerzo'])) $data['nro_nota_refuerzo'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;
        if(empty($data['jefe_unidad_autorizante'])) $data['jefe_unidad_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'descripcion' => $data['descripcion'],
            'importe' => $data['importe'],
            'nro_comprobante' => $data['nro_comprobante'],
            'observacion' => $data['observacion'],
            'nro_nota_refuerzo' => $data['nro_nota_refuerzo'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'jefe_unidad_autorizante' => $data['jefe_unidad_autorizante'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update('logistica.cargas_especiales', $carga);

        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga_especial' => $data['id'],
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
        $model = new CargaEspecialModel();
        $model->select("c.id, c.fecha_alta, c.descripcion, d.dependencia, c.id_unidad_policial, tc.descripcion as tipo_combustible, c.cantidad_litros, c.legajo_personal, j.nombre as jerarquia, p.apellido, p.nombre, c.nro_nota_refuerzo, c.id_tipo, c.importe");
        $model->from('logistica.cargas_especiales c');
        $model->join('personal.dependencias d', 'd.id_dependencia = c.id_dependencia');
        $model->join('logistica.tipo_combustibles tc', 'tc.id = c.id_tipo_combustible');
        $model->join('personal.personal ps', 'ps.legajo = c.legajo_personal');
        $model->join('personas p', 'p.cuil = ps.cuil');
        $model->join('personal.jerarquias j', 'j.id_jerarquia = ps.id_jerarquia');

        if(!empty($searchParam['id_dependencia'])) {
            $model->where('c.id_dependencia', $searchParam['id_dependencia']);
        }
        if(!empty($searchParam['id_unidad_policial'])) {
            $model->where('c.id_unidad_policial', $searchParam['id_unidad_policial']);
        }
        if(!empty($searchParam['fecha_desde']) && !empty($searchParam['fecha_hasta']) && $searchParam['fecha_desde']==$searchParam['fecha_hasta']) {
            $model->where('date(c.fecha_alta) =', $searchParam['fecha_desde']);
        }else {
            if(!empty($searchParam['fecha_desde'])) {
                $model->group_start();
                $model->where('c.fecha_alta >', $searchParam['fecha_desde']);
                $model->or_where('date(c.fecha_alta) =', $searchParam['fecha_desde']);
                $model->group_end();
            }
            if(!empty($searchParam['fecha_hasta'])) {
                $model->group_start();
                $model->where('c.fecha_alta <', $searchParam['fecha_hasta']);
                $model->or_where('date(c.fecha_alta) =', $searchParam['fecha_hasta']);
                $model->group_end();
            }
        }
        if(!empty($searchParam['descripcion'])) {
            $model->like('c.descripcion', $searchParam['descripcion']);
        }

        $model->order_by("c.fecha_alta","desc")->get();
        return $model->result();
    }

    public function eliminar($id) {
        $model = new CargaEspecialModel();
        $result = $model->where('id', $id)->delete('logistica.cargas_especiales');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}