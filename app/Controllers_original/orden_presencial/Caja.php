<?php
 
namespace App\Controllers;

use App\Models\SucursalModel;
use App\Libraries\CurlMercadoPago;
use App\Libraries\CurlMercadoLibre;
use App\Libraries\SucursalMercadoPago;
use App\Libraries\ApiPublicMercadoLibre;
use App\Libraries\CajaMercadoPago;
use App\Models\CajaModel;
use Exception;

class Caja extends BaseController {
  
  protected $cajaModel;
  protected $sucursalModel;
  protected $userId;
  protected $url_create_sucursal;
  protected $url_get_citys;
  protected $curlMercadoPago;
  protected $curlMercadoLibre;
  protected $apiMercadoLibre;
  protected $sucursalMercadoPago;
  protected $cajaMercadoPago;

  public function __construct() {
       $this->sucursalModel = new SucursalModel();
       $this->cajaModel     = new CajaModel();
       $this->apiMercadoLibre =  new ApiPublicMercadoLibre(); 
       $this->sucursalMercadoPago = new SucursalMercadoPago();
       $this->cajaMercadoPago     = new CajaMercadoPago();
  }

  public function index($idSucursal = null ){ 
    $sucursal = $this->sucursalModel->find($idSucursal);
    $data['sucursal'] = $sucursal;
    $data['cajas']  = $this->cajaModel->findByIdSucursal($idSucursal);
    $data['contenido'] = "caja/index.php";
    $data['titulo']=" Denuncias";
    
    return view('caja/index',$data);
  }

  public function create($id_sucursal = null) { 
    $sucursal = $this->sucursalModel->find($id_sucursal);
    $data['error'] =  false;
    $data['sucursal'] = $sucursal;
    $data['action'] = 'create';
    $data['action_text'] = 'Crear Caja';
    $data['caja'] = null;
    $data['validation'] = null;
    $data['category'] = CATEGORY_GASTRONOMIA_MP;

    if ($this->request->getMethod() == 'POST' || $this->request->getMethod() == 'post') {
       $validation =  \Config\Services::validation();
       helper(['form', 'url']);
       $validation = $this->validate([
          'name'               => ['rules' => 'required'],
          'category'           => ['rules' => 'required'],
          'external_store_id'  => ['rules' => 'required'],
      ]);
      
      $fixed_amount = $this->request->getVar('fixed_amount');
      $caja = [
          'id'                   => $this->request->getVar('id'),
          'id_caja_mercado_pago' => $this->request->getVar('id_caja_mercado_pago'),
          'name'                 => $this->request->getVar('name'),
          'fixed_amount'         => $fixed_amount != null ? $fixed_amount : false,
          // correspondiente a la sucursal  
          'external_store_id'    => $this->request->getVar('external_store_id'),
          'category'             => $this->request->getVar('category'),
          'id_sucursal'          => $this->request->getVar('id_sucursal')
      ];

    
      if (!$validation) { //error validation
        $category = CATEGORY_GASTRONOMIA_MP;
        $data['validation'] = $this->validator;
        $data['caja'] = $caja;
        $data['error'] = true;
        $data['category'] = $category;
        $data['msg'] = 'Error Completar Registro';
        $data['action'] = 'create';
        $data['action_text'] ='Crear Caja';
        return view('caja/create', $data );
    } else {
      // seteo a 0 debido a que seria el id de MercadoPago
      $caja['id_caja_mercado_pago'] = 0;
      $error =false;
      $result = null;
      try {
        $result =  $this->cajaModel->save($caja);
      } catch(Exception $ex ) {
        $error = true;
      }
      if ($result && !$error ) {
        $id = $this->cajaModel->insertID();
        $external_id = 'CAJA'.$id; 
        $caja['external_id'] = $external_id;  
        
        $resultMP = $this->saveMercadoPago($caja);
        $idCajaMP = 0;
        $qr_image = "";
        $qr_template_document = "";
        $qr_template_image = "";
        if ($resultMP && property_exists($resultMP, 'error') ) {
          ("ERROR SAVE MERCADO PAGO ");
          ($resultMP);
          $data['error'] = true;
          $data['msg'] = 'Mercado Pago : '.$resultMP->message; 
          $data['caja'] = $caja;
          return view('caja/create',$data);             
         } else {
          $idCajaMP = $resultMP->id;
          $qr_image = $resultMP->qr->image;
          $qr_template_document = $resultMP->qr->template_document;
          $qr_template_image    = $resultMP->qr->template_image;
        }

        $caja['id_caja_mercado_pago'] = $idCajaMP;
        $caja['qr_image'] = $qr_image;
        $caja['qr_template_document'] = $qr_template_document;
        $caja['qr_template_image']    = $qr_template_image;
        $this->cajaModel->set($caja);
        $this->cajaModel->where('id',$id);
        $this->cajaModel->update();
        return redirect()->to( base_url('sucursal/'.$sucursal['id'].'/cajas') ); 
      } else {
        ("ERROR SAVE MERCADO PAGO ");
        $data['error'] = true;
        $data['msg'] = "Se produjo un error al guardar los datos";
        return view('caja/create',$data);
      } 
    }
   } else {
      return view('caja/create',$data);
   }
  }
 
  

