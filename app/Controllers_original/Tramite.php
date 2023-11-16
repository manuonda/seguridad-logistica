<?php 
namespace App\Controllers;
use App\Models\TipoTramiteModel;
use App\Models\TramiteArchivoModel;
use App\Models\TramiteModel;
use App\Models\TramitePersonaModel;
use App\Models\TramiteArchivoFirmaDigitalModel;
use App\Models\TipoDocumentoModel;
use App\Libraries\Util;
use App\Libraries\Pdf;
use App\Models\DependenciaModel;
use App\Models\TramitePlanillaDetalleModel;

ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);
class Tramite extends BaseController {
    
    protected $tramiteModel;
    protected $util;
    protected $session;
    protected $userInSession;
    
    public function __construct() {
        $this->tramiteModel = new TramiteModel();
        $this->util = new Util();
        $this->session = session();
        $this->userInSession = $this->session->get('user');
    }

    public function index() {
        if(empty($this->userInSession)) {
//             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            helper('form');
            $tipoTramiteModel = new TipoTramiteModel();
            $data['listaTipoTramites'] = $tipoTramiteModel->where('habilitado', 't')->findAll();
            $data['tramitePlanilla'] = $tipoTramiteModel->find(TIPO_TRAMITE_PLANILLA_PRONTUARIAL);
            $data['userInSession'] = $this->session->get('user');
            $data['contenido'] = "tramite";
            echo view("frontend", $data);
            echo "<script type='text/javascript'>
            $(document).ready(function(){
            $('#modalito').modal('show');
            });
            </script>";
        }else {            
            return redirect()->to(base_url());
        }
    }
    
    public function validar($codigo) { 
        $tramite = $this->tramiteModel->where('codigo', $codigo)->first();
        $data['ua'] = $this->request->getUserAgent();

        if(empty($tramite)) {
            $data['error'] = "EL DOCUMENTO ES INVALIDO";
            $data['contenido'] = "validacion_view";
            echo view("frontend", $data);
            return;
        }else {
            $tramiteArchivoModel = new TramiteArchivoModel();
            $tipoTramiteModel = new TipoTramiteModel();
            $tramitePersonaModel = new TramitePersonaModel();
            $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
            $titular_tramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
            
            $fotoColor       = $tramiteArchivoModel->findByIdTramiteByFoto($tramite['id_tramite'], FOTO_COLOR);
          
        if ($fotoColor != null && $fotoColor[0]->ruta != null && isset($fotoColor[0]->ruta) && file_exists($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre)) {
            $image  =  file_get_contents($fotoColor[0]->ruta . "/" . $fotoColor[0]->nombre);
            $base64 = 'data:image/' . $fotoColor[0]->tipo . ';base64,' . base64_encode($image);
            $data['fotoColor'] = $base64;
            $data['fotoColorId'] = $fotoColor[0]->id_tramite_archivo;
        } else {
            $data['fotoColor'] = "";
            $data['fotoColorId'] = "";
        }


            $data['tipo_tramite'] = $tipoTramite['tipo_tramite'];
            $data['apellido_nombre'] = $titular_tramite['apellido'].', '.$titular_tramite['nombre'];
            $data['documento'] = $titular_tramite['documento'];
            $data['cuil'] = $titular_tramite['cuil'];
            $data['estado'] = $tramite['estado'];
            $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
            $data['fecha_envio_email'] = $tramite['fecha_envio_email'];
            $data['fecha_emision'] = $tramite['fecha_emision'];
            $data['contiene_firma_digital'] = $tramite['contiene_firma_digital'];
            
            if ($tramite['estado_pago']=="PENDIENTE"){
                $data['estado_pago']= 'PENDIENTE DE PAGO';
            }else{
                $data['estado_pago']= $tramite['estado_pago'];
            
            }

            $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
            if ( $tramite['id_tipo_tramite'] == TIPO_TRAMITE_PLANILLA_PRONTUARIAL) {
                 // Antecedentes
                 $tramitePlanillaDetalleModel = new TramitePlanillaDetalleModel();
                 $tramitePlanillaDetalle = $tramitePlanillaDetalleModel->getByIdTramite($tramite['id_tramite']);
     
                 $antecedentesPenales = "NO REGISTRA";
                 $antecedentesPoliciales = "NO REGISTRA";
     
                 if ($tramitePlanillaDetalle !== NULL && $tramitePlanillaDetalle) {
                     $numero_prontuario = $tramitePlanillaDetalle[0]->num_prontuario != NULL ? $tramitePlanillaDetalle[0]->num_prontuario : " ";
                     $letra_prontuario  = $tramitePlanillaDetalle[0]->letra_prontuario != NULL ? $tramitePlanillaDetalle[0]->letra_prontuario : " ";
                     $prontuario = $numero_prontuario . " - " . $letra_prontuario;
                     $antecedentesPenales  = $tramitePlanillaDetalle[0]->antecedentes_penales;
                     $antecedentesPoliciales = $tramitePlanillaDetalle[0]->antecedentes_policiales;
                 }
                 
                 $data['antecedentes_penales'] =$antecedentesPenales;
                 $data['antecedentes_policiales'] =$antecedentesPoliciales;

            }
            
            
            $data['contenido'] = "validacion_view";
            echo view("frontend", $data);
            return;
        }
    }
    
