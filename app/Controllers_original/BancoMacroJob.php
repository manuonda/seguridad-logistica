<?php
namespace App\Controllers;

use App\Models\TramitePersonaModel;
use App\Models\MovimientoPago;
use App\Models\TramiteModel;
use App\Models\DepartamentoModel;
use App\Models\LocalidadModel;
use App\Models\DependenciaModel;
use App\Models\TipoDocumentoModel;
use App\Models\TipoTramiteModel;
use App\Models\TurnoModel;
use App\Libraries\Util;
use App\Libraries\PagoBancoMacro;
use App\Libraries\UtilBancoMacro;
use App\Models\JobBancoMacroModel;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class BancoMacroJob  extends BaseController
{

  protected $movimientoPago;
  protected $tramiteModel;
  protected $tramitePersonaModel;
  protected $tipoDocumentoModel;
  protected $departamentoModel;
  protected $dependenciaModel;
  protected $localidadModel;
  protected $tipoTramiteModel;

  public function __construct()
  {
    $this->movimientoPago = new MovimientoPago();
    $this->tramiteModel  = new TramiteModel();
    $this->tramitePersonaModel = new TramitePersonaModel();
    $this->tipoDocumentoModel = new TipoDocumentoModel();
    $this->departamentoModel  = new DepartamentoModel();
    $this->dependenciaModel   = new DependenciaModel();
    $this->localidadModel     = new LocalidadModel();
    $this->tipoTramiteModel   = new TipoTramiteModel();
  }

  public function index()
  {
      // pago banco macro
      $utilBancoMacro = new UtilBancoMacro();
      $pagoBancoMacro = new PagoBancoMacro();
      $tramiteModel  = new TramiteModel();
      $resultadoBancoMacro = $pagoBancoMacro->login();
      $pagoBancoMacro = new PagoBancoMacro();
      $tramites = $tramiteModel->searchBancoMacroEstado();
      $jobBancoMacroModel = new JobBancoMacroModel();
      $numero_tramites= "";
       
      $cantProcesados = sizeof($tramites);
      $cantPagados = 0;
      $cantNoPagados = 0;
      log_message('info','fecha_inicio_job : '.date('Y-m-d H:i:s'));
      log_message('error', 'Inicio de Banco Macro');
               
      if ($resultadoBancoMacro->status) {
          $token = 	$resultadoBancoMacro->data;
          foreach ($tramites as $tramite) {
             
            $transaction = $pagoBancoMacro->getWithToken($tramite['id_tramite'], $token);
            //var_dump($transaction);
              if ($transaction && $transaction != null) {
                  if ($transaction->status) {
                    
                    log_message('error', '-------------------------'); 
                    log_message('info','------------------------');
                    log_message('info','idTramite : '.$tramite['id_tramite']);
                    log_message('info','status : '.$transaction->status);
                    log_message('info','<br>: estado : '.$transaction->data->estado);
                    log_message('info','<br> estado_actual : '.$utilBancoMacro->getStatusFromCode($transaction->data->estado));  
                     
                    echo "<br>-------------------------";
                    echo " idTramite : ".$tramite['id_tramite'];
                    echo "status : ".$transaction->status;
                    echo "<br>: estado : ".$transaction->data->estado;
                    echo "<br> estado_actual : ".$utilBancoMacro->getStatusFromCode($transaction->data->estado);  
                    $cantPagados++;
                    $estadoPago = $utilBancoMacro->getStatusFromCode($transaction->data->estado);
                       $tramite['estado_pago'] = $estadoPago;
                       $tramite['estado_pago_entidad'] = $transaction->data->estado;
                       $tramite['mensaje_estado_pago_entidad'] = $transaction->message;
                       if ($estadoPago == ESTADO_PAGO_PAGADO) {
                         $tramite['fecha_pago'] = date('Y-m-d H:i:s');
                       }
                       $tramiteModel->set($tramite);
                       $tramiteModel->where('id_tramite', $tramite['id_tramite']);
                       $tramiteModel->update();
                  }else {
                      $cantNoPagados++;
                  }
              }
          }
      }

      $data = [
        'fecha_registro' =>  date('Y-m-d H:i:s'),
        'cant_procesados' => $cantProcesados,
        'cant_pagados'    => $cantPagados,
        'cant_no_pagados' => $cantNoPagados
      ];

      log_message('info','fecha_salida_job : '.date('Y-m-d H:i:s'));
      log_message('info','cant_procesados' . $cantProcesados);
      log_message('info','cant_pagados'    . $cantPagados);
      log_message('info','cant_no_pagados' . $cantNoPagados);

      log_message('info','Salida de Banco Macro');
    

      
      $jobBancoMacroModel->insert($data);
  }
    
}
