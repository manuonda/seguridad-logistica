<?php 
namespace App\Controllers;
use App\Models\DependenciaModel;
use App\Models\TurnoParametroModel;
use App\Models\TurnoCantidadModel;
use App\Libraries\Util;
use App\Models\TurnoModel;
use App\Models\TramiteModel;
use App\Models\TramitePersonaModel;
use App\Libraries\Pdf;
use App\Models\TipoTramiteModel;
use App\Models\TurnoDependenciaModel;
use App\Models\TurnoFeriadoModel;
use App\Models\TipoDocumentoModel;
use App\Models\ExcepcionesModel;
use App\Libraries\EmailSendgrid;

class Turno extends BaseController {
    
    protected $util;
    protected $session;
    protected $userInSession;
    
    public function __construct() {
        $this->util = new Util();
        $this->session = session();
        $this->userInSession = $this->session->get('user');
    }

//     public function inicio($idDependencia=null) {
//         $this->crearNuevaFechaTurnoParaManiana();
//         $dependenciaModel = new DependenciaModel();
//         $turnoCantidadModel = new TurnoCantidadModel();
//         $fechaActual = date('Y-m-d');
//         $turnoCantidadModel->select('distinct(fecha)');
//         $data['turnoCantidades'] = $turnoCantidadModel->where('fecha >', $fechaActual)->orderBy('fecha', 'ASC')->findAll();
//         $data['dependencias'] = $dependenciaModel->findAllHabilitado();
//         $data['id_dependencia'] = $idDependencia;
//         $data['util'] = new Util();
//         $data['user_id'] = rand(5, 15);
//         log_message('critical', 'Some variable did not contain a value.');
//         $data['contenido'] = "turno";
//         echo view("frontend", $data);
//     }
    
//     public function sacarTurno($data) {
//         $this->crearNuevaFechaTurnoParaManiana();
//         $dependenciaModel = new DependenciaModel();
//         $turnoCantidadModel = new TurnoCantidadModel();
//         $fechaActual = date('Y-m-d');
//         $turnoCantidadModel->select('distinct(fecha)');
//         $data['turnoCantidades'] = $turnoCantidadModel->where('fecha >', $fechaActual)->orderBy('fecha', 'ASC')->findAll();
//         $data['dependencias'] = $dependenciaModel->findAllHabilitado();
//         $data['id_tramite'] = $data['id_tramite'];
//         $data['id_dependencia'] = $data['id_dependencia'];
//         $data['util'] = new Util();
//         $data['user_id'] = $data['cuil'];
//         $data['contenido'] = "turno";
//         echo view("frontend", $data);
//     }
    
    private function craerNuevaFechaTurnoApartirDeUltimaFecha() {
        $turnoCantidadModel = new TurnoCantidadModel();
        $turnoCantidadModel->select('distinct(fecha)');
        $fechaUltimaTurnoCantidad = $turnoCantidadModel->orderBy('fecha', 'DESC')->first();
        if(empty($fechaUltimaTurnoCantidad)) {
            echo 'No existe fecha ultima de turno.';
            return;
        }
        echo 'Fecha ultimo turno: '.$fechaUltimaTurnoCantidad['fecha'].'<br/>';
        
        $fechaUltimaTurno = $fechaUltimaTurnoCantidad['fecha'];
        $fechaAux = strtotime ('+1 day', strtotime($fechaUltimaTurno));
        $fechaManiana = date ('Y-m-d', $fechaAux);
        
        $turnoCantidadModel->select('distinct(fecha)');
        $listaTurnoCantidad = $turnoCantidadModel->where('fecha', $fechaManiana)->findAll();
//         $sql = $turnoCantidadModel->getLastQuery()->getQuery();
//         echo $sql;
        
        if(count($listaTurnoCantidad) == 0) {
            $index = 5;
            $horaInicio = '08:00';
            $fechaHora = date_create($fechaManiana.' '.$horaInicio);
            $cantidad = 0;
            for ($i = 0; $i < $index; $i++) {
                $turnoCantidad['fecha'] = $fechaManiana;
                $turnoCantidad['hora'] = date_format($fechaHora, 'H:i');
                $turnoCantidad['cantidad'] = $cantidad;
                $idTurnoCantidad = $turnoCantidadModel->insert($turnoCantidad, true);
                //                 echo $idTurnoCantidad;
                $fechaHora->modify('+1 hours');
            }
            
            echo "Se ha creado nuevos turno disponibles para la fecha $fechaManiana.";
        }else {
            echo "Ya existe la fecha $fechaManiana, por lo que NO se ha creado nuevos turno para dicha fecha.";
        }
    }
    
    private function isWeekend($date) {
        $fecha = strtotime($date);
        $nombreDia = date("l", $fecha);
        $nombreDia = strtolower($nombreDia);
//         echo $nombreDia;
        if($nombreDia == "saturday" || $nombreDia == "sunday") {
            return true;
        } else {
            return false;
        }
    }