    public function isPersonaValidada($cuil, $idTipoTramite) {
        return false;
//         $resultado = false;
//         $estadoTramite = null;
//         switch ($idTipoTramite) {
//             case TIPO_TRAMITE_CERTIFICADO_RESIDENCIA:
//                 $estadoTramite = TRAMITE_VALIDADO_VERIFICADO;
//                 break;
//             case TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA:
//                 $estadoTramite = TRAMITE_VALIDADO_VERIFICADO;
//                 break;
//             case TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA:
//                 $estadoTramite = TRAMITE_VALIDADO_VERIFICADO;
//                 break;
//             case TIPO_TRAMITE_CONSTANCIA_EXTRAVIO:
//                 $estadoTramite = TRAMITE_VALIDADO;
//                 break;
//             case TIPO_TRAMITE_PLANILLA_PRONTUARIAL:
//                 $estadoTramite = TRAMITE_VALIDADO_VERIFICADO; // verificado antecedentes penales y policiales.
//                 break;
//             case TIPO_TRAMITE_CONSTANCIA_DENUNCIA:
//                 $estadoTramite = TRAMITE_VALIDADO;
//                 break;
//             default:
//                 $estadoTramite = TRAMITE_VALIDADO;
//         }
        
//         $resultado = $this->tramiteModel->isPersonaValidada($cuil, $idTipoTramite, $estadoTramite);
//         echo json_encode($resultado);
    }
    
