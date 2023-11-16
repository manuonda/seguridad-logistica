<?php
namespace App\Models;

use App\Controllers\CargaParticular;
use CodeIgniter\Model;

class CargaParticularModel extends Model {

    protected $table      = 'logistica.cargas_particular';
    protected $primaryKey = 'id';


    public function get($id) {
        $model = new CargaParticularModel();
        $model->select('*');
        $model->from('logistica.cargas_particular');
        $model->where('id', $id)->get();
       
        return $model->row();
    }

    public function get_all() {
        $model =new CargaParticularModel();
        $model->select('*');
        $model->order_by("fecha_alta");
        return $model->get('logistica.cargas_particular')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $model = new CargaParticularModel();
        $model->select('*');
        $model->where('sincro_envio', $sincro_envio);
        $model->order_by("id");
        return $model->get('logistica.cargas_particular')->result();
    }
    public function cuenta($sincro_envio) {
        $model = new CargaParticularModel();
        $query = $model->query("select count(*) from logistica.cargas_particular where sincro_envio = $sincro_envio");        
        return $query->row();
    }
    
    public function guardar($data) {
        if(empty($data['marca'])) $data['marca'] = null;
        if(empty($data['id_tipo_movil'])) $data['id_tipo_movil'] = null;
        if(empty($data['anio'])) $data['anio'] = null;
        if(empty($data['modelo'])) $data['modelo'] = null;
        if(empty($data['kilometraje'])) $data['kilometraje'] = null;
        if(empty($data['legajo'])) $data['legajo'] = null;
        if(empty($data['dni'])) $data['dni'] = null;
        if(empty($data['id_unidad_policial'])) $data['id_unidad_policial'] = null;
        if(empty($data['id_dependencia'])) $data['id_dependencia'] = null;

        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['nro_resolucion'])) $data['nro_resolucion'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['nro_nota_refuerzo'])) $data['nro_nota_refuerzo'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;
        if(empty($data['jefe_unidad_autorizante'])) $data['jefe_unidad_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'dominio' => $data['dominio'],
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'anio' => $data['anio'],
            'id_tipo_movil' => $data['id_tipo_movil'],
            'kilometraje' => $data['kilometraje'],
            'id_dependencia' => $data['id_dependencia'],
            'id_unidad_policial' => $data['id_unidad_policial'],

            'legajo' => $data['legajo'],
            'dni' => $data['dni'],
            'apellido' => $data['apellido'],
            'nombre' => $data['nombre'],
            'cargo_funcion' => $data['cargo_funcion'],
            'lugar_de_trabajo' => $data['lugar_de_trabajo'],

            'id_estacion' => $data['id_estacion'],
            'id_tipo' => $data['id_tipo'],
            'id_tipo_combustible' => $data['id_tipo_combustible'],
            'cantidad_litros' => $data['cantidad_litros'],
            'nro_comprobante' => $data['nro_comprobante'],
            'importe' => $data['importe'],
            'nro_resolucion' => $data['nro_resolucion'],
            'observacion' => $data['observacion'],
            'nro_nota_refuerzo' => $data['nro_nota_refuerzo'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'jefe_unidad_autorizante' => $data['jefe_unidad_autorizante'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
            'sincro_envio' => 0
        );
        $this->db->insert('logistica.cargas_particular', $carga);
        $id_carga = $this->db->insert_id();

//        echo 'idVales='.$data['idVales'];
        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga_particular' => $id_carga,
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
        $model = new CargaParticularModel();
        $query = $model->query("update logistica.cargas_particular set sincro_envio = 1 where id = $id");
        return $query;            
    }    
    
    public function actualizar($data) {
        if(empty($data['marca'])) $data['marca'] = null;
        if(empty($data['id_tipo_movil'])) $data['id_tipo_movil'] = null;
        if(empty($data['anio'])) $data['anio'] = null;
        if(empty($data['modelo'])) $data['modelo'] = null;
        if(empty($data['kilometraje'])) $data['kilometraje'] = null;
        if(empty($data['legajo'])) $data['legajo'] = null;
        if(empty($data['id_unidad_policial'])) $data['id_unidad_policial'] = null;
        if(empty($data['id_dependencia'])) $data['id_dependencia'] = null;

        if(empty($data['nro_comprobante'])) $data['nro_comprobante'] = null;
        if(empty($data['importe'])) $data['importe'] = null;
        if(empty($data['nro_resolucion'])) $data['nro_resolucion'] = null;
        if(empty($data['observacion'])) $data['observacion'] = null;
        if(empty($data['nro_nota_refuerzo'])) $data['nro_nota_refuerzo'] = null;
        if(empty($data['jefe_logistica_autorizante'])) $data['jefe_logistica_autorizante'] = null;
        if(empty($data['jefe_unidad_autorizante'])) $data['jefe_unidad_autorizante'] = null;

        $this->db->trans_begin();
        $carga = array(
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'anio' => $data['anio'],
            'id_tipo_movil' => $data['id_tipo_movil'],
            'kilometraje' => $data['kilometraje'],
            'id_dependencia' => $data['id_dependencia'],
            'id_unidad_policial' => $data['id_unidad_policial'],

            'legajo' => $data['legajo'],
            'apellido' => $data['apellido'],
            'nombre' => $data['nombre'],
            'cargo_funcion' => $data['cargo_funcion'],
            'lugar_de_trabajo' => $data['lugar_de_trabajo'],

            'nro_comprobante' => $data['nro_comprobante'],
            'importe' => $data['importe'],
            'nro_resolucion' => $data['nro_resolucion'],
            'observacion' => $data['observacion'],
            'nro_nota_refuerzo' => $data['nro_nota_refuerzo'],
            'jefe_logistica_autorizante' => $data['jefe_logistica_autorizante'],
            'jefe_unidad_autorizante' => $data['jefe_unidad_autorizante'],
            'usuario_modificacion' => $this->session->userdata('user_id'),
            'fecha_modificacion' => date('Y-m-d H:i:s'),
            'sincro_envio' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update('logistica.cargas_particular', $carga);

        if(!empty($data['idVales'])) {
            $vales = explode(",", $data['idVales']);
            for ($i = 0; $i < count($vales); $i++) {
                $carga_vale = array(
                    'id_carga_particular' => $data['id'],
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
        $model = new CargaParticularModel();
        $model->select("c.id, c.fecha_alta, c.dominio, c.id_dependencia, c.id_unidad_policial, c.id_tipo_movil, c.marca, c.modelo, c.kilometraje, tc.descripcion as tipo_combustible, c.cantidad_litros, c.legajo, c.dni, c.cargo_funcion, c.apellido, c.nombre, c.lugar_de_trabajo, c.id_tipo, c.importe, c.nro_resolucion");
        $model->from('logistica.cargas_particular c');
        $model->join('logistica.tipo_combustibles tc', 'tc.id = c.id_tipo_combustible');

        if(!empty($searchParam['dominio'])) {
            $model->where('c.dominio', $searchParam['dominio']);
        }
        if(!empty($searchParam['dni'])) {
            $model->where('c.dni', $searchParam['dni']);
        }
        if(!empty($searchParam['id_tipo_movil'])) {
            $model->where('c.id_tipo_movil', $searchParam['id_tipo_movil']);
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

        $model->order_by("c.fecha_alta","desc")->get();
        return $model->result();
    }

    public function getKilometrajeAnterior($legajo_movil) {
        $model = new CargaParticularModel();
        $model->select("fecha_alta, kilometraje, cantidad_litros");
        $model->from('logistica.cargas_particular');
        $model->where('legajo_movil', $legajo_movil);
        $model->order_by("id","desc");
        $model->limit(1)->get();
        return $model->row();
    }

    public function findByDominio($dominio) {
        $model= new CargaParticularModel();
        $model->select("c.fecha_alta, c.marca, c.modelo, c.anio, c.id_tipo_movil, c.id_unidad_policial, c.id_dependencia, c.kilometraje, c.cantidad_litros, c.id_tipo_combustible, t.kilometros_por_litro");
        $model->from('logistica.cargas_particular c');
        $model->join('logistica.tipo_moviles t', 'c.id_tipo_movil = t.id');
        $model->where('c.dominio', $dominio);
        $model->order_by("c.id","desc")->get();
        $lista = $model->result();
        if(empty($lista)) {
            return null;
        }else {
            return $lista[0];
        }
    }

    public function eliminar($id) {
        $model = new CargaParticularModel();
        $result = $model->where('id', $id)->delete('logistica.cargas_particular');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}