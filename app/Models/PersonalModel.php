<?php
namespace App\Models;

use CodeIgniter\Model;


class PersonalModel extends Model {

    protected $table      = 'personal.personal';
    protected $primaryKey = 'legajo';


    public function findByLegajo($legajo) {
        $model = new PersonalModel();

        $model->select("ps.legajo, j.nombre as jerarquia, p.apellido, p.nombre");
        $model->from('personal.personal ps');
        $model->join('personal.jerarquias j', 'j.id_jerarquia = ps.id_jerarquia');
        $model->join('personas p', 'p.cuil = ps.cuil');
        $model->where('ps.legajo', $legajo);
        $query = $model->get();
        return $query->row();
    }

    public function guardar($data) {
        $this->db->trans_begin();
        if($this->existePersona($data['dni'])) {
            $persona = array(
                'apellido' => $data['apellido'],
                'nombre' => $data['nombre'],
                'sexo' => $data['sexo'],
                'usuario_modificacion' => $this->session->userdata('user_id'),
                'fecha_modificacion' => date('Y-m-d H:i:s')
            );
            $this->db->where('dni', $data['dni']);
            $this->db->update('personas', $persona);
        }else {
            $persona = array(
                'cuil' => $data['cuil'],
                'cuil_ciudadano' => $data['cuil'],
                'id_tipo_documento' => 1, // por defecto tipo DNI
                'dni' => $data['dni'],
                'letra_sexo' => 'f',
                'apellido' => $data['apellido'],
                'nombre' => $data['nombre'],
                'sexo' => $data['sexo'],
                'usuario_alta' => $this->session->userdata('user_id'),
                'fecha_alta' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('personas', $persona);
        }

        $personal = array(
            'legajo' => $data['legajo'],
            'id_jerarquia' => $data['id_jerarquia'],
            'id_dependencia' => $data['id_dependencia'],
            'cuil' => $data['cuil'],
            'usuario_alta' => $this->session->userdata('user_id'),
            'fecha_alta' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('personal', $personal);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        }else {
            $this->db->trans_commit();
            return 'ok';
        }
    }

    public function existePersona($dni) {

        $model = new PersonalModel();
        $model->select("p.dni");
        $model->from('personas p');
        $model->where('p.dni', $dni);
        $query = $model->get();
        if($query->row() == null) {
            return false;
        }else {
            return true;
        }
    }

    public function existePersonal($legajo) {
        $model = new PersonalModel();

        $model->select("p.legajo");
        $model->from('personal p');
        $model->where('p.legajo', $legajo);
        $query = $model->get();
        if($query->row() == null) {
            return false;
        }else {
            return true;
        }
    }
}