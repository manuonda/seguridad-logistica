<?php

namespace App\Controllers;

#require 'vendor/autoload.php';

use App\Models\LogNotificationModel;
use MercadoPago;

MercadoPago\SDK::setAccessToken(getenv('ACCESS_TOKEN'));

class Notifications extends BaseController
{

    protected $logNotificationModel;

    public function __construct() {
         $this->logNotificationModel = new LogNotificationModel();
    }

    /**
     * @topic: Identifica de qué se trata. Puede ser payment, chargebacks o merchant_order
     * @id   : Es un identificador único del recurso notificado.
     *
     * @merchang_order : Si estas integrando pagos presenciales, te recomendamos utilizar notificaciones IPN de topic merchant_order
     */
    function index(){
        $merchant_order = null;
        /*
        if ( isset($_GET["topic"]) && isset($_GET["id"])) {
            $data_log_notification['topic'] = $_GET['topic'];
            $data_log_notification['parametro_id'] = $_GET['id'];
            $data_log_notification['fecha_hora'] = date('Y-m-d').' '. date("h:i:sa");
            $this->logNotificationModel->save($data_log_notification);
            $status = 200;
            $topic = $_GET["topic"]; 
            
            if ($topic == "payment"){
                    
                   $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
                    // Get the payment and the corresponding merchant_order reported by the IPN.
                    $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
                    $mensaje = 'Status payment : '.$payment->status;
                    $data_log_notification['parametro_id'] = $_GET['id'];
                    $data_log_notification['mensaje'] = $mensaje;
                    $this->logNotificationModel->save($data_log_notification);
                        
                    http_response_code(200);
            } else if ($topic == "merchant_order" ) {
                    $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
                    $paid_amount = 0;
                    foreach ($merchant_order->payments as $payment) {
                        if ($payment['status'] == 'approved') {
                            $paid_amount += $payment['transaction_amount'];
                        }
                    }
                    $mensaje = ''; 
                    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
                    if ($paid_amount >= $merchant_order->total_amount) {
                        if (count($merchant_order->shipments) > 0) { // The merchant_order has shipments
                            if ($merchant_order->shipments[0]->status == "ready_to_ship") {
                                $mensaje = 'Totalmente pagado. Imprime la etiqueta and release your item';
                            }
                        } else { // The merchant_order don't has any shipments
                            $mensaje = 'Totalmente pagado. Release your item.';
                        }
                    } else {
                        $mensaje = 'No Pagado. No imprima la etiqueta';
                    }
                    $this->logNotificationModel->save($data_log_notification);
                    http_response_code(200);
            }
            http_response_code(200);
           
        } else {
          http_response_code(200); 
        }*/
       
       
        http_response_code(200);  
    }
    
}
