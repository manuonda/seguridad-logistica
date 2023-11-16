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

use CodeIgniter\Controller;
use App\Models\TramiteArchivoModel;
use App\Models\TurnoCantidadModel;
use App\Models\TurnoModel;
use App\Libraries\Pdf;
use App\Libraries\UtilMercadoPago;

use DateTime;
# definition de Mercado Pago
use MercadoPago;
use App\Models\TipoDocumentoModel;
use App\Models\PersonalModel;
use App\Models\UserModel;

ini_set('max_execution_time', 300);
class Aws extends BaseController {

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
        var_dump("prueba");
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
		$this->fiveDates = date( "Y-m-d", strtotime( $date . "-5 day"));
		

	}

	public function index($filter = null)
	{

	
			$data['filter'] = $filter;
			$data['contenido'] = "awsupload";
			// 		echo view("backend", $data);
			echo view("frontend", $data);
	
	}


    public function upload2()
    {

		var_dump("ingreso aqui");
        $archivo = $this->request->getFile("facturaServicio");
		var_dump($archivo);
		$id_tramite="89080";
		$referencia_foto="FRENTE";
		$id_tipo_tramite=1;

        if ($archivo->isValid()) {
			var_dump("a23423423234rchivo valido");
             $year = date("Y");
		     $month = date("m");
		     $status = "";
	    	 $message = "";

	    	$folder = "/dev/xvdf".ARCHIVOS_CERTIFICADOS;
			echo "writepaht : ";
			var_dump(WRITEPATH);
			echo "************** <br>";

	    
	    	$base_path = "/data/prueba_subidas/". $year . "/" . $month ;
			echo "<br> basepath : ";
			echo $base_path;
			echo "--------";
			if (!file_exists($base_path)) {
				echo "<br> file_not_exists";
				mkdir($base_path, 0777, true);
                echo "<br> no pudo escribir el archivo"; 
			} else {
				echo "file exists";
			}
			            
			echo "<br> salida";
            $prefijo_nombre = $id_tramite . '_' . date("Ymd_His");
            $nombre = $prefijo_nombre . '_' . $archivo->getName();
            $archivo->move($base_path, $nombre , true);
             echo "<br> base_path". $base_path;
             echo "<br> nombre : ". $nombre;
	    $image  =  file_get_contents($base_path."/".$nombre);
            $base64 = 'data:image/jpeg;base64,' . base64_encode($image);
            $data['base64'] = $base64;
            
           var_dump($base64);
           var_dump("salida2");   
            //             print_r('File has successfully uploaded');
        }

	
		$data['contenido'] = "awsupload";
	
		// 		echo view("backend", $data);
		echo view("frontend", $data);
    }


	

}
