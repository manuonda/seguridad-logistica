<?php
namespace App\Models;

use CodeIgniter\Model;

class CargaValeModel extends Model {

    protected $table      = 'logistica.cargas_vales';
    protected $primaryKey = 'id';

   
    public function findBySrincroEnvio($sincro_envio) {
        $model = new CargaValeModel();
        $model->select('*');
        $model->where('sincro_envio', $sincro_envio);
        $model->order_by("id");
        return $model->get('logistica.cargas_vales')->result();
    }

    public function findValesByIdCarga($id_carga) {
        $model = new CargaValeModel();
        $model->select("v.numero");
        $model->from('logistica.cargas_vales cv');
        $model->join('logistica.vales v', 'v.id = cv.id_vale');
        $model->where('cv.id_carga', $id_carga);
        $query = $model->get();
        $lista = $query->result();

        $vales = '';
        foreach($lista as $vale):
            $vales .= $vale->numero . ', ';
        endforeach;

        return substr ($vales, 0, -2);
    }

    public function cuenta($sincro_envio) {
        $model = new CargaValeModel();
        $query = $model->query("select count(*) from logistica.cargas_vales where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    
    public function findValesByIdCargaParticular($id_carga_particular) {
        $model = new CargaValeModel();

        $model->select("v.numero");
        $model->from('logistica.cargas_vales cv');
        $model->join('logistica.vales v', 'v.id = cv.id_vale');
        $model->where('cv.id_carga_particular', $id_carga_particular);
        $query = $model->get();
        $lista = $query->result();

        $vales = '';
        foreach($lista as $vale):
            $vales .= $vale->numero . ', ';
        endforeach;

        return substr ($vales, 0, -2);
    }

    public function findValesByIdCargaEspecial($id_carga_especial) {
        $model = new CargaValeModel();
        $model->select("v.numero");
        $model->from('logistica.cargas_vales cv');
        $model->join('logistica.vales v', 'v.id = cv.id_vale');
        $model->where('cv.id_carga_especial', $id_carga_especial);
        $query = $model->get();
        $lista = $query->result();

        $vales = '';
        foreach($lista as $vale):
            $vales .= $vale->numero . ', ';
        endforeach;

        return substr ($vales, 0, -2);
    }

    public function findValesByIdProvisionInterior($id_provision_interior) {
        $model = new CargaValeModel();

        $model->select("v.numero");
        $model->from('logistica.cargas_vales cv');
        $model->join('logistica.vales v', 'v.id = cv.id_vale');
        $model->where('cv.id_provision_interior', $id_provision_interior);
        $query = $model->get();
        $lista = $query->result();

        $vales = '';
        foreach($lista as $vale):
            $vales .= $vale->numero . ', ';
        endforeach;

        return substr ($vales, 0, -2);
    }
    
    public function update_sincro($id){
        $model = new CargaValeModel();
        $query = $model->query("update logistica.cargas_vales set sincro_envio = 1 where id = $id");
        return $query;            
    }        
    
}