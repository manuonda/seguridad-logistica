<?php

namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Models\PersonaModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;
use App\Models\MovimientoPago;
use App\Models\TipoPagoModel;
use App\Models\DependenciaModel;
use App\Models\HuellaModel;
use App\Models\TipoDocumentoModel;
use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\TramitePersonaModel;


use App\Models\TramiteArchivoFirmaDigitalModel;
use App\Controllers\Tramite;
use App\Libraries\PagoBancoMacro;
use App\Libraries\UtilBancoMacro;

use App\Libraries\Email;
use Exception;
use ZipArchive;



class Persona extends BaseController
{
	protected $tramiteModel;
	protected $tipoTramiteModel;
	protected $personaModel;
	protected $dependenciaModel;
	protected $session;
	protected $pager;
	protected $movimimentoPago;
	protected $tipoPagoModel;
	protected $tramiteController;
	protected $pagoBancoMacro;
	protected $utilBancoMacro;
	protected $tramiteArchivoFirmaDigitalModel;
	protected $huellasModel;
	
	
	public function __construct()
	{
		$this->tramiteModel = new TramiteModel();
		$this->tipoTramiteModel = new TipoTramiteModel();
		$this->huellaModel  = new HuellaModel();
		$this->session = session();
		$this->movimimentoPago = new MovimientoPago();
		$this->pager = \Config\Services::pager();
		$this->tipoPagoModel = new TipoPagoModel();
		$this->tramiteController = new Tramite();
		$this->pagoBancoMacro = new PagoBancoMacro();
		$this->utilBancoMacro =  new UtilBancoMacro();
		$this->dependenciaModel = new DependenciaModel();
		$this->huellasModel     = new HuellaModel();
		$this->tramiteArchivoFirmaDigitalModel = new TramiteArchivoFirmaDigitalModel();
	}

	public function index($idTramite = null) {
	   
		$tramite = $this->tramiteModel->find($idTramite);
	    $tramitePersonaModel = new TramitePersonaModel();
        $titular = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();
        $tutor = $tramitePersonaModel->where('id_tramite', $tramite['id_tramite'])->where('es_parte_interesada', INT_UNO)->first();
		
		// $titular =  $this->tramiteModel->findByCuil($titular['cuil']);
		if ($titular != null  ) {
			//$huellas = $this->huellaModel->findByCuil($titular['cuil']);
			$data['persona'] = $titular;
			//$data['huellas'] = $huellas;
			$tipoDocumentoModel = new TipoDocumentoModel();
			$departamentoModel = new DepartamentoModel();
			$localidadModel = new LocalidadModel();
			$dependenciaModel = new DependenciaModel();
			$tipoTramiteModel  = new TipoTramiteModel();
			$data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
			$data['dependencias'] = $dependenciaModel->findAllHabilitado();
			$data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
	
			if(empty($data['id_tramite'])) {
				$data['localidades'] = [];
			}else if(isset($data['id_departamento']) && $data['id_departamento'] != null) {
				$data['localidades'] = $localidadModel->where('id_departamento', $data['id_departamento'])->findAll();
			}else {
				$data['localidades'] = [];
			}
		   
			$data['id_tipo_tramite'] = TIPO_TRAMITE_CERTIFICADO_RESIDENCIA;
			$tipoTramite = $tipoTramiteModel->find($data["id_tipo_tramite"]);
			$data['controller'] = $tipoTramite['controlador'];
			$data['title'] =$tipoTramite["controlador_title"];

			$data['huellass'] = $this->huellasModel->search_huella($titular['cuil']);
			$data['huella_all'] = $this->huellasModel->search_all($titular['cuil']);
			$data['rcuil'] = $titular['cuil'];
			$data['contenido'] ="dashboard/edit_planillaprontuarial";
			// 		echo view("backend", $data);
			echo view("backend_planilla", $data); 
		} else{

		}
		
	}





}
