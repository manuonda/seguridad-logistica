<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of grupo_seccion_model
 */
class Session_model extends Model {
    /*
      public function __construct() {
      parent::__construct();
      $this->load->config('ion_auth', TRUE);
      $this->lang->load('ion_auth');

      //initialize db tables data
      $this->tables = $this->config->item('tables', 'ion_auth');
      }
     */

    /**
     * Obtener lista de sesiones actuales
     *
     * @return object
     * */
    public function get_all() {
        $this->db->from('sessions');
        $this->db->where("position(':\"identity\";' in user_data) > 0");
        $this->db->order_by('last_activity', 'desc');
        return $this->db->get();
    }

    public function check_session($identity) {
        //select * from sessions where substring(user_data from 1 for 3) = 'a:7' and position('felipe' in user_data) > 0;
        //$this->db->from('usuarios');
        //$this->db->where('username', $identity);
        //$this->db->where('password', $password);
        //if ($this->db->count_all_results() > 0) {
        $this->db->from('sessions');
        //$this->db->where('substring(user_data from 1 for 3) =','a:7');
        $this->db->where("position(':\"" . $identity . "\";' in user_data) > 0");
        return $this->db->count_all_results() > 0;
        //}
    }

    public function clean($identity) {
        $this->db->where("position(':\"" . $identity . "\";' in user_data) > 0");
        $this->db->delete('sessions');
    }
    
    public function delete($id) {
        $this->db->trans_begin();
        $this->db->delete('sessions', array('session_id' => $id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        $this->db->trans_commit();
        return TRUE;        
    }
     
    /**
     * 
     * @param type $id
     * @return type 
     */
    public function get_item($id = NULL) {
        if (isset($id)) {
            $this->db->where($this->tables['secciones'] . '.id_seccion', $id);
            $this->db->limit(1);
            return $this->db->get($this->tables['secciones']);
        }
    }

    /**
     * Permite actualizar la información de un Grupo de Secciones
     * 
     * @param int $id Id de Rol
     * @param array $data Datos a actualizar
     * @return boolean
     */
    public function update($id = NULL, array $data) {

        $group = $this->get_item($id)->row();

        $this->db->trans_begin();
        if (array_key_exists('nombre', $data) && $this->_name_check($data['nombre']) > 1) {
            $this->db->trans_rollback();
            //$this->set_error('account_creation_duplicate_' . $data['name']);
            //$this->set_error('update_unsuccessful');
            return FALSE;
        }
        $this->db->update($this->tables['secciones'], $data, array('id_seccion' => $group->id_seccion));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            //$this->set_error('update_unsuccessful');
            return FALSE;
        }
        $this->db->trans_commit();
        //$this->set_message('update_successful');
        return TRUE;
    }

    protected function _filter_data($table, $data) {
        $filtered_data = array();
        $columns = $this->db->list_fields($table);

        if (is_array($data)) {
            foreach ($columns as $column) {
                if (array_key_exists($column, $data))
                    $filtered_data[$column] = $data[$column];
            }
        }

        return $filtered_data;
    }

    /**
     * Permite checkear si nombre de Grupo de Secciones es válido o existente
     * 
     * @param string $group_name Nombre de Rol
     * @return bool
     */
    private function _name_check($name = '') {

        if (empty($name)) {
            return FALSE;
        }
        return $this->db->where('nombre', $name)
                        ->count_all_results($this->tables['secciones']) > 0;
    }

}

/* End of file session_model.php */
/* Location: ./application/models/session_model.php */
