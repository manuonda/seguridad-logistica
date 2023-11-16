<?php

namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\DependenciaModel;
use App\Models\TramitePersonaModel;
use App\Libraries\Util;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
#require 'vendor/autoload.php';

# definition de Mercado Pago
use MercadoPago;
MercadoPago\SDK::setAccessToken(getenv('ACCESS_TOKEN'));

class CertificadoConvivencia extends BaseController {

    protected $util;
    protected $fechaUtil;

    public function __construct()
    {
        $this->util = new Util();
        $this->fechaUtil = new FechaUtil();
    }
    public function index() {
        $this->cargarForm();
    }

    public function cargarForm($data = []) {
        $tipoDocumentoModel = new TipoDocumentoModel();
        $departamentoModel = new DepartamentoModel();
        $dependenciaModel = new DependenciaModel();
        $data['tipoDocumentos'] = $tipoDocumentoModel->findAll();
        $data['dependencias'] = $dependenciaModel->findAllHabilitado();
        $data['departamentos'] = $departamentoModel->where('id_provincia', 9)->findAll();
        $data['id_tipo_tramite'] = 20;
        $data['urlBancoMacro'] = $utilBancoMacro->getUrlBancoMacro();

        $data['contenido'] = "certificado_convivencia";
        echo view("frontend", $data);
    }

   
    /**
     * Funcion que permite guardar la informacion
     */
    public function guardar(){
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'nombre' => ['label' => 'Nombre', 'rules' => 'required|min_length[2]'],
            'apellido' => ['label' => 'Apellido', 'rules' => 'required|min_length[2]'],
            'fecha_nacimiento' => ['label' => 'Fecha nacimiento', 'rules' => 'required|exact_length[10]'],
            'id_tipo_documento' => ['label' => 'Tipo documento', 'rules' => 'required|numeric'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|min_length[6]'],
            'cuil' => ['label' => 'Cuil', 'rules' => 'required|numeric|min_length[11]'],
            'id_departamento' => ['label' => 'Departamento', 'rules' => 'required|numeric'],
            'localidad' => ['label' => 'Localidad', 'rules' => 'required'],
            'domicilio' => ['label' => 'Domicilio', 'rules' => 'required'],
            'telefono' => ['label' => 'Telefono', 'rules' => 'required'],
            'autoridad_presentar' => ['label' => 'Autoridad a Presentar', 'rules' => 'required'],
            'id_dependencia' => ['label' => 'Comisaría donde se va a verificar y validar', 'rules' => 'required|numeric'],
            //             'email' => ['label' => 'Email', 'rules' => 'required'],
        ]);

        $data['id_tipo_tramite'] = $this->request->getVar('id_tipo_tramite');
        $data['nombre'] = strtoupper($this->request->getVar('nombre'));
        $data['apellido'] = strtoupper($this->request->getVar('apellido'));
        $data['id_tipo_documento'] = $this->request->getVar('id_tipo_documento');
        $data['documento'] = strtoupper($this->request->getVar('documento'));
        $data['cuil'] = $this->request->getVar('cuil');
        $data['id_departamento'] = $this->request->getVar('id_departamento');
        $data['localidad'] = strtoupper($this->request->getVar('localidad'));
        $data['domicilio'] = strtoupper($this->request->getVar('domicilio'));
        $data['telefono'] = strtoupper($this->request->getVar('telefono'));
        $data['email'] = $this->request->getVar('email');

        $data['nombre_tutor'] = strtoupper($this->request->getVar('nombre_tutor'));
        $data['apellido_tutor'] = strtoupper($this->request->getVar('apellido_tutor'));
        $data['id_tipo_documento_tutor'] = $this->request->getVar('id_tipo_documento_tutor');
        $data['documento_tutor'] = strtoupper($this->request->getVar('documento_tutor'));
        $data['autoridad_presentar'] = strtoupper($this->request->getVar('autoridad_presentar'));
        $data['id_dependencia'] = $this->request->getVar('id_dependencia');
        if (empty($data['id_tipo_documento_tutor'])) {
            $data['id_tipo_documento_tutor'] = null;
        }

