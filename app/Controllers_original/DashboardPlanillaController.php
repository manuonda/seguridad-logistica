<?php
namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Models\TramitePersonaModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\MovimientoPago;
use App\Models\TipoPagoModel;
use App\Models\DependenciaModel;
use App\Libraries\PagoBancoMacro;
use App\Libraries\UtilBancoMacro;
use App\Libraries\EmailSendgrid;

class DashboardPlanillaController extends BaseController
{
	protected $tramiteModel;
	protected $tipoTramiteModel;
	protected $tramitePersonaModel;
	protected $dependenciaModel;
	protected $session;
	protected $pager;
	protected $movimimentoPago;
	protected $tipoPagoModel;
	protected $tramiteController;
	protected $pagoBancoMacro;
	protected $utilBancoMacro;

	public function __construct()
	{
		$this->tramiteModel = new TramiteModel();
		$this->tipoTramiteModel = new TipoTramiteModel();
		$this->tramitePersonaModel = new TramitePersonaModel();
		$this->session = session();
		$this->movimimentoPago = new MovimientoPago();
		$this->pager = \Config\Services::pager();
		$this->tipoPagoModel = new TipoPagoModel();
		$this->tramiteController = new Tramite();
		$this->pagoBancoMacro = new PagoBancoMacro();
		$this->utilBancoMacro =  new UtilBancoMacro();
		$this->dependenciaModel = new DependenciaModel();
	}

	public function index($filter = null)
	{

		if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA) {
			$filter= $this->session->get('filter');
			
	        if ($filter == null) {
	            $filter['idTramite'] = null;
	            $filter['cuil'] = null;
	            $filter['nombre'] = null;
	            $filter['apellido'] = null;
	            $filter['idTipoPago'] = null;
				$filter['idTipoTramite'] = null;
				$filter['fechaDesde'] = null;
				$filter['fechaHasta'] = null;
				$filter['idDependencia'] = null;
				$filter['estadoPago'] = null;
				$filter['estadoTramite'] = null;
	        }
	        $nombre     = $this->request->getVar('nombre');
	        $apellido   = $this->request->getVar('apellido');
	        $cuil       = $this->request->getVar('cuil');
	        $idTramite  = $this->request->getVar('idTramite');
	        $idTipoPago   = $this->request->getVar('idTipoPago');
	        $idTipoTramite = $this->request->getVar('idTipoTramite');
			$fechaDesde  = $this->request->getVar('fechaDesde');
			$fechaHasta  = $this->request->getVar('fechaHasta');
			$idDependencia  = $this->request->getVar('idDependencia');
			$estadoPago     = $this->request->getvar('estadoPago');
			$estadoTramite  = $this->request->getVar('estadoTramite');

	        if (isset($nombre)) {
	            $filter['nombre']   = $nombre;
	        }
	        if (isset($apellido)) {
	            $filter['apellido'] = $apellido;
	        }
	        if (isset($cuil)) {
	            $filter['cuil']     = $cuil;
	        }
	        if (isset($idTramite)) {
	            $filter['idTramite'] = $idTramite;
	        }
	        if (isset($idTipoPago)) {
	            $filter['idTipoPago'] = $idTipoPago;
	        }
	        if (isset($idTipoTramite)) {
	            $filter['idTipoTramite'] = $idTipoTramite;
			}
			if (isset($fechaDesde)) {
				$filter['fechaDesde'] = $fechaDesde;
			}
			if (isset($fechaHasta)) {
				$filter['fechaHasta'] = $fechaHasta;
			}
			if ( isset($idDependencia)) {
				$filter['idDependencia'] = $idDependencia;
			}
			if (isset($estadoPago)) {
				$filter['estadoPago'] = $estadoPago;
			}
			if (isset($estadoTramite)) {
				$filter['estadoTramite'] =$estadoTramite;
			}

			if ( isset($estadoPago)) {
				$filter['estadoPago'] = $estadoPago;
			}
			
			$data['dependencias'] = $this->dependenciaModel->findAllHabilitado();
	        $data['tipoPagos'] = $this->tipoPagoModel->findAll();
			$data['tipoTramites'] = $this->tipoTramiteModel->findAll();
			$data['estadoPagos']  = $this->getSelectEstadosPago();
			$data['estadoTramites'] = $this->getSelectEstadosTramite();
			
	        // 		$data['titulo'] = "Infracciones / Viales";
	        $data['filter'] = $filter;
	        $data['contenido'] = "dashboard/index";
	        // 		echo view("backend", $data);
	        echo view("frontend", $data);
	    }else {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
	}