    public function imprimir($idTramite) {
         $model = new TramiteArchivoFirmaDigitalModel();
		$archivoTramiteDigital = $model->where('id_tramite', $idTramite)->first();
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

    
    public function crear() {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $tipoTramiteModel = new TipoTramiteModel();
            $tipoDocumentoModel = new TipoDocumentoModel();
//             $data['listaTipoTramites'] = $tipoTramiteModel->findAll();
            $idsTipoTramites = [TIPO_TRAMITE_PLANILLA_PRONTUARIAL];
            $data['listaTipoTramites'] = $tipoTramiteModel->where('habilitado', 't')->whereNotIn('id_tipo_tramite', $idsTipoTramites)->findAll();
            $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
            $data['id_tipo_documento'] = 1; 
            $data['contenido'] = "crear_tramite";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function editar($idTramite, $urlRedirec) {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA 
            || $this->userInSession['id_rol']==ROL_JEFE_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $filter = $this->session->get('filter');
            $filter['urlRedirec'] = $urlRedirec;
            session()->set('filter', $filter);
            
            $tramiteModel = new TramiteModel();
            $tipoTramiteModel = new TipoTramiteModel();
            $tipoDocumentoModel = new TipoDocumentoModel();
            $tramitePersonaModel = new TramitePersonaModel();
            $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
            
            $tramite = $tramiteModel->find($idTramite);
            $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
            
            $tipoTramite = $tipoTramiteModel->find($tramite['id_tipo_tramite']);
            $data['id_tramite'] = $tramite['id_tramite'];
            $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
            $data['tipo_tramite'] = $tipoTramite['tipo_tramite'];
            $data['id_dependencia'] = $tramite['id_dependencia'];
            $data['estado'] = $tramite['estado'];
            $data['observaciones'] = $tramite['observaciones'];
            $data['editor1'] = $tramite['observaciones'];
            $data['id_persona'] = $titular['id_persona'];
            $data['nombre'] = $titular['nombre'];
            $data['apellido'] = $titular['apellido'];
            $data['id_tipo_documento'] = $titular['id_tipo_documento'];
            $data['documento'] = $titular['documento'];
            $data['telefono'] = $titular['telefono'];
            $data['email'] = $titular['email'];
            
            $data['contenido'] = "tramite_general";
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function volver() {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA 
            || $this->userInSession['id_rol']==ROL_JEFE_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $filter = $this->session->get('filter');
            if ($filter != null && !empty($filter['urlRedirec'])) {
                return redirect()->to(base_url().'/'.$filter['urlRedirec']);
            }else {
                return redirect()->to(base_url());
            }
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function cargarDatos() {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $validation =  \Config\Services::validation();
            $validation->setRules([
                'id_tipo_tramite' => ['label' => 'Tipo tramite', 'rules' => 'required|numeric'],
                'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
                'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            ]);

            $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
            $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
            $data['documento'] = strtoupper($this->request->getVar('documento'));
            
            if ($validation->withRequest($this->request)->run()) {
                switch ($data['id_tipo_tramite']) {
                    case TIPO_TRAMITE_CERTIFICADO_RESIDENCIA: {
                        return redirect()->to(base_url().'/certificadoResidencia/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    };
                    break;
                    case TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA: {
                        return redirect()->to(base_url().'/certificadoResidenciaConvivencia/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    };
                    break;
                    case TIPO_TRAMITE_CONSTANCIA_DENUNCIA: {
                        return redirect()->to(base_url().'/constanciaDenuncia/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    };
                    break;
                    case TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA: {
                        return redirect()->to(base_url().'/certificadoSupervivencia/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    };
                    break;
                    case TIPO_TRAMITE_CONSTANCIA_EXTRAVIO:{
                        return redirect()->to(base_url().'/constanciaPorExtravio/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    }
                    case  TIPO_TRAMITE_PLANILLA_PRONTUARIAL: {
                        return redirect()->to(base_url().'/planillaProntuarial/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');    
                    }
                    case  TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE: {
                        return redirect()->to(base_url().'/exposicionPorJustificativoLaboralPorFaltaDeTransporte/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    }
                    case  TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION: {
                        return redirect()->to(base_url().'/constanciaPorNoVotacion/nuevo/'.$data['id_tipo_documento'].'/'.$data['documento'].'/buscarTramitePersona');
                    }
                    default:
                        $tipoTramiteModel = new TipoTramiteModel();
                        $tipoDocumentoModel = new TipoDocumentoModel();
                        $tipoTramite = $tipoTramiteModel->find($data['id_tipo_tramite']);
                        $data['tipo_tramite'] = $tipoTramite['tipo_tramite'];
                        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
                        $data['contenido'] = "tramite_general";
                        echo view("frontend", $data);
                }
            }else {              
                $tipoTramiteModel = new TipoTramiteModel();
                $tipoDocumentoModel = new TipoDocumentoModel();
                $data['listaTipoTramites'] = $tipoTramiteModel->findAll();
                $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
                $data['id_tipo_documento'] = 1;
                $data['contenido'] = "crear_tramite";
                echo view("frontend", $data);
                echo '<script language="javascript">alert("Nro. de documento inválido");</script>';  
            }                
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function guardarTramiteGeneral() {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA 
            || $this->userInSession['id_rol']==ROL_JEFE_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $validation =  \Config\Services::validation();
            $validation->setRules([
                'id_tipo_tramite' => ['label' => 'Id Tipo tramite', 'rules' => 'required|numeric'],
                'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
                'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
                'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
                'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
                'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
                'email' => ['label' => 'Email', 'rules' => 'required|valid_email'],
            ]);
            
            $data['id_tramite'] = $this->request->getVar('id_tramite');
            $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
            $data['tipo_tramite'] = $this->request->getVar('tipo_tramite');
            $data['id_persona'] = $this->request->getVar('id_persona');
            $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
            $data['documento'] = strtoupper($this->request->getVar('documento'));
            $data['nombre'] = strtoupper($this->request->getVar('nombre'));
            $data['apellido'] = strtoupper($this->request->getVar('apellido'));
//             $data['observaciones'] = $this->request->getVar('observaciones');
            $data['observaciones'] = $this->request->getVar('editor1');
            $data['telefono'] = strtoupper($this->request->getVar('telefono'));
            $data['email'] = $this->request->getVar('email');
            
            $data['id_dependencia'] = $this->userInSession['id_dependencia'];
            
            if ($validation->withRequest($this->request)->run()) {
                $id_tramite = null;
                if(empty($data['id_tramite'])) { // insert
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                    while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                        $codigo = $this->util->generateRandomString(INT_DIEZ);
                    }
                    $data['codigo'] = $codigo;
                    $data['estado'] = TRAMITE_VALIDADO;
                    $data['estado_pago'] = ESTADO_PAGO_PENDIENTE;
                    $data['referencia_pago'] = COMISARIA_PAGO;
                    $id_tramite = $this->tramiteModel->insertarTramiteGeneral($data);
                }else { // update
                    $id_tramite = $this->tramiteModel->updateTramiteGeneral($data);
                }
                
                if($id_tramite==INT_MENOS_UNO) { // error al guardar en bbdd
                    $data['error'] = "¡Ha ocurrido un error inesperado, por favor vuelva a intentar!";
                    $tipoTramiteModel = new TipoTramiteModel();
                    $tipoDocumentoModel = new TipoDocumentoModel();
                    $data['listaTipoTramites'] = $tipoTramiteModel->findAll();
                    $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
                    $data['contenido'] = "tramite_general";
                    echo view("frontend", $data);
                    return;
                }

                if($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL) {
                    $filter = $this->session->get('filter');
                    $filter['documento'] = $data['documento'];
                    session()->set('filter', $filter);
                    return redirect()->to(base_url().'/buscarTramitePersona');
                }else { // sino es rol unidad administrativa
                    return redirect()->to(base_url().'/dashboard');
                }
            }else {
                $tipoDocumentoModel = new TipoDocumentoModel();
                $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
                $data['contenido'] = "tramite_general";
                echo view("frontend", $data);
            }
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function descargar($id_tramite) {
        if(!empty($this->userInSession) && ($this->userInSession['id_rol']==ROL_COMISARIA_SECCIONAL || $this->userInSession['id_rol']==ROL_UNIDAD_ADMINISTRATIVA 
            || $this->userInSession['id_rol']==ROL_JEFE_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $tramite = $this->tramiteModel->find($id_tramite);
            
            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    //         $pdf->SetTitle('Tramite');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(true);
//             $pdf->SetHeaderMargin(30);
            $pdf->SetTopMargin(10);
//             $pdf->SetMargins(15, 18, 15);
            $pdf->SetLeftMargin(15);
            $pdf->SetRightMargin(15);
            $pdf->setFooterMargin(20);
            $pdf->SetAutoPageBreak(true, 15); // important so styles don't break
            $pdf->SetFont('times', '', 12);
            
//             ob_end_clean();
            $pdf->AddPage();
            $html = $pdf->get_header();
            $pdf->writeHTML($html , true, false, false, false, '');
            
            $url_validacion_qr = base_url() . '/tramite/validar/' . $tramite['codigo'];
            // set style for barcode
            $style = array(
                //             'border' => 1,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => array(255, 255, 255), //false
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );
            
            // QRCODE,L : QR-CODE Low error correction
            $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 28, 28, $style, 'N');
//             ob_end_clean();
            
            $html = '<br/><br/>
                <table>
                    <tr>
                        <td width="100%" align="justify">' . $tramite['observaciones'];
            
            $html = $html . '
                        </td>
                    </tr>
                </table>
                </body>
                <html>';
            
            //         $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->writeHTML($html , true, false, false, false, '');            
            $pdf->Output('tramite-'.$id_tramite.'.pdf', 'D');
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function validarPago($codigo) {
        $tramiteModel = new TramiteModel();
        $tramite = $tramiteModel->where('codigo', $codigo)->first();
        $data['ua'] = $this->request->getUserAgent();
        
        if(empty($tramite) || empty($tramite['fecha_pago'])) {
            $data['error'] = "EL COMPROBANTE DE PAGO ES INVALIDO";
            $data['contenido'] = "turno_validacion_qr";
            echo view("frontend", $data);
            return;
        }else {
            
            $tipoTramiteModel = new TipoTramiteModel();
            $tramitePersonaModel = new TramitePersonaModel();
            $dependenciaModel = new DependenciaModel();
            $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
            $titular_tramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
            $dependencia = $dependenciaModel->find($tramite['id_dependencia']);
            
            $data['id_tramite'] = $tramite['id_tramite'];
            $data['documento'] = $titular_tramite['documento'];
            $data['nombre_apellido'] = $titular_tramite['nombre'].' '.$titular_tramite['apellido'];
            $data['fecha_pago'] = $tramite['fecha_pago'];
            $data['dependencia'] = $dependencia['dependencia'];
            $data['tipo_tramite'] = $tipoTramite['tipo_tramite'];
            $data['precio'] = $tipoTramite['precio']; // FIXME: debe tomar el importe de la tabla tramites
            
            $data['contenido'] = "pago_validacion_qr";
            echo view("frontend", $data);
            return;
        }
    }
}