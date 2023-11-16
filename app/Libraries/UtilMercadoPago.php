<?php namespace App\Libraries;


use App\Libraries\PagoMercadoPago;
use App\Libraries\PaymentMercadoPago;
use App\Models\TramitePersonaModel;
use CodeIgniter\Controller;
use App\Models\MovimientoPago;
use App\Models\TipoPagoModel;
use App\Models\TipoTramiteModel;
use App\Models\TramiteArchivoModel;
use App\Models\TramiteModel;
use App\Models\TurnoCantidadModel;
use App\Controllers\CertificadoResidencia;
use App\Controllers\CertificadoResidenciaConvivencia;
use App\Models\TurnoModel;
use App\Models\DependenciaModel;
use App\Libraries\Pdf;
use App\Libraries\UtilBancoMacro;

use Exception;

use DateTime;
# definition de Mercado Pago
use MercadoPago;

class UtilMercadoPago {
    



    /**
     * Obtiene el mensaje del estado de Mercado Pago
     */
    public function getMessageEstadoPago($estado)
    {
        $message  = "";
        switch ($estado) {
            case 'pending':
                $message =  'El usuario no completo el proceso de pago todavía';
                break;
            case 'approved':
                $message =  'El pago fue aprobado y acreditado';
                break;
            case 'authorized':
                $message =  'El pago fue autorizado pero no capturado todavía';
                break;
            case 'in_process':
                $message =  'El pago está en revisión.';
                break;
            case 'in_mediation':
                $message =  'El usuario inició una disputa.';
                break;
            case 'rejected':
                $message =  'El pago fue rechazado. El usuario podría reintentar el pago.';
                break;
            case 'cancelled':
                $message =  'El pago fue cancelado por una de las partes o el pago expiró.';
                break;
            case 'refunded':
                $message =  'El pago fue devuelto al usuario.';
                break;
            case 'charged_back':
                $message =   'Se ha realizado un contracargo en la tarjeta de crédito del comprador.';
                break;
        }
        return $message;
    }

    /**
     * Funcion correspondiente al Status de MercadoPago
     */
    public function getMessageStatus($estado)
    {
        $message  = "";
        switch ($estado) {
            case 'pending':
                $message =  'PENDIENTE';
                break;
            case 'approved':
                $message =  'APROBADO';
                break;
            case 'authorized':
                $message =  'AUTORIZADO';
                break;
            case 'in_process':
                $message =  'EN PROCESO';
                break;
            case 'in_mediation':
                $message =  'EN MEDIACION';
                break;
            case 'rejected':
                $message =  'RECHAZADO';
                break;
            case 'cancelled':
                $message =  'CANCELADO';
                break;
            case 'refunded':
                $message =  'DEVUELTO';
                break;
            case 'charged_back':
                $message =   'CONTRACARGO';
                break;
        }
        return $message;
    }

    public function  getStatus($statusBM = "") {
        $status = "";
        if ( $statusBM == "approved" || $statusBM == "authorized") {
            $status = APROBADO;
        } else if ( $statusBM == "pending" || $statusBM == "in_process" || $statusBM == "in_mediation") {
            $status = PENDIENTE;
        } else  {
            $status = CANCELADO;
        }

        return $status;
    }


    /**
     * Funcion que permte generar el 
     * pago de Mercado Pago y crear un registro en la tabla 
     * de resultado pago online para ser actualizado posteriormente con MercadoPago
     */
    public function generarPagoMercadoPago($id_tramite, $id_tipo_tramite ,$titular)
    {
        $movimientoPago = new MovimientoPago();
        $tipoPagoModel = new TipoPagoModel();
        $tipoTramiteModel = new TipoTramiteModel();
        //Obtenemos tipo pago 
        $tipoPago = $tipoPagoModel->where('acronimo', "ONLINE")->first();
        // Obtenemos el tramite para obtener el valor del tramite 
        $tipoTramite = $tipoTramiteModel->where('id_tipo_tramite', $id_tipo_tramite)->first();

        // se crea el resultado del pago online por el item 
        $movimientoPagoData = [];
        // Creamos el objeto a guardar en la tabla de ResultadoDePagoOnlineModel   
        $movimientoPagoData['collection_id'] = "";
        // Estado del pago. Ponemos por defecto pending
        $movimientoPagoData['collection_status'] = "pending"; //por defecto ponemos este pago
        // Tipo de pago. Por ejemplo: credit_card para tarjetas de crédito o ticket para medios de pago en efectivo
        $movimientoPagoData['payment_type']  = "";
        // ID de la orden de pago generada en Mercado Pago
        $movimientoPagoData['merchant_order_id']  = "";
        // ID de la preferencia de pago de la que se está retornando
        $movimientoPagoData['preference_id']      = "";
        // ID del país de la cuenta de Mercado Pago del vendedor. Por ejemplo: MLA para Argentina
        $movimientoPagoData['site_id']            = "MLA";
        // Valor aggregator
        $movimientoPagoData['processing_mode']    = "";
        // Valor null
        $movimientoPagoData['merchant_account_id'] = "";
        //id_tramite 
        $movimientoPagoData['id_tramite'] = $id_tramite;
        //id TipoPago
        $movimientoPagoData['id_tipo_pago'] = $tipoPago['id_tipo_pago'];
        // fecha_alta
        $movimientoPagoData['fecha_alta'] =  date('Y-m-d H:i:s');;


        // Se guarda siempre el resultado de pago online antes de enviar 
        // la informacion a MP, para verificar posteriormente esta informacion 
        // con lo que devuelva MP
        $result = $movimientoPago->save($movimientoPagoData);
        $idResultadoPagoOnline = null;
        if ($result) {
            $idResultadoPagoOnline = $movimientoPago->insertID();
        }

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        $preference->external_reference = $idResultadoPagoOnline;
        $preference->notification_url = getenv('NOTIFICATION_URL');

        // Crear Persona de Pago
        $payer = new MercadoPago\Payer();
        $payer->name = $titular['nombre'];
        $payer->surname = $titular['apellido'];
        $payer->email =   $titular['email'];
        $payer->date_created = date('Y-m-d\TH:i:s.000P');
        $payer->phone = array(
            "area_code" => "388",
            "number" => $titular['telefono']
        );

        $payer->identification = array(
            "type" =>    $titular['id_tipo_documento'],
            "number" =>  $titular['documento']
        );

        $payer->address = array(
            "street_name"   => $titular['domicilio'],
            "street_number" => intval(0),
            "zip_code"      => "4600"
        );

        //  establecer el usuario redireccionar
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/resultpaymentonline/';
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
        $price = 0;
        if ($tipoTramite != null && $tipoTramite['precio'] != null && $tipoTramite['precio'] != "") {
            $price = floatval($tipoTramite['precio']);
        }
        $item->unit_price = $price;

        // Item del pago 
        $preference->items = array($item);
        // Configuration urls 
        $preference->back_urls = $back_urls;
        // Payer
        $preference->payer = $payer;
        $preference->save();
        return $preference;

       
    }


}
?>
