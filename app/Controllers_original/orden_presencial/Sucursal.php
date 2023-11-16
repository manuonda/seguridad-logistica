<?php
 
namespace App\Controllers;

use App\Models\SucursalModel;
use App\Libraries\CurlMercadoPago;
use App\Libraries\CurlMercadoLibre;
use App\Libraries\SucursalMercadoPago;
use App\Libraries\ApiPublicMercadoLibre;
use Exception;

class Sucursal extends BaseController {
  
  protected $sucursalModel;
  protected $userId;
  protected $url_create_sucursal;
  protected $url_get_citys;
  protected $curlMercadoPago;
  protected $curlMercadoLibre;
  protected $apiMercadoLibre;
  protected $sucursalMercadoPago;

  public function __construct() {
       $this->sucursalModel = new SucursalModel();
       $this->apiMercadoLibre =  new ApiPublicMercadoLibre(); 
       $this->sucursalMercadoPago = new SucursalMercadoPago();
  }
  public function index()
  {  
    try{
      $data['sucursales']  = $this->sucursalModel->findAll(); 

    } catch(Exception $ex) {
      ($ex);
    }
    return view('sucursal/index',$data);
  }

  public function create() { 
    // correspondiente STATE_NAME_MP : al estado de Mercado Pago
    $state_name = STATE_NAME_MP;
    $data['error'] = false;
    $data['state_name'] = $state_name;
    $data['action'] = 'create';
    $data['action_text'] ='Crear Sucursal';
    $data['validation'] = null;
    $citys = $this->apiMercadoLibre->getCitys($state_name);
    $json_citys = json_decode($citys);
    // ($citys->cities);
    $data['citys'] = $json_citys->cities;
    $data['title'] = 'Sucursales';

    if ($this->request->getMethod() == 'POST' || $this->request->getMethod() == 'post') {
        $validation =  \Config\Services::validation();
        helper(['form', 'url']);
        $validation = $this->validate([
          'name' => ['label' => 'Requerido', 'rules' => 'required'],
          'street_number' => ['label' => 'street_number', 'rules' => 'required'],
          'street_name'   => ['label' => 'street_name'  , 'rules' => 'required'],
          'city_name'     => ['label' => 'city_name'    , 'rules' => 'required'],
          'latitude'      => ['label' => 'latitude'     , 'rules' => 'required'],
          'longitude'     => ['label' => 'longitude'    , 'rules' => 'required']
      
        ]);

        $sucursal = [
          'id' =>$this->request->getVar('id_sucursal'),
          'id_sucursal_mercado_pago' => $this->request->getVar('id_sucursal_mercado_pago'),
          'name' => $this->request->getVar('name'),
          'street_number' => $this->request->getVar('street_number'),
          'street_name'   => $this->request->getVar('street_name'),
          'state_name'    => $this->request->getVar('state_name'),
          'city_name'     => $this->request->getVar('city_name'),
          'latitude'      => $this->request->getVar('latitude'),
          'longitude'     => $this->request->getVar('longitude'),
          'external_id'   => $this->request->getVar('external_id')
      ];
      
      ($validation);
      if (!$validation) { //error validation
          $state_name = STATE_NAME_MP;
          $data['title'] = 'Sucursales';
          $data['error'] = true;
          $data['msg'] = 'Error Completar Registro';
          $data['validation'] = $this->validator;
          $data['sucursal'] = $sucursal;
          $citys = $this->apiMercadoLibre->getCitys($state_name);
          $json_citys = json_decode($citys);
          $data['citys'] = $json_citys->cities;
          $data['action'] = 'create';
          $data['action_text'] ='Crear Sucursal';
          return view('sucursal/create', $data );
      } else {
        // seteo a 0 debido a que seria el id de MercadoPago
        $sucursal['id_sucursal_mercado_pago'] = 0;
        $error =false;
        $result = null;
        try {
          $result =  $this->sucursalModel->save($sucursal);
        } catch(Exception $ex ) {
           $error = true;
           ($ex);
        }
        if ($result ) {
          $id = $this->sucursalModel->insertID();
          $external_id = 'SUC'.$id; 
          $sucursal['external_id'] = $external_id;          
          $idSucursalMP = $this->saveMercadoPago($sucursal);
          $sucursal['id_sucursal_mercado_pago'] = $idSucursalMP;
          $this->sucursalModel->set($sucursal);
          $this->sucursalModel->where('id',$id);
          $this->sucursalModel->update();
        }
        if ( $error ) {
         $data['error'] = true;
         $data['msg'] = "Se produjo un error al guardar los datos";
        } else { // no hay error al guardar los datos
          return redirect()->to( base_url('sucursal') );
        }
      }
    }
    return view('sucursal/create',$data);
  }