	/*
	* Funcion que permite realizar 
	* la limpieza de los filtros
   **/
	public function limpiar()
	{
		session()->set('filter', null);
		$this->index();
	}

	/**Funcion que permite obtener los 
	 * datos a filtrar de la busqueda mediante 
	 * post
	 * @param : post, parameters
	 */
	public function buscar()
	{

		$filter['idTramite'] = null;
		$filter['cuil'] = null;
		$filter['nombre'] = null;
		$filter['apellido'] = null;
		$filter['idTipoPago'] = null;
		$filter['idTipoTramite'] = null;
		$filter['idTramite'] = $_POST['idTramite'];
		$filter['cuil']         = $_POST['cuil'];
		$filter['nombre']       = $_POST['nombre'];
		$filter['apellido']     = $_POST['apellido'];
		$filter['idTipoTramite'] = $_POST['idTipoTramite'];
		$filter['idTipoPago']     = $_POST['idTipoPago'];
		$filter['fechaDesde']     = $_POST['fechaDesde'];
		$filter['fechaHasta']     = $_POST['fechaHasta'];
		$filter['idDependencia']     = $_POST['idDependencia'];
		$filter['estadoPago']    =  $_POST['estadoPago'];
		$filter['estadoTramite'] =  $_POST['estadoTramite'];
		
		
		session()->set('filter', $filter);
		$this->index($filter);
	}


	/**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de infracciones
	 **/
	public function pagination()
	{
		//$config = $this->get_configuration(); 
		$filter = session()->get('filter');

		//filter vial 
		if ($filter == null) {
			$filter['idTramite'] = null;
			$filter['cuil'] = null;
			$filter['nombre'] = null;
			$filter['apellido'] = null;
			$filter['idTipoPago'] = null;
			$filter['idTipoTramite'] = null;
			$filter['fechaDesde'] = null;
			$filter['fechaHasta'] = null;
			$filter['idDependencia'] = null;
			$filter['estadoPago'] = null;
			$filter['estadoTramite'] = null;
		}

		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $this->tramiteModel->search($filter, $rowperpage);
		$tramites = [];
		foreach ($rows as $tramite) {
			$tramites[] = $this->get_format_row($tramite);
		}
		// obtengo la cantidad sin filtrar para realizar la pagintation
		$cantidad = sizeof($this->tramiteModel->get_cantidad_rows($filter));
// 		var_dump($cantidad);

		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['page'] = $page;

		echo json_encode($data);
		return;
	}

	/**
	 * Funcion que permite establecer el format_row
	 */
	private function get_format_row($tramite)
	{
		$estado = "";
		if ($tramite['estado'] == TRAMITE_APROBADO) {
			$estado = '<span class="badge badge-success"><h8>APROBADO</h8></span>';
		}else if($tramite['estado'] == TRAMITE_VALIDADO) {
			$estado = '<span class="badge badge-success"><h8>VALIDADO</h8></span>';
		} else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
			$estado =  '<strong><span class="badge badge-secondary"><h8>PENDIENTE VALIDACION</h8></span></strong>';
		} else if ($tramite['estado'] == TRAMITE_INVALIDADO) {
			$estado = '<span class="badge badge-danger"><h8>INVALIDADO</h8></span>';
		}

		if ($tramite['estado_pago'] == APROBADO) {
			$pago = '<span class="badge badge-primary"><h8>APROBADO</h8></span>';
		} else if ($tramite['estado_pago'] == PENDIENTE || empty($tramite['estado_pago'])) {
			$pago = '<span class="badge badge-secondary"><h8>PENDIENTE</h8></span>';
		} else if ($tramite['estado_pago'] == CANCELADO) {
			$pago = '<span class="badge badge-danger"><h8>CANCELADO</h8></span>';
		} else {
			$pago = '<span class="badge badge-success"><h8>' . $tramite['estado_pago'] . '</h8></span>';
		}

