<?php

namespace App\Controllers;

use App\Libraries\PagoMercadoPago;
use App\Libraries\PaymentMercadoPago;
use App\Models\PersonaModel;
use App\Models\MovimientoPago;
use App\Models\TipoTramiteModel;
use App\Models\TramiteModel;

class Huellas extends BaseController {
  
  protected $resultPaymentModel;
  protected $tramiteModel;
  protected $personaModel;
  protected $tipoTramiteModel;
  protected $resultadoPagoOnlineModel;
  protected $pagoMercadoPago;

  protected $paymentMercadoPagoLib;

  public function __construct() {
       $this->resultPaymentModel = new MovimientoPago();
       $this->tramiteModel  = new TramiteModel();
       $this->personaModel = new PersonaModel();
       $this->tipoTramiteModel = new TipoTramiteModel();
       $this->resultadoPagoOnlineModel = new MovimientoPago();
       $this->paymentMercadoPagoLib = new PaymentMercadoPago();
       $this->pagoMercadoPago = new PagoMercadoPago();
       $this->
  }
  
  public function index() {
    $data['contenido'] = "certificado/descargar";
    echo view("frontend", $data);
  }

  /**
   * Function que permite guardar_observacion
   */
  function guardar_observacion($lcuil) {
    // $this->form_validation->set_rules('observa', 'Observacion', 'trim|required|');            
    $tex_observa = $this->input->post('observa');
    //        echo $tex_observa;
    //        exit();
    //        if($tex_observa )
    //        {
        $this->persona->update_observacion($lcuil, $tex_observa); 
                                                                        
    //        } 
        $this->data['main'] = 'persona/include/success';
        $this->load->view('include/template', $this->data);
        return;
  }

  /**
   * Clasificacion de Huellas Digitales del modal al establecer 
   * un numero correspondiente.
   */
  //----------------------------------    
  function modifica_hue_guardar2($lcuil) {
    //$this->form_validation->set_rules('cuil', 'CUIL', 'trim|required|numeric|exact_length[11]|integer');
    //$this->form_validation->set_rules('sdocumento', 'Tipo de Documento', 'callback__tipo_dni_check');
    //$this->form_validation->set_rules('dni', 'N°', 'trim|required|numeric|min_length[7]|max_length[8]|integer');
    //$this->form_validation->set_rules('apellido', 'Apellido', 'trim|required');
    //$this->form_validation->set_rules('nombre', 'Nombres', 'trim|required');
    $this->form_validation->set_rules('pulgar_d', 'Pulgar Derecho', 'max_length[1]');
    $this->form_validation->set_rules('indice_d', 'Indice Derecho', 'max_length[1]');
    $this->form_validation->set_rules('mayor_d', 'Mayor Derecho', 'max_length[1]');
    $this->form_validation->set_rules('anular_d', 'Anular Derecho', 'max_length[1]');
    $this->form_validation->set_rules('menique_d', 'Meñique Derecho', 'max_length[1]');
    $this->form_validation->set_rules('pulgar_i', 'Pulgar Izquierdo', 'max_length[1]');
    $this->form_validation->set_rules('indice_i', 'Indice Izquierdo', 'max_length[1]');
    $this->form_validation->set_rules('mayor_i', 'Mayor Izquiedo', 'max_length[1]');
    $this->form_validation->set_rules('anular_i', 'Anular Izquierdo', 'max_length[1]');
    $this->form_validation->set_rules('menique_i', 'Meñique Derecho', 'max_length[1]');
    //$this->form_validation->set_rules('sulugar', 'Lugar de Nacimiento', 'trim|callback__sulugar_check');
    
    $this->form_validation->set_message('numeric', 'El %s de documento debe ser numérico');
    $this->form_validation->set_message('integer', 'El campo %s debe ser entero');
    $huellas_m = $this->huella->search_huella($lcuil);
    $ver = $this->persona->search_persona($lcuil);       

    $cuil_p = NULL;
    $cuil_nn = NULL;

    $nombre_c = array(
    '1' => 'pulgar_d',
    '2' => 'indice_d',
    '3' => 'mayor_d',
    '4' => 'anular_d',
    '5' => 'minique_d',       
    '6' => 'pulgar_i',
    '7' => 'indice_i',
    '8' => 'mayor_i',
    '9' => 'anular_i',
    '10' => 'minique_i',           
    );    
    //++++++++++++++++ INICIO ++++++++++++++++++
   
    if (!empty($huellas_m)) {
     $tipificado = array();
//         $seccion = '';         
//         $i = 0;
     for ($i = 1; $i <= 10; $i++) {
        $tipificado[$i] = ' ';             
      }
    foreach ($huellas_m as $m):                                                                                                                                                            

    if($this->input->post($nombre_c[$m->dedo]))
    {
       if ($lcuil == $m->cuil){
          $cuil_p = $lcuil;
          $cuil_nn = $m->cuil_nn;                   
       }elseif ($lcuil == $m->cuil_nn) {
          $cuil_nn = $lcuil;
          $cuil_p = $m->cuil;
       }
     $tipificado[$m->dedo] = $this->input->post($nombre_c[$m->dedo]);
                  
       if ($this->input->post($nombre_c[$m->dedo]) != $m->huella)
       {  $this->huella->update_2_huella($cuil_p, $m->dedo, $m->id_huella, $this->input->post($nombre_c[$m->dedo]), $cuil_nn);            
          // echo $this->input->post($nombre_c[$m->dedo]);                
          }                                                                            
    } 
    endforeach;
   $separado = implode($tipificado);
   $serie = substr($separado, 0, 5);       
   $seccion = substr($separado, 5);
   $this->persona->update_huella($lcuil, $serie, $seccion);       
            
    }
    //++++++++++++++++ FINAL ++++++++++++++++++
//       $separado = implode($tipificado);
//       $serie = substr($separado, 0, 5);       
//       $seccion = substr($separado, 5);
//        var_dump($separado);
//        echo '<br>';
//        var_dump($serie);        
//        echo '<br>';
//        var_dump($seccion);
//       exit();
    if ($this->form_validation->run() == FALSE) {
        $this->modificar_per($lcuil);
    } else {                           
        
//            $this->data['main'] = 'persona/include/success';
        $this->data['main'] = 'persona/include/success_cerrar';
        
        $this->load->view('include/template', $this->data);
//            $this->output->enable_profiler(TRUE);
        return;

    }
}



 
}
