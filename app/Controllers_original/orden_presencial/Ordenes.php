<?php
 
namespace App\Controllers;

use App\Models\SucursalModel;
use App\Libraries\CurlMercadoPago;
use App\Libraries\CurlMercadoLibre;
use App\Libraries\SucursalMercadoPago;
use App\Libraries\ApiPublicMercadoLibre;
use App\Libraries\CajaMercadoPago;
use App\Libraries\OrdenMercadoPago;
use App\Models\CajaModel;
use App\Models\OrdenItemModel;
use App\Models\OrdenModel;
use App\Models\CategoriaModel;

use Exception;

class Ordenes extends BaseController {
  
  protected $cajaModel;
  protected $sucursalModel;
  protected $ordenModel;
  protected $sucursalMercadoPago;
  protected $cajaMercadoPago;
  protected $ordenItemModel;
  protected $categoriaModel;
  protected $ordenMercadoPago; 
  
  public function __construct() {
       $this->sucursalModel = new SucursalModel();
       $this->cajaModel     = new CajaModel();
       $this->apiMercadoLibre =  new ApiPublicMercadoLibre(); 
       $this->sucursalMercadoPago = new SucursalMercadoPago();
       $this->cajaMercadoPago     = new CajaMercadoPago();
       $this->ordenModel          = new OrdenModel();
       $this->ordenItemModel     = new OrdenItemModel();
       $this->categoriaModel      = new CategoriaModel();
       $this->ordenMercadoPago    = new OrdenMercadoPago();
  }

  public function index(){
    // sucursal por defecto 
    $sucursal = $this->sucursalModel->findAll();
    // caja por defecto
    $caja  = $this->cajaModel->find(7);
    $data['sucursales'] = $sucursal;
    $data['ordenes'] = [];
    $data['contenido'] = "orden/index.php";
    $data['titulo']=" Denuncias";
    return view('orden/index',$data);
  }


  public function create() { 
    $categoria = $this->categoriaModel->find(1);
    $data['error'] =  false;
    $data['sucursales'] = $this->sucursalModel->findAll();
    $data['categoria'] = $categoria;
    $data['action'] = 'create';
    $data['action_text'] = 'Crear Orden';
    $data['orden'] = null;
    $data['validation'] = null;
    $data['category'] = CATEGORY_GASTRONOMIA_MP;

    if ($this->request->getMethod() == 'POST' || $this->request->getMethod() == 'post') {
       $validation =  \Config\Services::validation();
       helper(['form', 'url']);
       $validation = $this->validate([
          'title'                 => ['rules' => 'required'],
          'description'           => ['rules' => 'required'],
          'total_amount'          => ['rules' => 'required'],
          'expiration_date'       => ['rules' => 'required'],
          'id_sucursal'           => ['rules' => 'required'],
          'id_caja'           => ['rules' => 'required']
      ]);

      $sucursal  = $this->sucursalModel->find($this->request->getVar('id_sucursal'));
      $caja      = $this->cajaModel->find($this->request->getVar('id_caja'));
      $categoria = $this->categoriaModel->find($this->request->getVar('id_categoria'));
      $experation_date = $this->request->getVar('expiration_date');
      $experation_date  = date('Y-m-d\TH:i:s.000P', strtotime($experation_date));

      
      $total_amount = $this->request->getVar('total_amount');
      $orden = [
          'id_sucursal'          => $this->request->getVar('id_sucursal'),
          'external_store_id'    => $sucursal['external_id'],
          'id_caja'              => $this->request->getVar('id_caja'),
          'external_pos_id'      => $caja['external_id'],
          'title'                => $this->request->getVar('title'),
          'description'          => $this->request->getVar('description'), 
          'expiration_date'      => $experation_date,
          'total_amount'         => $total_amount != null ? floatval($total_amount) : 0,
          'notification_url'     => getenv('NOTIFICATION_URL')    
      ];

      // orden item 
      $ordenItem = [ 
        'category'             => $categoria['nombre'],
        'title'                => $this->request->getVar('title'),
        'unit_price'           => $this->request->getVar('total_amount'),
        'quantity'             => 1, 
        'unit_measure'         => UNIT_MEASURE,
        'total_amount'         => $total_amount != null ? floatval($total_amount) : 0,
        'id_categoria'         => $this->request->getVar('id_categoria')   
      ];

    
      if (!$validation) { //error validation
        $data['validation'] = $this->validator;
        $data['caja'] = $caja;
        $data['error'] = true;
        $data['msg'] = 'Error Completar Registro';
        $data['action'] = 'create';
        $data['action_text'] ='Crear Orden';
        return view('orden/create', $data );
    } else {
      // seteo a 0 debido a que seria el id de MercadoPago
      $caja['id_caja_mercado_pago'] = 0;
      $error =false;
      $resultOrden = null;
      $resultOrdenItem = null ;
      $idOrden = 0;
      try {
        $resultOrden =  $this->ordenModel->save($orden);
        
        if ($resultOrden) {
          $ordenItem['id_orden'] = $this->ordenModel->insertID();
          $idOrden = $this->ordenModel->insertID();
          $ordenItem['id_orden'] =$idOrden;
          $resultOrdenItem = $this->ordenItemModel->save($ordenItem);
        }

      } catch(Exception $ex ) {
        $messageError = $ex;
        $error = true;
      }

      $external_reference = "";
      if ($resultOrden && $resultOrdenItem && !$error ) {
        $external_reference = 'order-id-'.$idOrden;
        $orden['external_reference'] = $external_reference;  
        $orden['items'] = $ordenItem;
      
        $ordenMP = $this->crearOrdenMP($orden, $ordenItem);

        // seteamos en vacio el valor 
        $resultMP = $this->ordenMercadoPago->save($ordenMP, $sucursal['external_id'], $caja['external_id']);
        if ($resultMP && property_exists($resultMP, 'error') ) {
          $data['error'] = true;
          $data['msg'] = 'Mercado Pago : '.$resultMP->message; 
          $data['orden'] = $orden;
          $data['ordenItem'] =$ordenItem;
          return view('orden/create',$data);             
         } else {
          $this->cajaModel->set($orden);
          $this->cajaModel->where('id',$idOrden);
          $this->cajaModel->update();
          return redirect()->to( base_url('ordenes') ); 
   
        }
      
      } else {
     
        $data['error'] = true;
        $data['msg'] = "Se produjo un error al guardar los datos";
        return view('orden/create',$data);
      } 
    }
   } else {
      return view('orden/create',$data);
   }
  }


  private function crearOrdenMP($data ,$ordenItemData){ 
    $orden['title']              = $data['title'];
    $orden['description']        = $data['description'];
    $orden['expiration_date']    = $data['expiration_date'];
    $orden['total_amount']       = $data['total_amount'];   
    $orden['external_reference'] = $data['external_reference'];  

    // convert ordenItem 
    $ordenItem['category']    = $ordenItemData['category'];
    $ordenItem['title']       = $ordenItemData['title'];
    $ordenItem['unit_price']  = floatval($ordenItemData['unit_price']);
    $ordenItem['quantity']    = $ordenItemData['quantity'];
    $ordenItem['unit_measure'] = $ordenItemData['unit_measure'];
    $ordenItem['total_amount'] = $ordenItemData['total_amount'];
    $orden['items'] =  [$ordenItem];
    return $orden;
  }
 

  public function buscar(){ 
    $idSucursal = $this->request->getVar('idSucursal');
    $idCaja     = $this->request->getVar('idCaja');
    $ordenes = $this->ordenModel->findById($idSucursal, $idCaja);
    return json_encode($ordenes);

  }
  

}