		// Referencia de pago
		$referencia_pago = "";
		if ($tramite['referencia_pago'] == BANCO_MACRO) {
			$referencia_pago = '<span class="badge badge-primary">BANCO MACRO</span>';
		} else if ($tramite['referencia_pago'] == MERCADO_PAGO) {
			$referencia_pago = '<span  class="badge badge-secondary">MERCADO PAGO</span>';
		} else if ($tramite['referencia_pago'] == COMISARIA_PAGO) {
			$referencia_pago = '<span  class="badge badge-warning"> COMISARIA PAGO</span>';
		} else if ($tramite['referencia_pago'] == "") {
			$referencia_pago = "NO TIENE REFERENCIA";
		}

		$row = '<tr>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] . '</td>' .
			'<td>' . $tramite['fecha_alta'] . '</td>' .
			'<td>' . $tramite['nombreTipoPago'] . '</td>' .
			'<td>' . $referencia_pago . '</td>' .
			'<td>' . $tramite['cuil'] .$tramite['estado_envio_email'].'</td>' .
			'<td>' . $tramite['nombre'] . ',' . $tramite['apellido'] . '</td>' .
			'<td>' . $estado . '</td>' .
			'<td>' . $pago . '</td>' .
			'<td width="100">' .
			'<div class="text-center">' .
			'<span><a href="' . base_url() . '/' . $tramite['controlador'] . '/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		    if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO || empty($tramite['estado_pago'])) {
		    	$row  = $row . '<a style="cursor:pointer" title="Cobrar Pago223" onclick="module_pago.mostrarPago(' . $tramite['id_tramite'] . ')">' .
				'<span class="oi oi-dollar" style="color:red"></span>' .
				'</a>';
				
		     }
	    $row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarComprobante('. $tramite['id_tramite'] .',"'.$tramite['controlador'].'")>' .
		                   '<span class="oi oi-print" style="color:blue"></span>' .
						   '</a>';
		$color = "green";
		if ($tramite['estado_envio_email']) {
			$color = "blue";
		}
						    
		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmail('. $tramite['id_tramite'] .',"'.$tramite['controlador'].'")>' .
		                   '<span class="oi oi-envelope-closed" style="color:'.$color.'"></span>' .
						   '</a>';
					
	
		// upload firma digital				   
		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Subir firma digital" onclick=module_util.mostrarModalFirmaDigital('. $tramite['id_tramite'] .',"'.$tramite['controlador'].'")>' .
		'<span class="oi oi-data-transfer-upload" style="grey"></span>' .
		'</a>';
						   				   
		$row = $row . '</div></td></tr>';

		return $row;
	}




	/**
	 * Funcion que permite obtener el estado actual 
	 * del pago de tramite
	 * @param id: idTramite
	 */
	public function get_pago_tramite($idTramite)
	{
		$tramite = $this->tramiteModel->find($idTramite);
		$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$data['tipo_tramite'] = $tipoTramite;

		if ($tramite != null) {
			if ($tramite['referencia_pago'] == BANCO_MACRO) {
			   $this->tramiteReferenciaBancoMacro($idTramite, $tramite);	 
			} else if ($tramite['referencia_pago'] == MERCADO_PAGO) {
				$this->tramiteReferenciaMercadoPago($idTramite, $tramite);
			} else if ($tramite['referencia_pago'] == "" || $tramite['referencia_pago'] == NULL ) {
			
				// Retornar para realizar el pago en comisaria
				$data['status'] ='PENDIENTE';                                                                                                   ;
				$data['referencia'] = BANCO_MACRO;
				$data['message'] = 'Realizar el pago en comisaria';
				$data['movimientos'] = [];
				$data['estado_pago']  = $this->utilBancoMacro->getFormatStatus($tramite['estado_pago']);
				$data['fecha'] =   "";
				$data['fecha_pago']  = "";
				$data['estado_pago_entidad'] = "";
				$data['tramite'] = $tramite;
		        $data['tipo_tramite'] = $tipoTramite;
				echo json_encode($data);
			    return;
			}
		} else {
			$data['status'] = 'ERROR_NO_EXISTE_TRAMITE';
			$data['message'] = 'No Existe tramite';
			$data['movimientos'] = [];
			$data['estado_pago']  = "-------------";
			$data['fecha_pago'] = "-------------";
			echo json_encode($data);
			return;
		}
	}


	/**
	 * Funcion que permite establecer la referencia del tramite por 
	 * BANCO_MACRO
	 */
	protected function tramiteReferenciaBancoMacro($idTramite) {
		
		$tramiteModel = new TramiteModel();
		$utilBancoMacro = new UtilBancoMacro();
		$tipoTramiteModel = new TipoTramiteModel();
		$tramite = $tramiteModel->find($idTramite);
		$tipoTramite = "";
		if ($tramite['id_tipo_tramite'] != "") {
			$tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
		}
		// transaction del banco macro
		$transaction = $this->pagoBancoMacro->get($idTramite);
		$estado_pago_entidad = "";
		$mensage_estado_pago_entidad ="";
		$actualizar = false;
		if ($transaction && $transaction != null ) {
             if($transaction->status) {
				$estado_pago_entidad = $transaction->data->estado;
				$mensage_estado_pago_entidad =$transaction->message; 
				$data['status'] = $this->utilBancoMacro->getStatus($transaction->data->estado);                                                                                                   ;
				$data['referencia'] = BANCO_MACRO;
				$data['message'] = $transaction->message;
				$data['movimientos'] = [];
				$data['estado_pago']  = $this->utilBancoMacro->getFormatStatus($transaction->data->estado);
				$data['fecha'] =  $transaction->data->fecha;
				$data['fecha_pago']  = $transaction->data->fechaPago;
				$data['estado_pago_entidad'] = $transaction->data->estado;
				$data['importe_contado'] = $tipoTramite['precio'];
				$data['rendido'] = 0;
			    $actualizar = true; 
			} else {
				$estado_pago_entidad = 'ERROR';
				$mensage_estado_pago_entidad = $transaction->message;
				$data['status'] = 'ERROR';
				$data['message'] = $transaction->message;
				$data['movimientos'] = [];
				$data['estado_pago']  = "-------------";
				$data['fecha_pago'] = "-------------";
				$actualizar =true;     
			 }
		} else {
			$estado_pago_entidad = 'ERROR';
			$mensage_estado_pago_entidad = 'No se pudo obtener la informacion del Banco Macro';
			$data['status'] = 'ERROR';
			$data['message'] = 'No se pudo obtener la informacion del Banco Macro';
			$data['movimientos'] = [];
			$data['estado_pago']  = "-------------";
			$data['fecha_pago'] = "-------------";
		}

		$data['tramite'] = $tramite;
		$data['tipo_tramite'] = $tipoTramite;

		// actualizo el tramite
		if ( $actualizar ) {
			$tramite['estado_pago'] = $data["status"];
			$tramite['estado_pago_entidad'] = $estado_pago_entidad;
			$tramite['mensaje_estado_pago_entidad'] = $mensage_estado_pago_entidad;
			$tramiteModel->set($tramite);
			$tramiteModel->where('id_tramite', $idTramite);
			$tramiteModel->update();
			 
		}
		echo json_encode($data);
		return;
 
	}

	
	/**
	 *  Funcion que permite establecer la referencia del tramite por 
	 *  MERCADO_PAGO
	 */
	protected function tramiteReferenciaMercadoPago($idTramite, $tramite)
	{
		$pagoMP = new PagoMercadoPago();
		$movimientos = $this->movimimentoPago->findByIdTramite($tramite['id_tramite']);
		$existeMovimientos =  $movimientos && sizeof($movimientos) > 0;
		if ($existeMovimientos) {
			// obtengo  el utlimo movimiento 
			$ultimoMovimiento = $movimientos[sizeof($movimientos) - 1];
			if ($ultimoMovimiento->collection_status == MP_APPROVED) {
				$data['status'] = 'OK';
				$data['message'] = '';
				$data['movimiento']  = $ultimoMovimiento;
				$data['movimientos'] = $movimientos;
				$data['tramite']     = $tramite;
				echo json_encode($data);
				return;
			} else if ($tramite['id_tipo_pago'] == TIPO_PAGO_ONLINE) {
				$pago = $pagoMP->movimientoMP($ultimoMovimiento->id);
				if ($pago == null) {
					$data['status'] = "ERROR";
					$data['message'] = "No se encontro ningun pago referido con el tramite";
					$data['movimientos'] = $movimientos;
					$tramite['estado_pago'] = ESTADO_PAGO_PENDIENTE;
					$tramite['fecha_pago'] = "----------";
					$data['tramite'] = $tramite;
					echo json_encode($data);
					return;
				} else {
					// Actualizo el estado actual del tramite con el pago de MP
					$status = "OK";
					// actualizo el estado del pago en la tabla Movimiento Pago
					$this->actualizarResultadoMovimientoMP($pago, $ultimoMovimiento->id);
					// actualizar tramite 
					$pago['estado_pago'] = $this->getMessageStatus($pago["status"]);
					$this->actualizarTramitePago($pago, $idTramite);
					$data['status'] = 'OK';

					$movimientos = $this->movimimentoPago->findByIdTramite($tramite['id_tramite']);
					$data['movimientos'] = $movimientos;
					$ultimoMovimiento = $movimientos[sizeof($movimientos) - 1];
					if ($ultimoMovimiento->collection_status != MP_APPROVED) {
						$data['status'] = 'ERROR';
						$data['message'] = 'Pago de Mercado Pago estado : ' . $this->getMessageStatus($ultimoMovimiento->collection_status);
					}
					$tramite = $this->tramiteModel->find($idTramite);
					$data['tramite'] = $tramite;
					echo json_encode($data);
					return;
				}
				// PAGO DE CONTADO EN COMISARIA 
			} else if ($tramite['id_tipo_pago'] == TIPO_PAGO_CONTADO) {
				// No existe ningun movimiento para el tramite
				$data['status'] = 'ERROR';
				$data['message'] = 'No Existen movimientos para el tramite';
				$data['movimientos'] = [];
				$data['fecha_pago'] = "-------------";
				// actualizo el tramite 
				$tramite['estado_pago'] = ESTADO_PAGO_PENDIENTE;
				$this->tramiteModel->update($idTramite, $tramite);
				$tramite = $this->tramiteModel->find($idTramite);
				$data['tramite'] = $tramite;
				echo json_encode($data);
				return;
			}
		} else {
			// No existe ningun movimiento para el tramite
			$data['status'] = 'ERROR';
			$data['message'] = 'No existe movimientos.';
			$data['movimientos'] = [];
			$data['fecha_pago'] = "-------------";
			// actualizo el tramite 
			$tramite['estado_pago'] = ESTADO_PAGO_PENDIENTE;
			$this->tramiteModel->update($idTramite, $tramite);
			$tramite = $this->tramiteModel->find($idTramite);
			$data['tramite'] = $tramite;
			echo json_encode($data);
			return;
		}
	}

	/**
	 * Funcion que permite realizar el pago en la comisaria
	 * COMSIARIA
	 * @author : dgarcia
	 * @version 1.0
	 * 
	 */
	public function pago_tramite_comisaria($idTramite)
	{

		$movimientoPagoModel =  new MovimientoPago();
		$tramite = $this->tramiteModel->find($idTramite);
		$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$tramite['estado_pago'] = APROBADO;
		$tramite['id_tipo_pago'] = TIPO_PAGO_CONTADO;
		$tramite['fecha_modificacion'] = date('Y-m-d H:i:s');
		$tramite['usuario_modificacion'] = session()->get('id');

		$this->tramiteModel->update($idTramite, $tramite);
		// creo un movimiento 
		$movimientoPago['id_tipo_pago'] = TIPO_PAGO_CONTADO;
		$movimientoPago['site_id'] = SITE_POLICIA;
		$movimientoPago['collection_status'] = MP_APPROVED;
		$movimientoPago['id_tramite'] = $idTramite;
		$movimientoPago['monto_recibido'] = $tipoTramite['precio'];
		$movimientoPagoModel->insert($movimientoPago);

		$data['tramite'] = $tramite;
		echo json_encode($data);
		return;
	}


	//--------------------------------------------------------------------


	/**
	 * Funcion que permite realizar el envio de email 
	 * al titular del tramite
	 */
	public function sendEmail($idTramite) {
	  $email = new EmailSendgrid();
	  $certificadoResidencia = new CertificadoResidencia();
	  $certificadoResidenciaConvivencia = new CertificadoResidenciaConvivencia(); 
	  $certificadoSupervivencia = new CertificadoSupervivencia();
	  $constanciaPorExtravio = new ConstanciaPorExtravio();
	  $constanciaDenuncia = new ConstanciaDenuncia();
	  $planillaProntuarial = new PlanillaProntuarial();
	  $certificadoConvivencia = new CertificadoConvivencia();

	  $tramiteModel = new TramiteModel();
	  $tipoTramiteModel = new TipoTramiteModel(); 
	  $remitente = getenv('EMAIL');
	  $tramite = $tramiteModel->find($idTramite);
	  $titular_tramite = $this->tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', 1)->first();
	  $tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
	  $subject= "Nro. Tramite : ".$tramite['id_tramite'].' Tipo Tramite : '.$tipoTramite['tipo_tramite'];
	  
	  $controlador = $tipoTramite['controlador'];
	  switch ($controlador) {
		  case "certificadoResidencia": $certificadoResidencia->guardarFileDisk($idTramite);  break;
		  case "certificadoResidenciaConvivencia": $certificadoResidenciaConvivencia->guardarFileDisk($idTramite); break;
		  case "certificadoSupervivencia": $certificadoSupervivencia->guardarFileDisk($idTramite); break;
		  case "constanciaPorExtravioDni" : $constanciaPorExtravioDni->guardarFileDisk($idTramite);break;
		  case "constanciaPorExtravio" : $constanciaPorExtravio->guardarFileDisk($idTramite); break;
		  case "constanciaDenuncia"    : $constanciaDenuncia->guardarFileDisk($idTramite);break;
		  // case "planillaProntuarial" ??? : $planillaProntuarial->guardarFileDisk($idTramite); break;
		  case "certificadoConvivencia" : $certificadoConvivencia->guardarFileDisk($idTramite);break;
	  }
	  
	  $filePath = getenv('FILE_DISK').'tramite'.$idTramite.".pdf";
	  $status = $email->sendEmail($remitente, $titular_tramite['email'],$subject ,$filePath,"Se envio un comporbante del Titular del Tramite"); 
	   // actualizo el estado de envio de email 
	  if ( $status == "OK") {
		  $tramite['estado_envio_email'] = true;
		  $tramiteModel->update($idTramite, $tramite);
	  }
	  $data = [
		  "status" => $status
	  ];
	  echo json_encode($data);
	  return;
	}


	/**
	 * 
	 */
	public function uploadFirmaDigital(){
        $archivo = $this->request->getFile('file_tramite');
		$idTramite = $this->request->getVar('id_tramite');
// 		var_dump($archivo);
// 		var_dump($idTramite);

		if ($archivo->isValid()) {
            $prefijo_nombre = $idTramite . '_' . date("Ymd_His");
            $nombre = $prefijo_nombre . '_' . $archivo->getName();

			$year = date("Y");
			$month = date("m");
			$status = "";
			$message = "";
	   
		   $base_path = WRITEPATH."/".ARCHIVOS_CERTIFICADOS ."/". $year . "/" . $month ;
		   if (!file_exists($base_path)) {
			   mkdir($base_path, 0777, true);
		   }

            
            $archivo->move($base_path, $nombre);
            //         $img->move(WRITEPATH);

            // $tramiteArchivo = [
            //     'id_tramite' =>  $id_tramite,
            //     'prefijo_nombre' => $prefijo_nombre,
            //     'nombre' => $nombre,
            //     'ruta'  => $ruta,
            //     'tipo'  => $archivo->getClientMimeType()
            // ];

            // $tramiteArchivoModel = new TramiteArchivoModel();
            // $tramiteArchivoModel->insert($tramiteArchivo);
            //             print_r('File has successfully uploaded');
        }
	}

}
