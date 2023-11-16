<?php
namespace App\Controllers;

use App\Models\TramiteModel;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
use App\Models\RendidoEncabezadoModel;
use App\Models\RendidoDetalleModel;

class DafVentanilla extends BaseController {

    public function index() {
        if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_DAF) {
            $data['contenido'] = "daf/ventanilla";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function buscar() {
        if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_DAF) {
            $documento = $this->request->getVar('documento');
            $tramite = $this->request->getVar('tramite');
            $this->listado($documento,$tramite);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function listado($documento, $tramite) {
        $tramiteModel = new TramiteModel();
        $tramiteModel->select('tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite, 
                                                  tramite_online.tramites.id_tipo_tramite, tramite_online.tramites.fecha_alta, 
                                                  tramite_online.tramite_personas.cuil, tramite_online.tramites.fecha_pago, tramite_online.tramites.id_tipo_pago,
                                                  tramite_online.tramites.urgente,
                                                  tramite_online.tramites.precio as precio_planilla,
                                                  tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                                  tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, 
                                                  tramite_online.tipo_tramites.importe_adicional,
                                                  tramite_online.tipo_tramites.controlador')
                                                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                                ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
//                                                 ->whereIn('tramite_online.tramites.estado_pago', [ESTADO_PAGO_PENDIENTE, ESTADO_PAGO_IMPAGO])
                                                ->whereNotIn('tramite_online.tramites.estado_pago', [ESTADO_PAGO_PAGADO])
                                                ->whereIn('tramite_online.tramites.id_dependencia', [ID_DEP_UAD_CENTRAL, 920]) // 920 UAD MOVIL
                                                ->where('tramite_online.tramites.rendido', null)
//                                                 ->where('tramite_online.tramites.fecha_pago>', '2022-05-22 23:59:59')
                                                ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);

        if(isset($documento) && !empty($documento)) {
            $tramiteModel->where('tramite_online.tramite_personas.documento', $documento);
        }

        if (isset($tramite) && !empty($tramite)){
            $tramiteModel->where('tramite_online.tramites.id_tramite', $tramite);
        }

        $data['listado'] =  $tramiteModel->orderBy('tramite_online.tramites.fecha_alta', 'desc')->findAll();
        $data['documento'] = $documento;
        $data['tramite'] = $tramite;
        $data['contenido'] = "daf/ventanilla";
        echo view("frontend", $data);
    }
    
    public function tramitesCobrados() {
        if(!empty(session()->get('id_rol')) && session()->get('id_rol')==ROL_DAF) {
            $tramiteModel = new TramiteModel();
            $tramiteModel->select('tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite,
                                  tramite_online.tramites.id_tipo_tramite,
                                  tramite_online.tramites.fecha_pago, tramite_online.tramites.id_tipo_pago,
                                  tramite_online.tramites.urgente, tramite_online.tramites.estado,
                                  tramite_online.tramites.precio as precio_planilla,
                                  tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, 
                                  tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio,
                                  tramite_online.tipo_tramites.importe_adicional')
                                  ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                  ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                  ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                                  ->where('tramite_online.tramites.estado_pago', ESTADO_PAGO_PAGADO)
                                  ->where('tramite_online.tramites.id_dependencia_de_cobro', ID_DEP_DAF)
                                  ->where('tramite_online.tramites.rendido', null)
                                  ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);
            
            $data['listado'] =  $tramiteModel->orderBy('tramite_online.tramites.fecha_pago', 'asc')->findAll();
            $data['contenido'] = "daf/tramites_cobrados";
            echo view("frontend", $data);
            
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    private function guardarCierre() {
        $tramiteModel = new TramiteModel();
        $tramiteModel->select('tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite,
                                  tramite_online.tramites.id_tipo_tramite,
                                  tramite_online.tramites.fecha_pago,
                                  tramite_online.tramites.urgente, tramite_online.tramites.estado,
                                  tramite_online.tramites.precio as precio_planilla,
                                  tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento,
                                  tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio,
                                  tramite_online.tipo_tramites.importe_adicional')
                                  ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                  ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                  ->where('tramite_online.tramites.estado_pago', ESTADO_PAGO_PAGADO)
                                  ->where('tramite_online.tramites.id_dependencia_de_cobro', ID_DEP_DAF)
                                  ->where('tramite_online.tramites.rendido', null)
                                  ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);
        
        $tramites =  $tramiteModel->orderBy('tramite_online.tramites.fecha_pago', 'desc')->findAll();
        $usuario  = $this->session->get('user');
        
        if ($tramites && sizeof($tramites) > 0) {
            //creacion rendicion encabezado
            $rendicionEncabezado = new RendidoEncabezadoModel();
            $rendicionDetalle = new RendidoDetalleModel();
            $encabezado['fecha_rendicion_desde'] = date('Y-m-d H:i:s');
            $encabezado['fecha_rendicion_hasta'] = date('Y-m-d H:i:s');
            $encabezado['fecha_alta'] =  date('Y-m-d H:i:s');
            $encabezado['usuario_alta'] = session()->get('id');
            $encabezado['id_dependencia'] = $usuario['id_dependencia'];
            
            $total = 0;
            $price = 0;
            
            foreach ($tramites as $tramite) {
                $price  = $this->calcularPrecio($tramite);
                $total = $total + $price;
            }
            
            $encabezado['total'] = $total;
            $idEncabezado = $rendicionEncabezado->insert($encabezado);
            
            // creacion de rendicion detalle
            foreach ($tramites as $tramite) {
                $detalle = [];
                $detalle['id_rendicion_encabezado'] = $idEncabezado;
                $detalle['id_tramite'] = $tramite['id_tramite'];
                $price  = $this->calcularPrecio($tramite);
                    
                $detalle['importe'] = $price;
                $detalle['fecha_pago'] = $tramite['fecha_pago'];
                //localmente no funciona con : true - pero funcioan con el 1
                $detalle['rendido']   = 1;
                $detalle['fecha_alta'] =  date('Y-m-d H:i:s');
                $detalle['usuario_alta'] = session()->get('id');
                $rendicionDetalle->insert($detalle);
                
                $tramite['rendido'] = 1;
                $tramiteModel->set($tramite);
                $tramiteModel->where('id_tramite', $tramite['id_tramite']);
                $tramiteModel->update();
            }
            
            return $idEncabezado;
        }
        return null;
    }
    
    public function verCierreCaja($idEncabezado) {    
        if(empty($idEncabezado)) {
            return redirect()->to('/home/error/1');
        }else {
            $this->generarRendicion($idEncabezado);
        }
    }
    
    public function cerrarCaja() {
        try {
            $idEncabezado = $this->guardarCierre();
            if(empty($idEncabezado)) {
                return redirect()->to('/home/error/1');
            }else {
                $this->generarRendicion($idEncabezado);
            }
        } catch (Exception $e) {
            return redirect()->to('/home/error/1');
        }
    }
    
    private function generarRendicion($idEncabezado) {
        if(empty($idEncabezado)) {
            return redirect()->to('/home/error/1');
        }

        $encabezadoModel = new RendidoEncabezadoModel();
        $detalleModel    = new RendidoDetalleModel();
//         $idEncabezado = $this->guardarCierre();
        $encabezado =  $encabezadoModel->find($idEncabezado);
        $detalles  = $detalleModel->getByIdEncabezado($idEncabezado);
        
        $cantidad = 0;
        $importe = 0;
        $resumenes = [];
        
        foreach ($detalles as $detalle) {
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
        
        $usuario  = $this->session->get('user');
        $fechaUtil = new FechaUtil();
        $fecha = $fechaUtil->fechaCastellano(date('d-m-Y'));
//         $encabezado['id_rendicion_encabezado'] = 123;
//         $encabezado['total'] = 999;
        $cadena = '
            <span align="center" style="font-size:20px;">MINISTERIO DE SEGURIDAD</span>
            <br align="center">
            <span align="center" style="font-size:20px;">POLICIA DE LA PROVINCIA DE JUJUY</span>
            <br><hr>
            <h3><u>' . $usuario['dependencia'] . '</u>, ' . $fecha . '</h3>
            <br>
			<h3><u>Nro. de Rendición : ' . $encabezado['id_rendicion_encabezado'] . '</u></h3>
			<br><br>
            <h3>
            AL SEÑOR<br>
            DIRECTOR DE ADMINISTRACION Y FINANZAS<BR>
            <u>SU DESPACHO:</u><br>
            <pre>                                       Elevo a Ud., extracto de los tramites cobrados por ventanilla por un Total de pesos $ ' . $encabezado['total'] . ', para su conocimiento y  demas fines que estime corresponder.-

                                       Atte.-</pre></h3>';
        
        $cadena .= '
            <h2 align="center" style="font-size:20px;">PLANILLA DE RENDICION</h2>
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
            <h2 align="center" style="font-size:20px;">RESUMEN DE MOVIMIENTOS REALIZADOS</h2>
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
        $pdf->SetTitle('Cierre de caja');
        $pdf->SetSubject('Subject');
        $pdf->SetKeywords('keywords');
        $pdf->SetFont('helvetica', 'N', 8);
        $pdf->AddPage();
        $pdf->writeHTML($cadena, true, false, false, false, '');
        ob_end_clean();    
        $pdf->Output('cierre-caja-' . $idEncabezado . '.pdf', 'I');
        ob_end_flush();
    }
    
    public function cerrarCaja222() {
        
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
