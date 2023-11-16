<?php
namespace App\Controllers;

use App\Libraries\Util;
use App\Models\DependenciaModel;
use App\Models\TramiteModel;
use App\Models\TurnoModel;
use App\Models\Central\PersonaCentralModel;
use App\Models\Central\ContravencionesCentralModel;
use App\Models\TipoDocumentoModel;

use App\Libraries\Pdf;

class Dap extends BaseController {
    
    protected $session;
    protected $userInSession;
    protected $util;
    protected $tipoDocumentoModel;
    protected $fiveDates;
    
    public function __construct() {
        $this->session = session();
        $this->userInSession = $this->session->get('user');
        $this->util = new Util();
        $this->tipoDocumentoModel = new TipoDocumentoModel();
        $date = date('Y/m/d');
		$this->fiveDates = date( "Y-m-d", strtotime( $date . "-5 day"));
    }

    public function index() {
        if(!empty($this->userInSession) && (session()->get('id_rol')==ROL_ANTECEDENTE || session()->get('id_rol')==ROL_CIAC)) {
            $fechaActual = date('Y-m-d');
            $nombre = "";
            $apellido = "";
            $documento = "";
            $idDependencia = "";
            $tipoPlanilla = "";
            $numeroTramite =  "";

            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['fecha_turno'])) {
                $fechaActual = $filter['fecha_turno'];
            }
            if (isset($filter['nombre']) && !empty($filter['nombre'])) {
                $nombre = $filter['nombre'];
            }
    
            if (isset($filter['apellido']) && !empty($filter['apellido'])) {
                $apellido =  $filter['apellido'];
            }
    
            if (isset($filter['documento']) && !empty($filter['documento'])) {
                $documento = $filter['documento'];
            }
            
            if (isset($filter['id_dependencia']) && !empty($filter['id_dependencia'])) {
                $idDependencia = $filter['id_dependencia'];
            }
            if (isset($filter['tipoPlanilla']) && !empty($filter['tipoPlanilla'])) {
                $tipoPlanilla = $filter['tipoPlanilla'];
            }

            if (isset($filter['numeroTramite']) && !empty($filter['numeroTramite'])) {
                $numeroTramite = $filter['numeroTramite'];
            }
            
