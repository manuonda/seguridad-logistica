<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class CargaModel extends Model {
    protected $table      = 'logistica.cargas';
    protected $primaryKey = 'id';


    function get($id) {
        $model = new CargaModel();
        $model->select('*');
        $model->from('logistica.cargas');
        return $model->where('id', $id)->get()->getResultObject();
    }

    public function get_all() {
        $model = new CargaModel();
        $model->select('*');
        $model->order_by("fecha_alta");
        return $model->get('logistica.cargas')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $model = new CargaModel();
        $model->select('*');
        $model->where('sincro_envio', $sincro_envio);
        $model->order_by("id");
        return $model->get('logistica.cargas')->result();
    }
    public function cuenta($sincro_envio) {

        $query = $this->db->query("select count(*) from logistica.cargas where sincro_envio = $sincro_envio");        
        return $query->row();  
    }    

    public function agregar($data) {
        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['nro_nota_refuerzo'])) $data['nro_nota_refuerzo'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;
        if(empty($data['jefe_unidad_autorizante'])) $data['jefe_unidad_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'legajo_movil' => $data['legajo_movil'],
            'kilometraje' => $data['kilometraje'],
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
        $this->db->insert('logistica.cargas', $carga);
        $id_carga = $this->db->insert_id();

//        echo 'idVales='.$data['idVales'];
        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga' => $id_carga,
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
            $query = $this->db->query("update logistica.cargas set sincro_envio = 1 where id = $id");
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

    public function buscar($searchParam) {
        $model = new CargaModel();

        $model->select("c.id, c.fecha_alta, c.legajo_movil, d.dependencia, c.id_unidad_policial, t.descripcion as tipo, m.marca, m.modelo, m.dominio, c.kilometraje, tc.descripcion as tipo_combustible, c.cantidad_litros, c.legajo_personal, j.nombre as jerarquia, p.apellido, p.nombre, c.nro_nota_refuerzo, c.id_tipo, c.importe");
        $model->from('logistica.cargas c');
        $model->join('logistica.moviles m', 'm.legajo = c.legajo_movil');
        $model->join('personal.dependencias d', 'd.id_dependencia = c.id_dependencia');
        $model->join('logistica.tipo_moviles t', 't.id = m.id_tipo_movil');
        $model->join('logistica.tipo_combustibles tc', 'tc.id = c.id_tipo_combustible');
        $model->join('personal.personal ps', 'ps.legajo = c.legajo_personal');
        $model->join('personas p', 'p.cuil = ps.cuil');
        $model->join('personal.jerarquias j', 'j.id_jerarquia = ps.id_jerarquia');

        if(!empty($searchParam['legajo_movil'])) {
            $model->where('c.legajo_movil', $searchParam['legajo_movil']);
        }
        if(!empty($searchParam['legajo_personal'])) {
            $model->where('c.legajo_personal', $searchParam['legajo_personal']);
        }
        if(!empty($searchParam['id_tipo_movil'])) {
            $model->where('m.id_tipo_movil', $searchParam['id_tipo_movil']);
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

        $model->order_by("c.fecha_alta","desc");
        $query = $model->get();
        return $query->result();
    }

    public function getKilometrajeAnterior($legajo_movil) {
        $model = new CargaModel();
        $model->select("fecha_alta, kilometraje, cantidad_litros");
        $model->from('logistica.cargas');
        $model->where('legajo_movil', $legajo_movil);
        $model->order_by("id","desc");
        $model->limit(1)->get();
        return $model->row();
    }

    public function eliminar($id) {
        $model = new CargaModel();
        $result = $model->where('id', $id)->delete();
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}