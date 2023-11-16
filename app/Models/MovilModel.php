<?php
namespace App\Models;

use CodeIgniter\Model;


class MovilModel extends Model {

    protected $table      = 'logistica.moviles';
    protected $primaryKey = 'legajo';


    public function get($legajo) {
        $model =new MovilModel();
        $model->select('*');
        $model->from('logistica.moviles');
        $model->where('legajo', $legajo)->get();
        return $model->row();
    }

    public function findByLegajo($legajo) {
        $model =new MovilModel();

        $model->select("m.id_unidad_policial, u.nombre as unidadPolicial, m.id_dependencia, d.dependencia, t.id as id_tipo, t.kilometros_por_litro, t.descripcion as tipo, m.marca, m.modelo, m.dominio, m.id_situacion");
        $model->from('logistica.moviles m');
        $model->join('logistica.tipo_moviles t', 'm.id_tipo_movil = t.id');
        $model->join('personal.unidades_policiales u', 'u.id_unidad_policial = m.id_unidad_policial');
        $model->join('personal.dependencias d', 'd.id_dependencia = m.id_dependencia');
        $model->where('m.legajo', $legajo)->get();
        return $model->row();
    }

    public function get_all() {
        $model =new MovilModel();

        $model->select('*');
        $model->order_by("legajo");
        return $model->get('logistica.moviles')->result();
    }

    public function findBySrincroEnvio($sincro_envio) {
        $model =new MovilModel();
        $model->select('*');
        $model->where('sincro_envio', $sincro_envio);
        $model->order_by("legajo");
        return $model->get('logistica.moviles')->result();
    }

    public function cuenta($sincro_envio) {
        $model =new MovilModel();
        $query = $model->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    
    public function findByUnidadRegional($id_unidad_regional) {
        $model =new MovilModel();

        $model->select('id, nombre');
        $model->from('segvial.destacamentos');
        $model->where('id_unidad_regional', $id_unidad_regional)->get();
        return $model->result();
    }

    public function guardar($data) {
        $model =new MovilModel();

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
        $model->insert($datos);
        return $model->getInsertID();
    }

    public function actualizar($data) {
        $model =new MovilModel();

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
        return $model->update($this->primaryKey, $datos);
    }

    public function update_sincro($id){
         $query = $this->db->query("update logistica.moviles set sincro_envio = 1 where legajo = $id");
         return $query;
    }
    
    public function buscar($searchParam) {
        $model = new MovilModel();
        $sql = "SELECT legajo, anio, marca, modelo, dominio, nro_chasis_o_cuadro, nro_motor, id_situacion, id_tipo_movil, id_unidad_policial, id_dependencia, flag_depositario_judicial
    			FROM   logistica.moviles
    			WHERE 1 = 1 ";

        if(!empty($searchParam)) {
            if(!empty($searchParam['legajo'])) {
                $sql .= " and legajo = '" . $searchParam['legajo'] ."'";
            }
            if(!empty($searchParam['anio'])) {
                $sql .= " and anio = " . $searchParam['anio'];
            }
            if(!empty($searchParam['modelo'])) {
                $sql .= " and modelo = '" . $searchParam['modelo'] ."'";
            }
            if(!empty($searchParam['id_tipo_movil'])) {
                $sql .= " and id_tipo_movil = " . $searchParam['id_tipo_movil'];
            }
            if(!empty($searchParam['id_unidad_policial'])) {
                $sql .= " and id_unidad_policial = " . $searchParam['id_unidad_policial'];
            }
            if(!empty($searchParam['id_situacion'])) {
                $sql .= " and id_situacion = " . $searchParam['id_situacion'];
            }
        }

        $sql .= " ORDER BY legajo desc ";
        $query = $model->query($sql);
        return $query->result();
    }

    public function hayParametroConValor($searchParam) {
        foreach ($searchParam as $key => $value) {
            if (!empty($value)) {
                return true;
            }
        }

        return false;
    }

    public function eliminar($id) {
        $model = new MovilModel();
        $result = $model->where('id', $id)->delete('logistica.destacamentos');
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }
}