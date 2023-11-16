<?php
namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Models\TramitePersonaModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\MovimientoPago;
use App\Models\TipoPagoModel;
use App\Models\DependenciaModel;
use App\Models\CategoriaRebaModel;
use App\Models\TramiteRebaModel;
use App\Models\TramiteArchivoFirmaDigitalModel;
use App\Libraries\PagoBancoMacro;
use App\Libraries\UtilBancoMacro;
use App\Libraries\EmailSendgrid;
use App\Libraries\Util;
use Exception;
use ZipArchive;

ini_set('max_execution_time', 300);
class Dashboard extends BaseController
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
	protected $tramiteArchivoFirmaDigitalModel;
    protected $fiveDates;

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
		$this->tramiteArchivoFirmaDigitalModel = new TramiteArchivoFirmaDigitalModel();
		$date = date('Y/m/d');
		$this->fiveDates = date( "Y-m-d", strtotime( $date . "0 day"));
		

		if (session()->get('isLoggedIn') == NULL) {
			//return redirect()->to('/caducado');
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	public function index($filter = null)
	{

		if (session()->get('isLoggedIn') == NULL) {
			//return redirect()->to('/caducado');
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol') == ROL_JEFE_UNIDAD_ADMINISTRATIVA
			|| session()->get('id_rol') == ROL_JEFE_DAP) || session()->get('id_rol') == ROL_UAD_REBA_CENTRAL||session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5) {

			$id_rol = session()->get('id_rol');
			$filter = $this->session->get('filter');

			if ($filter == null) {
				$filter['idTramite'] = null;
				$filter['cuil'] = null;
				$filter['nombre'] = null;
				$filter['apellido'] = null;
				$filter['idTipoPago'] = null;
				$filter['idTipoTramite'] = null;
				$filter['fechaDesde'] = $this->fiveDates;
				$filter['fechaHasta'] = date('Y/m/d');
				$filter['idDependencia'] = null;
				$filter['estadoPago'] = null;
				$filter['estadoTramite'] = null;
				$filter['documento'] = null;
				$filter['estado_verificacion'] = null;
				$filter['disabled'] = null;
			}

			
			$values = array();
			$planillaProntuarial =  $this->tipoTramiteModel->findByByNombreControlador("planillaProntuarial");

			if ($id_rol == ROL_JEFE_DAP && $planillaProntuarial != null) {
				$planilla = $planillaProntuarial[0];
				$values =  array($planilla->id_tipo_tramite);
				$filter['idTipoTramite'] = $values;
				$data['tipoTramites'] =  $this->tipoTramiteModel->where('habilitado', 't')->findAll();
			} else if ($id_rol == ROL_JEFE_UNIDAD_ADMINISTRATIVA) {
				$tipoTramites = $this->tipoTramiteModel->where('habilitado', 't')->findAll();
				$newTipoTramites = array();
				foreach ($tipoTramites as $tipoTramite) {
					if ($tipoTramite['id_tipo_tramite'] != $planillaProntuarial[0]->id_tipo_tramite) {
						$newTipoTramites[] = $tipoTramite;
					}
				}
				$data['tipoTramites'] = $newTipoTramites;
			} else if ($id_rol == ROL_UAD_REBA_CENTRAL) {
				$reba =  $this->tipoTramiteModel->findByIdTipoTramite(TIPO_TRAMITE_PAGO_REBA);
				$rebita = $reba[0];
				$values =  array($rebita->id_tipo_tramite);
				$filter['idTipoTramite'] = $values;
				$data['tipoTramites'] =  $this->tipoTramiteModel->where('habilitado', 't')->findAll();
			} else {
				$data['tipoTramites'] = $this->tipoTramiteModel->where('habilitado', 't')->findAll();;
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
			$documento      = $this->request->getVar('documento');
			$estado_verificacion =$this->request->getVar('estado_verificacion');
			$disabled = "";
			// filtro por la dependencia 
			if (session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5) {
                $idDependencia = session()->get('id_dependencia');
				$disabled = "disabled";
			} 

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
			if (isset($idTipoTramite) &&  isset($filter['idTipoTramite'])) {
				$filter['idTipoTramite'] = $idTipoTramite;
			}

			if (isset($fechaDesde) && $fechaDesde != "") {
				$filter['fechaDesde'] = $fechaDesde;
			} else {
				$filter['fechaDesde'] = $this->fiveDates;
			}
			if (isset($fechaHasta) && $fechaHasta != "") {
				$filter['fechaHasta'] = $fechaHasta;
			} else {
				$filter['fechaHasta'] =  date('Y/m/d');
			}

			if (isset($idDependencia)) {
				$filter['idDependencia'] = $idDependencia;
			}
			if (isset($estadoPago)) {
				$filter['estadoPago'] = $estadoPago;
			}
			if (isset($estadoTramite)) {
				$filter['estadoTramite'] = $estadoTramite;
			}

			if (isset($estadoPago)) {
				$filter['estadoPago'] = $estadoPago;
			}

			if (isset($documento)) {
				$filter['documento'] = $documento;
			}

			if (isset($estado_verificacion)) {
				$filter['estado_verificacion'] =$estado_verificacion;
			}

		
			session()->set('filter', $filter);

			// 			$data['dependencias'] = $this->dependenciaModel->findAllHabilitado();
			$data['dependencias'] = $this->dependenciaModel->findAllHabilitadoYUadUnidadesRegionales();
			$data['tipoPagos'] = $this->tipoPagoModel->findAll();

			$data['estadoPagos']  = $this->getSelectEstadosPago();
			$data['estadoTramites'] = $this->getSelectEstadosTramite();
			$data['rol'] = $id_rol;
			$data['disabled'] = $disabled;
			// 		$data['titulo'] = "Infracciones / Viales";
			$data['filter'] = $filter;
			$data['contenido'] = "dashboard/index";
			// 		echo view("backend", $data);
			echo view("frontend", $data);
		} else {
			return redirect()->to(base_url());
			//throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	/*
	* Funcion que se utiliza
	* para listar los tramites para verificar domicilio
	*/
	public function listado_verificacion_domicilio()
	{
	    if (!empty(session()->get('id_rol'))) {
    		$id_rol = session()->get('id_rol');
    		$data['rol'] = $id_rol;
    		$data['contenido'] = "dashboard/listado_verificacion_domicilio";
    		echo view("frontend", $data);
		} else {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	/*
	* Funcion que se utiliza
	* para listar los tramites para verificar domicilio
	*/
	public function listado_verificacion_domicilio_comisaria()
	{
		if (!empty(session()->get('id_rol'))) {
    		$id_rol = session()->get('id_rol');
    		$data['rol'] = $id_rol;
    		$data['contenido'] = "dashboard/listado_verificacion_domicilio_comisaria";
    		echo view("frontend", $data);
		} else {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	/*
	* Funcion que permite realizar 
	* la limpieza de los filtros
   **/
	public function limpiar()
	{
	    if (!empty(session()->get('id_rol'))) {
    		session()->set('filter', null);
    		$this->index();
	    } else {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
	}

	/**Funcion que permite obtener los 
	 * datos a filtrar de la busqueda mediante 
	 * post
	 * @param : post, parameters
	 */
	public function buscar()
	{
	// {   $date = date('Y/m/d');
	// 	$fiveDates = date( "Y-m-d", strtotime( $date . "-5 day"));
		if (session()->get('isLoggedIn') == NULL) {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

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
		$filter['idTipoTramite'] = isset($_POST['idTipoTramite']) ? $_POST['idTipoTramite'] : null;
		$filter['idTipoPago']     = $_POST['idTipoPago'];
		$filter['estado_verificacion'] =$_POST['estado_verificacion'];

		if (isset($_POST['fechaDesde']) && $_POST['fechaDesde'] != "") {
			$filter['fechaDesde']     = $_POST['fechaDesde'];	
		} else {
			$filter['fechaDesde'] = $this->fiveDates;
		}
		if ( isset($_POST['fechaHasta']) && $_POST['fechaHasta'] != "") {
	 	  $filter['fechaHasta']     = $_POST['fechaHasta'];
		} else {
          $filter['fechaHasta'] = date('Y/m/d');
		}
		if(isset($_POST['idDependencia'])) {
		    $filter['idDependencia'] = $_POST['idDependencia'];
		}else {
		    $filter['idDependencia'] = session()->get('id_dependencia');
		}

		$filter['estadoPago']    =  $_POST['estadoPago'];
		$filter['estadoTramite'] =  $_POST['estadoTramite'];
		$filter['documento'] =  $_POST['documento'];

		session()->set('filter', $filter);
		$this->index($filter);
	}


	/**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de infracciones
	 **/
	public function pagination()
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
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
			$filter['fechaDesde'] = $this->fiveDates;
			$filter['fechaHasta'] = date('Y/m/d');
			$filter['idDependencia'] = null;
			$filter['estadoPago'] = null;
			$filter['estadoTramite'] = null;
			$filter['documento'] = null;
			$filter['estado_verificacion'] = null;
		} else {
			if ($filter['fechaDesde'] == null  || $filter['fechaDesde'] == "") {
				$filter['fechaDesde'] = $this->fiveDates;
			}

			if ($filter['fechaHasta'] == null || $filter['fechaHasta'] == "") {
				$filter['fechaHasta'] = date('Y/m/d');
			}
		}


		

// 		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $this->tramiteModel->search($filter, $rowperpage);
		$tramites = [];
		foreach ($rows as $tramite) {
			$tramites[] = $this->get_format_row($tramite);
		}
		// obtengo la cantidad sin filtrar para realizar la pagintation
// 		$cantidad = sizeof($this->tramiteModel->get_cantidad_rows($filter));
		$cantidad = count($this->tramiteModel->search($filter));
		
		//var_dump("cantidad : ".$cantidad);
        //$cantidad=537;   
		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['page'] = $page;
		$data['cantidad'] = $cantidad;


		echo json_encode($data);
		return;
	}

	/**
	 * Funcion que permite obtener la 
	 * pagination de los tramites para verificar domicilio comisarias
	 **/
	public function pagination_tramites_verificacion_domicilio_comisaria()
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
	    
		$usuario  = $this->session->get('user');
		$dependencia = null;
		if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_COMISARIA_SECCIONAL) {
			$dependencia = $usuario['id_dependencia'];
		}

		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $this->tramiteModel->search_tramites_verificacion_domicilio_comisaria($rowperpage, $dependencia);
		$tramites = [];
		foreach ($rows as $tramite) {
			$tramites[] = $this->get_format_row_tramites_verificacion_domicilio_comisaria($tramite);
		}
		// obtengo la cantidad sin filtrar para realizar la pagintation
		$datos = $this->tramiteModel->get_cantidad_rows_verificacion_domicilio_comisaria($dependencia);
		$cantidad = sizeof($datos);

		for ($i = 0; $i < count($datos); $i++) {
			$domicilio = '';
			$domicilio .= $datos[$i]['calle'] ? $datos[$i]['calle'] . ', ' : '';
			$domicilio .= $datos[$i]['numero'] ? 'Numero ' . $datos[$i]['numero'] . ', ' : '';
			$domicilio .= $datos[$i]['manzana'] ? 'Mz.: ' . $datos[$i]['manzana'] . ', ' : '';
			$domicilio .= $datos[$i]['lote'] ? 'Lt.: ' . $datos[$i]['lote'] . ', ' : '';
			$domicilio .= $datos[$i]['piso'] ? 'piso: ' . $datos[$i]['piso'] . ', ' : '';
			$domicilio .= $datos[$i]['dpto'] ? 'dpto.: ' . $datos[$i]['dpto'] . ', ' : '';
			$domicilio .= $datos[$i]['barrio'] ? 'Barrio: ' . $datos[$i]['barrio'] . ', ' : '';
			$domicilio .= $datos[$i]['localidad'] ? $datos[$i]['localidad'] . ', ' : '';
			$domicilio .= $datos[$i]['depto'] ? $datos[$i]['depto'] . ', ' : '';

			$domicilio = trim($domicilio, ', ');

			$datos[$i]['ayn'] = $datos[$i]['apellido'] . ' ' . $datos[$i]['nombre'];
			$datos[$i]['domicilio'] = $domicilio;
			$datos[$i]['verificador'] = '';
		}

		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['aexcel'] = json_encode($datos);
		$data['page'] = $page;

		echo json_encode($data);
		return;
	}

	/**
	 * Funcion que permite establecer el format_row de los tramites con verificacion de domicilio comisarias
	 */
	private function get_format_row_tramites_verificacion_domicilio_comisaria($tramite)
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }
	    
		$domicilio = '';
		$domicilio .= $tramite['calle'] ? $tramite['calle'] . ', ' : '';
		$domicilio .= $tramite['numero'] ? 'Numero ' . $tramite['numero'] . ', ' : '';
		$domicilio .= $tramite['manzana'] ? 'Mz.: ' . $tramite['manzana'] . ', ' : '';
		$domicilio .= $tramite['lote'] ? 'Lt.: ' . $tramite['lote'] . ', ' : '';
		$domicilio .= $tramite['piso'] ? 'piso: ' . $tramite['piso'] . ', ' : '';
		$domicilio .= $tramite['dpto'] ? 'dpto.: ' . $tramite['dpto'] . ', ' : '';
		$domicilio .= $tramite['barrio'] ? 'Barrio: ' . $tramite['barrio'] . ', ' : '';
		$domicilio .= $tramite['localidad'] ? $tramite['localidad'] . ', ' : '';
		$domicilio .= $tramite['depto'] ? $tramite['depto'] . ', ' : '';

		$domicilio = trim($domicilio, ', ');

		$row = '<tr>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . date('d-m-Y', strtotime($tramite['fecha_alta']))  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] . '</td>' .
			'<td>' . $tramite['cuil'] . $tramite['estado_envio_email'] . '</td>' .
			'<td>' . $tramite['apellido'] . ',' . $tramite['nombre'] . '</td>' .
			'<td>' . $domicilio . '</td>';

		$row = $row . '</tr>';

		return $row;
	}

	/**
	 * Funcion que permite obtener la 
	 * pagination de los tramites para verificar
	 **/
	public function pagination_tramites_verificacion_domicilio()
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$usuario  = $this->session->get('user');
		$dependencia = null;
		if (!empty(session()->get('id_rol')) && session()->get('id_rol') == ROL_COMISARIA_SECCIONAL) {
			$dependencia = $usuario['id_dependencia'];
		}

		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $this->tramiteModel->search_tramites_verificacion_domicilio($rowperpage, $dependencia);
		$tramites = [];
		foreach ($rows as $tramite) {
			$tramites[] = $this->get_format_row_tramites_verificacion_domicilio($tramite);
		}
		// obtengo la cantidad sin filtrar para realizar la pagintation
		$datos = $this->tramiteModel->get_cantidad_rows_verificacion_domicilio($dependencia);
		$cantidad = sizeof($datos);

		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['aexcel'] = json_encode($datos);
		$data['page'] = $page;

		echo json_encode($data);
		return;
	}

	/**
	 * Funcion que permite establecer el format_row de los tramites con verificacion de domicilio
	 */
	private function get_format_row_tramites_verificacion_domicilio($tramite)
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$estado = "";
		if ($tramite['estado'] == TRAMITE_APROBADO) {
			$estado = '<span class="badge badge-success"><h8>APROBADO</h8></span>';
		} else if ($tramite['estado'] == TRAMITE_VALIDADO) {
			$estado = '<span class="badge badge-success"><h8>VALIDADO</h8></span>';
		} else if ($tramite['estado'] == TRAMITE_NO_VERIFICADO) {
			$estado = '<span class="badge badge-danger"><h8>' . TRAMITE_NO_VERIFICADO . '</h8></span>';
		} else if ($tramite['estado'] == TRAMITE_VALIDADO_VERIFICADO) {
			$estado = '<span class="badge badge-success"><h8>' . TRAMITE_VALIDADO_VERIFICADO . '</h8></span>';
		} else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
			$estado =  '<strong><span class="badge badge-secondary"><h8>PENDIENTE VALIDACION</h8></span></strong>';
		} else if ($tramite['estado'] == TRAMITE_INVALIDADO) {
			$estado = '<span class="badge badge-danger"><h8>INVALIDADO</h8></span>';
		}

		if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO) {
			$pago = '<span class="badge badge-primary"><h8>PAGADO</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_PENDIENTE || empty($tramite['estado_pago'])) {
			$pago = '<span class="badge badge-secondary"><h8>PENDIENTE</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_CANCELADO || $tramite['estado_pago'] == ESTADO_PAGO_IMPAGO) {
			$pago = '<span class="badge badge-danger"><h8>IMPAGO</h8></span>';
		} else {
			$pago = '<span class="badge badge-danger"><h8>' . $tramite['estado_pago'] . '</h8></span>';
		}

		// Referencia de pago
		/*$referencia_pago = "";
		if ($tramite['referencia_pago'] == BANCO_MACRO) {
			$referencia_pago = '<span class="badge badge-primary">BANCO MACRO</span>';
		} else if ($tramite['referencia_pago'] == MERCADO_PAGO) {
			$referencia_pago = '<span  class="badge badge-secondary">MERCADO PAGO</span>';
		} else if ($tramite['referencia_pago'] == COMISARIA_PAGO) {
			$referencia_pago = '<span  class="badge badge-warning"> COMISARIA PAGO</span>';
		} else if ($tramite['referencia_pago'] == "") {
			$referencia_pago = "NO TIENE REFERENCIA";
		}*/


		/*$link = '<span>'.
				'<a class="btn btn-secondary" '.
				' href="' . base_url() . '/persona/' . $tramite['cuil'] . '" '.
				' title="Editar Datos de la persona"><span class="oi oi-document" style="color:#3380FF"></span>Editar Datos Personales</a></span>';*/

		$domicilio = '';
		$domicilio .= $tramite['calle'] ? $tramite['calle'] . ', ' : '';
		$domicilio .= $tramite['numero'] ? 'Numero ' . $tramite['numero'] . ', ' : '';
		$domicilio .= $tramite['manzana'] ? 'Mz.: ' . $tramite['manzana'] . ', ' : '';
		$domicilio .= $tramite['lote'] ? 'Lt.: ' . $tramite['lote'] . ', ' : '';
		$domicilio .= $tramite['piso'] ? 'piso: ' . $tramite['piso'] . ', ' : '';
		$domicilio .= $tramite['dpto'] ? 'dpto.: ' . $tramite['dpto'] . ', ' : '';
		$domicilio .= $tramite['barrio'] ? 'Barrio: ' . $tramite['barrio'] . ', ' : '';
		$domicilio .= $tramite['localidad'] ? $tramite['localidad'] . ', ' : '';
		$domicilio .= $tramite['depto'] ? $tramite['depto'] . ', ' : '';

		$domicilio = trim($domicilio, ', ');

		$row = '<tr>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] . '</td>' .
			'<td>' . $tramite['dependencia'] . '</td>' .
			'<td>' . $tramite['cuil'] . $tramite['estado_envio_email'] . '</td>' .
			'<td>' . $tramite['apellido'] . ',' . $tramite['nombre'] . '</td>' .
			'<td>' . $domicilio . '</td>' .
			//'<td>' . $link.'</td>'.
			'<td>' . $estado . '</td>' .
			'<td>' . $pago . '</td>';
		// 			'<td width="100">' .
		// 			'<div class="text-center">' ;

		/*if ( $tramite['controlador'] === 'planillaProntuarial') {
			$row = $row .'<span><a href="' . base_url() . '/persona/index/'. $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		   } else {	
			$row = $row .'<span><a href="' . base_url() . '/' . $tramite['controlador'] . '/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		   }*/



		// 			if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO || empty($tramite['estado_pago'])) {
		// 			   $estado  ="";
		// 			   if(empty($item['estado_pago'])) {
		// 					$estado = ESTADO_PAGO_PENDIENTE; 
		// 				}else { 
		// 					$estado =  $tramite['estado_pago']; 
		// 			   }

		// 			   $row =$row.'<a href="#" id="link-cobrar-'.$tramite['id_tramite'].'" '.
		// 			         ' onclick="module_pago.mostrarFormPagoEfectivo( \''.$tramite['id_tramite'].'\', \''.$estado.'\', \''.$tramite['tipo_tramite'].'\',\''. $tramite['precio'].'\')" class="btn btn-danger" style="padding: .315rem .25rem;"><span class="oi oi-dollar" style="color:red"></span></a>';
		// 			   $row = $row.'<a href="#" id="link-pago-'.$tramite['id_tramite'].'" '. 
		// 			            ' onclick="module_pago.verPagoEfectivo(\''.$tramite['id_tramite'].'\',\''.$tramite['estado_pago'].'\',\''.$tramite['tipo_tramite'].'\',\''.$tramite['precio'].'\')" class="btn btn-danger" style="display: none; padding: .315rem .25rem;"><span class="oi oi-eye" style="color:red"></span></a>';


		// 				// $row  = $row . '<a style="cursor:pointer" title="Cobrar Pago" onclick="module_pago.mostrarPago(' . $tramite['id_tramite'] . ')">' .
		// 				// '<span class="oi oi-dollar" style="color:red"></span>' .
		// 				// '</a>';
		// 	   	   }

		// 		  $row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarTramite(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
		// 			 '<span class="oi oi-print" style="color:blue"></span>' .
		// 			 '</a>';

		// 		// upload firma digital				   
		// 		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Subir firma digital" onclick=module_util.mostrarModalFirmaDigital(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
		// 			'<span class="oi oi-data-transfer-upload" style="grey"></span>' .
		// 			'</a>';

		// 		if($tramite['contiene_firma_digital'] == true) {
		// 				$color = "green";
		// 			if ($tramite['estado_envio_email']) {
		// 					  $color = "blue";
		// 		    }

		// 			$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmail(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
		// 				 '<span class="oi oi-envelope-closed" style="color:' . $color . '"></span>' .
		// 					'</a>'; 

		// 			if ( isset($tramite['telefono'])) {
		// 			   $row  = $row . '&nbsp;&nbsp;<a href="https://api.whatsapp.com/send?phone=+549'.$tramite['telefono'].'&text=hola,%20qué%20tal?" style="cursor:pointer" title="Mensaje por wsp Web" target="_blank" >'.
		// 				'<span class="fa fa-whatsapp" style="green"></span>' .
		// 			   '</a>';  
		// 			   }	
		// 		}	


		// 		$row = $row . '</div></td></tr>';
		$row = $row . '</tr>';

		return $row;
	}

	/**
	 * Funcion que permite establecer el format_row
	 */
	private function get_format_row($tramite)
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$util = new Util();
		$estado = "";
		
		if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
		    if(empty($tramite['estado'])) {
		        $estado = '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VALIDACION.'</h8></span><br />';
		    }else if($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
		        $estado = '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VALIDACION.'</h8></span><br />';
		    }else if($tramite['estado'] == TRAMITE_VALIDADO) {
		        $estado = '<span class="badge badge-success"><h8>'.TRAMITE_VALIDADO.'</h8></span><br />';
		    }else {
		        $estado = $tramite['estado'];
		    }
		    
		    if(empty($tramite['estado_verificacion'])) {
		        $estado .= '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VERIFICACION.'</h8></span>';
		    }else if($tramite['estado_verificacion']==TRAMITE_PENDIENTE_VERIFICACION) {
		        $estado .= '<span class="badge badge-secondary"><h8>'.TRAMITE_PENDIENTE_VERIFICACION.'</h8></span>';
		    }else if($tramite['estado_verificacion']==TRAMITE_VERIFICADO) {
		        $estado .= '<span class="badge badge-success"><h8>'.TRAMITE_VERIFICADO.'</h8></span>';
		    }else if($tramite['estado_verificacion']==TRAMITE_VERIFICADO_CON_OBSERVACION) {
		        $estado .= '<span class="badge badge-info"><h8>'.TRAMITE_VERIFICADO_CON_OBSERVACION.'</h8></span>';
		    }else if($tramite['estado_verificacion']==TRAMITE_VERIFICADO_CON_INFORME) {
		        $estado .= '<span class="badge badge-info"><h8>'.TRAMITE_VERIFICADO_CON_INFORME.'</h8></span>';
		    }else {
		        $estado .= $tramite['estado_verificacion'];
		    }
		}else {
		    if ($tramite['estado'] == TRAMITE_APROBADO) {
		        $estado = '<span class="badge badge-success"><h8>APROBADO</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_VALIDADO) {
		        $estado = '<span class="badge badge-success"><h8>VALIDADO</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_NO_VERIFICADO) {
		        $estado = '<span class="badge badge-danger"><h8>' . TRAMITE_NO_VERIFICADO . '</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_VALIDADO_VERIFICADO) {
		        $estado = '<span class="badge badge-success"><h8>' . TRAMITE_VALIDADO_VERIFICADO . '</h8></span>';
		    } else if ($tramite['estado'] == TRAMITE_PENDIENTE_VALIDACION) {
		        $estado =  '<strong><span class="badge badge-secondary"><h8>PENDIENTE VALIDACION</h8></span></strong>';
		    } else if ($tramite['estado'] == TRAMITE_INVALIDADO) {
		        $estado = '<span class="badge badge-danger"><h8>INVALIDADO</h8></span>';
		    }
		}

		if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO) {
			$pago = '<span class="badge badge-primary"><h8>PAGADO</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_PENDIENTE || empty($tramite['estado_pago'])) {
			$pago = '<span class="badge badge-secondary"><h8>PENDIENTE</h8></span>';
		} else if ($tramite['estado_pago'] == ESTADO_PAGO_CANCELADO || $tramite['estado_pago'] == ESTADO_PAGO_IMPAGO) {
			$pago = '<span class="badge badge-danger"><h8>IMPAGO</h8></span>';
		} else {
			$pago = '<span class="badge badge-danger"><h8>' . $tramite['estado_pago'] . '</h8></span>';
		}

		// Referencia de pago
		$referencia_pago = "";
		if ($tramite['referencia_pago'] == BANCO_MACRO) {
			$referencia_pago = '<span class="badge badge-primary">BANCO MACRO</span>';
		} else if(date_format(date_create($tramite['fecha_alta']), 'm/d/Y') == FECHA_VOTACION && $tramite['id_tipo_tramite'] == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) {
          $referencia_pago= '<span class="badge badge-info" style="font-size: 80%;">GRATIS</span>';
		} else if ($tramite['referencia_pago'] == MERCADO_PAGO) {
			$referencia_pago = '<span  class="badge badge-secondary">MERCADO PAGO</span>';
		} else if ($tramite['referencia_pago'] == COMISARIA_PAGO) {
			$referencia_pago = '<span  class="badge badge-warning"> COMISARIA PAGO</span>';
		} else if ($tramite['referencia_pago'] == "") {
			$referencia_pago = "";
		} else {
			$referencia_pago = "";
		}


        //Comprobamos por cada tramite si existe un registro validado para marcarlo 
	    $styleColorValidado = "";
	    $listadoTramites = $this->tramiteModel->getTramiteValidado($tramite['documento'],$tramite['id_tramite'], $tramite['id_tipo_tramite']);
	    if ( sizeof($listadoTramites) > 0 && ( $tramite['estado'] != TRAMITE_VALIDADO && $tramite['estado'] != TRAMITE_VALIDADO_VERIFICADO) ) {
	        $styleColorValidado = "background-color:#90EE90";
	    }

		if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL && $tramite['urgente'] == INT_UNO) {
			$styleColorValidado = "background-color:#F6CED8";
		}

		
		$link = '<span>' .
			'<a class="btn btn-secondary" ' .
			' href="' . base_url() . '/persona/' . $tramite['cuil'] . '" ' .
			' title="Editar Datos de la persona"><span class="oi oi-document" style="color:#3380FF"></span>Editar Datos Personales</a></span>';


		$row = '<tr style='.$styleColorValidado.'>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] .' '.$tramite['tipo_planilla'].'</td>' .
			'<td>' . date_format(date_create($tramite['fecha_alta']), 'd/m/Y H:i') . '</td>' .
			'<td>' . $tramite['dependencia'] . '</td>' .
			'<td id="col-forma-pago-' . $tramite['id_tramite'] . '">' . $tramite['nombreTipoPago'] . '</td>' .
			'<td>' . $referencia_pago . '</td>' .
			'<td>' . $tramite['documento'] . '</td>' .
			'<td>' . $tramite['apellido'] . ', ' . $tramite['nombre'] . '</td>' .
			'<td>' . $estado . '</td>' .
			'<td id="col-estado-pago-' . $tramite['id_tramite'] . '">' . $pago . '</td>' .
			'<td width="100">' .
			'<div class="text-center">';


		 if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
			$row = $row . '<span><a href="' . base_url() . '/tramiteReba/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		} else {
			$row = $row . '<span><a href="' . base_url() . '/' . $tramite['controlador'] . '/edit/' . $tramite['id_tramite'] . '" title="Editar Datos del Tramite"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		} 
      

	

		 


		if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO || empty($tramite['estado_pago'])) {
			$estado  = "";
			if (empty($item['estado_pago'])) {
				$estado = ESTADO_PAGO_PENDIENTE;
			} else {
				$estado =  $tramite['estado_pago'];
			}
		}

		$actionVerificador = "";
		$actionVerificador2 = "";
		if ($tramite['estado'] == TRAMITE_VALIDADO) {
			if ($util->isTramiteOnline($tramite['id_tipo_tramite'])) {
				$actionVerificador = '<a href="' . base_url() . '/' . $tramite['controlador'] . '/ver/' . $tramite['id_tramite'] . '/buscarTramitePersona" class="btn btn-info" style="padding: .315rem .25rem;">Ver</a>';
				if (
					$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA ||
					$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA ||
					$tramite['id_tipo_tramite'] == TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA
				) {
					$actionVerificador2 = '<a style="cursor:pointer"  href="' . base_url() . '/' . $tramite['controlador'] . '/verificar/' . $tramite['id_tramite'] . '/buscarTramitePersona" title="Verificar domicilio">' .
						'<span class="oi oi-menu" style="color:red"></span></a>';
					$row = $row . $actionVerificador2;
				}
			}
		}

		if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
			if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO) {
				$row = $row . '&nbsp;&nbsp;<a href="' . base_url() . '/tramiteReba/getCuponPagoOnline/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Descargar Cupon de Pago">' .
					'<span class="oi oi-tablet" style="color:brown"></span>' .
					'</a>';
			} else {
				$row = $row . '&nbsp;&nbsp;<a href="' . base_url() . '/tramiteReba/getCuponesPago/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Descargar Cupones de Pago">' .
					'<span class="oi oi-print" style="color:brown"></span>' .
					'</a>';
				$row = $row . '&nbsp;&nbsp;<a href="#" style="cursor:pointer" onclick="module_pago.mostrarFormPagoEfectivoReba(' . $tramite['id_tramite'] . ', \'' . $tramite['estado_pago'] . '\', \'' . $tramite['tipo_tramite'] . '\', -1)" title="Registrar pago">' .
					'<span class="oi oi-check"></span>' .
					'</a>';
			}
		} else if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
		    $row = $row .
    		    '&nbsp;&nbsp;<a href="'.base_url().'/planillaProntuarial/verificar/'.$tramite['id_tramite'].'/dashboard" title="ver antecedentes"><span class="oi oi-clipboard" style="color: red"></span></a>';
		    
			if ($tramite['estado_pago'] != ESTADO_PAGO_PAGADO) {
			  $price = $tramite['precio'] + $tramite['importe_adicional'];
			  $row = $row .
					'&nbsp;&nbsp;<a href="#" style="cursor:pointer" onclick=module_pago.mostrarFormPagoEfectivoPlanillaProntuarial('.$tramite['id_tramite'].',"PENDIENTE","Planilla",'.$price.') >'.
					' <span class="oi oi-check"></span>'.
					'</a>'; 
			}
			$row = $row . '&nbsp;&nbsp;<a target="_blank" href="' . base_url() . '/planillaProntuarial/getConstanciaPlanillaProntuarial/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Cupon de pago">' .
			'<span class="oi oi-data-transfer-download" style="color:green"></span>' .
			'</a>';
			$row = $row . '&nbsp;&nbsp;<a target="_blank" href="' . base_url() . '/planillaProntuarial/getDocumentoPlanillaProntuarial/' . $tramite['id_tramite'] . '" style="cursor:pointer" title="Ver Planilla">' .
			 			'<span class="oi oi-print" style="color:blue"></span>' .
			 			'</a>';
		}  else if($tramite['id_tipo_tramite'] == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) {
			if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO && $tramite['estado'] == TRAMITE_VALIDADO) {
			$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarTramite(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
				'<span class="oi oi-print" style="color:blue"></span>' .
				'</a>';
			}
		}  else if($tramite['id_tipo_tramite'] == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION ) {
			if($tramite['estado'] == TRAMITE_VALIDADO) { 
			if(date_format(date_create($tramite['fecha_alta']), 'm/d/Y') == FECHA_VOTACION || $tramite['estado_pago'] == ESTADO_PAGO_PAGADO){
			   $row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarTramite(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
				'<span class="oi oi-print" style="color:blue"></span>' .
				'</a>';
			  }
			}
		}
		 else {
			$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Imprimir" onclick=module_util.descargarTramite(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
				'<span class="oi oi-print" style="color:blue"></span>' .
				'</a>';
		}

		if ($tramite['id_tipo_tramite'] != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA && 
		    $tramite['id_tipo_tramite'] != TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE &&
			$tramite['id_tipo_tramite'] != TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) {
    		// upload firma digital				   
    		$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Subir firma digital" onclick=module_util.mostrarModalFirmaDigital(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
    			'<span class="oi oi-data-transfer-upload" style="grey"></span>' .
    			'</a>';
		}

		if ($tramite['contiene_firma_digital'] == true && 
		    $tramite['id_tipo_tramite'] != TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) {
			$color = "green";
			if ($tramite['estado_envio_email']) {
				$color = "blue";
			}

			if (!empty($tramite['email']) && $tramite['id_tipo_tramite'] != TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA) {
				$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmail(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
					'<span class="oi oi-envelope-closed" style="color:' . $color . '"></span>' .
					'</a>';
			}

		
		} else if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE ) {
            $color = "";
			if ($tramite['estado_envio_email']) {
				$color = "blue";
			}
			if ($tramite['estado_pago'] == ESTADO_PAGO_PAGADO && $tramite['estado'] == TRAMITE_VALIDADO) {
				$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmailDirectoSinFirma(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
				'<span class="oi oi-envelope-closed" style="color:' . $color . '"></span>' .
				'</a>';
			}
			
		} else if ($tramite['id_tipo_tramite'] == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION ) {
            $color = "";
			if ($tramite['estado_envio_email']) {
				$color = "blue";
			}
			if ($tramite['estado'] == TRAMITE_VALIDADO) {
			    if(date_format(date_create($tramite['fecha_alta']), 'm/d/Y') == FECHA_VOTACION || $tramite['estado_pago'] == ESTADO_PAGO_PAGADO){
                            
				$row  = $row . '&nbsp;&nbsp;<a style="cursor:pointer" title="Email" onclick=module_util.envioEmailDirectoSinFirma(' . $tramite['id_tramite'] . ',"' . $tramite['controlador'] . '")>' .
				'<span class="oi oi-envelope-closed" style="color:' . $color . '"></span>' .
				'</a>';
			  }
			}
			
		}

		if (isset($tramite['telefono'])) {
			$row  = $row . '&nbsp;&nbsp;<a href="https://api.whatsapp.com/send?phone=+549' . $tramite['telefono'] . '&text=hola,%20qué%20tal?" style="cursor:pointer" title="Mensaje por wsp Web" target="_blank" >' .
				'<span class="fa fa-whatsapp" style="green"></span>' .
				'</a>';
		}


		if ($tramite['referencia_pago'] == BANCO_MACRO  && ($tramite['estado_pago'] === ESTADO_PAGO_PENDIENTE  || empty($tramite['estado_pago']))) {
			$row  = $row  . '<a style="cursor:pointer" title="Sincronizar Pago" onclick="module_pago.mostrarPago(' . $tramite['id_tramite'] . ')">' .
				'<span class="oi oi-loop-circular" style="color:red"></span>' .
				'</a>';
		}

		$row = $row . '</div></td></tr>';

		return $row;
	}




	/**
	 * Funcion que permite obtener el estado actual 
	 * del pago de tramite, en este caso se esta obteniendo la informacion 
	 * del Banco Macro
	 * @param id: idTramite
	 */
	public function get_pago_tramite($idTramite)
	{
		$tramite = $this->tramiteModel->find($idTramite);
		$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$data['tipo_tramite'] = $tipoTramite;

		if ($tramite != null) {
			if ($tramite['referencia_pago'] == BANCO_MACRO) {
				$data = $this->tramiteReferenciaBancoMacro($idTramite, $tramite);
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
	protected function tramiteReferenciaBancoMacro($idTramite)
	{

		$tramiteModel = new TramiteModel();
		$utilBancoMacro = new UtilBancoMacro();
		$tipoTramiteModel = new TipoTramiteModel();
		$pagoBancoMacro = new PagoBancoMacro();
		$tramiteRebaModel = new TramiteRebaModel();
		$categoriaRebaModel = new CategoriaRebaModel();
		$tramite = $tramiteModel->find($idTramite);
		$tipoTramite = "";
		if ($tramite['id_tipo_tramite'] != "") {
			$tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
		}
		// transaction del banco macro
		$resultadoBancoMacro = $pagoBancoMacro->login();
		if ($resultadoBancoMacro->status) {
			$token = 	$resultadoBancoMacro->data;

			$transaction = $pagoBancoMacro->getWithToken($idTramite, $token);
           
		

           if ($transaction && $transaction != null) {
				if ($transaction->status) {

                   
					$price = 0;
					if ($tipoTramite!= null && $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {

						$tramiteReba = $tramiteRebaModel->where('id_tramite',$idTramite)->first();
						$categoriaReba = $categoriaRebaModel->find($tramiteReba['id_categoria_reba']);
						$price = floatVal($categoriaReba['precio']) ;
						$categoriaUnoPrecio = floatVal($tramiteReba['precio_uno']);
						$categoriaDosPrecio = floatVal($tramiteReba['precio_dos']);
						$categoriaTresPrecio = floatVal($tramiteReba['precio_tres']);
						$price = ($categoriaUnoPrecio +  $categoriaDosPrecio + $categoriaTresPrecio) ; 
					
						
					} else if ($tipoTramite != null && $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL){
					// OTRO TIPO DE TRAMITE
					  $price = floatval($tipoTramite['precio']) + floatval($tipoTramite['importe_adicional']) ;
					
					} else if($tipoTramite != null &&  $tipoTramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA &&  $tipoTramite['precio'] != null && $tipoTramite['precio'] != "") {
					   // OTRO TIPO DE TRAMITE
					   $price = floatval($tipoTramite['precio']) ;
					} 

					$data['status'] = 'OK';
					$data['referencia'] = BANCO_MACRO;
					$data['message'] = $transaction->message;
					$data['fecha'] =  $transaction->data->fecha;
					$data['fecha_pago']  = $transaction->data->fechaPago;
					$data['rendido'] = 0;
					$data['importe'] = $price;
					$data['estado_pago_entidad'] = $transaction->data->estado;
					$data['estado_pago'] =  $utilBancoMacro->getStatusFromCode($transaction->data->estado);
  

					// actualizo el tramite
					$estadoPago = $utilBancoMacro->getStatusFromCode($transaction->data->estado);
					$tramite['estado_pago'] = $estadoPago;
					$tramite['estado_pago_entidad'] = $transaction->data->estado;
					$tramite['mensaje_estado_pago_entidad'] = $transaction->message;
					if ($estadoPago == ESTADO_PAGO_PAGADO) {
						$tramite['fecha_pago'] = date('Y-m-d H:i:s');
					}
					$tramiteModel->set($tramite);
					$tramiteModel->where('id_tramite', $tramite['id_tramite']);
					$tramiteModel->update();
				} else {
                  // No existe el numero de tramite en la plataforma de Banco Macro 
				  $data['status'] = 'ERROR';
				  $data['message'] = $transaction->message;
				  $data['movimientos'] = [];
				  $data['estado_pago']  = ESTADO_PAGO_IMPAGO;
				  $data['fecha_pago'] = "-------------";

				  $tramite['estado_pago'] = ESTADO_PAGO_IMPAGO;
				  $tramite['estado_pago_entidad'] = ESTADO_PAGO_IMPAGO;
				  $tramite['mensaje_estado_pago_entidad'] = $transaction->message;
				  $tramiteModel->set($tramite);
				  $tramiteModel->where('id_tramite', $tramite['id_tramite']);
				  $tramiteModel->update();
  
				}
			} else {
				$data['status'] = 'ERROR';
				$data['message'] = 'No se pudo obtener la informacion del Banco Macro';
				$data['movimientos'] = [];
				$data['estado_pago']  = "-------------";
				$data['fecha_pago'] = "-------------";
			}
		} else {
			$data['status'] = 'ERROR';
			$data['message'] = 'No se pudo conectar al Banco Macro';
			$data['movimientos'] = [];
			$data['estado_pago']  = "-------------";
			$data['fecha_pago'] = "-------------";
		}

		$data['tramite'] = $tramite;
		$data['tipo_tramite'] = $tipoTramite;


		return $data;
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
		$util = new Util();
		if (session()->get('isLoggedIn') == NULL) {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$tramite = $this->tramiteModel->find($idTramite);
		$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$status = "";

		try {
			// se genera el comprobante de pago en disco
			$filePath = WRITEPATH . 'temp/';
			$date =  date('dmYsiH');
			$nombreArchivo = $filePath . "comprobantePago-" . $idTramite . "-" . $date . ".pdf";
			$this->generarComprobantePago($idTramite, 'F', $nombreArchivo);

			// envio por email del comprobante de pago
			$email = new EmailSendgrid();
			$remitente = getenv('EMAIL');
			$titular_tramite = $this->tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', 1)->first();
			$subject = "Comprobante de pago por Tramite de " . $tipoTramite['tipo_tramite'];

			try {
    			$status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $nombreArchivo, $util->getComprobantePago());
    			$status = "OK";
    			// actualizo el estado de envio de email
    			if ($status == "OK") {
    				$tramite['comprobante_pago_enviado'] = INT_UNO;
    			} else {
    				log_message('error', 'Error en el envio por email del comprobante de pago del tramite=' . $idTramite . ' de la persona documento=' . $titular_tramite['documento'] . ', status=' . $status);
    				$tramite['comprobante_pago_enviado'] = INT_CERO;
    			}
			} catch (Exception $e) {
			    log_message('error', $e);
			    log_message('error', 'No se envio el email a '.$titular_tramite['email'].' de la persona cuil '.$titular_tramite['cuil'].', nro tramite '.$titular_tramite['id_tramite']);
			}

			$tramite['id_dependencia_de_cobro'] = session()->get('id_dependencia');
			$tramite['estado_pago'] = ESTADO_PAGO_PAGADO;
			$tramite['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$tramite['referencia_pago'] = COMISARIA_PAGO;
			$tramite['fecha_pago'] = date('Y-m-d H:i:s');
			$tramite['fecha_modificacion'] = date('Y-m-d H:i:s');
			$tramite['usuario_modificacion'] = session()->get('id');
			$this->tramiteModel->update($idTramite, $tramite);

			// creo un movimiento
			$movimientoPagoModel = new MovimientoPago();
			$movimientoPago['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$movimientoPago['site_id'] = SITE_POLICIA;
			$movimientoPago['collection_status'] = MP_APPROVED;
			$movimientoPago['id_tramite'] = $idTramite;
			$movimientoPago['monto_recibido'] = $tipoTramite['precio'];
			$movimientoPago['fecha_alta'] = date('Y-m-d H:i:s');
			$movimientoPago['usuario_alta'] = session()->get('id');
			$idMovimientoPago = $movimientoPagoModel->insert($movimientoPago, true);
			$tramite['idMovimientoPago'] = $idMovimientoPago;

			$status = "OK";
		} catch (Exception $e) {
		    log_message('error', $e);
// 		    var_dump($e);
			$status = "ERROR";
		}

		$data['status'] = $status;
		$data['tramite'] = $tramite;
		echo json_encode($data);
		return;
	}


	/**
	 * Se cambia el estado del tramite , pero no se envia el email y 
	 * tampoco se genera un comprobante
	 *  */
	public function pago_tramite_comisaria_reba($idTramite)
	{
		$util = new Util();
		if (session()->get('isLoggedIn') == NULL) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$tramite = $this->tramiteModel->find($idTramite);
		$tipoTramite = $this->tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$status = "";

		try {

			$tramite['estado_pago'] = ESTADO_PAGO_PAGADO;
			$tramite['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$tramite['referencia_pago'] = COMISARIA_PAGO;
			$tramite['fecha_pago'] = date('Y-m-d H:i:s');
			$tramite['fecha_modificacion'] = date('Y-m-d H:i:s');
			$tramite['usuario_modificacion'] = session()->get('id');
			$this->tramiteModel->update($idTramite, $tramite);

			// creo un movimiento
			$movimientoPagoModel = new MovimientoPago();
			$movimientoPago['id_tipo_pago'] = TIPO_PAGO_CONTADO;
			$movimientoPago['site_id'] = SITE_POLICIA;
			$movimientoPago['collection_status'] = MP_APPROVED;
			$movimientoPago['id_tramite'] = $idTramite;
			$movimientoPago['monto_recibido'] = $tipoTramite['precio'];
			$movimientoPago['fecha_alta'] = date('Y-m-d H:i:s');
			$movimientoPago['usuario_alta'] = session()->get('id');
			$idMovimientoPago = $movimientoPagoModel->insert($movimientoPago, true);
			$tramite['idMovimientoPago'] = $idMovimientoPago;

			$status = "OK";
		} catch (Exception $e) {
			$status = "ERROR";
		}

		$data['status'] = $status;
		$data['tramite'] = $tramite;
		echo json_encode($data);
		return;
	}

	public function setNroComprobantePago($idMovimientoPago)
	{
		$movimientoPagoModel = new MovimientoPago();
		$movimientoPago['nro_comprobante'] = strtoupper($this->request->getVar('nro_comprobante'));
		if (empty($movimientoPago['nro_comprobante'])) {
			$data['error'] = true;
			$data['mensaje'] = 'El nro de comprobante es obligatorio.';
		} else {
			$movimientoPago['fecha_modificacion'] = date('Y-m-d H:i:s');
			$movimientoPago['usuario_modificacion'] = session()->get('id');
			$resultado = $movimientoPagoModel->update($idMovimientoPago, $movimientoPago);
			if ($resultado) {
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['mensaje'] = 'Ha ocurrido un error al guardar el nro. de comprobante, intente de nuevo.';
			}
		}

		echo json_encode($data);
		return;
	}

	public function getUltimoMovimiento($idTramite)
	{
		$movimientoPagoModel = new MovimientoPago();
		$movimiento = $movimientoPagoModel->where('id_tramite', $idTramite)->orderBy('fecha_alta', 'desc')->first();
		echo json_encode($movimiento);
	}

	//--------------------------------------------------------------------


	/**
	 * Funcion que permite realizar el envio de email 
	 * al titular del tramite
	 */
	public function sendEmail($idTramite)
	{
		$email = new EmailSendgrid();
		$util = new Util();
		$tramiteModel = new TramiteModel();
		$tipoTramiteModel = new TipoTramiteModel();
		$remitente = getenv('EMAIL');
		$tramite = $tramiteModel->find($idTramite);
		$titular_tramite = $this->tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', 1)->first();
		$tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$subject = "Comprobante Digital : " . $tramite['id_tramite'] . ' Tipo Tramite : ' . $tipoTramite['tipo_tramite'];


		$archivoTramiteDigital = $this->tramiteArchivoFirmaDigitalModel->where('id_tramite', $idTramite)->first();
		if (
			$archivoTramiteDigital != null &&
			isset($archivoTramiteDigital['ruta']) &&
			file_exists($archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre'])
		) {


			// $filePath = getenv('FILE_DISK') . 'tramite' . $idTramite . ".pdf";
			$filePath = $archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre'];
			$status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $filePath, $util->getComprobanteDigital());
			// actualizo el estado de envio de email 
			if ($status == "OK") {
				$tramite['estado_envio_email'] = true;
				$tramite['fecha_envio_email'] =  date('Y-m-d H:i:s');
				$tramiteModel->update($idTramite, $tramite);
			}
			$data = [
				"status" => $status
			];
		} else {
			$data = [
				"status" => "No existe el archivo de la firma digital para el tramite : " . $idTramite
			];
		}


		echo json_encode($data);
		return;
	}


	/**
	 * Funcion que permite enviar el email directo
	 * permitiendo descargar el archivo el certificado 
	 * y adjuntarlo, sin subir firma digital
	 */
	public function sendEmailDirectoSinFirma($idTramite)
	{
		$email = new EmailSendgrid();
		$util = new Util();
		$tramiteModel = new TramiteModel();
		$tipoTramiteModel = new TipoTramiteModel();
		$remitente = getenv('EMAIL');
		$tramite = $tramiteModel->find($idTramite);
		$titular_tramite = $this->tramitePersonaModel->where('id_tramite', $idTramite)->where('es_titular_tramite', 1)->first();
		$tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
		$subject = "Comprobante Digital : " . $tramite['id_tramite'] . ' Tipo Tramite : ' . $tipoTramite['tipo_tramite'];

		if ( $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) {
		    $exposicion = new ConstanciaPorNoVotacion();
		    $exposicion->guardarFileDisk($idTramite);
		}
		else if ( $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) {
          $exposicion = new ExposicionPorJustificativoLaboralPorFaltaDeTransporte();
		  $exposicion->guardarFileDisk($idTramite);
		}

		  $pathFileDirectory = WRITEPATH . 'temp/';
		  $filePath = $pathFileDirectory.$titular_tramite['cuil']."-".$idTramite.".pdf";
		  if ($tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) {
		      $status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $filePath, $util->getComprobanteDigitalSinFirmar($tipoTramite['tipo_tramite']));
		  }else if ($tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE) {
		      $status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $filePath, $util->getComprobanteDigitalSinFirmar($tipoTramite['tipo_tramite']));
		  }else {
		      $status = $email->sendEmail($remitente, $titular_tramite['email'], $subject, $filePath, $util->getComprobanteDigital());
		  }

		  // actualizo el estado de envio de email 
		  if ($status == "OK") {
		  	$tramite['estado_envio_email'] = true;
		  	$tramite['fecha_envio_email'] =  date('Y-m-d H:i:s');
		  	$tramiteModel->update($idTramite, $tramite);
		  }
		  $data = [
		  	"status" => $status
		  ];
		


		echo json_encode($data);
		return;
	}


	/**
	 * Function mostrarFirmaDigital
	 */
	public function mostrarFirmaDigital()
	{
		$idTramite = $this->request->getVar("id_tramite");
		$archivoTramiteDigital = $this->tramiteArchivoFirmaDigitalModel->where('id_tramite', $idTramite)->first();
		$ruta = "";
		$status = "";
		$message = "";

		try {
			if (
				$archivoTramiteDigital != null &&
				isset($archivoTramiteDigital['ruta']) &&
				file_exists($archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre'])
			) {
				$ruta = $archivoTramiteDigital['ruta'];
				$status = "OK";
			} else {
				$status = "ERROR";
			}
		} catch (Exception $e) {
			$status = "ERROR";
		}

		$data = [
			"ruta" => $ruta,
			"status" => $status,
			"message" => $message
		];

		return $this->response->setJSON($data);
	}


	/**
	 * Funcion que permite descargar la firma digital
	 */
	public function descargarFirmaDigital()
	{
		$idTramite = $this->request->getVar('id_tramite');
		$archivoTramiteDigital = $this->tramiteArchivoFirmaDigitalModel->where('id_tramite', $idTramite)->first();
		ob_start();
		try {
			if (
				$archivoTramiteDigital != null &&
				isset($archivoTramiteDigital['ruta']) &&
				file_exists($archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre'])
			) {
				ob_start();
				header("Content-type: application/pdf");
				header('Content-Disposition: attachment; filename="' . basename($archivoTramiteDigital['nombre']) . '"');

				header('Content-Length: ' . filesize($archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre']));
				flush(); // Flush system output buffer
				readfile($archivoTramiteDigital['ruta'] . "/" . $archivoTramiteDigital['nombre']);
				//die();
			} else {
				http_response_code(404);
				die();
			}
		} catch (Exception $ex) {
		}
	}

	/**
	 * Upload Firma Digital
	 */
	public function uploadFirmaDigitalSingle()
	{
		$archivo = $this->request->getFile('file_tramite');
		$idTramite = $this->request->getVar('id_file_tramite');

		$year = date("Y");
		$month = date("m");
		$status = "";
		$message = "";
		try {

			$base_path = WRITEPATH . "/" . ARCHIVOS_DIGITALES . "/" . $year . "/" . $month;
			if (!file_exists($base_path)) {
				mkdir($base_path, 0777, true);
			}



			if ($archivo->isValid()) {
				$prefijo_nombre = $idTramite . '_' . date("Ymd_His");
				$nombre = $prefijo_nombre . '_' . $archivo->getName();
				$archivo->move($base_path, $nombre);

				$tramite =  $this->tramiteModel->find($idTramite);
				$tramite['contiene_firma_digital'] = true;
				$archivoTramiteDigital = $this->tramiteArchivoFirmaDigitalModel->where("id_tramite", $idTramite)->first();
				if ($archivoTramiteDigital == NULL) {
					$tramiteArchivo = [
						'id_tramite' =>  $idTramite,
						'prefijo_nombre' => $prefijo_nombre,
						'nombre' => $nombre,
						'ruta'  => $base_path,
						'tipo'  => $archivo->getClientMimeType(),
						'fecha_alta' => date('Y-m-d H:i:s'),
						'usuario_alta' => session()->get('id')
					];
					$this->tramiteArchivoFirmaDigitalModel->insert($tramiteArchivo);
				} else {
					$tramiteArchivo = [
						'id_tramite' =>  $idTramite,
						'prefijo_nombre' => $prefijo_nombre,
						'nombre' => $nombre,
						'ruta'  => $base_path,
						'tipo'  => $archivo->getClientMimeType(),
						'fecha_modificacion' => date('Y-m-d H:i:s'),
						'usuario_modificacion' => session()->get('id')
					];
					$this->tramiteArchivoFirmaDigitalModel->update($archivoTramiteDigital['id'], $tramiteArchivo);
				}
				$this->tramiteModel->update($idTramite, $tramite);


				$status = "OK";
			} else {
				$status = "ERROR";
				$message = "Archivo no es valido";
			}
		} catch (Exception $e) {
			$status = "ERROR";
			$message = $e->getMessage();
		}

		$data = [
			"status" => $status,
			"message" => $message
		];

		return $this->response->setJSON($data);
	}


	/**
	 * Upload Firma Digital Single With Format en el nombre 
	 * del archivo para verficiar sus datos
	 * Es para subida de multiples archivos firmados.
	 */
	public function uploadFirmaDigitalSingleFormat()
	{
		$archivo = $this->request->getFile('file');
		$status = "";
		$message = "";


		$year = date("Y");
		$month = date("m");

		try {

			$base_path = WRITEPATH . "/" . ARCHIVOS_DIGITALES . "/" . $year . "/" . $month;
			if (!file_exists($base_path)) {
				mkdir($base_path, 0777, true);
			}

			if ($archivo->isValid()) {

				$nombre_file =  $archivo->getName();
				$valores = explode("-", $nombre_file);
				if (count($valores) >  0) {
					$idTramite = $valores[1];
					$prefijo_nombre = $idTramite . '_' . date("Ymd_His");
					$nombre = $prefijo_nombre . '_' . $archivo->getName();

					$archivo->move($base_path, $nombre);
					$archivoTramiteDigital = $this->tramiteArchivoFirmaDigitalModel->where("id_tramite", $idTramite)->first();
					if ($archivoTramiteDigital == NULL) {
						$tramiteArchivo = [
							'id_tramite' =>  $idTramite,
							'prefijo_nombre' => $prefijo_nombre,
							'nombre' => $nombre,
							'ruta'  => $base_path,
							'tipo'  => $archivo->getClientMimeType(),
							'fecha_alta' => date('Y-m-d H:i:s'),
							'usuario_alta' => session()->get('id')
						];
						$this->tramiteArchivoFirmaDigitalModel->insert($tramiteArchivo);
					} else {
						$tramiteArchivo = [
							'id_tramite' =>  $idTramite,
							'prefijo_nombre' => $prefijo_nombre,
							'nombre' => $nombre,
							'ruta'  => $base_path,
							'tipo'  => $archivo->getClientMimeType(),
							'fecha_modificacion' => date('Y-m-d H:i:s'),
							'usuario_modificacion' => session()->get('id')
						];
						$this->tramiteArchivoFirmaDigitalModel->update($archivoTramiteDigital['id'], $tramiteArchivo);
					}
					$status = "OK";
				} else {
					$status = "ERROR";
					$message = "Error en el nombre del archivo ";
				}
			} else {
				$status = "ERROR";
				$message = "Archivo no es valido";
			}
		} catch (Exception $e) {
			$status = "ERROR";
			$message = $e->getMessage();
		}

		$data = [
			"status" => $status,
			"message" => $message
		];

		return $this->response->setJSON($data);
	}


	/**
	 * Funcion que permite descargar los archivos de
	 * certificados digitales
	 */
	public function descargarcertificados()
	{
		$zip = new ZipArchive;
		$fileName = 'archivos';
		$pathFile = WRITEPATH . 'archivos/';

		$certificadoResidencia = new CertificadoResidencia();
		$certificadoResidenciaConvivencia = new CertificadoResidenciaConvivencia();
		$certificadoSupervivencia = new CertificadoSupervivencia();
		$constanciaPorExtravio = new ConstanciaPorExtravio();
		$constanciaDenuncia = new ConstanciaDenuncia();
		$planillaProntuarial = new PlanillaProntuarial();
		$certificadoConvivencia = new CertificadoConvivencia();

		$filter['idTramite'] = null;
		$filter['cuil'] = null;
		$filter['nombre'] = null;
		$filter['apellido'] = null;
		$filter['idTipoPago'] = null;
		$filter['idTipoTramite'] = null;
		$filter['idTramite'] = $this->request->getVar('idTramite');
		$filter['cuil']         = $this->request->getVar('cuil');
		$filter['nombre']       = $this->request->getVar('nombre');
		$filter['apellido']     = $this->request->getVar('apellido');
		$filter['idTipoTramite'] = $this->request->getVar('idTipoTramite');
		$filter['idTipoPago']     = $this->request->getVar('idTipoPago');
		$filter['fechaDesde']     = $this->request->getVar('fechaDesde');
		$filter['fechaHasta']     = $this->request->getVar('fechaHasta');
		$filter['idDependencia']     = $this->request->getVar('idDependencia');
		$filter['estadoPago']    =  $this->request->getVar('estadoPago');
		$filter['estadoTramite'] =  $this->request->getVar('estadoTramite');


		// Row per page
		//$rowperpage = 5;

		//obtenemos los tramites
		$rows = $this->tramiteModel->search($filter, NULL);


		$tramites = [];
		$primerTramite = null;
		$ultimoTramite = null;

		if ($rows != null && count($rows) > 0) {
			$primerTramite = $rows[0];
			$ultimoTramite = $rows[count($rows) - 1];

			$date =  date('dmY');
			$fileName = $fileName . "-" . $primerTramite['id_tramite'] . "-" . $ultimoTramite['id_tramite'] . "-" . $date . ".zip";
		}


		if ($rows != null && count($rows) > 0 && $zip->open($pathFile . $fileName, ZipArchive::CREATE) === TRUE) {

			foreach ($rows as $tramite) {
				$controlador =  $tramite['controlador'];
				$titular_tramite = $this->tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();


				switch ($controlador) {
					case "certificadoResidencia":
						$certificadoResidencia->guardarFileDisk($tramite['id_tramite']);
						break;
					case "certificadoResidenciaConvivencia":
						$certificadoResidenciaConvivencia->guardarFileDisk($tramite['id_tramite']);
						break;
					case "certificadoSupervivencia":
						$certificadoSupervivencia->guardarFileDisk($tramite['id_tramite']);
						break;
					case "constanciaPorExtravioDni":
						$constanciaPorExtravioDni->guardarFileDisk($tramite['id_tramite']);
						break;
					case "constanciaPorExtravio":
						$constanciaPorExtravio->guardarFileDisk($tramite['id_tramite']);
						break;
					case "constanciaDenuncia":
						$constanciaDenuncia->guardarFileDisk($tramite['id_tramite']);
						break;
						// case "planillaProntuarial" ??? : $planillaProntuarial->guardarFileDisk($idTramite); break;
					case "certificadoConvivencia":
						$certificadoConvivencia->guardarFileDisk($tramite['id_tramite']);
						break;
				}


				$date =  date('dmYsiH');
				$nombreFile = $titular_tramite['cuil'] . "-" . $tramite['id_tramite'] . "-" . $date . ".pdf";
				$filePath = WRITEPATH . 'archivos/' . $nombreFile;
				$zip->addFile($filePath, $nombreFile);
			}

			$zip->close();

			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename=' . $fileName);

			flush();
			readfile($pathFile . $fileName);
			// delete file
			unlink($pathFile . $fileName);
		} else {
			echo ("NO HAY DATOS PARA DESCARGAR ");
		}
	}
}