        if ($validation->withRequest($this->request)->run()) {
            $spambot = $this->request->getVar('porque_motivo');
            if (!empty($spambot)) { // si es un spambot
                log_message('error', 'spambot: documento=' . $data['documento'] . ', nombre=' . $data['nombre'] . ', apellido=' . $data['apellido']);
                $data['error'] = "¡Ha ocurrido un error de validación, vuelva intentar!";
                $data['porque_motivo'] = $spambot;
                $this->cargarForm($data);
                return;
            }

            $tramiteModel = new TramiteModel();
            $codigo = $this->util->generateRandomString(10);
            while (!empty($tramiteModel->where('codigo', $codigo)->findAll())) {
                $codigo = $this->util->generateRandomString(10);
            }
            $data['codigo'] = $codigo;

            //             $id_tramite = $tramiteModel->insert($data);
            $id_tramite = $tramiteModel->insertarCertificadoResidencia($data);


            // Crea un objeto de preferencia
            $preference = new MercadoPago\Preference();
            $preference->external_reference = $id_tramite;
            $preference->notification_url = getenv('NOTIFICATION_URL');

            // Crear Persona de Pago
            $payer = new MercadoPago\Payer();
            $payer->name = $data['nombre'];
            $payer->surname = $data['apellido'];
            $payer->email =   $data['email'];
            $payer->date_created = "2018-06-02T12:58:41.425-04:00";
            $payer->phone = array(
                "area_code" => "388",
                "number" => $data['telefono']
            );

            $payer->identification = array(
                "type" =>    $data['id_tipo_documento'],
                "number" =>  $data['documento']
            );

            $payer->address = array(
                "street_name"   => $data['domicilio'],
                "street_number" => 4560,
                "zip_code"      => "4600"
            );

            // Crear el usuario y esperar la respuesta de Mercado Pago
            // para guardar la informacion en mi base de datos con la opcion de user_id de mercado pago 

            //  establecer el usuario redireccionar
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/resultpayment/';
            // Back URLS
            $back_urls = array(
                "success" =>  $base_url . 'success',
                "failure" =>  $base_url . 'failure',
                "pending" =>  $base_url . 'pending'
            );

            // Crea un ítem 
            $item = new MercadoPago\Item();
            $item->title = 'Total a pagar';
            $item->quantity = 1;
            $item->unit_price = 1;

            // Item del pago 
            $preference->items = array($item);
            // Configuration urls 
            $preference->back_urls = $back_urls;
            // Payer
            $preference->payer = $payer;

            $preference->save();
            $data['preference'] = $preference;
            $data['id_tramite'] = $id_tramite;
            $data['contenido'] = "certificado_convivencia_pago";
            echo view("frontend", $data);
            return;
        } else {
            $this->cargarForm($data);
        }
    }
    
    /**
     * Funcion que permite descargar 
     * el certificado obtenido
     */
    public function descargar($id_tramite)
    {
        $tramiteModel = new TramiteModel();
        $data = $tramiteModel->find($id_tramite);

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);

        $pdf->AddPage();
        $html = $pdf->get_header();
        $html = $this->get_body($html, $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        //         $url_validacion_qr = base_url().'permiso/validar/'.$id_permiso_circulacion;
        $url_validacion_qr = base_url() . '/tramite/validar/' . $data['codigo'];
        //         $url_validacion_qr = base_url().'inicio/desencriptar/123456789123456789123456789146498797897897897878';
        // set style for barcode
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,L : QR-CODE Low error correction
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 40, 40, $style, 'N');
        //         $pdf->write2DBarcode($html_encriptado, 'QRCODE,L', 12, 10, 40, 40, $style, 'N', true);
        ob_end_clean();
        $pdf->Output('tramite.pdf', 'D');
    }

    private function get_body($html, $data)
    {
        $tramitePersonaModel = new TramitePersonaModel();
        $dependenciaModel = new DependenciaModel();
        $titular_tramite = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', 1)->first();
        $parte_interesada = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_parte_interesada', 1)->first();
        $dependencia = $dependenciaModel->find($data['id_dependencia']);
        $fechaCastellano = $this->fechaUtil->fechaCastellano(2);
        $html = $html . '<table>
        <tr>
            <td width="100%" align="justify">
                <div align="center">

                    <h1><b><u>CERTIFICADO DE CONVIVENCIA</u></b></h1>
                    <br/><br/>
                </div>

- - - : El Funcionario Policial que suscribe <u><b>CERTIFICA</b></u>: Que ' . $titular_tramite['apellido'] . ' ' . $titular_tramite['nombre'] . ' 
quien acredita su identidad personal mediante la presentación de su
DNI Nº ' . $titular_tramite['documento'] . ' actualmente <u><b>RESIDE</b></u> en ' . $titular_tramite['domicilio'] . ', ' . $titular_tramite['localidad'] . '.
<br/><br/>
- - - : A solicitud de la parte interesada y al solo efecto de ser presentado ante ' . $data['autoridad_presentar'] . '
, se expide, firma y estampa código QR en la '. $dependencia['dependencia'] . ', con asiento en la ciudad de SAN SALVADOR DE JUJUY, PROVINCIA DE JUJUY, REPUBLICA ARGENTINA a los ' . $fechaCastellano . '.
    
                        </td>
                    </tr>
                </table>
                </body>';
        return $html;
    }

      /**
     * Funcion que permite guardar el archivo en 
     * un disco determinado
     */
    public function guardarFileDisk($id_tramite) {
        $pathFile = WRITEPATH . 'archivos/';
        $tramiteModel = new TramiteModel();
        $data = $tramiteModel->find($id_tramite);
        $tramitePersonaModel = new TramitePersonaModel();
        $titular_tramite = $tramitePersonaModel->where('id_tramite', $data['id_tramite'])->where('es_titular_tramite', INT_UNO)->first();


        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Tramite');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(false); // important so styles don't break
        $pdf->SetFont('times', '', 12);

        $pdf->AddPage();
        $html = $pdf->get_header();
        $html = $this->get_body($html, $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        //         $url_validacion_qr = base_url().'permiso/validar/'.$id_permiso_circulacion;
        $url_validacion_qr = base_url() . 'permiso/validar/' . $data['codigo'];
        //         $url_validacion_qr = base_url().'inicio/desencriptar/123456789123456789123456789146498797897897897878';
        // set style for barcode
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255), //false
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,L : QR-CODE Low error correction
        $pdf->write2DBarcode($url_validacion_qr, 'QRCODE,L', 12, 10, 40, 40, $style, 'N');
        //         $pdf->write2DBarcode($html_encriptado, 'QRCODE,L', 12, 10, 40, 40, $style, 'N', true);
        // ob_end_clean();
        $date =  date('dmYsiH');
        $pdf->Output($pathFile.$titular_tramite['cuil']."-".$id_tramite."-".$date.".pdf", 'F');
  
    }

}
