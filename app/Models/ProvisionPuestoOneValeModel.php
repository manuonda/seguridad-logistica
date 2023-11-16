<?php
namespace App\Models;

use CodeIgniter\Model;


class ProvisionPuestoOneValeModel extends Model {

   

    public function findBySrincroEnvio($sincro_envio) {
        $this->db->select('*');
        $this->db->where('sincro_envio', $sincro_envio);
        $this->db->order_by("id");
        return $this->db->get('logistica.provision_puesto1_vales')->result();
    }

    function findValesByIdProvision($id_provision_puesto1) {
        $this->db->select("v.numero");
        $this->db->from('logistica.provision_puesto1_vales ppv');
        $this->db->join('logistica.vales v', 'v.id = ppv.id_vale');
        $this->db->where('ppv.id_provision_puesto1', $id_provision_puesto1);
        $query = $this->db->get();
        $lista = $query->result();

        $vales = '';
        foreach($lista as $vale):
            $vales .= $vale->numero . ', ';
        endforeach;

        return substr ($vales, 0, -2);
    }
    public function cuenta($sincro_envio) {
//        $query = $this->db->query("select count(*) from logistica.moviles where sincro_envio = $sincro_envio or $sincro_envio is null");
        $query = $this->db->query("select count(*) from logistica.provision_puesto1_vales where sincro_envio = $sincro_envio");        
        return $query->row();  
    }
    
    public function update_sincro($id){
            $query = $this->db->query("update logistica.provision_puesto1_vales set sincro_envio = 1 where id = $id");
       
            return $query;            
    }
    
}