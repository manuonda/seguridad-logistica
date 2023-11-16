<?php

namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Libraries\PaymentMercadoPago;
use App\Models\TramitePersonaModel;
use App\Models\MovimientoPago;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;


class GenerarPago extends BaseController {
  
  protected $resultPaymentModel;
  protected $tramiteModel;
  protected $tramitePersonaModel;
  protected $tipoTramiteModel;
  protected $resultadoPagoOnlineModel;
  protected $pagoMercadoPago;

  protected $paymentMercadoPagoLib;

  public function __construct() {
       $this->resultPaymentModel = new MovimientoPago();
       $this->tramiteModel  = new TramiteModel();
       $this->tramitePersonaModel = new TramitePersonaModel();
       $this->tipoTramiteModel = new TipoTramiteModel();
       $this->resultadoPagoOnlineModel = new MovimientoPago();
       $this->paymentMercadoPagoLib = new PaymentMercadoPago();
       $this->pagoMercadoPago = new PagoMercadoPago();
  }

  /**
   * IdTramite
   */
  public function index($id_tramite) {

     $tramite = $this->tramiteModel->find($id_tramite);
     $data['id_tramite'] = $id_tramite;
     $persona = $this->tramitePersonaModel->find($id_tramite) ;
     if ($persona && $persona != null ) {
       $data['nombre'] = $persona->nombre;
       $data['apellido'] = $persona->apellido;
       $data['email'] = $persona->email;
       $data['telefono'] = $persona->telefono;
       $data['id_tipo_documento'] = $persona->id_tipo_documento;
       $data['documento'] = $persona->documento;
       $data['domicilio'] = $persona->domicilio;
     }

     // Obtenemos el tramite para obtener el valor del tramite 
     $tipoTramite = $this->tipoTramiteModel->where('id_tipo_tramite', $data['id_tipo_tramite'])->first();
       
     if ( $tipoTramite != null && $tipoTramite['precio'] != null && $tipoTramite['precio'] != ""){
         $data['title'] = strtoupper($tipoTramite['nombre']);
     }

     
      // Generamos el object de preference de Mercado Pago
      $preference = $this->generarPago($id_tramite, $data);
      $data['preference'] = $preference;
      // ---------------------
      $data['id_tramite'] = $id_tramite;
      $data['contenido'] = "certificado_residencia_pago";
      echo view("frontend", $data);
      return;
  }


}