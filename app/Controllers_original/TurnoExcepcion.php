<?php
namespace App\Controllers;

use App\Models\ExcepcionesModel;


class TurnoExcepcion extends BaseController {
	protected $turnoExcepcionModel;
	
	protected $session;
	protected $userInSession;
	
	public function __construct() {
	    $this->session = session();
	    $this->userInSession = $this->session->get('user');
	}

	public function get_excepcion_x_dependencia($id_dependencia = null) {
	   if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA || $this->userInSession['id_rol']==ROL_JEFE_UNIDAD_ADMINISTRATIVA)) {
	       if(empty($id_dependencia)) {
	           throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	       }else {
	           $this->turnoExcepcionModel = new ExcepcionesModel();
	           $rows = $this->turnoExcepcionModel->where('id_dependencia', $id_dependencia)->findAll();
	           $excepciones = [];
	           foreach ($rows as $excepcion) {
	               $excepciones[] = $this->get_format_excepcion($excepcion);
	           }
	           
	           $data['excepciones'] = $excepciones;
	           
	           echo json_encode($data);
	           return;
	       }
	   }else {
	       throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	   }        
	}
    
    private function get_format_excepcion($excepcion){
		if($excepcion['atencion'] == 'm'){
			$atencion = 'p/mañana';
		}else{
			$atencion = 'p/tarde';
		}
		$row = '<tr>' .
			'<td>' . $excepcion['fecha_dia']  . ' '.$atencion.'</td>' .
			'<td>' . $excepcion['hora_inicio'] . '</td>' .
			'<td>' . $excepcion['hora_fin'] .'</td>' .
			'<td>' . $excepcion['motivo'] .'</td>' .
			'<td> <a onclick="quitarexcepcion(this)" href="'.base_url().'/turnoExcepcion/eliminarexcepcion/'. $excepcion['id_turno_excepcion'] .'">Quitar Excepción</a></td>' .
			'</tr>';
            
        return $row;
    }
	
	public function set_excepcion(){
		$fechaDia = $this->request->getVar('fecha_dia');
		$horaInicio = $this->request->getVar('hora_inicio');
		$horaFin = $this->request->getVar('hora_fin');
		$motivo = $this->request->getVar('motivo');
		$atencion = $this->request->getVar('atencion');
		$id_dependencia = $this->request->getVar('id_dependencia');

		$registro = [
			'fecha_dia'	=> $fechaDia,
			'hora_inicio' => $horaInicio,
			'hora_fin' => $horaFin,
			'motivo' => $motivo,
			'atencion' => $atencion,
			'id_dependencia' => $id_dependencia
		];
		
		$this->turnoExcepcionModel = new ExcepcionesModel();
		try{
			$estado = $this->turnoExcepcionModel->insert($registro, true);
			$excepcion_id = $this->turnoExcepcionModel->getInsertID();
			$row = '<tr><td>'.$fechaDia.'</td><td>'.$horaInicio.'</td><td>'.$horaFin.'</td><td>'.$motivo.'</td><td> <a onclick="quitarexcepcion(this)" href="'.base_url().'/turnoExcepcion/eliminarexcepcion/'. $excepcion_id .'">Quitar Excepción</a></td></tr>';
			$data['estado'] = 1;
			$data['ok'] = $row;
		} catch (\Exception $e) {
			$data['estado'] = $e->getCode();
			$data['ok'] = '';
		}
		// if (!is_null($estado)){

		// }else{
			
		// }

		echo json_encode($data);
		return;
	}

	public function eliminarexcepcion($id){
		$this->turnoExcepcionModel = new ExcepcionesModel();
		$estado = $this->turnoExcepcionModel->delete($id);
		$data['ok'] = $estado;
		echo json_encode($data);
		return;		
	}
}