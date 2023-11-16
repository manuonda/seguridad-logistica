<?php

namespace App\Controllers;

use App\Models\TramitePersonaModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\MovimientoPago;
use App\Models\TipoPagoModel;
use App\Models\RendidoEncabezadoModel;
use App\Models\DependenciaModel;
use App\Models\TramiteArchivoFirmaDigitalModel;
use App\Libraries\PagoBancoMacro;
use App\Libraries\UtilBancoMacro;
use App\Libraries\FechaUtil;
use App\Models\RendidoDetalleModel;
use App\Libraries\Pdf;
use App\Models\CategoriaRebaModel;
use App\Models\TramiteRebaModel;
use Exception;
use ZipArchive;

class Rendicion extends BaseController
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
	protected $encabezadoModel;
	protected $tramiteArchivoFirmaDigitalModel;

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
		$this->encabezado = new RendidoEncabezadoModel();
		$this->tramiteArchivoFirmaDigitalModel = new TramiteArchivoFirmaDigitalModel();
	}

	public function index($filter = null)
	{
		$tipoTramiteModel = new TipoTramiteModel();
		$tramiteRebaModel = new TramiteRebaModel();
		$categoriaRebaModel = new CategoriaRebaModel();


		if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == ROL_COMISARIA_SECCIONAL || session()->get('id_rol') == ROL_JEFE_UNIDAD_ADMINISTRATIVA
			|| session()->get('id_rol') == ROL_JEFE_DAP || session()->get('id_rol') == ROL_UAD_UNIDAD_REGIONAL_UR5 ||
			session()->get('id_rol') == ID_DEP_UAD_HUMAHUACA_UR3 || session()->get('id_rol') == ID_DEP_UAD_SAN_PEDRO_UR2 
			|| session()->get('id_rol') == ID_DEP_UAD_PERICO_UR6 || session()->get('id_rol') == ID_DEP_UAD_LGSM_UR4  )) {

			$id_rol = session()->get('id_rol');
			$filter = $this->session->get('filterTramites');
			$filterRendicion = $this->session->get('filterRendicion');


			$usuario  = $this->session->get('user');
			if ($filter == null) {
				$filter['idTramite'] = null;
				$filter['cuil'] = null;
				$filter['nombre'] = null;
				$filter['apellido'] = null;
				$filter['idTipoPago'] = TIPO_PAGO_CONTADO;
				$filter['idTipoTramite'] = null;
				$filter['idTramite'] = null;
				$filter['cuil']         = null;
				$filter['nombre']       = null;
				$filter['apellido']     = null;
				$filter['fechaDesde']     = null;
				$filter['fechaHasta']     = date('d/m/Y');
				$filter['idTipoTramite'] =  null;
				$filter['idTipoPago']     = null;
				$filter['fechaDesde']     = null;
				$filter['fechaHasta']     = null;
				$filter['estadoPago']    = ESTADO_PAGO_PAGADO;
				$filter['estadoTramite'] =  null;
				$filter['documento'] =  null;
				$filter['rendicion'] = true;
				$filter['idDependencia'] = $usuario['id_dependencia'];
			} else {

				$filter['fechaHasta'] = date('d/m/Y');
				$fechaHasta = $this->request->getVar('fechaHasta');
				$filter['fechaHasta'] = $fechaHasta;
			}

			if ($filterRendicion == null) {
				$filterRendicion['fechaDesde']     = null;
				$filterRendicion['fechaHasta']     =  null;
				$filterRendicion['id_dependencia'] = $usuario['id_dependencia'];
			}
            
			if ($usuario['id_dependencia'] != null && $usuario['id_dependencia'] == "") {
				log_message("error","id_depencia null". $usuario['id_rol']);
				 throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}
			$filter['idDependencia'] = $usuario['id_dependencia'];

			//obtengo cantidad de registros
			$cantidad = sizeof($this->tramiteModel->get_cantidad_rows($filter));


			//obtenemos los tramites
			$tramites = $this->tramiteModel->search_rendicion($filter, null);
			$cantTramites = $tramites != null ? sizeof($tramites) : 0;
			$total  = 0;
			if ($tramites != null && $tramites && sizeof($tramites) > 0) {
				$total = 0;
				$price = 0;
				foreach ($tramites as $tramite) {

					if ( $usuario['id_dependencia'] ==  ID_DEP_UAD_HUMAHUACA_UR3 || $usuario['id_dependencia'] == ID_DEP_UAD_SAN_PEDRO_UR2
					    || $usuario['id_dependencia'] ==  ID_DEP_UAD_PERICO_UR6 || $usuario['id_dependencia'] == ID_DEP_UAD_LGSM_UR4 || $usuario['id_dependencia'] == ID_DEP_UAD_PALPALA_UR8) {
						if( $tramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA) {
						  $price  = $this->calcularPrecio($tramite);
						  $total = $total + $price;
						}
					 } else {
						 $price  = $this->calcularPrecio($tramite);
						 $total = $total + $price;
					 }
					
				
				}
			}
			session()->set('filterTramites', $filter);
			session()->set('filterRendicion', $filterRendicion);


			$data['dependencias'] = $this->dependenciaModel->findAll();
			$data['tipoPagos'] = $this->tipoPagoModel->findAll();
			$data['totalImporte'] = $total;
			$data['estadoPagos']  = $this->getSelectEstadosPago();
			$data['estadoTramites'] = $this->getSelectEstadosTramite();
			$data['rol'] = $id_rol;
			// 		$data['titulo'] = "Infracciones / Viales";
			$data['filter'] = $filter;
			$data['contenido'] = "rendicion/index";

			// 		echo view("backend", $data);
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
		session()->set('filterTramites', null);
		$this->index();
	}

	public function limpiarRendicion()
	{
		session()->set('filterRendicion', null);
		$this->index();
	}

	/**Funcion que permite obtener los 
	 * datos a filtrar de la busqueda mediante 
	 * post
	 * @param : post, parameters
	 */
	public function buscar()
	{
		if (session()->get('isLoggedIn') == NULL) {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		$usuario  = $this->session->get('user');

		$filter['idTramite'] = null;
		$filter['cuil'] = null;
		$filter['nombre'] = null;
		$filter['apellido'] = null;
		$filter['idTipoPago'] = TIPO_PAGO_CONTADO;
		$filter['idTipoTramite'] = null;
		$filter['idTramite'] = null;
		$filter['cuil']         = null;
		$filter['nombre']       = null;
		$filter['apellido']     = null;

		$filter['idTipoTramite'] =  null;
		$filter['idTipoPago']     = TIPO_PAGO_CONTADO;
		//$filter['fechaDesde']     = $_POST['fechaDesde'];
		$filter['fechaDesde']     = null;
		$filter['fechaHasta']     = $_POST['fechaHasta'];
		$filter['idDependencia']  = null;
		$filter['estadoPago']    = ESTADO_PAGO_PAGADO;
		$filter['estadoTramite'] =  null;
		$filter['documento'] =  null;
		$filter['rendicion'] = true;
		$filter['idDependencia'] = $usuario['id_dependencia'];

		session()->set('filterTramites', $filter);
		$this->index($filter);
	}

	public function buscarRendicion()
	{
		if (session()->get('isLoggedIn') == NULL) {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$usuario  = $this->session->get('user');
		//$filter['fechaDesde']     = $_POST['fechaDesde'];
		$filter['fechaHasta']     = $_POST['fechaHasta'];
		$filter['id_dependencia'] = $usuario['id_dependencia'];

		session()->set('filterRendicion', $filter);
		$this->index($filter);
	}


	/**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de infracciones
	 **/
	public function pagination()
	{
		//$config = $this->get_configuration(); 
		$filter = session()->get('filterTramites');
		$usuario  = $this->session->get('user');

		if ($usuario['id_dependencia'] != null && $usuario['id_dependencia'] == "") {
			log_message("error","pagination null". $usuario['id_rol']);
			 throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		//filter vial 
		if ($filter == null) {
			$filter['idTramite'] = null;
			$filter['cuil'] = null;
			$filter['nombre'] = null;
			$filter['apellido'] = null;
			$filter['idTipoPago'] = TIPO_PAGO_CONTADO;
			$filter['idTipoTramite'] = null;
			$filter['idTramite'] = null;
			$filter['cuil']         = null;
			$filter['nombre']       = null;
			$filter['apellido']     = null;

			$filter['idTipoTramite'] =  null;
			$filter['idTipoPago']     = null;
			$filter['fechaDesde']     = null;
			$filter['fechaHasta']     = null;
			$filter['idDependencia']  = null;
			$filter['estadoPago']    = ESTADO_PAGO_PAGADO;
			$filter['estadoTramite'] =  null;
			$filter['documento'] =  null;
			$filter['rendicion'] = true;
			$filter['idDependencia'] = $usuario['id_dependencia'];
		}
		$filter['idDependencia'] = $usuario['id_dependencia'];


		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $this->tramiteModel->search($filter, $rowperpage);
		$tramites = [];

		foreach ($rows as $tramite) {
			if ( $usuario['id_dependencia'] == ID_DEP_UAD_HUMAHUACA_UR3 || $usuario['id_dependencia'] == ID_DEP_UAD_SAN_PEDRO_UR2 
			    || $usuario['id_dependencia'] ==  ID_DEP_UAD_PERICO_UR6 || $usuario['id_dependencia'] == ID_DEP_UAD_LGSM_UR4 || $usuario['id_dependencia'] == ID_DEP_UAD_PALPALA_UR8
			) {
				if ( $tramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA) {
			         $tramites[] = $this->get_format_row($tramite);
				}
			} else  {
			    $tramites[] = $this->get_format_row($tramite);
			}
		}
		// obtengo la cantidad sin filtrar para realizar la pagintation
		$cantidad = sizeof($this->tramiteModel->get_cantidad_rows($filter));

		// Initialize $data Array
		$data['pagination'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['tramites'] = $tramites;
		$data['page'] = $page;

		echo json_encode($data);
		return;
	}

	/**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de infracciones
	 **/
	public function paginationRendicion()
	{
		//$config = $this->get_configuration(); 
		$encabezadoModel = new RendidoEncabezadoModel();
		$filter = session()->get('filterRendicion');
		$usuario  = $this->session->get('user');

		if ($usuario['id_dependencia'] != null && $usuario['id_dependencia'] == "") {
			log_message("error","pagination rendicion null id_dependencia". $usuario['id_rol']);
			 throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		//filter vial 
		if ($filter == null) {
			$filter['fechaDesde']     = null;
			$filter['fechaHasta']     = null;
			$filter['id_dependencia'] = $usuario['id_dependencia'];
		}


		$filter['id_dependencia'] = $usuario['id_dependencia'];

		// Row per page
		$rowperpage = 20;
		$page = intval($this->request->getVar('page'));

		//obtenemos los tramites
		$rows = $encabezadoModel->search($filter, $rowperpage);
		$rendiciones = [];
		foreach ($rows as $tramite) {
			 $rendiciones[] = $this->get_format_row_rendicion($tramite);
		}
	
		// obtengo la cantidad sin filtrar para realizar la pagintation
		$cantidad = sizeof($encabezadoModel->get_cantidad_rows($filter));

		// Initialize $data Array
		$data['paginationRendicion'] = $this->pager->makeLinks($page, $rowperpage, $cantidad);
		$data['rendiciones'] = $rendiciones;
		$data['pageRendicion'] = $page;

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

		$fechaPago = $tramite['fecha_pago'] != null && $tramite['fecha_pago'] != '' ? date_format(date_create($tramite['fecha_pago']), 'd/m/Y H:m')
			: '';

        
		$price = $this->calcularPrecio($tramite);

        

		$row = '<tr>' .
			'<td>' . $tramite['id_tramite']  . '</td>' .
			'<td>' . $tramite['tipo_tramite'] .'</td>' .
			// '<td>' . date_format(date_create($tramite['fecha_alta']), 'd/m/Y H:m') . '</td>' .
			'<td>' . $fechaPago . '</td>' .
			'<td>' . $tramite['nombreTipoPago'] . '</td>' .
			'<td>' . $referencia_pago . '</td>' .
			'<td>' . $tramite['documento']  .'</td>' .
			'<td>' . $tramite['apellido'] . ' , ' . $tramite['nombre'] . '</td>' .
			//'<td>' . $link.'</td>'.
			'<td>' . $estado . '</td>' .
			'<td>' . $pago . '</td>' .
			'<td> $' . $price  . '</td>' .
			'<td width="100">' .
			'<div class="text-center">';


		$row = $row . '<span class="badge badge-secondary"><h8>NO RENDIDO</h8></span>';



		$row = $row . '</div></td></tr>';

		return $row;
	}

	/**
	 * Funcion que permite establecer el format_row
	 */
	private function get_format_row_rendicion($rendicion)
	{
		$fechaRendicionDesde = $rendicion['fecha_rendicion_desde'] != null && $rendicion['fecha_rendicion_desde'] != '' ? date_format(date_create($rendicion['fecha_rendicion_desde']), 'd/m/Y')
			: '---';
		$fechaRendicionHasta = $rendicion['fecha_rendicion_hasta'] != null && $rendicion['fecha_rendicion_hasta'] != '' ? date_format(date_create($rendicion['fecha_rendicion_hasta']), 'd/m/Y')
			: '---';
		$fecha = $rendicion['fecha_alta'] != null && $rendicion['fecha_alta'] != '' ? date_format(date_create($rendicion['fecha_alta']), 'd/m/Y')
			: '---';


		$row = '<tr>' .
			'<td>' . $rendicion['id_rendicion_encabezado']  . '</td>' .
			//'<td>' . $fechaRendicionDesde . '</td>' .
			'<td>' . $fechaRendicionHasta . '</td>' .
			// '<td>' . date_format(date_create($rendicion['fecha_alta']), 'd/m/Y H:m') . '</td>' .
			'<td> $' . number_format($rendicion['total'], 2, ",", ".")  . '</td>' .

			// '<td>' . $fecha . '</td>' .
			'<td width="100">' .
			'<div class="text-center">';

		$row = $row . '<span><a href="' . base_url() . '/rendicion/rendicionpdf?id_encabezado=' . $rendicion['id_rendicion_encabezado'] . '" title="Descargar Rendicion"><span class="oi oi-document" style="color:#3380FF"></span></a></span>';
		$row = $row . '</div></td></tr>';

		return $row;
	}


	public function operation()
	{
		$usuario  = $this->session->get('user');
		$tramiteModel = new TramiteModel();
		$fechaDesde = $this->request->getVar('fecha_desde');
		$fechaHasta  = $this->request->getVar('fecha_hasta');
		$rendicionEncabezado = new RendidoEncabezadoModel();
		$rendicionDetalle = new RendidoDetalleModel();
		$idEncabezado  = "";

		

		$filter['idTramite'] = null;
		$filter['cuil'] = null;
		$filter['nombre'] = null;
		$filter['apellido'] = null;
		$filter['idTipoPago'] = TIPO_PAGO_CONTADO;
		$filter['idTipoTramite'] = null;
		$filter['idTramite'] = null;
		$filter['cuil']         = null;
		$filter['nombre']       = null;
		$filter['apellido']     = null;

		$filter['idTipoTramite'] =  null;
		$filter['idTipoPago']     = null;
		$filter['fechaDesde']     = null;
		$filter['fechaHasta']     = $fechaHasta;
		$filter['estadoPago']    = ESTADO_PAGO_PAGADO;
		$filter['estadoTramite'] =  null;
		$filter['documento'] =  null;
		$filter['rendicion'] = true;
		$filter['idDependencia'] = $usuario['id_dependencia'];

		$tramites = $tramiteModel->search_rendicion($filter, null);
		$cantTramites = $tramites != null ? sizeof($tramites) : 0;
		$total  = 0;
		if ($tramites != null && $tramites && sizeof($tramites) > 0) {
			$total = 0;
			$price = 0;

		
			foreach ($tramites as $tramite) {

				if ( $usuario['id_dependencia'] ==  ID_DEP_UAD_HUMAHUACA_UR3 || $usuario['id_dependencia'] == ID_DEP_UAD_SAN_PEDRO_UR2
				    || $usuario['id_dependencia'] ==  ID_DEP_UAD_PERICO_UR6 || $usuario['id_dependencia'] == ID_DEP_UAD_LGSM_UR4 || $usuario['id_dependencia'] == ID_DEP_UAD_PALPALA_UR8) {
				   if( $tramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA) {
                    $price  = $this->calcularPrecio($tramite);
					 $total = $total + $price;
				   }
				} else {
					$price  = $this->calcularPrecio($tramite);
					$total = $total + $price;
				}
			}
		}

		return $this->response->setJSON(
			array(
				'tramites' => $cantTramites,
				'total' => $total
			)
		);
	}

	public function realizarrendicion()
	{

		$usuario  = $this->session->get('user');
		$tramiteModel = new TramiteModel();
		$fechaDesde = $this->request->getVar('fecha_desde');
		$fechaHasta  = $this->request->getVar('fecha_hasta');
		$rendicionEncabezado = new RendidoEncabezadoModel();
		$rendicionDetalle = new RendidoDetalleModel();
		$idEncabezado  = "";

		if ($usuario['id_dependencia'] != null && $usuario['id_dependencia'] == "") {
			log_message("error","realizar rendicion null". $usuario['id_rol']);
			 throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$filter['idTramite'] = null;
		$filter['cuil'] = null;
		$filter['nombre'] = null;
		$filter['apellido'] = null;
		$filter['idTipoPago'] = TIPO_PAGO_CONTADO;
		$filter['idTipoTramite'] = null;
		$filter['idTramite'] = null;
		$filter['cuil']         = null;
		$filter['nombre']       = null;
		$filter['apellido']     = null;

		$filter['idTipoTramite'] =  null;
		$filter['idTipoPago']     = null;
		$filter['fechaDesde']     = $fechaDesde;
		$filter['fechaHasta']     = $fechaHasta;
		$filter['idDependencia']  = null;
		$filter['estadoPago']    = ESTADO_PAGO_PAGADO;
		$filter['estadoTramite'] =  null;
		$filter['documento'] =  null;
		$filter['rendicion'] = true;
		$filter['idDependencia'] = $usuario['id_dependencia'];

		$tramites = $tramiteModel->search_rendicion($filter, null);


		if ($tramites && sizeof($tramites) > 0) {
			//creacion encabezado 
			$encabezado['fecha_rendicion_desde'] = isset($fechaDesde) && !empty($fechaDesde) ? $fechaDesde : NULL;
			$encabezado['fecha_rendicion_hasta'] =  $fechaHasta;
			$encabezado['fecha_alta'] =  date('Y-m-d H:i:s');
			$encabezado['usuario_alta'] = session()->get('id');
			$encabezado['id_dependencia'] = $usuario['id_dependencia'];

			$total = 0;
			$price = 0;

			foreach ($tramites as $tramite) {

				// $price  = $this->calcularPrecio($tramite);
				// $total = $total + $price;

				if ( $usuario['id_dependencia'] ==  ID_DEP_UAD_HUMAHUACA_UR3 || $usuario['id_dependencia'] == ID_DEP_UAD_SAN_PEDRO_UR2
				    || $usuario['id_dependencia'] ==  ID_DEP_UAD_PERICO_UR6 || $usuario['id_dependencia'] == ID_DEP_UAD_LGSM_UR4 || $usuario['id_dependencia'] == ID_DEP_UAD_PALPALA_UR8
				) {
					if( $tramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA) {
					  $price  = $this->calcularPrecio($tramite);
					  $total = $total + $price;
					}
				 } else {
					 $price  = $this->calcularPrecio($tramite);
					 $total = $total + $price;
				 }
			}
			$encabezado['total'] = $total;

			$idEncabezado = $rendicionEncabezado->insert($encabezado);

			foreach ($tramites as $tramite) {
				$detalle = [];
				$detalle['id_rendicion_encabezado'] = $idEncabezado;
				$detalle['id_tramite'] = $tramite['id_tramite'];
                $price = 0 ;
				// utilizado para insertar registro que no este en las DEP  y su tramite no sea Reba
				$insertarRegistro = true;
				if ( $usuario['id_dependencia'] ==  ID_DEP_UAD_HUMAHUACA_UR3 || $usuario['id_dependencia'] == ID_DEP_UAD_SAN_PEDRO_UR2
				    || $usuario['id_dependencia'] ==  ID_DEP_UAD_PERICO_UR6 || $usuario['id_dependencia'] == ID_DEP_UAD_LGSM_UR4 || $usuario['id_dependencia'] == ID_DEP_UAD_PALPALA_UR8
				) {
					if( $tramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA) {
					  $price  = $this->calcularPrecio($tramite);
					} else {
						$insertarRegistro =false;
					}
				 } else {
					 $price  = $this->calcularPrecio($tramite);
					
				 }

                if($insertarRegistro) {
					$detalle['importe'] = $price;
					$detalle['fecha_pago'] = $tramite['fecha_pago'];
					//localmente no funciona con : true - pero funcioan con el 1
					$detalle['rendido']   = 1;
					$detalle['fecha_alta'] =  date('Y-m-d H:i:s');
					$detalle['usuario_alta'] = session()->get('id');
					$rendicionDetalle->insert($detalle);
				}
			
				$tramite['rendido'] = 1;
				$tramiteModel->set($tramite);
				$tramiteModel->where('id_tramite', $tramite['id_tramite']);
				$tramiteModel->update();
			}
		}

		return $this->response->setJSON(array('id_operation' => $idEncabezado));
	}

	public function rendicionpdf()
	{

		$usuario = $this->session->get('user');
		$encabezadoModel = new RendidoEncabezadoModel();
		$detalleModel    = new RendidoDetalleModel();
		$fechaUtil = new FechaUtil();

		$usuario  = $this->session->get('user');

		// $idEncabezado = $this->request->getVar('id_encabezado');
		$idEncabezado = $this->request->getVar('id_encabezado');
		$encabezado =  $encabezadoModel->find($idEncabezado);
		$detalles  = $detalleModel->getByIdEncabezado($idEncabezado);
		$linea = sizeof($detalles);
		$idTipoTramite = null;
		$cantidad = 0;
		$importe = 0;
		$resumenes = [];
		$detalleStr = "";
		$paso2 = false;

		foreach ($detalles as $detalle) {
			$detalleStr = $detalle['tipo_tramite'];
			$encontrado = false;
			for ($i = 0; $i < sizeof($resumenes); $i++) {

				$detalleCompara = $resumenes[$i];
				if ($detalle['id_tipo_tramite'] == $detalleCompara['id_tipo_tramite']) {
					$importe  = $detalleCompara['importe'] + $this->calcularPrecio($detalle);
					$cantidad = $detalleCompara['cantidad'] + 1;

					$detalleCompara['importe'] = $importe;
					$detalleCompara['cantidad'] = $cantidad;
					$detalleCompara['tipo_tramite'] = $detalle['tipo_tramite'];

					$resumenes[$i] = $detalleCompara;
					$encontrado = true;
				}
			}
			if (!$encontrado) {

				//echo "<br> No existe dentro del array ";
				$detalleCompara = [
					'cantidad' => 1,
					'id_tipo_tramite' => $detalle['id_tipo_tramite'],
					'importe' => $detalle['importe'],
					'tipo_tramite' => $detalle['tipo_tramite']
				];
				$resumenes[] = $detalleCompara;
			}
		}


		$fecha = $fechaUtil->fechaCastellano(date('d-m-Y'));
		$cadena = '
            <span align="center" style="font-size:25px;">MINISTERIO DE SEGURIDAD</span>
            <br align="center">
            <span align="center" style="font-size:25px;">POLICIA DE LA PROVINCIA DE JUJUY</span>
            <br><hr>
            <h3><u>' . $usuario['dependencia'] . '</u>, ' . $fecha . '</h3>
            <br>
			<h3><u>Nro. de Rendición : ' . $encabezado['id_rendicion_encabezado'] . '</u></h3>
			<br><br>
            <h3>
            AL SEÑOR<br>
            DIRECTOR DE ADMINISTRACION Y FINANZAS<BR>
            <u>SU DESPACHO:</u><br>
            <pre>                                       Elevo a Ud., Planilla Semanal de Rendion de Fes 
con un Total a Rendir de pesos $' . $encabezado['total'] . ', para su conocimiento y demas fines que estime corresponder.-

                                       Atte.-</pre></h3>';

		$cadena .= '		    
            <h2 align="center" style="font-size:25px;">PLANILLA DE RENDICION</h2>
            <table align="center" cellspacing="0" cellpadding="1" style="border: 1px solid #000;" width="525" style="font-size:30px;">
                <tr>
                    <th style="font-size:10px;border: 1px solid #000;" width="40">Orden</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="100">Nro. Tramite</th>
					<th width="331" style="font-size:10px;" style="font-size:10px;border: 1px solid #000;" >Detalle</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="55">Importe</th>
                </tr>';

		$j = 0;
		$borde = 'border-bottom: 1px solid #000;';
		$orden = 1;

		foreach ($detalles as $detalle) {
			$cadena .= '<tr>';
			$cadena .= '<td style="border:1px solid #000; font-size:10px;">' . $orden . '</td>';
			$cadena .= '<td style="border:1px solid #000; font-size:10px;">' . $detalle['id_tramite'] . '</td>';
			$cadena .= '<td width="331" style="border:1px solid #000; font-size:10px;">' . $detalle['tipo_tramite'] . '</td>';
			$cadena .= '<td align="right" style="border:1px solid #000; font-size:10px;">$' . number_format($detalle['importe'], 2, ",", ".") . '</td>';
			$cadena .= '</tr>';
			$orden++;
		}

		$cadena .= '
                <tr>
                    <td align="right" colspan="3" style="border:1px solid #000; font-size:10px;">Total a Rendir</td>
                    <td align="right" style="border:1px solid #000; font-size:10px;">$' . number_format($encabezado['total'], 2, ",", ".") . '</td>
                </tr>
            </table>
            <h2 align="center" style="font-size:25px;">RESUMEN DE MOVIMIENTOS REALIZADOS</h2>
            <table align="center" cellspacing="0" cellpadding="1" border="1" width="525" style="font-size:30px;">
                <tr>
                    <th width="60"  style="font-size:10px;">CANTIDAD</th>
                    <th width="410" style="font-size:10px;">DETALLE</th>
                    <th width="55"  style="font-size:10px;">IMPORTE</th>
				</tr>';
		foreach ($resumenes as $resum) {

			$cadena .= '<tr>';
			$cadena .= '<td style="font-size:10px;">' . $resum['cantidad'] . '</td>';
			$cadena .= '<td style="font-size:10px;" align="lefth">' . $resum['tipo_tramite'] . '</td>';
			$cadena .= '<td style="font-size:10px;" align="right">$' . number_format($resum['importe'], 2, ",", ".") . '</td>';
			$cadena .= '</tr>';
		}
		$cadena .= '
                <tr>
                    <td align="right" colspan="2"  style="font-size:10px;">Total a Rendir</td>
                    <td align="right" style="font-size:10px;">$' . number_format($encabezado['total'], 2, ",", ".") . '</td>
                </tr>
            </table>         
        ';

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetAuthor('Policia de Jujuy');
		$pdf->SetTitle('Rendición FES');
		$pdf->SetSubject('Subject');
		$pdf->SetKeywords('keywords');
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->AddPage();
		$pdf->writeHTML($cadena, true, false, false, false, '');
		$pdf->Output('rendicion-' . $idEncabezado . '.pdf', 'D');
	}
}