  public function edit($idSucursal) {
    $sucursal = $this->sucursalModel->find($idSucursal);
    $state_name = STATE_NAME_MP;
    $data['error'] = false;
    $data['msg'] = '';
    $data['state_name'] = $state_name;
    $data['action'] = 'update';
    $data['action_text'] ='Actualizar Sucursal';
    $data['validation'] = null;
    $data['sucursal'] = $sucursal;
    $citys = $this->apiMercadoLibre->getCitys($state_name);
    $json_citys = json_decode($citys);
    // ($citys->cities);
    $data['citys'] = $json_citys->cities;
    $data['title'] = 'Sucursales';
    if ($this->request->getMethod() == 'post' || $this->request->getMethod() == 'POST') {
      
      $validation =  \Config\Services::validation();
      helper(['form', 'url']);
  
      $validation = $this->validate([
        'name' => ['label' => 'Requerido', 'rules' => 'required'],
        'street_number' => ['label' => 'street_number', 'rules' => 'required'],
        'street_name'   => ['label' => 'street_name'  , 'rules' => 'required'],
        'city_name'     => ['label' => 'city_name'    , 'rules' => 'required'],
        'latitude'      => ['label' => 'latitude'     , 'rules' => 'required'],
        'longitude'     => ['label' => 'longitude'    , 'rules' => 'required']
      ]);

      $id_sucursal_mercado_pago = $this->request->getVar('id_sucursal_mercado_pago') != ''  ?
                                  $this->request->getVar('id_sucursal_mercado_pago'): 0 ; 
      $sucursal = [
        'id' =>$this->request->getVar('id_sucursal'),
        'id_sucursal_mercado_pago' => $id_sucursal_mercado_pago,
        'name' => $this->request->getVar('name'),
        'street_number' => $this->request->getVar('street_number'),
        'street_name'   => $this->request->getVar('street_name'),
        'state_name'   => $this->request->getVar('state_name'),
        'city_name'    => $this->request->getVar('city_name'),
        'latitude'     =>$this->request->getVar('latitude'),
        'longitude'    => $this->request->getVar('longitude'),
        'external_id'  => $this->request->getVar('external_id')
      ];

      if (!$validation) { //error validation
          $state_name = STATE_NAME_MP;
          $data['error'] = true;
          $data['msg'] = 'Error Completar Registro';
          $data['title'] = 'Sucursales';
          $data['validation'] = $this->validator;
          $data['sucursal'] = $sucursal;
          $citys = $this->apiMercadoLibre->getCitys($state_name);
          $json_citys = json_decode($citys);
          $data['citys'] = $json_citys->cities;
          $data['action'] = 'create';
          $data['action_text'] ='Crear Sucursal';
          return view('sucursal/create', $data );
      } else {
          $error = false;
          try {
           
            $sucursal['external_id'] = 'SUC'.$sucursal['id'];
            $idSucursalMP = $this->saveMercadoPago($sucursal);  
            $sucursal['id_sucursal_mercado_pago'] = $idSucursalMP;
            $sucursal['external_id'] = 'SUC'.$sucursal['id'];
            $this->sucursalModel->set($sucursal);
            $this->sucursalModel->where('id',$sucursal['id']);
            $this->sucursalModel->update();
            
          }catch(Exception $error) {
             $error = true;
          }        
          if ( $error ) {
            $data['error'] = true;
            $data['msg'] = "Se produjo un error al guardar los datos";
          } else { // no hay error al guardar los datos
             return redirect()->to( base_url('sucursal') );
          }
      }
    } else { // edit with data 
      return view('sucursal/create',$data);
    }
     
  }