            $this->listado($fechaActual, $idDependencia,$nombre, $apellido, $documento , $tipoPlanilla,$numeroTramite);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function buscar() {
        if(!empty($this->userInSession) && (session()->get('id_rol')==ROL_ANTECEDENTE || session()->get('id_rol')==ROL_CIAC)) {
            $fecha_turno = $this->request->getVar('fecha_turno');
            $idDependencia  = $this->request->getVar('idDependencia');
            $nombre         = $this->request->getVar('nombre');
            $apellido       = $this->request->getVar('apellido');
            $documento      = $this->request->getVar('documento');
            $tipoPlanilla   = $this->request->getVar('tipoPlanilla');
            $numeroTramite  = $this->request->getVar('numeroTramite'); 

            $filter['fecha_turno'] = $fecha_turno;
            $filter['id_dependencia'] = $idDependencia;
            $filter['nombre']         = $nombre;
            $filter['apellido']       = $apellido; 
            $filter['documento']      = $documento; 
            $filter['tipoPlanilla']    = $tipoPlanilla; 
            $filter['numeroTramite']   = $numeroTramite;

            $this->session->set('filter', $filter);
            $this->listado($fecha_turno, $idDependencia,$nombre, $apellido, $documento,$tipoPlanilla,$numeroTramite);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function listado($fechaTurno , $idDependencia ,$nombre, $apellido, $documento, $tipoPlanilla,$numeroTramite) {
        if(!empty($this->userInSession) && (session()->get('id_rol')==ROL_ANTECEDENTE || session()->get('id_rol')==ROL_CIAC)) {

                $tramite = 'tramite_online.tramites';
                $persona = 'tramite_online.tramite_personas';

            $turnoModel = new TurnoModel();
            $dependenciaModel = new DependenciaModel();         
            $turnoModel->select('distinct(tramite_online.tramite_personas.cuil), tramite_online.turnos.id_turno, tramite_online.turnos.id_tramite, tramite_online.turnos.fecha, tramite_online.turnos.hora, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite,
                                tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, tramite_online.tramites.estado,
                                tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador, tramite_online.tramites.contiene_firma_digital,
                                tramite_online.tramites.tipo_planilla, public.personas.num_prontuario, public.personas.letra_prontuario,personal.dependencias.dependencia, tramite_online.tramites.estado_verificacion,
                                tramite_online.tramite_personas.cuil, tramite_online.tramite_personas.fecha_nacimiento')
                                ->join('tramite_online.tramites', 'tramite_online.tramites.id_tramite = tramite_online.turnos.id_tramite')
                                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                ->join('public.personas', 'public.personas.cuil_ciudadano = tramite_online.tramite_personas.cuil', 'left')
                                ->join('personal.dependencias', 'personal.dependencias.id_dependencia = tramite_online.tramites.id_dependencia')           
                                ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO)
                                ->where('tramite_online.tipo_tramites.id_tipo_tramite ', TIPO_TRAMITE_PLANILLA_PRONTUARIAL);
                               // ->where('tramite_online.tramites.id_dependencia', $id_dependencia);
                                  
            if (isset($nombre) && !empty($nombre)) {
                $turnoModel->like($persona.'.nombre', $nombre);
            }

            if (isset($apellido) && !empty($apellido)) {
                $turnoModel->like($persona.'.apellido', $apellido);
            }
                    
            if (isset($documento) && !empty($documento)) {
                $turnoModel->where($persona.'.documento', $documento);
            }              
                 
            if(isset($fechaTurno) && !empty($fechaTurno)) {
                $turnoModel->where('tramite_online.turnos.fecha', $fechaTurno);
            }

            if (isset($idDependencia) && !empty($idDependencia)) {
                $turnoModel->where($tramite.'.id_dependencia', $idDependencia);
            }

            if (isset($tipoPlanilla) && !empty($tipoPlanilla)){
                $turnoModel->where($tramite.'.tipo_planilla', $tipoPlanilla);
            }

            if (isset($numeroTramite) && !empty($numeroTramite)){
                $turnoModel->where($tramite.'.id_tramite', $numeroTramite);
            }

//             $listado =  $turnoModel->orderBy('tramite_online.turnos.fecha', 'ASC');
            $listado =  $turnoModel->findAll();
//             $personaCentralModel = new PersonaCentralModel();
//             foreach ($listado as $key => $item) {
//                 if($listado[$key]['tipo_planilla']==RENOVACION && empty($listado[$key]['num_prontuario'])) {
//                     $personaCentral = $personaCentralModel->where('cuil_ciudadano', $listado[$key]['cuil'])->first();
//                     if(!empty($personaCentral)) {
//                         $listado[$key]['num_prontuario'] = $personaCentral['num_prontuario'];
//                         $listado[$key]['letra_prontuario'] = $personaCentral['letra_prontuario'];
//                     }
//                 }
//             }

            $data['listado'] = $listado;            
//             var_dump($turnoModel->getLastQuery()); return;
            $data['fecha_turno'] = $fechaTurno;
            $data['id_dependencia'] = $idDependencia;
            $data['nombre']         = $nombre;
            $data['apellido']       = $apellido; 
            $data['documento']      = $documento; 
            $data['tipoPlanilla']    = $tipoPlanilla; 
            $data['dependencias'] = $dependenciaModel->findAllHabilitadoYUadUnidadesRegionales();
            $data['tipo_planillas'] = ['PRIMERA_VEZ','RENOVACION']; 
            $data['numeroTramite'] = $numeroTramite; 
           

            $data['contenido'] = "dap_lista_turno_planilla";
            $vista = view("frontend", $data);
//             $this->session->set('pdf_turno', $vista);
            $this->session->set('pdf_listado', $listado);
            echo $vista;
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function buscarTramitePersona() {
        if(!empty($this->userInSession) && (session()->get('id_rol')==ROL_ANTECEDENTE || session()->get('id_rol')==ROL_CIAC)) {
            $fechaDesde = $this->request->getVar('fechaDesde');
            $fechaHasta  = $this->request->getVar('fechaHasta');
            $idDependencia  = $this->request->getVar('idDependencia');
            $nombre         = $this->request->getVar('nombre');
            $apellido       = $this->request->getVar('apellido');
            $documento      = $this->request->getVar('documento');
            $tipoPlanilla   = $this->request->getVar('tipoPlanilla');
            $numeroTramite  = $this->request->getVar('numeroTramite'); 

            if (isset($fechaDesde) && $fechaDesde != "") {
				$fechaDesde = $fechaDesde;
			} else {
				$fechaDesde = $this->fiveDates;
			}
			if (isset($fechaHasta) && $fechaHasta != "") {
				$fechaHasta = $fechaHasta;
			} else {
			    $ahora = time();
			    // Le decimos que ahora, + 1 día
			    $manana = strtotime("+1 day", $ahora);
			    $fechaHasta = date("Y-m-d", $manana);
			}

            $filter['id_dependencia'] = $idDependencia;
            $filter['nombre']         = $nombre;
            $filter['apellido']       = $apellido; 
            $filter['documento']      = $documento; 
            $filter['tipoPlanilla']    = $tipoPlanilla; 
            $filter['numeroTramite']   = $numeroTramite;

          
            $this->session->set('filter', $filter);
            $this->listadoPersona($fechaDesde,$fechaHasta, $idDependencia,$nombre, $apellido, $documento,$tipoPlanilla,$numeroTramite);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    private function listadoPersona($fechaDesde,$fechaHasta, $idDependencia ,$nombre, $apellido, $documento, $tipoPlanilla,$numeroTramite) {
      
        if(!empty($this->userInSession) && (session()->get('id_rol')==ROL_ANTECEDENTE || session()->get('id_rol')==ROL_CIAC)) {

            $tramite = 'tramite_online.tramites';
            $persona = 'tramite_online.tramite_personas';
            
            $tramiteModel = new TramiteModel();
            $dependenciaModel = new DependenciaModel();
            $id_dependencia = $this->session->get('id_dependencia');
            $tramiteModel->select('tramite_online.tramites.fecha_alta,
                                   tramite_online.tramites.id_tramite, 
                                   tramite_online.tipo_tramites.tipo_tramite, 
                                   tramite_online.tramites.id_tipo_tramite,
                                   tramite_online.tramites.tipo_planilla,
                                   tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.fecha_nacimiento, 
                                   tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                   tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, 
                                   tramite_online.tipo_tramites.controlador,
                                   tramite_online.tramites.contiene_firma_digital,personal.dependencias.dependencia,
                                   tramite_online.tramites.estado_verificacion,tramite_online.tramites.urgente,tramite_online.tramite_personas.cuil')       
                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                ->join('personal.dependencias', 'personal.dependencias.id_dependencia = tramite_online.tramites.id_dependencia', 'left')           
                ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO)
				->where('tramite_online.tipo_tramites.id_tipo_tramite =',TIPO_TRAMITE_PLANILLA_PRONTUARIAL);

                if (!empty($fechaDesde)) {
                    $tramiteModel->where('tramite_online.tramites.fecha_alta>=', $fechaDesde);
                }
                if (!empty($fechaHasta)) {
                    $tramiteModel->where('tramite_online.tramites.fecha_alta<=', $fechaHasta);
                }

                if (isset($nombre) && !empty($nombre)) {
                    $tramiteModel->like($persona.'.nombre', $nombre);
                }
                 
    
                if (isset($apellido) && !empty($apellido)) {
                    $tramiteModel->like($persona.'.apellido', $apellido);
                }
                        
                if (isset($documento) && !empty($documento)) {
                    $tramiteModel->where($persona.'.documento', $documento);
                }              
                     
             
                if (isset($idDependencia) && !empty($idDependencia)) {
                    $tramiteModel->where($tramite.'.id_dependencia', $idDependencia);
                }
    
                if (isset($tipoPlanilla) && !empty($tipoPlanilla)){
                    $tramiteModel->like($tramite.'.tipo_planilla', $tipoPlanilla);
                }
    
                if (isset($numeroTramite) && !empty($numeroTramite)){
                    $tramiteModel->where($tramite.'.id_tramite', $numeroTramite);
                }
    

                $listado = $tramiteModel->orderBy('tramite_online.tipo_tramites.id_tipo_tramite', 'ASC')->findAll();
//                 $personaCentralModel = new PersonaCentralModel();
//                 foreach ($listado as $key => $item) {
//                     if($listado[$key]['tipo_planilla']==RENOVACION && empty($listado[$key]['num_prontuario'])) {
//                         $personaCentral = $personaCentralModel->where('cuil_ciudadano', $listado[$key]['cuil'])->first();
//                         if(!empty($personaCentral)) {
//                             $listado[$key]['num_prontuario'] = $personaCentral['num_prontuario'];
//                             $listado[$key]['letra_prontuario'] = $personaCentral['letra_prontuario'];
//                         }
//                     }
//                 }
                
                $data['listado'] = $listado;
                //var_dump($tramiteModel->getLastQuery());
                $data['fechaDesde'] = $fechaDesde;
                $data['fechaHasta'] = $fechaHasta;
                $data['id_dependencia'] = $idDependencia;
                $data['nombre']         = $nombre;
                $data['apellido']       = $apellido; 
                $data['documento']      = $documento; 
                $data['tipoPlanilla']    = $tipoPlanilla; 
                $data['dependencias'] = $dependenciaModel->findAllHabilitadoYUadUnidadesRegionales();
                $data['tipo_planillas'] = ['PRIMERA_VEZ','RENOVACION']; 
                $data['numeroTramite'] = $numeroTramite; 
                $data['util'] = $this->util;
                $data['contenido'] = "dap_lista_persona";
            echo view("frontend", $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    function pdf_old(){
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        
        $pdf->AddPage('L');
        $listado =$this->session->get('pdf_turno');
        $pos1 = strpos($listado,'<table');
        $pos2 = strpos($listado,'</table>');
        //$cant = strlen($listado);
        //d($pos1);
        //d($pos2);
        $html = substr($listado, $pos1, ($pos2-$pos1)+8);
        //dd($html);
        $pdf->SetFont('times', '', 14);
        $pdf->writeHTML('<h1 style="text-align: center;">LISTADO DE TURNO - DAD</h1><br>', true, 0, true, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->writeHTML($html, true, 0, true, 0);
        ob_end_clean(); 
        $pdf->Output('example_006.pdf', 'I');
        ob_end_flush();
      
    }

    function pdf(){
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true, 20); // important so styles don't break
        $listado =$this->session->get('pdf_listado');        
        $pdf->SetFont('times', '', 14);
        $pdf->AddPage('P');
        $pdf->writeHTML('<h1 style="text-align: center;">LISTADO DE TURNOS</h1><br>FECHA: '.date("d-m-Y",strtotime($listado[0]['fecha'])).'<br>', true, 0, true, 0);
        $pdf->SetFont('times', '', 10);
        $html = '<table border="1" cellpadding="0" cellspacing="0">';
        $html .= '<tr align="center">';
        $html .= '<th style="width:20px;"><strong>#</strong></th>';
        $html .= '<th style="width:40px;"><strong>Hora</strong></th>';
        $html .= '<th><strong>Tipo Planilla</strong></th>';
        $html .= '<th><strong>Prontuario</strong></th>';
        $html .= '<th><strong>Documento</strong></th>';
        $html .= '<th style="width:210px;"><strong>Apellido y Nombre</strong></th>';            
        $html .= '</tr>';
        $i = 1;
        foreach($listado as $list){
            $html .= '<tr>';
            $html .= '<td align="center">'.substr($i, -3).'</td>';
            $html .= '<td align="center">'.$list['hora'].'</td>';
            $html .= '<td align="center">'.str_replace('_',' ',$list['tipo_planilla']).'</td>';
            $html .= '<td align="center">'.$list['num_prontuario'].'-'.$list['letra_prontuario'].'</td>';
            $html .= '<td align="center">'.$list['documento'].'</td>';
            $html .= '<td>&nbsp;&nbsp;'.$list['apellido'].' '.$list['nombre'].'</td>';        
            $html .= '</tr>';
            $i++;
        }
        $html .='</table>'; 
        $pdf->writeHTML($html, true, 0, true, 0);
        ob_end_clean(); 
        $pdf->Output('listado-turnos.pdf', 'I');
        ob_end_flush();
    }    
    
    function pdf2(){
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true, 73);
        
        $listado =$this->session->get('pdf_listado');
        $html = '<table border="1" cellpadding="0" cellspacing="0">';
        $i = 0;

        foreach($listado as $list){
            if($list['estado']==TRAMITE_VALIDADO) {
                if(($i % 2) == 0){
                    $html .= '<tr valign="top">';
                }
                $html .= '<td style="width: 269px; height:200px;">';
                $html .= '<table style="width:100%; font-size: 10px;">';
                $html .= '<tr>';
                $html .= '<td style="width:70px;">&nbsp;Prontuario:</td>';
                $html .= '<td style="width:180px; font-size: 15px;"><strong>'.$list['num_prontuario'].'-'.$list['letra_prontuario'].'</strong></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td>&nbsp;Apellido:</td>';
                $html .= '<td>'.$list['apellido'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td>&nbsp;Nombre:</td>';
                $html .= '<td>'.$list['nombre'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td>&nbsp;Nro. DNI.:</td>';
                $html .= '<td style="height:40px;">'.$list['documento'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td colspan="2" align="center">FECHA TURNO: '.date("d-m-Y",strtotime($list['fecha'])).' '.$list['hora'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td colspan="2" align="center">TRAMITE: '.$list['tipo_planilla'].'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td colspan="2" align="lefth">&nbsp;Nro. Cupon: '.($i+1).'</td>';
                $html .= '</tr>';
                $html .= '</table>';
                $html .= '</td>';

                if(($i % 2) != 0){
                    $html .= '</tr>';
                }
                $i++;
            }
        }
        if(($i % 2) != 0){
            $html .= '<td style="width: 269px; height:200px;">';
            $html .= '<table style="width:100%; font-size: 15px;">';
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';            
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';
            $html .= '<tr>';           
            $html .= '<td colspan="2" align="center"></td>';
            $html .= '</tr>';                                                            
            $html .= '<tr>';           
            $html .= '<td colspan="2" align="center"></td>';
            $html .= '</tr>'; 
            $html .= '<tr>';           
            $html .= '<td colspan="2" align="lefth"></td>';
            $html .= '</tr>';                                                                                                                                                                       
            $html .= '</table>';
            $html .= '</td>';

            $html .= '</tr>';
        }        
        $html .= '</table>';

        $pdf->AddPage('P');
        $pdf->SetFont('times', '', 10);
        $pdf->writeHTML($html, true, 0, true, 0);
        ob_end_clean(); 
        $pdf->Output('cupones.pdf', 'I');
        ob_end_flush();        
        //d($listado);
        //echo $html;
    }

    ######################################################################################################################################################
    ######################################################################################################################################################
    ########################### BUSCA SI UNA PERSONA TIENE DEUDA EN CONTRAVENCIONES ######################################################################

    public function buscarContravencion() {
        $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();                                     
        $data['contenido'] = "dap_consulta_contravenciones";        
        echo view("frontend", $data);
      } 

    public function verificar() {
    $validation =  \Config\Services::validation();
    //
    helper(['form', 'url']);
    $validation;
    $tipoDni = $this->request->getVar('id_tipo_documento');
    
    $validation = $this->validate([
        'id_tipo_documento'       => ['rules' => 'required'],
        'documento'               => ['rules' => 'required'],        
    ]);
    //
    if (!$validation) {
        $data['validation'] = $this->validator;
        //        
        $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
        $data['documento'] = $this->request->getVar('documento');        
        //        
        $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();                                
        //
        $data['contenido'] = "dap_consulta_contravenciones";
        echo view("frontend", $data);
        return;
    } else {//entra aqui si se valido bien los datos         
        $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
        $data['documento'] = $this->request->getVar('documento');        
        //
        $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();//trae listado de tipos de documentos
        //  
        $personaCentralModel = new PersonaCentralModel();
                        
        //$personaCentral = $personaCentralModel->where('dni', $this->request->getVar('documento'))->first();                                                        
        //$personaCentral = $personaCentralModel->findByDni($this->request->getVar('id_tipo_documento'), trim($this->request->getVar('documento')));
        $personaCentral = $personaCentralModel->findByDni(Null, trim($this->request->getVar('documento')));                                                        
        //dd($personaCentral);
        //
        if(!empty($personaCentral)) {//entra aqui si encontro el DNI
            $contravencionesCentralModel = new ContravencionesCentralModel();

            $infractor_comercio = $contravencionesCentralModel->contravencionComercial($personaCentral['cuil_ciudadano']);//Busca en la tabla contravenciones.contravenciones
            $infractor_otros = $contravencionesCentralModel->contravencionOtros($personaCentral['cuil_ciudadano']);//Busca en la tabla contravenciones.contravencion_involucrados

            //para controlar donde tiene faltas Contravencionales
            if(!empty($infractor_comercio)){ 
                $externo = $infractor_comercio[0]->nombre;                
            }
            if(!empty($infractor_otros)){
                $externo = $infractor_otros[0]->nombre; 
            }
            //dd($infractor_otros);
            if(!empty($infractor_comercio) || !empty($infractor_otros)){            
                    $data['status'] = "ERROR";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' registra CONTRAVENCION pendiente. Pase a Regularizar su Situacion en ' . $externo;
                }else{
                    $data['status'] = "EXITO";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' NO registra CONTRAVENCION pendiente';
                }
             
            /* if($this->request->getVar('tipo_contravencion') == 1){//si es contravencion comercial, busca en la tabla contravenciones.contravenciones                
                $infractor_comercio = $contravencionesCentralModel->contravencionComercial($personaCentral['cuil_ciudadano']);
                if(!empty($infractor_comercio)){
                    $data['status'] = "ERROR";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' registra deuda pendiente';
                }else{
                    $data['status'] = "EXITO";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' NO registra deuda pendiente';
                }
            }else{//si es contravencion otros, busca en la tabla contravenciones.contravencion_involucrados
                $infractor_otros = $contravencionesCentralModel->contravencionOtros($personaCentral['cuil_ciudadano']);
                //dd($infractor_otros);
                if(!empty($infractor_otros)){
                    $data['status'] = "ERROR";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' registra deuda pendiente';
                }else{
                    $data['status'] = "EXITO";
                    $data['message'] = 'El Sr/Sra ' . $personaCentral['apellido'] . ' ' . $personaCentral['nombre'] .' con DNI ' . $personaCentral['dni'] . ' NO registra deuda pendiente';
                }
            } */

       }else{//si no encontro el dni
          $data['status'] = "ERROR";
          $data['message'] = 'El DNI ingresado, no se encuentra registrado en nuestra Base de Datos';
        }
        //
        $data['contenido'] ="dap_consulta_contravenciones";
        echo view("frontend", $data);       
    }
}

}