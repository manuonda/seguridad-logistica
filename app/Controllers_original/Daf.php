<?php

namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
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
use App\Libraries\Email;
use App\Libraries\FechaUtil;
use App\Models\RendidoDetalleModel;
use App\Libraries\Pdf;
use Exception;
use ZipArchive;

class Daf extends BaseController
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

	    if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == DAP_RENDICION || session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_DAF)) {

			$id_rol = session()->get('id_rol');
			$filter = $this->session->get('filter');

			$usuario  = $this->session->get('user');


			if ($filter == null) {
				$filter['numero'] = null;
				$filter['fechaDesde']     = null;
				$filter['fechaHasta']     =  null;
				$filter['id_dependencia'] = null;
			}


			session()->set('filter', $filter);

			$data['dependencias'] = $this->dependenciaModel->findAllHabilitado();
			//$data['dependencias'] = $this->dependenciaModel->findAll();

			$data['rol'] = $id_rol;
			$data['filter'] = $filter;
			$data['contenido'] = "rendicion_dap/index";

			// 		echo view("backend", $data);
			echo view("frontend", $data);
		} else {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	public function mostrar()
	{

	    if (!empty(session()->get('id_rol')) && (session()->get('id_rol') == DAP_RENDICION || session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_DAF)) {

			$idEncabezado = $this->request->getVar('id_encabezado');

			$encabezadoModel = new RendidoEncabezadoModel();
			$detalleModel    = new RendidoDetalleModel();
			
			$encabezado = $encabezadoModel->find($idEncabezado);
			$dependenciaModel = new DependenciaModel();
			$dependencia = $dependenciaModel->find($encabezado['id_dependencia']);   
			$detalles  = $detalleModel->getByIdEncabezado($idEncabezado);
			$cantidad = 0;
			$importe = 0;
			$resumenes = [];
		

			foreach ($detalles as $detalle) {
				$detalleStr = $detalle['tipo_tramite'];
				$encontrado = false;
				for ($i = 0; $i < sizeof($resumenes); $i++) {

					$detalleCompara = $resumenes[$i];
					if ($detalle['id_tipo_tramite'] == $detalleCompara['id_tipo_tramite']) {
						$importe = $detalleCompara['importe'] + $detalle['importe'];
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

			$data['encabezado']  = $encabezado;
			$data['resumenes'] = $resumenes;
			$data['detalles'] = $detalles;
			$data['dependencia'] = $dependencia;
		
			$data['contenido'] = "rendicion_dap/ver";

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
		session()->set('filter', null);
		$this->index();
	}

	public function limpiarRendicion()
	{
		session()->set('filter', null);
		$this->index();
	}


	public function buscar()
	{
		if (session()->get('isLoggedIn') == NULL) {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$usuario  = $this->session->get('user');
		// $filter['fechaDesde']     = $_POST['fechaDesde'];
		// $filter['fechaHasta']     = $_POST['fechaHasta'];
		$filter['id_dependencia'] = $_POST['id_dependencia'];
		$filter['numero'] = $_POST['numero'];

		session()->set('filter', $filter);
		$this->index($filter);
	}




	/**
	 * Funcion que permite obtener la 
	 * pagination de la tabla de infracciones
	 **/
	public function paginationRendicion()
	{
		//$config = $this->get_configuration(); 
		$encabezadoModel = new RendidoEncabezadoModel();
		$filter = session()->get('filter');
		$usuario  = $this->session->get('user');
		//filter vial 
		if ($filter == null) {
			$filter['fechaDesde']     = null;
			$filter['fechaHasta']     = null;
			$filter['id_dependencia'] = null;
			$filter['numero'] = null;
		}

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

		//var_dump($rendiciones);
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
	private function get_format_row_rendicion($rendicion)
	{
		$fechaRendicionDesde = $rendicion['fecha_rendicion_desde'] != null && $rendicion['fecha_rendicion_desde'] != '' ? date_format(date_create($rendicion['fecha_rendicion_desde']), 'd/m/Y')
			: '---';
		$fechaRendicionHasta = $rendicion['fecha_rendicion_hasta'] != null && $rendicion['fecha_rendicion_hasta'] != '' ? date_format(date_create($rendicion['fecha_rendicion_hasta']), 'd/m/Y')
			: '---';
		$fecha = $rendicion['fecha_alta'] != null && $rendicion['fecha_alta'] != '' ? date_format(date_create($rendicion['fecha_alta']), 'd/m/Y')
			: '---';
		$estadoRendicion = "";
		if ($estadoRendicion = $rendicion['estado_rendicion'] == null || $rendicion['estado_rendicion'] == 0) {

			$estadoRendicion = '<span  id="rendicion-desaprobada-' . $rendicion['id_rendicion_encabezado'] . '" class="badge badge-secondary"><h8>SIN PRESENTAR</h8></span>' .
				'<span  style="display: none;" id="rendicion-aprobada-' . $rendicion['id_rendicion_encabezado'] . '" class="badge badge-success"><h8>PRESENTADO</h8></span>';
		} else {
			$estadoRendicion = '<span id="rendicion-aprobada-' . $rendicion['id_rendicion_encabezado'] . '" class="badge badge-success"><h8>PRESENTADO</h8></span>';
		}

		$row = '<tr>' .
			'<td>' . $rendicion['id_rendicion_encabezado']  . '</td>' .
			//'<td>' . $fechaRendicionDesde . '</td>' .
			'<td>' . $fechaRendicionHasta . '</td>' .
			// '<td>' . date_format(date_create($rendicion['fecha_alta']), 'd/m/Y H:m') . '</td>' .
			'<td>' . $rendicion['total'] . '</td>' .
			'<td>' . $rendicion['dependencia'] . '</td>' .
			'<td>' . $estadoRendicion . '</td>' .
			'<td width="300">' .
			'<div class="text-center">';
		if (($rendicion['estado_rendicion'] == null || $rendicion['estado_rendicion'] == 0) && (session()->get('id_rol')== DAP_RENDICION || session()->get('id_rol')== ROL_DAF)) {
			$row = $row . '<button type="button" style="padding: .315rem .25rem;margin-left:0.5rem" id="btn-rendicion-' . $rendicion['id_rendicion_encabezado'] . '" class="btn btn-primary" onclick="aprobar(' . $rendicion['id_rendicion_encabezado'] . ')">
				<span class="oi oi-check"></span>
				Aprobar</button>';
		}
		$row = $row . '<span><a class="btn btn-secondary" style="padding: .315rem .25rem;margin-left:0.5rem" href="' . base_url() . '/daf/mostrar?id_encabezado=' . $rendicion['id_rendicion_encabezado'] . '" title="Mostrar Rendicion"><span class="oi oi-eye"></span>Ver</a></span>';
		$row = $row . '<span><a class="btn btn-info" style="padding: .315rem .25rem; margin-left:0.5rem" href="' . base_url() . '/daf/rendicionpdf?id_encabezado=' . $rendicion['id_rendicion_encabezado'] . '" title="Descargar Rendicion"><span class="oi oi-data-transfer-download"></span>Descargar</a></span>';

		$row = $row . '</div></td></tr>';

		return $row;
	}


	public function aprobarRendicion()
	{

		$usuario  = $this->session->get('user');
		$idEncabezado = $this->request->getVar('idEncabezado');
		$rendicionEncabezadoModel = new RendidoEncabezadoModel();

		$encontradoEncabezado = $rendicionEncabezadoModel->find($idEncabezado);
		if ($encontradoEncabezado) {
			$encontradoEncabezado['estado_rendicion'] = 1;
			$encontradoEncabezado['usuario_modificacion'] = session()->get('id');
			$encontradoEncabezado['fecha_modificacion'] = date('Y-m-d H:i:s');
			$rendicionEncabezadoModel->update($idEncabezado, $encontradoEncabezado);
			return $this->response->setJSON(array(
				'id_operation' => $idEncabezado,
				'status' => "OK"
			));
		}
		return $this->response->setJSON(array('id_operation' => $idEncabezado));
	}


	public function rendicionpdf()
	{

		$usuario = $this->session->get('user');
		$encabezadoModel = new RendidoEncabezadoModel();
		$detalleModel    = new RendidoDetalleModel();
		$fechaUtil = new FechaUtil();
		$dependenciaModel = new DependenciaModel();

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
					$importe = $detalleCompara['importe'] + $detalle['importe'];
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

        $dependencia = $dependenciaModel->find($encabezado['id_dependencia']);
		$fecha = $fechaUtil->fechaCastellano(date('d-m-Y'));
		$cadena = '
            <span align="center" style="font-size:25px;">MINISTERIO DE SEGURIDAD</span>
            <br align="center">
            <span align="center" style="font-size:25px;">POLICIA DE LA PROVINCIA DE JUJUY</span>
            <br><hr>
            <h3><u>' . $dependencia['dependencia'] . '</u>, ' . $fecha . '</h3>
            <br>
			<h3><u>Nro. de Rendición : '.$encabezado['id_rendicion_encabezado'].'</u></h3>
			<br><br>
            <h3>
            AL SEÑOR<br>
            DIRECTOR DE ADMINISTRACION Y FINANZAS<BR>
            <u>SU DESPACHO:</u><br>
            <pre>                                       Elevo a Ud., Planilla Semanal de Rendion de Fes 
con un Total a Rendir de pesos $' . $encabezado['total'] . ',para su conocimiento y demas fines que estime corresponder.-

                                       Atte.-</pre></h3>';

		$cadena .= '		    
            <h2 align="center" style="font-size:25px;">PLANILLA DE RENDICION</h2>
            <table align="center" cellspacing="0" cellpadding="1" style="border: 1px solid #000;" width="525" style="font-size:30px;">
                <tr>
                    <th style="font-size:10px;border: 1px solid #000;" width="40">Orden</th>
                    <th style="font-size:10px;border: 1px solid #000;" width="100">Nro. Tramite</th>
                    <th width="331" style="font-size:10px;border: 1px solid #000;">Detalle</th>
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
			$cadena .= '<td align="right" style="border:1px solid #000; font-size:10px;">' . $detalle['importe'] . '</td>';
			$cadena .= '</tr>';
			$orden++;
		}

		$cadena .= '
                <tr>
                    <td align="right" colspan="3" style="border:1px solid #000; font-size:10px;">Total a Rendir</td>
                    <td align="right" style="border:1px solid #000; font-size:10px;">' . $encabezado['total'] . '</td>
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
			$cadena .= '<td style="font-size:10px;" align="right">' . $resum['importe'] . '</td>';
			$cadena .= '</tr>';
		}
		$cadena .= '
                <tr>
                    <td align="right" colspan="2"  style="font-size:10px;">Total a Rendir</td>
                    <td align="right" style="font-size:10px;">' . $encabezado['total'] . '</td>
                </tr>
            </table>         
        ';

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetAuthor('Policia de Jujuy');
		$pdf->SetTitle('Rendision FES');
		$pdf->SetSubject('Subject');
		$pdf->SetKeywords('keywords');
		$pdf->SetFont('helvetica', 'N', 8);
		$pdf->AddPage();
		$pdf->writeHTML($cadena, true, false, false, false, '');
		$pdf->Output('rendicion-' . $idEncabezado . '.pdf', 'D');
	}
}
