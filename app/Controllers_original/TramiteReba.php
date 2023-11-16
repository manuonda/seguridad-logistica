<?php
namespace App\Controllers;

use App\Models\TipoDocumentoModel;
use App\Models\TramiteModel;
use App\Models\CategoriaRebaModel;

use App\Libraries\Util;
use App\Models\TramiteRebaModel;
use App\Models\TramitePersonaModel;
use App\Libraries\Pdf;
use App\Libraries\UtilBancoMacro;
use App\Models\TipoTramiteModel;

class TramiteReba extends BaseController {

    protected $session;
    protected $util;
    protected $tramiteModel;
    protected $categoriaRebaModel;
    protected $tramiteRebaModel;


    public function __construct() {
        $this->util = new Util();
        $this->tramiteModel = new TramiteModel();
        $this->session = session();
    }

    public function index() {
        //$data['id_tipo_tramite'] = TIPO_TRAMITE_PAGO_REBA;
        if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL
            || session()->get('id_rol')==ROL_UAD_REBA_CENTRAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $tipoDocumentoModel = new TipoDocumentoModel();
            $categoriaRebaModel = new CategoriaRebaModel();
            $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
            $data['categorias'] = $categoriaRebaModel->findAll();
            $data['contenido'] = 'pago_reba';
            $data['concepto_uno'] = 'REBA VIGENCIA ANUAL';
            $data['concepto_dos'] = 'CERTIFICADO REBA';
            $data['concepto_tres'] = 'CERTIFICACION DE COPIAS';
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


    public function edit($idTramite) {
        if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL
            || session()->get('id_rol')==ROL_UAD_REBA_CENTRAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $tramite = $this->tramiteModel->find($idTramite);
            $tramitePersonaModel = new TramitePersonaModel();
            $categoriaRebaModel = new CategoriaRebaModel();
            $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
            $categorias = $categoriaRebaModel->findAll();
    
            $data['id_tramite'] = $tramite['id_tramite'];
            $data['id_tipo_tramite'] = $tramite['id_tipo_tramite'];
            $data['autoridad_presentar'] = $tramite['autoridad_presentar'];
           
            $data['id_dependencia'] = $tramite['id_dependencia'];
            $data['estado'] = $tramite['estado'];
            $data['codigo'] = $tramite['codigo'];
            $data['observaciones'] = $tramite['observaciones'];
            $data['id_persona_titular'] = $titular['id_persona'];
            $data['nombre'] = $titular['nombre'];
            $data['apellido'] = $titular['apellido'];
            $data['fecha_nacimiento'] = $titular['fecha_nacimiento'];
            $data['id_tipo_documento'] = $titular['id_tipo_documento'];
            $data['documento'] = $titular['documento'];
            $data['numero_tramite'] = $titular['nro_tramite_dni'];
            $data['cuil'] = $titular['cuil'];
            $data['id_departamento'] = $titular['id_departamento'];
            $data['id_localidad'] = $titular['id_localidad'];
            $data['id_barrio'] = $titular['id_barrio'];
            $data['barrio'] = $titular['barrio'];
            $data['numero'] = $titular['numero'];
            $data['manzana'] = $titular['manzana'];
            $data['lote'] = $titular['lote'];
            $data['piso'] = $titular['piso'];
            $data['dpto'] = $titular['dpto'];
            $data['calle'] = $titular['calle'];
            $data['telefono'] = $titular['telefono'];
            $data['email'] = $titular['email'];
            $data['categorias'] = $categorias;
           
            $tipoDocumentoModel = new TipoDocumentoModel();
            $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
    
            $tramiteRebaModel = new TramiteRebaModel();
            $tramiteReba = $tramiteRebaModel->where('id_tramite',$idTramite)->first();
            $data['id_tramite_reba'] = $tramiteReba['id'];
            $data['id_categoria_reba'] = $tramiteReba['id_categoria_reba'];
            $data['concepto_uno'] = $tramiteReba['concepto_uno'];
            $data['cantidad_uno'] = $tramiteReba['cantidad_uno'];
            $data['precio_uno'] =   $tramiteReba['precio_uno'];
    
            $data['concepto_dos'] = $tramiteReba['concepto_dos'];
            $data['cantidad_dos'] = $tramiteReba['cantidad_dos'];
            $data['precio_dos'] =   $tramiteReba['precio_dos'];
            
            $data['concepto_tres'] = $tramiteReba['concepto_tres'];
            $data['cantidad_tres'] = $tramiteReba['cantidad_tres'];
            $data['precio_tres'] =   $tramiteReba['precio_tres'];
    
            $data['en_concepto_de'] = $tramiteReba['en_concepto_de'];
            $data['denominacion_negocio'] = $tramiteReba['denominacion_negocio'];
            $data['contenido'] = 'pago_reba';
            echo view("frontend", $data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function guardar() {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation =  \Config\Services::validation();
        $tramiteRebaModel = new TramiteRebaModel();
        helper(['form', 'url']);
        $validation;
        $tipoDni = $this->request->getVar('id_tipo_documento');
        
        $validation = $this->validate([
            'id_tipo_documento'       => ['rules' => 'required'],
            'documento'               => ['rules' => 'required'],
            'fecha_nacimiento'        => ['rules' => 'required'],
            'cuil'                    => ['rules' => 'required']
        ]);

        if (!$validation) {
            $data['validation'] = $this->validator;
            
            $data['documento'] = $this->request->getVar('documento');
            $data['numero_tramite'] = $this->request->getVar('numero_tramite');
            $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
            $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
            $data['id_categoria_reba'] =$this->request->getVar('id_categoria_reba');
            $data['id_tipo_tramite'] = TIPO_TRAMITE_PAGO_REBA;
            $data['nombre'] = strtoupper($this->request->getVar('nombre'));
            $data['apellido'] = strtoupper($this->request->getVar('apellido'));
            $data['en_concepto_de'] = strtoupper($this->request->getVar('en_concepto_de'));
            $data['denominacion_negocio'] = strtoupper($this->request->getVar('denominacion_negocio'));
            $data['concepto_uno'] = strtoupper($this->request->getVar('concepto_uno'));
            $data['cantidad_uno'] = $this->request->getVar('cantidad_uno');
            $data['precio_uno']   = $this->request->getVar('precio_uno');
            $data['concepto_dos'] = strtoupper($this->request->getVar('concepto_dos'));
            $data['cantidad_dos'] = $this->request->getVar('cantidad_dos');
            $data['precio_dos']   = $this->request->getVar('precio_dos');
            $data['concepto_tres'] = strtoupper($this->request->getVar('concepto_tres'));
            $data['cantidad_tres'] = $this->request->getVar('cantidad_tres');
            $data['precio_tres']   = $this->request->getVar('precio_tres');
            $tipoDocumentoModel = new TipoDocumentoModel();
            $data['tipoDocumentos'] = $this->tipoDocumentoModel->findAll();
            $data['contenido'] = "pago_reba";
            echo view("frontend", $data);
            return;
        } else {
            
            $data['id_tramite']      = $this->request->getVar('id_tramite');
            $data['id_tramite_reba'] = $this->request->getVar('id_tramite_reba');
            $data['id_persona_titular'] = $this->request->getVar('id_persona_titular');
            
            $data['documento'] = $this->request->getVar('documento');
            $data['numero_tramite'] = $this->request->getVar('numero_tramite');
            $data['fecha_nacimiento'] = $this->request->getVar('fecha_nacimiento');
            $data['id_tipo_documento'] =$this->request->getVar('id_tipo_documento');
            $data['cuil']              =$this->request->getVar('cuil');
            
            $data['id_categoria_reba'] =$this->request->getVar('id_categoria_reba');
            $data['id_tipo_tramite'] = TIPO_TRAMITE_PAGO_REBA;
            $data['nombre'] = strtoupper($this->request->getVar('nombre'));
            $data['apellido'] = strtoupper($this->request->getVar('apellido'));
            $data['telefono'] =$this->request->getVar('telefono');
            $data['email'] =$this->request->getVar('email');
           
            $data['en_concepto_de'] = strtoupper($this->request->getVar('en_concepto_de'));
            $data['denominacion_negocio'] = strtoupper($this->request->getVar('denominacion_negocio'));
            $data['concepto_uno'] = strtoupper($this->request->getVar('concepto_uno'));
            $data['cantidad_uno'] = $this->request->getVar('cantidad_uno');
            $data['precio_uno']   = $this->request->getVar('precio_uno');
            $data['concepto_dos'] = strtoupper($this->request->getVar('concepto_dos'));
            $data['cantidad_dos'] = $this->request->getVar('cantidad_dos');
            $data['precio_dos']   = $this->request->getVar('precio_dos');
            $data['concepto_tres'] = strtoupper($this->request->getVar('concepto_tres'));
            $data['cantidad_tres'] = $this->request->getVar('cantidad_tres');
            $data['precio_tres']   = $this->request->getVar('precio_tres');
            $data['id_dependencia']   = session()->get('id_dependencia');

            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6Lf4wOQUAAAAAB3A4koIXJlk0_iWx5ll6HytJrg1';
            $recaptcha_response = $this->request->getVar('recaptcha_response');
            //             echo 'recaptcha_response=='.$recaptcha_response;
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            //             echo 'fff=='.$recaptcha;
            $recaptcha_json = json_decode($recaptcha);

            $id_tramite = null;
            if (empty($data['id_tramite'])) { // insert
                $codigo = $this->util->generateRandomString(INT_DIEZ);
                while (!empty($this->tramiteModel->where('codigo', $codigo)->findAll())) {
                    $codigo = $this->util->generateRandomString(INT_DIEZ);
                }

                $data['codigo'] = $codigo;
                $id_tramite = $tramiteRebaModel->insertar($data);
            } else {// update
                $id_tramite = $tramiteRebaModel->actualizar($data);
            }
                
            if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_UNIDAD_ADMINISTRATIVA || session()->get('id_rol')==ROL_UAD_REBA_CENTRAL)) {
                return redirect()->to(base_url().'/dashboard');
            }else if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
                return redirect()->to(base_url().'/tramiteReba/buscar');
            }
        } 
    }
    
