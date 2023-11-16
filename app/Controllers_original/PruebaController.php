<?php

namespace App\Controllers;

use App\Models\DepartamentoModel;
use App\Models\TramiteModel;
use App\Models\TipoDocumentoModel;
use App\Models\DependenciaModel;
use App\Models\PersonaModel;
use App\Libraries\Util;
use App\Libraries\FechaUtil;
use App\Libraries\Pdf;
use App\Libraries\PagoBancoMacro;



class PruebaController extends BaseController {

    protected $pagoBancoMacro;
    public function __construct() {
        $this->pagoBancoMacro = new PagoBancoMacro();
    }

    /**
     * Funcion que permite realizar el login 
     */
    public function login(){
   
      $resultado = $this->pagoBancoMacro->login();
      echo json_encode($resultado);
      return;
    }


    public function get(){
      $idTransaction = $this->request->getvar('id_transaction');
      $token = $this->request->getvar('token');
      $transaction = $this->pagoBancoMacro->get($idTransaction, $token);
      echo json_encode($transaction);
      return;

    }

    public function list() {
        $token = $this->request->getvar('token');
        $list =  $this->pagoBancoMacro->list($token);
//         var_dump($list);
    }


}?>