  /**
   * Funcion que permite editar 
   * una determinada caja
   */
  public function edit($id){
   
    $caja = $this->cajaModel->find($id);
    ($caja);
    $data['error'] =  false;
    $data['caja'] = $caja;
    $data['action'] = 'update';
    $data['action_text'] = 'Actualizar Caja';
    $data['caja'] = $caja;
    $data['validation'] = null;
    $data['category'] = CATEGORY_GASTRONOMIA_MP;
    $sucursal = null;
    if( $caja && $caja['id_sucursal'] != null ) {
      $sucursal = $this->sucursalModel->find($caja['id_sucursal']);
      $data['sucursal'] = $sucursal; 
    }

    if ($this->request->getMethod() == 'POST' || $this->request->getMethod() == 'post') {
       $validation =  \Config\Services::validation();
       helper(['form', 'url']);
       $validation = $this->validate([
          'name'               => ['rules' => 'required'],
          'category'           => ['rules' => 'required'],
          'external_store_id'  => ['rules' => 'required'],
      ]);
      
      $fixed_amount = $this->request->getVar('fixed_amount');
      $caja = [
          'id'                   => $this->request->getVar('id'),
          'id_caja_mercado_pago' => $this->request->getVar('id_caja_mercado_pago'),
          'name'                 => $this->request->getVar('name'),
          'fixed_amount'         => $fixed_amount != null ? $fixed_amount : false,
          // correspondinete a la sucursal
          'external_store_id'    => $this->request->getVar('external_store_id'),
          'category'             => $this->request->getVar('category'),
          'id_sucursal'          => $this->request->getVar('id_sucursal'),
      ];
      
      if( $caja['id_sucursal'] != null && isset($caja['id_sucursal'])) {
        $sucursal = $this->sucursalModel->find($caja['id_sucursal']);
        $data['sucursal'] = $sucursal; 
      }
    
      if (!$validation) { //error validation
        $category = CATEGORY_GASTRONOMIA_MP;
        $data['validation'] = $this->validator;
        $data['caja'] = $caja;
        $data['error'] = true;
        $data['category'] = $category;
        $data['msg'] = 'Error Completar Registro';
        $data['action'] = 'update';
        $data['action_text'] ='Actualizar Caja';
        return view('caja/create', $data );
    } else {
      // seteo a 0 debido a que seria el id de MercadoPago
      $caja['id_caja_mercado_pago'] = 0;
      $external_id = 'CAJA'.$id; 
      $caja['external_id'] = $external_id;  
      // correspondiente al $idCaja
      $caja['store_id'] = $id;   
      $error =false;
      $result = null;
      $msg = "";
      $resultMP = "";
      try {
        $resultMP = $this->saveMercadoPago($caja);
      
      } catch(Exception $ex ) {
        $error = true;
        $msg= $ex;
        echo "<br><br><br>";
        echo "error obtenido mercado pago ";
        ($ex);
        echo "<br><br>";
      }
       
     
        $idCajaMP = 0;
        $qr_image = "";
        $qr_template_document = "";
        $qr_template_image = "";

        if ($resultMP && property_exists($resultMP, 'error') ) {
          $data['error'] = true;
          $data['msg'] = 'Mercado Pago : '.$resultMP->message; 
          $data['caja'] = $caja;
          return view('caja/create',$data);             
         } else {
          $idCajaMP = $resultMP->id;
          $qr_image = $resultMP->qr->image;
          $qr_template_document = $resultMP->qr->template_document;
          $qr_template_image    = $resultMP->qr->template_image;
        }
        try {
          $caja['id_caja_mercado_pago'] = $idCajaMP;
          $caja['qr_image'] = $qr_image;
          $caja['qr_template_document'] = $qr_template_document;
          $caja['qr_template_image']    = $qr_template_image;
          $this->cajaModel->set($caja);
          $this->cajaModel->where('id',$caja['id']);
          $this->cajaModel->update();
          return redirect()->to( base_url('sucursal/'.$sucursal['id'].'/cajas') ); 
        }catch(Exception $ex) {
           $error = true;
        }
        
        if ($error) {
          $data['error'] = true;
          $data['msg'] = "Se produjo un error al guardar los datos : ";
          return view('caja/create',$data);
        }    

       
    }
   } else {
      return view('caja/create',$data);
   }
  }

    /**
   * Funcion que permite guardar o actualizar 
   * una Sucursal de Mercado Pago
   */
  public function saveMercadoPago($data = null ){
    ("save mercado pago ");
    echo "<br><br><br>";
    ($data);
    ("**************");
    echo "<br><br>";

    $caja = [
      "name"              => $data['name'], 
      "fixed_amount"      => $data['fixed_amount'],
      "category"          => intval($data['category']),
      "external_store_id" => $data['external_store_id'],
      "external_id"      => $data['external_id']
    ];
    $idSucursalMercado= "";
    $result = "";
    if ( empty($data['id_caja_mercado_pago']) || !isset($data['id_caja_mercado_pago'])) {
      $result = $this->cajaMercadoPago->save($caja);      
    } else {
       $result = $this->cajaMercadoPago->update($data['id_caja_mercado_pago'],$caja);
    } 
    return $result;
  }

 public function get_cajas($idSucursal = null ) {
  $cajas = $this->cajaModel->findByIdSucursal($idSucursal);
   return  json_encode($cajas);
  }

  public function get_id($idCaja = null) {
    $caja = $this->cajaModel->find($idCaja);
    return json_encode($caja);
  }
 

}


?>