  public function delete($idSucursal = null ) {
    $sucursal = $this->sucursalModel->find($idSucursal);
    $state_name = STATE_NAME_MP;
    $data['error'] = false;
    $data['msg'] = '';
    $data['state_name'] = $state_name;
    $data['action'] = 'update';
    $data['action_text'] ='Actualizar Sucursal';
    $data['validation'] = null;
    $data['sucursal'] = $sucursal;
    $citys = $this->apiMercadoLibre->getCitys($state_name);
    $json_citys = json_decode($citys);
    // ($citys->cities);
    $data['citys'] = $json_citys->cities;
    $data['title'] = 'Sucursales';
    if ($this->request->getMethod() == 'post' || $this->request->getMethod() == 'POST') {
      
      $validation =  \Config\Services::validation();
      helper(['form', 'url']);
  
      $validation = $this->validate([
        'name' => ['label' => 'Requerido', 'rules' => 'required'],
        'street_number' => ['label' => 'street_number', 'rules' => 'required'],
        'street_name'   => ['label' => 'street_name'  , 'rules' => 'required'],
        'city_name'     => ['label' => 'city_name'    , 'rules' => 'required'],
        'latitude'      => ['label' => 'latitude'     , 'rules' => 'required'],
        'longitude'     => ['label' => 'longitude'    , 'rules' => 'required']
      ]);

      $id_sucursal_mercado_pago = $this->request->getVar('id_sucursal_mercado_pago') != ''  ?
                                  $this->request->getVar('id_sucursal_mercado_pago'): 0 ; 
      $sucursal = [
        'id' =>$this->request->getVar('id_sucursal'),
        'id_sucursal_mercado_pago' => $id_sucursal_mercado_pago,
        'name' => $this->request->getVar('name'),
        'street_number' => $this->request->getVar('street_number'),
        'street_name'   => $this->request->getVar('street_name'),
        'state_name'   => $this->request->getVar('state_name'),
        'city_name'    => $this->request->getVar('city_name'),
        'latitude'     =>$this->request->getVar('latitude'),
        'longitude'    => $this->request->getVar('longitude'),
        'external_id'  => $this->request->getVar('external_id')
      ];

      if (!$validation) { //error validation
          $state_name = STATE_NAME_MP;
          $data['error'] = true;
          $data['msg'] = 'Error Completar Registro';
          $data['title'] = 'Sucursales';
          $data['validation'] = $this->validator;
          $data['sucursal'] = $sucursal;
          $citys = $this->apiMercadoLibre->getCitys($state_name);
          $json_citys = json_decode($citys);
          $data['citys'] = $json_citys->cities;
          $data['action'] = 'create';
          $data['action_text'] ='Crear Sucursal';
          return view('sucursal/create', $data );
      } else {
          $error = false;
          try {
           
            $sucursal['external_id'] = 'SUC'.$sucursal['id'];
            $idSucursalMP = $this->saveMercadoPago($sucursal);  
            $sucursal['id_sucursal_mercado_pago'] = $idSucursalMP;
            $sucursal['external_id'] = 'SUC'.$sucursal['id'];
            $this->sucursalModel->set($sucursal);
            $this->sucursalModel->where('id',$sucursal['id']);
            $this->sucursalModel->update();
            
          }catch(Exception $error) {
             $error = true;
          }        
          if ( $error ) {
            $data['error'] = true;
            $data['msg'] = "Se produjo un error al guardar los datos";
          } else { // no hay error al guardar los datos
             return redirect()->to( base_url('sucursal') );
          }
      }
    } else { // edit with data 
      return view('sucursal/create',$data);
    }
     
  }


  /**
   * Funcion que permite guardar o actualizar 
   * una Sucursal de Mercado Pago
   */
  public function saveMercadoPago($data = null ){
      /* Array Parameter Data */
      $sucursalMP = [
        'name'=> $data['name'], 
        'location'=>  [
           'street_number' => $data['street_number'],
           'street_name'   => $data['street_name'],
           'state_name'    => $data['state_name'], // provincia
           'city_name'     => $data['city_name'], //departamento
           'latitude'      => $data['latitude'],
           'longitude'     => $data['longitude'],
           'reference'     => 'Cerca de la esquina de mi casa'
         ],
        'external_id' => $data['external_id']
    ];

    $idSucursalMercado= "";
    $result = "";
    if ( empty($data['id_sucursal_mercado_pago']) || !isset($data['id_sucursal_mercado_pago'])) {
      $result = $this->sucursalMercadoPago->save($sucursalMP);      
    } else {
      $result = $this->sucursalMercadoPago->update($data['id_sucursal_mercado_pago'],$sucursalMP);
    } 
    
    echo "-----------------";
    echo "aqui resultad MP";
    ($result);
    echo "<br>";
    echo "----------------";

    // result 
    if ($result && property_exists($result, 'error') ) {
       $idSucursalMercado = null;
    } else{
      $idSucursalMercado = $result->id;
    }
    echo '<br>';
    echo 'idSucursalMercadoPago'.$idSucursalMercado;
    return $idSucursalMercado;
  }

 }
?>