    public function buscar() {
        if(!empty(session()->get('id_rol')) && (session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL || session()->get('id_rol')==ROL_UAD_UNIDAD_REGIONAL_UR5)) {
            $fechaDesde = $this->request->getVar('fechaDesde');
            $fechaHasta = $this->request->getVar('fechaHasta');
            $documento = $this->request->getVar('documento');
            
            if(empty($fechaDesde)) {
                $fechaDesde = date('Y-m-d');
            }
            if(empty($fechaHasta)) {
                $fechaAux = strtotime('+1 day', strtotime($fechaDesde));
                $fechaHasta = date('Y-m-d', $fechaAux);
            }
            
            $filter['fechaDesde'] = $fechaDesde;
            $filter['fechaHasta'] = $fechaHasta;
            $filter['documento'] = $documento;
            $this->session->set('filter', $filter);
            $this->listado($fechaDesde, $fechaHasta, $documento);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    

    private function getCategoriaReba($idCategoria){
        $categoriaRebaModel = new CategoriaRebaModel();
        $categoriaReba = $categoriaRebaModel->find($idCategoria);  
        return $this->response->setJSON($categoriaReba);
    }
    
    private function listado($fechaDesde, $fechaHasta, $documento) {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $utilBancoMacro = new UtilBancoMacro();
        $tramiteModel = new TramiteModel();
        $id_dependencia = session()->get('id_dependencia');
        $tramiteModel->select('tramite_online.tramites.id_tramite, tramite_online.tipo_tramites.tipo_tramite, tramite_online.tramites.id_tipo_tramite, tramite_online.tramites.fecha_alta, tramite_online.tramite_personas.cuil,
                                                tramite_online.tramite_personas.apellido, tramite_online.tramite_personas.nombre, tramite_online.tramite_personas.documento, public.tipo_documentos.tipo_documento, tramite_online.tramites.estado,
                                                tramite_online.tramites.estado_pago, tramite_online.tramites.referencia_pago, tramite_online.tipo_tramites.precio, tramite_online.tipo_tramites.controlador, tramite_online.categoria_reba.nombre categoria_reba,
                                                tramite_online.tramites.contiene_firma_digital,
                                                 (   
                                                  tramite_online.tramites_reba.precio_uno  + 
                                                  tramite_online.tramites_reba.precio_dos  +  
                                                  tramite_online.tramites_reba.precio_tres ) AS suma
                                                ')
                                                ->join('tramite_online.tipo_tramites', 'tramite_online.tipo_tramites.id_tipo_tramite = tramite_online.tramites.id_tipo_tramite')
                                                ->join('tramite_online.tramite_personas', 'tramite_online.tramite_personas.id_tramite = tramite_online.tramites.id_tramite')
                                                ->join('public.tipo_documentos', 'public.tipo_documentos.id_tipo_documento = tramite_online.tramite_personas.id_tipo_documento')
                                                ->join('tramite_online.tramites_reba', 'tramite_online.tramites_reba.id_tramite =  tramite_online.tramites.id_tramite')
                                                ->join('tramite_online.categoria_reba','tramite_online.tramites_reba.id_categoria_reba = tramite_online.categoria_reba.id')
        ->where('tramite_online.tramites.id_dependencia', $id_dependencia)
        ->where('tramite_online.tramites.id_tipo_tramite', TIPO_TRAMITE_PAGO_REBA)
        ->where('tramite_online.tramite_personas.es_titular_tramite', INT_UNO);
        
        if(!empty($fechaDesde)) {
            $tramiteModel->where('tramite_online.tramites.fecha_alta>=', $fechaDesde);
        }
        if(!empty($fechaHasta)) {
            $tramiteModel->where('tramite_online.tramites.fecha_alta<=', $fechaHasta);
        }
        if(!empty($documento)) {
            $tramiteModel->where('tramite_online.tramite_personas.documento=', trim($documento));
        }
//         ->where('tramite_online.tramite_personas.documento', $documento)

        $tramitesAll = $tramiteModel->orderBy('tramite_online.tramites.fecha_alta', 'desc')->findAll();
        $tramites = [];
        if ($tramites != null && $tramites && sizeof($tramites) > 0) {
            $total = 0;
            foreach ($tramites as $tramite) {
                $price = $this->calcularPrecio2($tramite);
                $tramite['suma']  = $price;
            }
        }
        
        $data['listado'] =  $tramitesAll;
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();
        $data['fechaDesde'] = $fechaDesde;
        $data['fechaHasta'] = $fechaHasta;
        $data['documento'] = $documento;
        $data['contenido'] = "uad_unidad_regional_lista";
       echo view("frontend", $data);
    }

    	/**
	 * Funcion que permite calcular el precio del tramite de Reba
	 * 
	 */
	private function calcularPrecio2($tramite)
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$tipoTramiteModel = new TipoTramiteModel();
		$categoriaRebaModel =  new CategoriaRebaModel();
		$tramiteRebaModel = new TramiteRebaModel();
	

		// Obtenemos el tramite para obtener el valor del tramite 
		$tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $tramite['id_tipo_tramite'])->first();
	

		// Tipo de Tramite Categoria Reba
		if ($tipoTramite != null && $tipoTramite['id_tipo_tramite'] == TIPO_TRAMITE_PAGO_REBA) {
		

			$tramiteReba = $tramiteRebaModel->where('id_tramite', $tramite['id_tramite'])->first();
			$categoriaReba = $categoriaRebaModel->find($tramiteReba['id_categoria_reba']);
			$categoriaUnoPrecio = floatVal($tramiteReba['precio_uno']);
			$categoriaDosPrecio = floatVal($tramiteReba['precio_dos']);
			$categoriaTresPrecio = floatVal($tramiteReba['precio_tres']);

			$price = ($categoriaUnoPrecio +  $categoriaDosPrecio + $categoriaTresPrecio);
		} else if ($tipoTramite != null &&  $tipoTramite['id_tipo_tramite'] != TIPO_TRAMITE_PAGO_REBA &&  $tipoTramite['precio'] != null && $tipoTramite['precio'] != "") {
			// OTRO TIPO DE TRAMITE
	       $price = floatval($tipoTramite['precio']) *  100;
		}

		// $price_format = number_format($price, 2, ",", ".");
		return $price;
	}

    /**
    * Primer metodo que se usaba para reba, pero ya no se usa. 
    */
    private function getTramiteRebaByCuil($cuil) {
        $status = "OK";
        $tramite = "";
        $tramiteRebaModel = new TramiteRebaModel();
        $tramites = $tramiteRebaModel->searchTramitesByCuil($cuil); 
        if ( $tramites && sizeof($tramites) > 0 ) {
            $tramite = $tramites[0];
        }

        return $this->response->setJSON($tramite);
    }
    
    /**
     * Funcion que permite buscar un tramite reba por cuil. Se llama desde el sistema de Reba.
     */
    public function getTramiteRebaByNroCuponPago($tokenParam, $idTramite) {
        $token = "sdfa654dawrkkuhmghfgh223154qweqwerqwerweqkjsifgdfdf988pppoookkkq987";
        if($tokenParam === $token) {
            $status = "OK";
            $tramite = "";
            $tramiteRebaModel = new TramiteRebaModel();
            $tramites = $tramiteRebaModel->searchTramiteRebaByIdTramite($idTramite);
            if ( $tramites && sizeof($tramites) > 0 ) {
                $tramite = $tramites[0];
            }
            return $this->response->setJSON($tramite);
        }else {
            $tramite['error'] = "Token de seguridad incorrecto.";
            return $this->response->setJSON($tramite);
        }
    }
    
    public function getCuponesPago($id_tramite) {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $tramitePersonaModel = new TramitePersonaModel();
        $tramiteRebaModel = new TramiteRebaModel();
        $tramite = $this->tramiteModel->find($id_tramite);
        $titularTramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tramiteReba = $tramiteRebaModel->where('id_tramite', $tramite['id_tramite'])->first();
        $dependencia = null;
        switch (session()->get('id_dependencia')) {
            case ID_DEP_UAD_SAN_PEDRO_UR2:
                $dependencia = 'ur2';
                break;
            case ID_DEP_UAD_HUMAHUACA_UR3:
                $dependencia = 'ur3';
                break;
            case ID_DEP_UAD_LGSM_UR4:
                $dependencia = 'ur4';
                break;
            case ID_DEP_UAD_LA_QUIACA_UR5:
                $dependencia = 'ur5';
                break;
            case ID_DEP_UAD_PERICO_UR6:
                $dependencia = 'ur6';
                break;
            case ID_DEP_UAD_ALTO_COMEDERO_UR7:
                $dependencia = 'ur7';
                break;
            case ID_DEP_UAD_PALPALA_UR8:
                $dependencia = 'ur8';
                break;
            case ID_DEP_UAD_TILCARA:
                $dependencia = 'tilcara';
                break;
            case ID_DEP_UAD_ABRAPAMPA:
                $dependencia = 'abrapampa';
                break;
            case ID_DEP_UAD_EL_CARMEN:
                $dependencia = 'elcarmen';
                break;
            default:
                $dependencia = 'central';
                break;
        }
        
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
//         $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(6);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);
        
        $pdf->AddPage();        
        $html = '<html>
                 <body>
                 ';
        $html = $pdf->get_header_cupon_pago($html);
        $html = $this->getBodyCupon($html, $tramite, $tramiteReba, $titularTramite, 'COMPROBANTE RENDICION F.E.S', $dependencia, false);
        $html = $pdf->get_header_cupon_pago($html);
        $html = $this->getBodyCupon($html, $tramite, $tramiteReba, $titularTramite, 'COMPROBANTE CLIENTE', $dependencia, false);
        $html = $pdf->get_header_cupon_pago($html);
        $html = $this->getBodyCupon($html, $tramite, $tramiteReba, $titularTramite, 'OFICINA - DIV. LEYES ESPECIALES - UNIDAD ADMINISTRATIVA DIGITAL', $dependencia, false);
        $html = $html . '            
                </body>
                </html>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        $pdf->SetAlpha(1);
        $pdf->Output('Reba-'.$titularTramite['documento'].'.pdf', 'D');
    }
    
    public function getCuponPagoOnline($id_tramite) {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tramitePersonaModel = new TramitePersonaModel();
        $tramiteRebaModel = new TramiteRebaModel();
        $tramite = $this->tramiteModel->find($id_tramite);
        $titularTramite = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tramiteReba = $tramiteRebaModel->where('id_tramite', $tramite['id_tramite'])->first();
        $dependencia = null;
        switch (session()->get('id_dependencia')) {
            case ID_DEP_UAD_SAN_PEDRO_UR2:
                $dependencia = 'ur2';
                break;
            case ID_DEP_UAD_HUMAHUACA_UR3:
                $dependencia = 'ur3';
                break;
            case ID_DEP_UAD_LGSM_UR4:
                $dependencia = 'ur4';
                break;
            case ID_DEP_UAD_LA_QUIACA_UR5:
                $dependencia = 'ur5';
                break;
            case ID_DEP_UAD_PERICO_UR6:
                $dependencia = 'ur6';
                break;
            case ID_DEP_UAD_ALTO_COMEDERO_UR7:
                $dependencia = 'ur7';
                break;
            case ID_DEP_UAD_PALPALA_UR8:
                $dependencia = 'ur8';
                break;
            case ID_DEP_UAD_TILCARA:
                $dependencia = 'tilcara';
                break;
            case ID_DEP_UAD_ABRAPAMPA:
                $dependencia = 'abrapampa';
                break;
            case ID_DEP_UAD_EL_CARMEN:
                $dependencia = 'elcarmen';
                break;
            default:
                $dependencia = 'central';
                break;
        }
        
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        //         $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(6);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);
        
        $pdf->AddPage();
        $html = '<html>
                 <body>
                 ';
        $html = $pdf->get_header_cupon_pago($html);
        $html = $this->getBodyCupon($html, $tramite, $tramiteReba, $titularTramite, 'OFICINA - DIV. LEYES ESPECIALES - UNIDAD ADMINISTRATIVA DIGITAL', $dependencia, true);
        $html = $html . '
                </body>
                </html>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        $pdf->SetAlpha(1);
        $pdf->Output('Reba-'.$titularTramite['documento'].'.pdf', 'D');
    }
    
    protected function getBodyCupon($html, $tramite, $tramiteReba, $titularTramite, $cuonPara, $dependencia, $pagadoConMacro) {
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // <br/><br/><b>ABONAR A TRAVEZ DE MACRO CLIC O EN VENTANILLA DE POLICIA DE JUJUY</b><br/>
        // <img src="assets/img/cupon-titulo.png" width="370" height="27" />
        // <img src="assets/img/cupon-titulo2.png" width="380" height="28" />
        $en_concepto_de = strtoupper($tramiteReba['en_concepto_de']);
        $categoria = substr($en_concepto_de, -3);
        if($categoria == 'D-1' || $categoria == 'D-2' || $categoria == 'D-3') {
            $categoria = substr($en_concepto_de, -3);
            $en_concepto_de = substr($en_concepto_de, 0, -3);
        }else {
            $categoria = substr($en_concepto_de, -1);
            $en_concepto_de = substr($en_concepto_de, 0, -1);
        }
        
        $total_abonar = $tramiteReba['precio_uno'] + $tramiteReba['precio_dos'] + $tramiteReba['precio_tres'];
        $html = $html . '
        <table border="0">
        <tr>
            <td width="7%" align="center">
                <img src="assets/img/uad-'.$dependencia.'.png" width="14" height="190" />
            </td>
            <td width="93%">
                <table border="0" cellspacing="0">
                    <tr>
                        <td width="100%" align="center">
                            <img src="assets/img/uad-'.$dependencia.'-titulo.png" width="450" height="25" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%" align="left">
                            <font size="10">En concepto de: '.$en_concepto_de.'</font><font size="15"><b>'.$categoria.'</b></font>
                        </td>
                        <td rowspan="3" align="right"><b><font size="10">ORDEN DE PAGO<br/>'.$tramite['id_tramite'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></b></td>
                    </tr>
                    <tr>
                        <td width="70%" align="left">
                            <font size="10">Apellido y Nombre: '.strtoupper($titularTramite['apellido']).' '.strtoupper($titularTramite['nombre']).' - CUIL: '.$titularTramite['cuil'].'</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="70%" align="left">
                            <font size="10">Denominación de Negocio: '.strtoupper($tramiteReba['denominacion_negocio']).' - TEL CEL. '.strtoupper($titularTramite['telefono']).'</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="70%" align="left">
                            <font size="10">Fecha y hora de emisión: '.date('d-m-Y H:i').'</font>
                        </td>
                    </tr>
                    </table>
                    <br/>
                    <table border="0.1px">
                          <tr>
                            <th width="74%" align="center"><font size="10">DETALLE</font></th>
                            <th width="13%" align="center"><font size="10">CANTIDAD</font></th>
                            <th width="13%" align="center"><font size="10">MONTO</font></th>
                          </tr>
                          <tr>
                            <td><font size="9">&nbsp;&nbsp;'.strtoupper($tramiteReba['concepto_uno']).'</font></td>
                            <td align="center"><font size="9">'.$tramiteReba['cantidad_uno'].'</font></td>
                            <td align="center"><font size="9">'.number_format($tramiteReba['precio_uno'], 2, ',', '.').'</font></td>
                          </tr>
                          <tr>
                            <td><font size="9">&nbsp;&nbsp;'.strtoupper($tramiteReba['concepto_dos']).'</font></td>
                            <td align="center"><font size="9">'.$tramiteReba['cantidad_dos'].'</font></td>
                            <td align="center"><font size="9">'.number_format($tramiteReba['precio_dos'], 2, ',', '.').'</font></td>
                          </tr>
                          <tr>
                            <td><font size="9">&nbsp;&nbsp;'.strtoupper($tramiteReba['concepto_tres']).'</font></td>
                            <td align="center"><font size="9">'.$tramiteReba['cantidad_tres'].'</font></td>
                            <td align="center"><font size="9">'.number_format($tramiteReba['precio_tres'], 2, ',', '.').'</font></td>
                          </tr>
                    </table>
                    <br/>
                    <table border="0" width="100%">   
                        <tr>
                            <td width="86%" align="left">
                                <table border="0">   
                                    <tr>
                                        <td align="rigth">
                                            <font size="10"><b>TOTAL ABONAR: </b></font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="14%" align="left">
                                <table border="0.1px">
                                    <tr>
                                        <td align="center">
                                            <font size="10"><b>'.number_format($total_abonar, 2, ',', '.').'</b></font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table border="0">   
                        <tr>
                            <td width="60%" align="left">
                                <font size="10"><b>'.$cuonPara.'</b></font>
                            </td>
                        </tr>';
                if($pagadoConMacro) {        
                    $html = $html . '
                        <tr>
                            <td width="60%" align="left">
                                <font size="10"><b>Cupon pagado a travez del Macro clic</b></font>
                            </td>
                        </tr>';
                }
                    
                    $html = $html . '
                    </table>
                </td>
            </tr>        
        </table>
        <br/><br/><br/><br/>';
        return $html;
    }
       
}