    public function crearXNuevasFechasDeTurnosPorDep($id_dependencia, $cantidad){
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $fechaActual = date('Y-m-d');
            while($cantidad){
                $this->crearNuevaFechaTurnoParaManianaPorDep($id_dependencia, $fechaActual, $fechaActual);
                echo '<br>';
                $cantidad--;
            }
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function crearNuevaFechaTurnoParaManianaPorDep($id_dependencia, &$fechaManiana = null, $fechaActual= null) {
        $turnoCantidadModel = new TurnoCantidadModel();
        if(is_null($fechaActual)){
            $fechaActual = date('Y-m-d');
        }        
        $fechaAux = strtotime('+1 day', strtotime($fechaActual));
        $fechaManiana = date('Y-m-d', $fechaAux);
        
//         $this->isWeekend($fechaManiana);
//         return;
        if($this->isWeekend($fechaManiana)) { //  se verifica si es un dia fin de semana
            $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
            $fechaManiana = date('Y-m-d', $fechaAux);
            if($this->isWeekend($fechaManiana)) {
                $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
                $fechaManiana = date('Y-m-d', $fechaAux);
            }
        }
        
        // se verifica que no sea un dia feriado
        $turnoFeriadoModel = new TurnoFeriadoModel();
        $turnoFeriado = $turnoFeriadoModel->where('fecha', $fechaManiana)->first();
        while (!empty($turnoFeriado)) {
            $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
            $fechaManiana = date('Y-m-d', $fechaAux);
            $turnoFeriado = $turnoFeriadoModel->where('fecha', $fechaManiana)->first();
        }
        
        $turnoCantidadModel->select('distinct(tramite_online.turno_cantidad.fecha)');
        $listaTurnoCantidad = $turnoCantidadModel->join('tramite_online.turno_dependencias', 'tramite_online.turno_dependencias.id_turno_cantidad = tramite_online.turno_cantidad.id_turno_cantidad')
                                                 ->where('tramite_online.turno_dependencias.id_dependencia', $id_dependencia)
                                                 ->where('tramite_online.turno_cantidad.fecha', $fechaManiana)->findAll();
//         $sql = $turnoCantidadModel->getLastQuery()->getQuery();
//         echo $sql;
        
        if(count($listaTurnoCantidad) == 0) {
            $turnoDependenciaModel = new TurnoDependenciaModel();
            $turnoParametroModel = new TurnoParametroModel();
            $turnoParametro = $turnoParametroModel->where('id_dependencia', $id_dependencia)->first();
            if(empty($turnoParametro)) {
                echo "No existe parametros de turnos para la dependencia $id_dependencia.";
                return;
            }else {
                // turnos por la maniana
//                 $index = 5;
//                 $horaInicio = '08:00';

                /**************************MANEJO DE EXCEPCION DE TURNO**************************/

                $turnoExcepcionModel = new ExcepcionesModel();
                $semana = ['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];
                $diaSemana = date('w', strtotime($fechaManiana));
                $parametroExcepcion = $turnoExcepcionModel->groupStart()
                                                            ->where('tramite_online.turno_excepcion.fecha_dia', $fechaManiana)
                                                            ->orWhere('tramite_online.turno_excepcion.fecha_dia',$semana[$diaSemana])
                                                            ->groupEnd()
                                                            ->where('tramite_online.turno_excepcion.atencion', 'm')
                                                            ->where('tramite_online.turno_excepcion.id_dependencia', $id_dependencia)
                                                            ->first();
                if(empty($parametroExcepcion)) {
                    $horaInicio = $turnoParametro['hora_inicio_atencion_mañana'];
                    $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                    $horaFin = $turnoParametro['hora_fin_atencion_mañana'];
                    $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                    $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                }else{
                    $horaInicio = $parametroExcepcion['hora_inicio'];
                    $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                    $horaFin = $parametroExcepcion['hora_fin'];
                    $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                    $cantidad = $turnoParametro['cantidad_turno_por_hora'];                    
                }
                
                /********************************************************************************/
                while ($fechaHora < $fechaHoraFinAtencion) {     
//                 for ($i = 0; $i < $index; $i++) {
                    $turnoCantidad['fecha'] = $fechaManiana;
                    $turnoCantidad['hora'] = date_format($fechaHora, 'H:i');
                    $turnoCantidad['cantidad'] = $cantidad;
                    $turnoCantidad['fecha_alta'] = date('Y-m-d H:i:s');
                    $idTurnoCantidad = $turnoCantidadModel->insert($turnoCantidad, true);
                    //                 echo $idTurnoCantidad;
                    
                    $turnoDependencia['id_dependencia'] = $id_dependencia;
                    $turnoDependencia['id_turno_cantidad'] = $idTurnoCantidad;
                    $turnoDependencia['fecha_alta'] = date('Y-m-d H:i:s');
                    $idTurnoDep = $turnoDependenciaModel->insert($turnoDependencia, true);
                    $fechaHora->modify('+1 hours');
                }

                echo "Se ha creado nuevos turno disponibles para la dependencia $id_dependencia en la fecha $fechaManiana de horas $horaInicio - $horaFin";
                
                if(!empty($turnoParametro['hora_inicio_atencion_tarde']) && !empty($turnoParametro['hora_fin_atencion_tarde'])) {

                    /**************************MANEJO DE EXCEPCION DE TURNO**************************/
                    $parametroExcepcion = $turnoExcepcionModel->groupStart()
                                                                ->where('tramite_online.turno_excepcion.fecha_dia', $fechaManiana)
                                                                ->orWhere('tramite_online.turno_excepcion.fecha_dia',$semana[$diaSemana])
                                                                ->groupEnd()
                                                                ->where('tramite_online.turno_excepcion.atencion', 't')
                                                                ->where('tramite_online.turno_excepcion.id_dependencia', $id_dependencia)
                                                                ->first();
                    if(empty($parametroExcepcion)) {                    
                        // turnos por la tarde
                        $horaInicio = $turnoParametro['hora_inicio_atencion_tarde'];
                        $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                        $horaFin = $turnoParametro['hora_fin_atencion_tarde'];
                        $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                        $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                    }else{
                        $horaInicio = $parametroExcepcion['hora_inicio'];
                        $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                        $horaFin = $parametroExcepcion['hora_fin'];
                        $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                        $cantidad = $turnoParametro['cantidad_turno_por_hora'];                    
                    }
                    
                    /****************************************************************************** */
                    while ($fechaHora < $fechaHoraFinAtencion) {
                        $turnoCantidad['fecha'] = $fechaManiana;
                        $turnoCantidad['hora'] = date_format($fechaHora, 'H:i');
                        $turnoCantidad['cantidad'] = $cantidad;
                        $turnoCantidad['fecha_alta'] = date('Y-m-d H:i:s');
                        $idTurnoCantidad = $turnoCantidadModel->insert($turnoCantidad, true);
                        //                 echo $idTurnoCantidad;
                        $turnoDependencia['id_dependencia'] = $id_dependencia;
                        $turnoDependencia['id_turno_cantidad'] = $idTurnoCantidad;
                        $turnoDependencia['fecha_alta'] = date('Y-m-d H:i:s');
                        $idTurnoDep = $turnoDependenciaModel->insert($turnoDependencia, true);
                        $fechaHora->modify('+1 hours');
                    }
                    
                    echo " y de horas $horaInicio - $horaFin";
                }
                
                // echo "Se ha creado nuevos turno disponibles para la dependencia $id_dependencia en la fecha $fechaManiana.";
            }            
        }else {
            echo "Ya existen turnos de la dependencia $id_dependencia para la fecha $fechaManiana, por lo que NO se ha creado nuevos turno para dicha fecha.";
        }
    }
    
    public function crearXNuevasFechasDeTurnosPorDepYtipoTramite($id_dependencia, $cantidad, $idTipoTramite, $descTipoTramite) {
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $fechaActual = date('Y-m-d');
            while($cantidad){
                $this->crearNuevaFechaTurnoParaManianaPorDepYtipoTramite($id_dependencia, $fechaActual, $fechaActual, $idTipoTramite, $descTipoTramite);
                echo '<br>';
                $cantidad--;
            }
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function crearNuevaFechaTurnoParaManianaPorDepYtipoTramite($id_dependencia, &$fechaManiana = null, $fechaActual= null, $idTipoTramite, $descTipoTramite) {
        $turnoCantidadModel = new TurnoCantidadModel();
        if(is_null($fechaActual)){
            $fechaActual = date('Y-m-d');
        }
        
        $fechaAux = strtotime('+1 day', strtotime($fechaActual));       
        $fechaManiana = date('Y-m-d', $fechaAux);
        
        //         $this->isWeekend($fechaManiana);
        //         return;
        if($this->isWeekend($fechaManiana)) { //  se verifica si es un dia fin de semana
            $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
            $fechaManiana = date('Y-m-d', $fechaAux);
            if($this->isWeekend($fechaManiana)) {
                $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
                $fechaManiana = date('Y-m-d', $fechaAux);
            }
        }
        
        // se verifica que no sea un dia feriado
        $turnoFeriadoModel = new TurnoFeriadoModel();
        $turnoFeriado = $turnoFeriadoModel->where('fecha', $fechaManiana)->first();
        while (!empty($turnoFeriado)) {
            $fechaAux = strtotime('+1 day', strtotime($fechaManiana));
            $fechaManiana = date('Y-m-d', $fechaAux);
            $turnoFeriado = $turnoFeriadoModel->where('fecha', $fechaManiana)->first();
        }
        
        $turnoCantidadModel->select('distinct(tramite_online.turno_cantidad.fecha)');
        $listaTurnoCantidad = $turnoCantidadModel->join('tramite_online.turno_dependencias', 'tramite_online.turno_dependencias.id_turno_cantidad = tramite_online.turno_cantidad.id_turno_cantidad')
                                                ->where('tramite_online.turno_dependencias.id_dependencia', $id_dependencia)
                                                ->where('tramite_online.turno_dependencias.id_tipo_tramite', $idTipoTramite)
                                                ->where('tramite_online.turno_dependencias.desc_tipo_tramite', $descTipoTramite)
                                                ->where('tramite_online.turno_cantidad.fecha', $fechaManiana)->findAll();
        //         $sql = $turnoCantidadModel->getLastQuery()->getQuery();
        //         echo $sql;
        
        if(count($listaTurnoCantidad) == 0) {
            $turnoDependenciaModel = new TurnoDependenciaModel();
            $turnoParametroModel = new TurnoParametroModel();
            $turnoParametro = $turnoParametroModel->where('id_dependencia', $id_dependencia)
                                                    ->where('id_tipo_tramite', $idTipoTramite)
                                                    ->where('desc_tipo_tramite', $descTipoTramite)
                                                    ->first();
            if(empty($turnoParametro)) {
                echo "No existe parametros de turnos para la dependencia $id_dependencia, .";
                return;
            }else {
                // turnos por la maniana
                //                 $index = 5;
                //                 $horaInicio = '08:00';
                
                /**************************MANEJO DE EXCEPCION DE TURNO**************************/
                
                $turnoExcepcionModel = new ExcepcionesModel();
                $semana = ['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];
                $diaSemana = date('w', strtotime($fechaManiana));
                $parametroExcepcion = $turnoExcepcionModel->groupStart()
                ->where('tramite_online.turno_excepcion.fecha_dia', $fechaManiana)
                ->orWhere('tramite_online.turno_excepcion.fecha_dia',$semana[$diaSemana])
                ->groupEnd()
                ->where('tramite_online.turno_excepcion.atencion', 'm')
                ->where('tramite_online.turno_excepcion.id_dependencia', $id_dependencia)
                ->first();
                if(empty($parametroExcepcion)) {
                    $horaInicio = $turnoParametro['hora_inicio_atencion_mañana'];
                    $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                    $horaFin = $turnoParametro['hora_fin_atencion_mañana'];
                    $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                    $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                }else{
                    $horaInicio = $parametroExcepcion['hora_inicio'];
                    $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                    $horaFin = $parametroExcepcion['hora_fin'];
                    $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                    $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                }
                
                /********************************************************************************/
                while ($fechaHora < $fechaHoraFinAtencion) {
                    //                 for ($i = 0; $i < $index; $i++) {
                    $turnoCantidad['fecha'] = $fechaManiana;
                    $turnoCantidad['hora'] = date_format($fechaHora, 'H:i');
                    $turnoCantidad['cantidad'] = $cantidad;
                    $turnoCantidad['fecha_alta'] = date('Y-m-d H:i:s');
                    $idTurnoCantidad = $turnoCantidadModel->insert($turnoCantidad, true);
                    //                 echo $idTurnoCantidad;
                    
                    $turnoDependencia['id_dependencia'] = $id_dependencia;
                    $turnoDependencia['id_turno_cantidad'] = $idTurnoCantidad;
                    $turnoDependencia['fecha_alta'] = date('Y-m-d H:i:s');
                    $turnoDependencia['id_tipo_tramite'] = $idTipoTramite;
                    $turnoDependencia['desc_tipo_tramite'] = $descTipoTramite;
                    $idTurnoDep = $turnoDependenciaModel->insert($turnoDependencia, true);
                    $fechaHora->modify('+1 hours');
                }
                
                echo "Se ha creado nuevos turno disponibles para la dependencia $id_dependencia, tramite $idTipoTramite - $descTipoTramite,  en la fecha $fechaManiana de horas $horaInicio - $horaFin";
                
                if(!empty($turnoParametro['hora_inicio_atencion_tarde']) && !empty($turnoParametro['hora_fin_atencion_tarde'])) {
                    
                    /**************************MANEJO DE EXCEPCION DE TURNO**************************/
                    $parametroExcepcion = $turnoExcepcionModel->groupStart()
                    ->where('tramite_online.turno_excepcion.fecha_dia', $fechaManiana)
                    ->orWhere('tramite_online.turno_excepcion.fecha_dia',$semana[$diaSemana])
                    ->groupEnd()
                    ->where('tramite_online.turno_excepcion.atencion', 't')
                    ->where('tramite_online.turno_excepcion.id_dependencia', $id_dependencia)
                    ->first();
                    if(empty($parametroExcepcion)) {
                        // turnos por la tarde
                        $horaInicio = $turnoParametro['hora_inicio_atencion_tarde'];
                        $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                        $horaFin = $turnoParametro['hora_fin_atencion_tarde'];
                        $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                        $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                    }else{
                        $horaInicio = $parametroExcepcion['hora_inicio'];
                        $fechaHora = date_create($fechaManiana.' '.$horaInicio);
                        $horaFin = $parametroExcepcion['hora_fin'];
                        $fechaHoraFinAtencion = date_create($fechaManiana.' '.$horaFin);
                        $cantidad = $turnoParametro['cantidad_turno_por_hora'];
                    }
                    
                    /****************************************************************************** */
                    while ($fechaHora < $fechaHoraFinAtencion) {
                        $turnoCantidad['fecha'] = $fechaManiana;
                        $turnoCantidad['hora'] = date_format($fechaHora, 'H:i');
                        $turnoCantidad['cantidad'] = $cantidad;
                        $turnoCantidad['fecha_alta'] = date('Y-m-d H:i:s');
                        $idTurnoCantidad = $turnoCantidadModel->insert($turnoCantidad, true);
                        //                 echo $idTurnoCantidad;
                        $turnoDependencia['id_dependencia'] = $id_dependencia;
                        $turnoDependencia['id_turno_cantidad'] = $idTurnoCantidad;
                        $turnoDependencia['fecha_alta'] = date('Y-m-d H:i:s');
                        $turnoDependencia['id_tipo_tramite'] = $idTipoTramite;
                        $turnoDependencia['desc_tipo_tramite'] = $descTipoTramite;
                        $idTurnoDep = $turnoDependenciaModel->insert($turnoDependencia, true);
                        $fechaHora->modify('+1 hours');
                    }
                    
                    echo " y de horas $horaInicio - $horaFin";
                }
                
                // echo "Se ha creado nuevos turno disponibles para la dependencia $id_dependencia en la fecha $fechaManiana.";
            }
        }else {
            echo "Ya existen turnos de la dependencia $id_dependencia para la fecha $fechaManiana, por lo que NO se ha creado nuevos turno para dicha fecha.";
        }
    }
    
    public function configParametros() {
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $dependenciaModel = new DependenciaModel();
//             $userInSession = $this->session->get('user');
//             if(!empty($userInSession)) {
//                 $data['id_dependencia'] = $userInSession['id_dependencia'];
//             }
            $data['dependencias'] = $dependenciaModel->findAllHabilitadoConfigTurno();
            $data['contenido'] = "config_parametro_turno";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }        
    }
    
    /**
     * Funcion que permite guardar la informacion
     */
    public function guardar() {
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $validation =  \Config\Services::validation();
            $validation->setRules([
                'id_dependencia' => ['label' => 'Dependencia', 'rules' => 'required|numeric'],
                'hora_inicio_atencion_mañana' => ['label' => 'Hora inicio atencion por la mañana', 'rules' => 'required|min_length[5]|max_length[5]'],
                'hora_fin_atencion_mañana' => ['label' => 'Hora fin atencion por la mañana', 'rules' => 'required|min_length[5]|max_length[5]'],
                'cantidad_turno_por_hora' => ['label' => 'Cantidad de turnos por hora', 'rules' => 'required|numeric'],
            ]);
            
            $data['id_turno_parametro'] = $this->request->getVar('id_turno_parametro');
            $data['id_dependencia'] = $this->request->getVar('id_dependencia');
            $data['tipo_tramite'] = $this->request->getVar('tipo_tramite');
            $data['hora_inicio_atencion_mañana'] = $this->request->getVar('hora_inicio_atencion_mañana');
            $data['hora_fin_atencion_mañana'] = $this->request->getVar('hora_fin_atencion_mañana');
            $data['hora_inicio_atencion_tarde'] = $this->request->getVar('hora_inicio_atencion_tarde');
            $data['hora_fin_atencion_tarde'] = $this->request->getVar('hora_fin_atencion_tarde');
            
            if(empty($data['hora_inicio_atencion_tarde'])) {
                $data['hora_inicio_atencion_tarde'] = null;
            }
            if(empty($data['hora_fin_atencion_tarde'])) {
                $data['hora_fin_atencion_tarde'] = null;
            }
            $data['cantidad_turno_por_hora'] = $this->request->getVar('cantidad_turno_por_hora');
            
            if ($validation->withRequest($this->request)->run()) {
                if($data['id_dependencia'] == ID_DEP_UAD_CENTRAL) {
                    list($data['id_tipo_tramite'], $data['desc_tipo_tramite']) = explode("_", $data['tipo_tramite']);
                }else {
                    $data['id_tipo_tramite'] = null;
                    $data['desc_tipo_tramite'] = null;
                }
                
                $turnoParametroModel = new TurnoParametroModel();
                if(empty($data['id_turno_parametro'])) { // insert
                    $data['usuario_alta'] = session()->get('id');
                    $data['fecha_alta'] = date('Y-m-d H:i:s');
                    $turnoParametroModel->insert($data, false);
                }else { // update
                    $data['usuario_modificacion'] = session()->get('id');
                    $data['fecha_modificacion'] = date('Y-m-d H:i:s');
                    $turnoParametroModel->update($data['id_turno_parametro'], $data);
                }
                
                $data['guardar_exito'] = true;
            }else {
                $data['guardar_exito'] = false;
            }
            
            $dependenciaModel = new DependenciaModel();
            $data['dependencias'] = $dependenciaModel->findAllHabilitado();
            $data['contenido'] = "config_parametro_turno";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function getConfigTurno($idDependencia) {
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $turnoParametroModel = new TurnoParametroModel();
            $turnoParametro = $turnoParametroModel->where('id_dependencia', $idDependencia)->first();
            echo json_encode($turnoParametro);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function getConfigTurnoPorTipoTramite($idDependencia, $idTipoTramite, $descTipoTramite) {
        if (!empty($this->userInSession) && session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA) {
            $turnoParametroModel = new TurnoParametroModel();
            $turnoParametro = $turnoParametroModel->where('id_dependencia', $idDependencia)
                                                ->where('id_tipo_tramite', $idTipoTramite)
                                                ->where('desc_tipo_tramite', $descTipoTramite)
                                                ->first();
            echo json_encode($turnoParametro);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function hayTurnoParaLaDependencia($id_dependencia) {
        $turnoCantidades = $this->getFechasDeTurnoPorDependencia($id_dependencia);
        $hayTurnos = false;
        if(!empty($turnoCantidades)) {
            $hayTurnos = true;
        }
        echo json_encode($hayTurnos);
    }
    
    public function hayTurnoParaLaDependenciaPorTramite($id_dependencia, $id_tipo_tramite, $desc_tipo_tramite) {
        $turnoCantidades = $this->getFechasDeTurnoPorDependenciaYporTramite($id_dependencia, $id_tipo_tramite, $desc_tipo_tramite);
        $hayTurnos = false;
        if(!empty($turnoCantidades)) {
            $hayTurnos = true;
        }
        echo json_encode($hayTurnos);
    }
    
    public function getHoras($fecha, $id_dependencia) {
        $turnoCantidadModel = new TurnoCantidadModel();
        $turnoHoras = $turnoCantidadModel->select('tramite_online.turno_cantidad.*')
                                         ->join('tramite_online.turno_dependencias', 'tramite_online.turno_dependencias.id_turno_cantidad = tramite_online.turno_cantidad.id_turno_cantidad')
                                        ->where('tramite_online.turno_dependencias.id_dependencia', $id_dependencia)
                                        ->where('tramite_online.turno_cantidad.fecha', $fecha)->where('cantidad >', 0)->findAll();
//         $turnoHoras = $turnoCantidadModel->where('fecha', $fecha)->where('cantidad <', 2)->findAll();
        echo json_encode($turnoHoras);
    }
    
    public function getHorasPorTipoTramite($fecha, $id_dependencia, $idTipoTramite, $descTipoTramite) {
        $turnoCantidadModel = new TurnoCantidadModel();
        $turnoHoras = $turnoCantidadModel->select('tramite_online.turno_cantidad.*')
                                        ->join('tramite_online.turno_dependencias', 'tramite_online.turno_dependencias.id_turno_cantidad = tramite_online.turno_cantidad.id_turno_cantidad')
                                        ->where('tramite_online.turno_dependencias.id_dependencia', $id_dependencia)
                                        ->where('tramite_online.turno_dependencias.id_tipo_tramite', $idTipoTramite)
                                        ->where('tramite_online.turno_dependencias.desc_tipo_tramite', $descTipoTramite)
                                        ->where('tramite_online.turno_cantidad.fecha', $fecha)->where('cantidad >', 0)->findAll();
        echo json_encode($turnoHoras);
    }
    
    public function guardarTurno() {
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'id_tramite' => ['label' => 'Id tramite', 'rules' => 'required|numeric'],
            'id_turno_cantidad' => ['label' => 'Id turno cantidad', 'rules' => 'required|numeric'],
            'fecha' => ['label' => 'Fecha', 'rules' => 'required|min_length[10]|max_length[10]'],
            'hora' => ['label' => 'Hora', 'rules' => 'required|min_length[8]|max_length[8]']
        ]);

        $data['id_tramite'] = $this->request->getVar('id_tramite');
        $data['fecha'] = $this->request->getVar('fecha');
        $data['hora'] = $this->request->getVar('hora');
        $data['fecha_alta'] = date('Y-m-d H:i:s');
        $data['id_turno_cantidad'] = $this->request->getVar('id_turno_cantidad');
        $turnoCantidad['id_turno_cantidad'] = $this->request->getVar('id_turno_cantidad');
        
        $resultado['error'] = true;
        if ($validation->withRequest($this->request)->run()) {
            $turnoModel = new TurnoModel();
            $codigo = $this->util->generateRandomString(INT_DIEZ);
            while (!empty($turnoModel->where('codigo', $codigo)->findAll())) {
                $codigo = $this->util->generateRandomString(INT_DIEZ);
            }
            $data['codigo'] = $codigo;
            $turnoModel->insert($data, false);
            
            $turnoCantidadModel = new TurnoCantidadModel();
            $turnoCantAux = $turnoCantidadModel->where('id_turno_cantidad', $turnoCantidad['id_turno_cantidad'])->first();
            $turnoCantidad['cantidad'] = $turnoCantAux['cantidad']-1;
            $turnoCantidad['fecha_modificacion'] = date('Y-m-d H:i:s');
            $turnoCantidadModel->update($turnoCantidad['id_turno_cantidad'], $turnoCantidad);
            
            $this->enviarTurnoPorEmail($data['id_tramite']);
            
            $resultado['error'] = false;
        }else {
            $resultado['mensajes'] = [];
//             $resultado['mensajes'] = ['111', '222'];
            if(!empty($validation->getError('id_tramite'))) {
                $resultado['mensajes']['id_tramite'] = $validation->getError('id_tramite');
            }
            if(!empty($validation->getError('fecha'))) {
                $resultado['mensajes']['fecha'] = $validation->getError('fecha');
            }
            if(!empty($validation->getError('hora'))) {
                $resultado['mensajes']['hora'] = $validation->getError('hora');
            }
        }
        
        echo json_encode($resultado);
    }
    
    private function enviarTurnoPorEmail($idTramite) {
        $util = new Util();
        $tramiteModel = new TramiteModel();
        $tramitePersonaModel     = new TramitePersonaModel();
        $turnoModel = new TurnoModel();
        $tramite = $tramiteModel->find($idTramite);
        $tipoTramiteModel = new TipoTramiteModel();
        $tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
        $turno = $turnoModel->where('id_tramite', $idTramite)->first();
        
        // se genera el comprobante de pago en disco
        $filePath = WRITEPATH . 'temp/';
        $date = date('dmYsiH');
        $nombreArchivo = $filePath."comprobanteTurno-".$idTramite."-".$date.".pdf";
        $this->generarComprobanteTurno($idTramite, 'F', $nombreArchivo, $turno);
        
        // envio por email del comprobante de turno
        $email = new EmailSendgrid();
        $remitente = getenv('EMAIL');
        $titular_tramite = $tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', 1)->first();
        $subject = "Comprobante de turno por Tramite de " . $tipoTramite['tipo_tramite'];
        $requisitos = null;
        
        if ( $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
           if($tramite['tipo_planilla'] == "PRIMERA_VEZ") {
             $requisitos = "<br><br>
             <div class='content'>
             <strong>Requisitos - Primera Vez</strong>
             <ul style='padding-left: 1rem;'>
             <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
             <li>Certificado de Nacimiento Actualizado, Original y Fotocopia</li>
             <li>Asistir con lapicera propia ya sea de color negra o azul</li>
             <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
             <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
             <li>Grupo Sanguineo firmado por el Médico o autoridad competente</li>
             <li>$ 1000 si es que va a realizar el pago en efectivo.</li>
         </ul></div>";
           } else if ($tramite['tipo_planilla'] == "RENOVACION"){
             $requisitos  = "<div class='content'>
             <br><br>
             <strong>Requisitos - Renovación</strong>
             <ul style='padding-left: 1rem;'>
             <!--                             <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li> -->
             <!--                             <li>Asistir con lapicera propia ya sea de color negra o azul</li> -->
             <!--                             <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li> -->
             <!--                             <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li> -->
             <!--                             <li>Horario: De acuerdo al turno otorgado por WEB</li> -->
             <!--                             <li>$ 390,00 si es que va a realizar el pago en efectivo.</li> -->
             
                                         <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
                                         <li>Asistir con lapicera propia ya sea de color negra o azul</li>
                                         <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
                                         <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
                                         <li>$ 1000 si es que va a realizar el pago en efectivo.</li>
                                     </ul></div>";
           }
        }
        $htmlValor = $util->getTemplateTurno($requisitos);
        //var_dump($htmlValor);
        $status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $nombreArchivo, $htmlValor);
        // actualizo el estado de envio de email
        if ($status == "OK") {
            $turno['comprobante_turno_enviado'] = INT_UNO;
        }else {
            log_message('error', 'Error en el envio por email del turno='.$turno['id_turno'].' de la persona documento='.$titular_tramite['documento'].', status='.$status);
            $turno['comprobante_turno_enviado'] = INT_CERO;
        }
        
        $turnoModel->update($turno['id_turno'], $turno);
    }
    
    /**
     * Funcion que permite descargar el turno
     */
    public function descargar($id_tramite) {
        $turnoModel = new TurnoModel();
        $turno = $turnoModel->where('id_tramite', $id_tramite)->first();
        $this->generarComprobanteTurno($id_tramite, 'D', null, $turno);
    }
    
    private function generarComprobanteTurno($id_tramite, $tipoOutput, $nombreArchivo, $turno) {
        $tramiteModel = new TramiteModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $data = $tramiteModel->find($id_tramite);
        $titularTramite = $tramitePersonaModel->where('id_tramite', $id_tramite)->where('es_titular_tramite', INT_UNO)->first();
        
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);
        
        $pdf->AddPage();
        $html = $pdf->get_header();
        $html = $this->get_body($html, $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $url_validacion_qr = base_url() . '/turno/validar/' . $turno['codigo'];
        
        // set style for barcode
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        
        // QRCODE,L : QR-CODE Low error correction
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 28, 28, $style, 'N');
        
        ob_end_clean();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $tipoDocumento = $tipoDocumentoModel->find($titularTramite['id_tipo_documento']);
        $pdf->SetAlpha(0.3);
        if ( $data['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
            $imgdata = base64_decode($pdf->imagenConTextoYcoordenadas($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento'], 540, 410));
        }else {
            $imgdata = base64_decode($pdf->imagenConTextoYcoordenadas($titularTramite['apellido'].' '.$titularTramite['nombre'].' - '.$tipoDocumento['tipo_documento'].': '.$titularTramite['documento'], 540, 220));
        }
        
        $pdf->Image('@'.$imgdata);
        $pdf->SetAlpha(1);
        
        if($tipoOutput=='F') {
            $pdf->Output($nombreArchivo, $tipoOutput);
        }else {
            ob_end_clean();
            $pdf->Output('turno-'.$titularTramite['documento'].'.pdf', $tipoOutput);
            ob_end_flush();
        }
    }
    
    private function get_body($html, $data) {
        $tipoTramiteModel = new TipoTramiteModel();
        $tramitePersonaModel = new TramitePersonaModel();
        $dependenciaModel = new DependenciaModel();
        $turnoModel = new TurnoModel();
        $titular_tramite = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $dependencia = $dependenciaModel->find($data['id_dependencia']);
        $tipoTramite = $tipoTramiteModel->find($data['id_tipo_tramite']);
        $turno = $turnoModel->where('id_tramite', $data['id_tramite'])->first();
        $nombreTramite = $tipoTramite['tipo_tramite'];
        $direccion = null;
        if($data['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
            $nombreTramite = $nombreTramite.' - '.$data['tipo_planilla'];
            // FIXME: La direccion debe tomarse desde base de datos.
            $direccion = "Av. Cnel. Mariano Santibañez N° 1372 - Centro - San Salvador de Jujuy";
            
        }
        
        $html = $html . '<table>
                    <tr>
                        <td width="100%" align="justify">
<div align="center">
<br/><br/>
<b>COMPROBANTE DE TURNO</b>
<br/><br/>
</div>
              
<table>
    <tr>
        <td>APELLIDO Y NOMBRE:</td>
        <td>' . $titular_tramite['apellido'] . ' ' . $titular_tramite['nombre'] . '</td>
    </tr>
    <tr>
        <td>DNI:</td>
        <td>' . $titular_tramite['documento'] . '</td>
    </tr>
    <tr>
        <td>CUIL:</td>
        <td>' . $titular_tramite['cuil'] . '</td>
    </tr>
    <tr>
        <td>TRAMITE:</td>
        <td>' . strtoupper($nombreTramite) . '</td>
    </tr>
    <tr>
        <td>LUGAR:</td>
        <td>' . $dependencia['dependencia'] . '</td>
    </tr>';
        
        if(!empty($direccion)) {
            $html .= '<tr>
                        <td>DIRECCION:</td>
                        <td>' . $direccion . '</td>
                    </tr>';
        }

$html .= '<tr>
            <td>FECHA Y HORA:</td>
        <td>' . date_format(date_create($turno['fecha']), 'd/m/Y') . ' ' . $turno['hora'] . ' hs.</td>
    </tr>
</table>

    
                        </td>
                    </tr>
                </table>';
      $requisitos = "";
      if ( $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
        if($data['tipo_planilla'] == "PRIMERA_VEZ") {
          $html .= "<br><br>
          <strong>Requisitos - Primera Vez.</strong>
          <ul style='padding-left: 1rem;'>
          <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
          <li>Certificado de Nacimiento Actualizado, Original y Fotocopia</li>
          <li>Asistir con lapicera propia ya sea de color negra o azul</li>
          <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
          <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
          <li>Grupo Sanguineo firmado por el Médico o autoridad competente</li>
          <li>$ 1000 si es que va a realizar el pago en efectivo.</li>
      </ul>";
        } else if ($data['tipo_planilla'] == "RENOVACION"){
          $html .= "
          <br><br>
          <strong>Requisitos - Renovación</strong>
          <ul style='padding-left: 1rem;'>
              <li>DNI o Tirilla DNI en Trámite Original y Fotocopia</li>
              <li>Asistir con lapicera propia ya sea de color negra o azul</li>
              <li>En caso de que el DNI tenga domicilio en otra provincia al momento de presentarse a realizar la planilla deberán asistir con certificado de residencia y con certificado de antecedentes penales emitido por RNR (Avda. Alte. Brown Nº 174)</li>
              <li>Una (1) Fotografía 4x4 actualizada color fondo celeste, sin anteojos (sin ningun elemento que cubra el rostro y cabellera)</li>
              <li>$ 1000 si es que va a realizar el pago en efectivo.</li>
          </ul>";
        }
     }

        $html = $html . '</body>';
        return $html;
    }
    
    public function validar($codigo) {
        $turnoModel = new TurnoModel();
        $turno = $turnoModel->where('codigo', $codigo)->first();
        $data['ua'] = $this->request->getUserAgent();
        
        if(empty($turno)) {
            $data['error'] = "EL TURNO ES INVALIDO";
            $data['contenido'] = "turno_validacion_qr";
            echo view("frontend", $data);
            return;
        }else {
            $tramiteModel = new TramiteModel();
            $tramite = $tramiteModel->where('id_tramite', $turno['id_tramite'])->first();
            $tipoTramiteModel = new TipoTramiteModel();
            $tramitePersonaModel = new TramitePersonaModel();
            $dependenciaModel = new DependenciaModel();
            $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
            $titular_tramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
            $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
            
            $data['apellido_nombre'] = $titular_tramite['apellido'].' '.$titular_tramite['nombre'];
            $data['documento'] = $titular_tramite['documento'];
            $data['cuil'] = $titular_tramite['cuil'];
            $data['tipo_tramite'] = $tipoTramite['tipo_tramite'];
            $data['dependencia'] = $dependencia['dependencia'];
            $data['fecha'] = $turno['fecha'];
            $data['hora'] = $turno['hora'];
            
            $data['contenido'] = "turno_validacion_qr";
            echo view("frontend", $data);
            return;
        }
    }
    
}