<?php

use App\Controllers\BaseController;


class Sincronizar extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->library('correo');
         $this->load->helper(array('url', 'form', 'array'));
        $this->load->library('form_validation');

        $this->load->model('movil_model');
        $this->load->model('talonario_model');
        $this->load->model('vales_model');
        $this->load->model('carga_model');
        $this->load->model('carga_particular_model');
        $this->load->model('carga_vale_model');
        $this->load->model('carga_especial_model');        
        $this->load->model('provision_interior_model');              
        $this->load->model('provision_puesto1_model');        
        $this->load->model('provision_puesto1_vale_model');                
        $this->load->model('sincronizacion_model');
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            $this->data['contenido'] = "sincronizar_view";
            $this->load->view('frontend', $this->data);
        }else {
            redirect('admin/login');
        }
    }

    public function enviar_registros() {
        if ($this->ion_auth->logged_in()) {
            $this->data['contenido'] = "sincronizar_view";
            $this->load->view('frontend', $this->data);
        }else {
            redirect('admin/login');
        }
    }

    public function mostrar_sincro($lista) {
        if ($this->ion_auth->logged_in()) {
            $this->data['contenido'] = "sincronizar_view";
            $this->load->view('frontend', $this->data);
        }else {
            redirect('admin/login');
        }
    }
//    public function enviar_registros_al_servidor() {
////        $this->data['mensaje'] = 'Recuperando registros de talonarios ......';
////        $this->data['lista_talonarios'] = $this->talonario_model->findBySrincroEnvio(1);
////        echo count($talonarios) . ' registros de talonarios ......';
//        $contenido = NULL;
//        $cant_reg = 0;
//
//        $file = fopen("E:/sincro_".date('d-m-Y').".txt", "w+");
//
//        // INSERT
//        fwrite($file, "-- Insert Moviles --" . PHP_EOL);
//        $moviles = $this->movil_model->findBySrincroEnvio(0);
//        foreach($moviles as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "INSERT INTO logistica.moviles (legajo, anio, marca, modelo, dominio, nro_chasis_o_cuadro, nro_motor, id_tipo_movil, id_unidad_policial, id_dependencia, id_situacion, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, flag_depositario_judicial, sincro_envio)
//                           VALUES ('$item->legajo', '$item->anio', '$item->marca', '$item->modelo', '$item->dominio', '$item->nro_chasis_o_cuadro', '$item->nro_motor', '$item->id_tipo_movil', '$item->id_unidad_policial', '$item->id_dependencia', '$item->id_situacion', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->flag_depositario_judicial', '1');" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Insert Talonarios --" . PHP_EOL);
//        $talonarios = $this->talonario_model->findBySrincroEnvio(0);
//        foreach($talonarios as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "INSERT INTO logistica.talonarios (id, id_tipo_combustible, inicio, fin, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio)
//                           VALUES ('$item->id', '$item->id_tipo_combustible', '$item->inicio', '$item->fin', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1');" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Insert Vales --" . PHP_EOL);
//        $vales = $this->vales_model->findBySrincroEnvio(0);
//        foreach($vales as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "INSERT INTO logistica.vales (id, numero, asignado, id_talonario, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio)
//                           VALUES ('$item->id', '$item->numero', '$item->asignado', '$item->id_talonario', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1');" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Insert Cargas --" . PHP_EOL);
//        $cargas = $this->carga_model->findBySrincroEnvio(0);
//        foreach($cargas as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            $kilometraje = $this->getValue($item->kilometraje);
//            $nro_comprobante = $this->getValue($item->nro_comprobante);
//            $observacion = $this->getValue($item->observacion);
//            $nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
//            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
//            $jefe_unidad_autorizante = $this->getValue($item->jefe_unidad_autorizante);
//            $importe = $this->getValue($item->importe);
//            fwrite($file, "INSERT INTO logistica.cargas (id, legajo_movil, kilometraje, legajo_personal, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, id_tipo_combustible, cantidad_litros, nro_nota_refuerzo, jefe_logistica_autorizante, jefe_unidad_autorizante, observacion, nro_comprobante, id_estacion, id_tipo, importe, sincro_envio)
//                           VALUES ('$item->id', '$item->legajo_movil', $kilometraje, '$item->legajo_personal', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->id_tipo_combustible', '$item->cantidad_litros', $nro_nota_refuerzo, $jefe_logistica_autorizante, $jefe_unidad_autorizante, $observacion, $nro_comprobante, '$item->id_estacion', '$item->id_tipo', $importe, '1');" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Insert Cargas Particulares --" . PHP_EOL);
//        $cargas_particular = $this->carga_particular_model->findBySrincroEnvio(0);
//        foreach($cargas_particular as $item):
//            $modelo = $this->getValue($item->modelo);
//            $anio = $this->getValue($item->anio);
//            $legajo = $this->getValue($item->legajo);
//            $dni = $this->getValue($item->dni);
//
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            $kilometraje = $this->getValue($item->kilometraje);
//            $nro_comprobante = $this->getValue($item->nro_comprobante);
//            $observacion = $this->getValue($item->observacion);
//            $nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
//            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
//            $jefe_unidad_autorizante = $this->getValue($item->jefe_unidad_autorizante);
//            $importe = $this->getValue($item->importe);
//            $nro_resolucion = $this->getValue($item->nro_resolucion);
//            fwrite($file, "INSERT INTO logistica.cargas_particular (id, dominio, marca, modelo, anio, id_tipo_movil, kilometraje, legajo, dni, apellido, nombre, cargo_funcion, lugar_de_trabajo, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, id_tipo_combustible, cantidad_litros, nro_nota_refuerzo, jefe_logistica_autorizante, jefe_unidad_autorizante, observacion, nro_comprobante, id_estacion, id_tipo, importe, nro_resolucion, sincro_envio)
//                           VALUES ('$item->id', '$item->dominio', '$item->marca', $modelo, $anio, '$item->id_tipo_movil', $kilometraje, $legajo, $dni, '$item->apellido', '$item->nombre', '$item->cargo_funcion', '$item->lugar_de_trabajo', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->id_tipo_combustible', '$item->cantidad_litros', $nro_nota_refuerzo, $jefe_logistica_autorizante, $jefe_unidad_autorizante, $observacion, $nro_comprobante, '$item->id_estacion', '$item->id_tipo', $importe, $nro_resolucion, '1');" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Insert Carga Vales --" . PHP_EOL);
//        $carga_vales = $this->carga_vale_model->findBySrincroEnvio(0);
//        foreach($carga_vales as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "INSERT INTO logistica.cargas_vales (id, id_carga, id_carga_particular, id_vale, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio)
//                           VALUES ('$item->id', '$item->id_carga', '$item->id_carga_particular', '$item->id_vale', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1');" . PHP_EOL);
//        endforeach;
//
//        // UPDATES
//        fwrite($file, "-- Update Moviles --" . PHP_EOL);
//        $moviles = $this->movil_model->findBySrincroEnvio(2);
//        foreach($moviles as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "UPDATE logistica.moviles SET anio='$item->anio', marca='$item->marca', modelo='$item->modelo', dominio='$item->dominio', nro_chasis_o_cuadro='$item->nro_chasis_o_cuadro', nro_motor='$item->nro_motor', id_tipo_movil='$item->id_tipo_movil', id_unidad_policial='$item->id_unidad_policial', id_dependencia='$item->id_dependencia', id_situacion='$item->id_situacion', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, flag_depositario_judicial='$item->flag_depositario_judicial' WHERE legajo='$item->legajo';" . PHP_EOL);
//        endforeach;
//
////        fwrite($file, "-- Update Talonarios --" . PHP_EOL);
////        $moviles = $this->movil_model->findBySrincroEnvio(2);
////        foreach($moviles as $item):
////            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
////            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
////            fwrite($file, "UPDATE logistica.moviles SET anio='$item->anio', marca='$item->marca', modelo='$item->modelo', dominio='$item->dominio', nro_chasis_o_cuadro='$item->nro_chasis_o_cuadro', nro_motor='$item->nro_motor', id_tipo_movil='$item->id_tipo_movil', id_unidad_policial='$item->id_unidad_policial', id_dependencia='$item->id_dependencia', id_situacion='$item->id_situacion', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, flag_depositario_judicial='$item->flag_depositario_judicial' WHERE legajo='$item->legajo';" . PHP_EOL);
////        endforeach;
//
//        // UPDATES
//        fwrite($file, "-- Update Vales --" . PHP_EOL);
//        $vales = $this->vales_model->findBySrincroEnvio(2);
//        foreach($vales as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "UPDATE logistica.vales SET asignado='$item->asignado', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion WHERE id='$item->id';" . PHP_EOL);
//        endforeach;
//
//        fwrite($file, "-- Update Cargas --" . PHP_EOL);
//        $cargas = $this->carga_model->findBySrincroEnvio(2);
//        foreach($cargas as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            $kilometraje = $this->getValue($item->kilometraje);
//            $nro_comprobante = $this->getValue($item->nro_comprobante);
//            $observacion = $this->getValue($item->observacion);
//            $importe = $this->getValue($item->importe);
//            fwrite($file, "UPDATE logistica.cargas SET kilometraje=$kilometraje, id_estacion='$item->id_estacion', id_tipo_combustible='$item->id_tipo_combustible', cantidad_litros='$item->cantidad_litros', nro_comprobante=$nro_comprobante, observacion=$observacion, usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, id_tipo='$item->id_tipo', importe=$importe WHERE id='$item->id';" . PHP_EOL);
//        endforeach;
//        
//        $contenido = fgets($file);
//        fclose($file);
//        
//        
//        return $contenido;
//            
////        $this->data['contenido'] = "sincronizar_view";
////        $this->load->view('frontend', $this->data);
//    }

    public function lista_insert() {
//        $this->data['mensaje'] = 'Recuperando registros de talonarios ......';
//        $this->data['lista_talonarios'] = $this->talonario_model->findBySrincroEnvio(1);
//        echo count($talonarios) . ' registros de talonarios ......';
        $contenido = NULL;  
//inicio
//        $file = fopen(DIRECTORIO_TEMP . "sincro_".date('d-m-Y').".txt", "w+");        
        $file = fopen(DIRECTORIO_TEMP . "sincro_i.txt", "w+");                
        $nodo = PC;
        $cant_reg=0;
        // INSERT
//        fwrite($file, "-- Insert Moviles --" . PHP_EOL);
        fwrite($file, "" . PHP_EOL);
        $moviles = $this->movil_model->findBySrincroEnvio(0);
        foreach($moviles as $item):
            $cant_reg++;                            
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            fwrite($file, "INSERT INTO logistica.moviles (legajo, anio, marca, modelo, dominio, nro_chasis_o_cuadro, nro_motor, id_tipo_movil, id_unidad_policial, id_dependencia, id_situacion, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, flag_depositario_judicial, sincro_envio, pc) VALUES ('$item->legajo', '$item->anio', '$item->marca', '$item->modelo', '$item->dominio', '$item->nro_chasis_o_cuadro', '$item->nro_motor', '$item->id_tipo_movil', '$item->id_unidad_policial', '$item->id_dependencia', '$item->id_situacion', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->flag_depositario_judicial', '1', $nodo);" . PHP_EOL);  
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;    

//        fwrite($file, "-- Insert Talonarios --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $talonarios = $this->talonario_model->findBySrincroEnvio(0);
        foreach($talonarios as $item):
            $cant_reg++;                          
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            fwrite($file, "INSERT INTO logistica.talonarios (id_clie, id_tipo_combustible, inicio, fin, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) VALUES ('$item->id', '$item->id_tipo_combustible', '$item->inicio', '$item->fin', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }              
        endforeach;        
        }
//        fwrite($file, "-- Insert Vales --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $vales = $this->vales_model->findBySrincroEnvio(0);
        foreach($vales as $item):
            $cant_reg++;                           
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            fwrite($file, "INSERT INTO logistica.vales (id_clie, numero, asignado, id_talonario, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) VALUES ('$item->id', '$item->numero', '$item->asignado', '$item->id_talonario', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;        
        }
//        fwrite($file, "-- Insert Cargas --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $cargas = $this->carga_model->findBySrincroEnvio(0);
        foreach($cargas as $item):
            $cant_reg++;                   
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            $kilometraje = $this->getValue($item->kilometraje);
            $kilometraje = $item->kilometraje;
            $nro_comprobante = $this->getValue($item->nro_comprobante);
            $observacion = $this->getValue($item->observacion);
            $nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
            $jefe_unidad_autorizante = $this->getValue($item->jefe_unidad_autorizante);
            $importe = $this->getValue($item->importe);
            fwrite($file, "INSERT INTO logistica.cargas (id_clie, legajo_movil, kilometraje, legajo_personal, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, id_tipo_combustible, cantidad_litros, nro_nota_refuerzo, jefe_logistica_autorizante, jefe_unidad_autorizante, observacion, nro_comprobante, id_estacion, id_tipo, importe, sincro_envio, pc) VALUES ('$item->id', '$item->legajo_movil', $kilometraje, '$item->legajo_personal', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->id_tipo_combustible', '$item->cantidad_litros', $nro_nota_refuerzo, $jefe_logistica_autorizante, $jefe_unidad_autorizante, $observacion, $nro_comprobante, '$item->id_estacion', '$item->id_tipo', $importe, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;
        }
        
//        fwrite($file, "-- Insert Cargas Particulares --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $cargas_particular = $this->carga_particular_model->findBySrincroEnvio(0);
        foreach($cargas_particular as $item):
            $cant_reg++;                    
            $modelo = $this->getValue($item->modelo);
            $anio = $this->getValue($item->anio);
            $legajo = $this->getValue($item->legajo);
            $dni = $this->getValue($item->dni);

            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            $kilometraje = $this->getValue($item->kilometraje);
            $marca = $this->getValue($item->marca);
            $id_tipo_movil = $this->getValue($item->id_tipo_movil);
            $nro_comprobante = $this->getValue($item->nro_comprobante);
            $observacion = $this->getValue($item->observacion);
            $nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
            $jefe_unidad_autorizante = $this->getValue($item->jefe_unidad_autorizante);
            $importe = $this->getValue($item->importe);
            $nro_resolucion = $this->getValue($item->nro_resolucion);
            fwrite($file, "INSERT INTO logistica.cargas_particular (id_clie, dominio, marca, modelo, anio, id_tipo_movil, kilometraje, legajo, dni, apellido, nombre, cargo_funcion, lugar_de_trabajo, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, id_tipo_combustible, cantidad_litros, nro_nota_refuerzo, jefe_logistica_autorizante, jefe_unidad_autorizante, observacion, nro_comprobante, id_estacion, id_tipo, importe, nro_resolucion, sincro_envio, pc) VALUES ('$item->id', '$item->dominio', $marca, $modelo, $anio, $id_tipo_movil, $kilometraje, $legajo, $dni, '$item->apellido', '$item->nombre', '$item->cargo_funcion', '$item->lugar_de_trabajo', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '$item->id_tipo_combustible', '$item->cantidad_litros', $nro_nota_refuerzo, $jefe_logistica_autorizante, $jefe_unidad_autorizante, $observacion, $nro_comprobante, '$item->id_estacion', '$item->id_tipo', $importe, $nro_resolucion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;        
        }
        
//        fwrite($file, "-- Insert Cargas Especiales --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $cargas_especiales = $this->carga_especial_model->findBySrincroEnvio(0);
        foreach($cargas_especiales as $item):
            $cant_reg++;                   
           // $modelo = $this->getValue($item->modelo);
            //$anio = $this->getValue($item->anio);
            //$legajo = $this->getValue($item->legajo);
            //$dni = $this->getValue($item->dni);
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            //$kilometraje = $this->getValue($item->kilometraje);
            $nro_comprobante = $this->getValue($item->nro_comprobante);
            $observacion = $this->getValue($item->observacion);
            $nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
            $jefe_unidad_autorizante = $this->getValue($item->jefe_unidad_autorizante);
            $importe = $this->getValue($item->importe);
            //$nro_resolucion = $this->getValue($item->nro_resolucion);
            fwrite($file, "INSERT INTO logistica.cargas_especiales (id_clie, descripcion, id_dependencia, id_unidad_policial, legajo_personal, id_tipo_combustible, cantidad_litros, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, nro_nota_refuerzo, jefe_logistica_autorizante, jefe_unidad_autorizante, observacion, nro_comprobante, id_estacion, id_tipo, importe, sincro_envio, pc) "
    . "     VALUES ('$item->id', '$item->descripcion', '$item->id_dependencia', '$item->id_unidad_policial', '$item->legajo_personal', '$item->id_tipo_combustible', '$item->cantidad_litros', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, $nro_nota_refuerzo, $jefe_logistica_autorizante, $jefe_unidad_autorizante, $observacion, $nro_comprobante, '$item->id_estacion', '$item->id_tipo', $importe, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;          
        }
        
//        fwrite($file, "-- Insert Carga Vales --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $carga_vales = $this->carga_vale_model->findBySrincroEnvio(0);
        foreach($carga_vales as $item):
            $cant_reg++;               
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            $id_carga_particular = $this->getValue($item->id_carga_particular);
            $id_carga = $this->getValue($item->id_carga);
            fwrite($file, "INSERT INTO logistica.cargas_vales (id_clie, id_carga, id_carga_particular, id_vale, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) VALUES ('$item->id', $id_carga, $id_carga_particular, '$item->id_vale', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;
        }
//        fwrite($file, "-- Insert Provision Interior --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){
        $provision_interior = $this->provision_interior_model->findBySrincroEnvio(0);
        foreach($provision_interior as $item):
            $cant_reg++;                     
            //$modelo = $this->getValue($item->modelo);
            //$anio = $this->getValue($item->anio);
            //$legajo = $this->getValue($item->legajo);
            //$dni = $this->getValue($item->dni);
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            //$kilometraje = $this->getValue($item->kilometraje);
            $nro_comprobante = $this->getValue($item->nro_comprobante);
            $observacion = $this->getValue($item->observacion);
            $aclaracion_de_provision = $this->getValue($item->aclaracion_de_provision);
            //$nro_nota_refuerzo = $this->getValue($item->nro_nota_refuerzo);
            $jefe_logistica_autorizante = $this->getValue($item->jefe_logistica_autorizante);
            $id_tipo_combustible_2 = $this->getValue($item->id_tipo_combustible_2);
            $cantidad_litros_2 = $this->getValue($item->cantidad_litros_2);
            $importe = $this->getValue($item->importe);
            $destino_2 = $this->getValue($item->destino_2);
            
            //$nro_resolucion = $this->getValue($item->nro_resolucion);
            fwrite($file, "INSERT INTO logistica.provision_interior (id_clie, destino_1, legajo_personal, id_estacion, id_tipo_combustible_1, cantidad_litros_1, nro_comprobante, importe, jefe_logistica_autorizante, aclaracion_de_provision, observacion, destino_2, id_tipo_combustible_2, cantidad_litros_2, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) "
    . "VALUES ('$item->id', '$item->destino_1', '$item->legajo_personal', '$item->id_estacion', '$item->id_tipo_combustible_1', '$item->cantidad_litros_1', $nro_comprobante, $importe, $jefe_logistica_autorizante, $aclaracion_de_provision, $observacion, $destino_2, $id_tipo_combustible_2, $cantidad_litros_2, '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;  
        }
        
//        fwrite($file, "-- Insert Provision Puesto 1 --" . PHP_EOL);   
        if($cant_reg < MAX_SINCRO){
        $provision_puesto1 = $this->provision_puesto1_model->findBySrincroEnvio(0);
        foreach($provision_puesto1 as $item):
            $cant_reg++;
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            $observacion = $this->getValue($item->observacion);
            fwrite($file, "INSERT INTO logistica.provision_puesto1 (id_clie, legajo_personal, observacion, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) "
    . "VALUES ('$item->id', '$item->legajo_personal', $observacion, '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;  
        }
        
//        fwrite($file, "-- Insert Provision Puesto 1 Vales --" . PHP_EOL);   
        if($cant_reg < MAX_SINCRO){        
        $provision_puesto1_vale = $this->provision_puesto1_vale_model->findBySrincroEnvio(0);
        foreach($provision_puesto1_vale as $item):
            $cant_reg++;               
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            //$observacion = $this->getValue($item->observacion);
            fwrite($file, "INSERT INTO logistica.provision_puesto1_vales (id_clie, id_provision_puesto1, id_vale, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, sincro_envio, pc) "
    . "VALUES ('$item->id', '$item->id_provision_puesto1', '$item->id_vale', '$item->usuario_alta', '$item->fecha_alta', $usuario_modificacion, $fecha_modificacion, '1', $nodo);" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }            
        endforeach;        
        }
        
        fclose($file);
//        exit();
//fin        
        //$file = fopen("E:/sincro_".date('d-m-Y').".txt", "r");
//        $file = fopen(DIRECTORIO_TEMP . "sincro_".date('d-m-Y').".txt", "r");
        $file = fopen(DIRECTORIO_TEMP . "sincro_i.txt", "r");        
        
        
// echo "antes";
//        while(!feof($fp)) {   
        $linea = NULL;
        $i= 0;
        while(!feof($file)) {            
//            $linea = fgets($fp);
            
            $i++;
            $linea = fgets($file);            
//            echo $linea . "<br />";
            //break;            
    //            $contenido = $contenido . $linea .'<br>';

    //            if(!empty($linea)){
//                $str = $linea;
//                echo strlen($str); // 6
//                //var_dump($linea);
//                echo "ingresooo ah    ora";
//                exit();
//               $contenido = $contenido . $linea;         
//            }
//            
            $contenido = $contenido . $linea;             
            if($i > MAX_SINCRO){
                break;
            }             
        }

//        fclose($fp);        
        fclose($file);

        return $contenido;
    }

    public function lista_update() {    
        $contenido = NULL;  
//inicio
        //$file = fopen("E:/sincro_up_".date('d-m-Y').".txt", "w+");
//        $file = fopen(DIRECTORIO_TEMP . "sincro_up_".date('d-m-Y').".txt", "w+");
        $file = fopen(DIRECTORIO_TEMP . "sincro_up.txt", "w+");        

        // INSERT
//        fwrite($file, "-- Insert Moviles --" . PHP_EOL);
        $nodo = PC;
        $cant_reg=0;
        fwrite($file, "" . PHP_EOL);
        // UPDATES
//        fwrite($file, "-- Update Moviles --" . PHP_EOL);
        $moviles = $this->movil_model->findBySrincroEnvio(2);
        foreach($moviles as $item):
            $cant_reg++;
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            fwrite($file, "UPDATE logistica.moviles SET anio='$item->anio', marca='$item->marca', modelo='$item->modelo', dominio='$item->dominio', nro_chasis_o_cuadro='$item->nro_chasis_o_cuadro', nro_motor='$item->nro_motor', id_tipo_movil='$item->id_tipo_movil', id_unidad_policial='$item->id_unidad_policial', id_dependencia='$item->id_dependencia', id_situacion='$item->id_situacion', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, flag_depositario_judicial='$item->flag_depositario_judicial' WHERE legajo='$item->legajo' and pc='$nodo';" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }                   
        endforeach;

//        fwrite($file, "-- Update Talonarios --" . PHP_EOL);
//        $moviles = $this->movil_model->findBySrincroEnvio(2);
//        foreach($moviles as $item):
//            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
//            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
//            fwrite($file, "UPDATE logistica.moviles SET anio='$item->anio', marca='$item->marca', modelo='$item->modelo', dominio='$item->dominio', nro_chasis_o_cuadro='$item->nro_chasis_o_cuadro', nro_motor='$item->nro_motor', id_tipo_movil='$item->id_tipo_movil', id_unidad_policial='$item->id_unidad_policial', id_dependencia='$item->id_dependencia', id_situacion='$item->id_situacion', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, flag_depositario_judicial='$item->flag_depositario_judicial' WHERE legajo='$item->legajo';" . PHP_EOL);
//        endforeach;

        // UPDATES
//        fwrite($file, "-- Update Vales --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){        
        $vales = $this->vales_model->findBySrincroEnvio(2);
        foreach($vales as $item):
            $cant_reg++;
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            fwrite($file, "UPDATE logistica.vales SET asignado='$item->asignado', usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion WHERE id_clie='$item->id' and pc='$nodo';" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }                         
        endforeach;
        }
//        fwrite($file, "-- Update Cargas --" . PHP_EOL);
        if($cant_reg < MAX_SINCRO){        
        $cargas = $this->carga_model->findBySrincroEnvio(2);
        foreach($cargas as $item):
            $cant_reg++;
            $usuario_modificacion = $this->getValue($item->usuario_modificacion);
            $fecha_modificacion = $this->getValue($item->fecha_modificacion);
            $kilometraje = $this->getValue($item->kilometraje);
            $nro_comprobante = $this->getValue($item->nro_comprobante);
            $observacion = $this->getValue($item->observacion);
            $importe = $this->getValue($item->importe);
            fwrite($file, "UPDATE logistica.cargas SET kilometraje=$kilometraje, id_estacion='$item->id_estacion', id_tipo_combustible='$item->id_tipo_combustible', cantidad_litros='$item->cantidad_litros', nro_comprobante=$nro_comprobante, observacion=$observacion, usuario_modificacion=$usuario_modificacion, fecha_modificacion=$fecha_modificacion, id_tipo='$item->id_tipo', importe=$importe WHERE id_clie='$item->id' and pc='$nodo';" . PHP_EOL);
            if($cant_reg >= MAX_SINCRO){
                break;
            }             
            
        endforeach;        
        }
        
        fclose($file);
//        echo "lista Update";
//        exit();
//fin        

//        $file = fopen(DIRECTORIO_TEMP . "sincro_up_".date('d-m-Y').".txt", "r");
        $file = fopen(DIRECTORIO_TEMP . "sincro_up.txt", "r");        
        
        $i= 0;
//        while(!feof($fp)) {
        while(!feof($file)) { 
            if($i >= MAX_SINCRO){
                break;
            }             
            $i++;
//            $linea = fgets($fp);
            $linea = fgets($file);            
            //echo $linea . "<br />";
//            $contenido = $contenido . $linea .'<br>';
            $contenido = $contenido . $linea;
//            if($i== MAX_SINCRO){
//                break;
//            }
        }

//        fclose($fp);        
        fclose($file);

        return $contenido;
    }
    
    public function actualiza_sincro_h($registros){

        $this->data['registros'] = $registros;
        $this->data['tipo_operacion'] = 1;
                
        $this->sincronizacion_model->insert_h($this->data);        
    }
    
    
    public function actualiza_sincro_h_up($registros){

        $this->data['registros'] = $registros;
        $this->data['tipo_operacion'] = 2;
                
        $this->sincronizacion_model->insert_h($this->data);        
//        'usuario_alta' => $this->session->userdata('user_id'),
//        'fecha_alta' => date('Y-m-d H:i:s'),        
    }    
    public function contar_insert($sincro){
//        $this->data['registros'] = $registros;
//        $this->data['tipo_operacion'] = 2;
    $x = 0;
//    echo $this->movil_model->cuenta(0)->count.'-<br>';
//    exit();
//    $x = $this->movil_model->cuenta(0);  
//    echo $x->count . '<br>';
//    echo $x . '<br>';    
//    var_dump($this->movil_model->cuenta(0)); 
//    echo $x;
//    exit();
    $x = $this->movil_model->cuenta($sincro)->count;
    $x = $x + $this->talonario_model->cuenta($sincro)->count;     
    $x = $x + $this->vales_model->cuenta($sincro)->count;     
    $x = $x + $this->carga_model->cuenta($sincro)->count;     
    $x = $x + $this->carga_particular_model->cuenta($sincro)->count;     
    $x = $x + $this->carga_especial_model->cuenta($sincro)->count;         
    $x = $x + $this->carga_vale_model->cuenta($sincro)->count;         
    $x = $x + $this->provision_interior_model->cuenta($sincro)->count;         
    $x = $x + $this->provision_puesto1_model->cuenta($sincro)->count;             
    $x = $x + $this->provision_puesto1_vale_model->cuenta($sincro)->count;                 
    return $x;
    }
    
    public function contar_update($sincro){
    $x = 0;
    $x = $this->movil_model->cuenta($sincro)->count;
//    $x = $x + $this->talonario_model->cuenta(0)->count;     
    $x = $x + $this->vales_model->cuenta($sincro)->count;     
    $x = $x + $this->carga_model->cuenta($sincro)->count;     
    
//    $x = $x + $this->carga_particular_model->cuenta(0)->count;     
//    $x = $x + $this->carga_especial_model->cuenta(0)->count;         
//    $x = $x + $this->carga_vale_model->cuenta(0)->count;         
//    $x = $x + $this->provision_interior_model->cuenta(0)->count;         
//    $x = $x + $this->provision_puesto1_model->cuenta(0)->count;             
//    $x = $x + $this->provision_puesto1_vale_model->cuenta(0)->count;                 
    return $x;
    }    
    
    public function envia_data() {

        $resp_i = 0;
//        $band_i = 0;
        $resp_up = 0;
        $lista_no=0;
        $cant_insert = 0;
        $cant_update = 0;
        $cant_insert = $this->contar_insert(0);
        $cant_update = $this->contar_update(2);
//        echo $cant_insert.'-'.$cant_update;
//        exit();
        $lista_i = $this->lista_insert();
        echo "listo archivo insert";
        exit();
        
        $resultado = count(explode(";", $lista_i));
//        echo $cant_update .'<br>';
//        var_dump($resultado);
//        $lista_up = $this->lista_update();
//        var_dump($lista_up);
//        exit();
        if( $cant_insert> 0){            
            $resp_i_an = $this->native_curl55($lista_i);
            
            //$resp_up_an = $this->native_curl_up($lista_up);

            $porcion_i = explode("_", $resp_i_an);

            $resp_i = $porcion_i[0];            
            $lista_no = $lista_no + $porcion_i[1];
            
            if (!empty($resp_i)) {
                $this->actualiza_sincro_h($resp_i);
            }
//            $band_i = 1;
        }
                        

        $lista_up = $this->lista_update();
//        $resultado_up = count(explode(";", $lista_up));
//        if ($resultado_up > 1) {
        if ($cant_update > 0) {
            
            $resp_up_an = $this->native_curl_up($lista_up);
            $porcion_up = explode("_", $resp_up_an);
            $lista_no = $lista_no + $porcion_up[1];
            $resp_up = $porcion_up[0];
            if (!empty($resp_up)) {
                $this->actualiza_sincro_h_up($resp_up);
            }
//            $band_up = 1;
        }

//        $this->actualiza_sincro_h($resp_i + $resp_up);
        
        $this->data['lista'] = $resp_i + $resp_up;
//        $this->data['lista_pre'] = $resultado + $resultado_up -($resp_i + $resp_up+2);
        $this->data['lista_pre'] = $cant_insert + $cant_update -($resp_i + $resp_up+$lista_no);        
        $this->data['lista_no'] = $lista_no;
//        $this->data['lista_i'] = $resp_i;        
//        $this->data['lista_up'] = $resp_up;        
        
        $this->data['contenido'] = "sincronizar/sincronizar_resul";
        $this->load->view('frontend', $this->data);              
        
    }    

    function native_curl_up($data) {
//API URL
//        $url = 'http://localhost/restserver/api/logistica/migrarup/';
        $url = 'http://dap.policiadejujuy.gov.ar/restserver/api/logistica/migrarup/';

//API key
        $apiKey = 'CODEX123';

//Auth credentials
        $username = "admin";
        $password = "1234";

//user information
        $userData = array(
            'user_sincro' => $this->session->userdata('user_id'),            
            'pc' => PC,
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'datos' => $data
        );
//create a new cURL resource
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

        $result = curl_exec($ch);

//close cURL resource
        curl_close($ch);

//        echo "antes de mostrar result vuelta--  up en PC<br>";
//        var_dump($result);
//        $array_d = json_decode($result);
//        if($array_d == 0){
//            echo "es igual a cero sippppp";
//        }
//        var_dump($array_d);
//        exit();        

//        if (empty($array_d)) {
//            echo "esta todo vacio ahora<br>";
////            exit();
//            return NULL;
//        } else {
            
        if (is_array(json_decode($result, true))) {
            $array_d = json_decode($result);
            
//            if($array_d == 0){
//                var_dump($array_d);
//                echo "el valor es string cero";
////                exit();
////                return NULL;
//            }
            
//            echo "la variable contiene elementos.<br>";
//            var_dump($array_d);
//            exit();
//            var_dump($result);
            
//            $array_d = json_decode($result);
            $p = 0;  
            $i=0;
            $no=0;
            $id_leg = NULL;
            foreach ($array_d as $obj) {
                if (!empty($obj)) {
                    $i++;
                    $id_leg[$i] = explode("_", $obj);
                    $movil = NULL;
                    //$pos = strpos($obj, $tabla_i[$m]);
                    switch ($id_leg[$i][1]) {
                        case "m":
                            $movil = $this->movil_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "t":                            
                            $movil = $this->talonario_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "c":
                            $movil = $this->carga_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "v":
                            $movil = $this->vales_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "cp":
                            $movil = $this->carga_particular_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "cv":
                            $movil = $this->carga_vale_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "ce":
                            $movil = $this->carga_especial_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pi":
                            $movil = $this->provision_interior_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pp1":
                            $movil = $this->provision_puesto1_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pp1v":
                            $movil = $this->provision_puesto1_vale_model->update_sincro($id_leg[$i][0]);
                            break;                                                
                        case "no":
                            //$movil = $this->provision_puesto1_vale_model->update_sincro($id_leg[$i][0]);
                            $no++;
                            break;                                                

                        }

//                    $movil = $this->movil_model->update_sincro($obj);
//                    var_dump($moviles);
//                    echo '<br>' . $movil . '<br>';
                    if($movil){
                        $p++;
                    }
                }
            }
//            $valores = array(
//                "1" => $p,
//                "2" => $no,
//            );
            

            //return $p;
            return $p.'_'.$no;
        }else{
//            echo "salioooo";
//            exit();
            return '0_0';            
//            return NULL;
        }
    }
    
    
    function native_curl55($data) {
//API URL
//        $url = 'http://localhost/restserver/api/logistica/migrar/';
        $url = 'http://dap.policiadejujuy.gov.ar/restserver/api/logistica/migrar/';

//API key
        $apiKey = 'CODEX123';

//Auth credentials
        $username = "admin";
        $password = "1234";

//user information
        $userData = array(
            'user_sincro' => $this->session->userdata('user_id'),
            'pc' => PC,
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'datos' => $data
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "holaaaaaaa");

        $result = curl_exec($ch);

//close cURL resource
        curl_close($ch);
//        echo "antes de mostrar result en PC<br>";
//        var_dump($result);
//        exit();
//        
//        if (is_array(json_decode($result, true))) {
//             echo "este es un array";
//        }else{
//             echo "No es un array";
//        }
        
//        echo '<br>'.count(json_decode($result, true));
//        exit();
        
        
//        echo "antes de mostrar result en PC<br>";
//        var_dump($array_d);
//        exit();
        
//        if (empty($array_d)) {
//            echo "esta todo vacio ahora<br>";
//                        exit();
//            return NULL;
//
//        } else {
//            echo "la variable contiene elementos.<br>";
//            exit();
            
            
//            if($result == 0){
//                echo "el valor es string cero";
//                return NULL;
//            }
//            $array_d = json_decode($result);
            
            
        if (is_array(json_decode($result, true))) {
//            echo "ingreso es array";
//            exit();
            $array_d = json_decode($result);
            $p = 0;
//        $tabla_i = array(
//        "1" => "m",
//        "2" => "t",
//        "3" => "c",
//        "4" => "v",
//        "5" => "cp",            
//        "6" => "cv",
//        );            
            $i=0;
            $no=0;

            $id_leg = NULL;
            foreach ($array_d as $obj) {
                if (!empty($obj)) {
                    $i++;
                    $id_leg[$i] = explode("_", $obj);
                    $movil = NULL;
//                    var_dump($obj);
//                    exit();
                    //$pos = strpos($obj, $tabla_i[$m]);
                    switch ($id_leg[$i][1]) {
                        case "m":
                            $movil = $this->movil_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "t":                            
                            $movil = $this->talonario_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "c":
                            $movil = $this->carga_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "v":
                            $movil = $this->vales_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "cp":
                            $movil = $this->carga_particular_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "cv":
                            $movil = $this->carga_vale_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "ce":
                            $movil = $this->carga_especial_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pi":
                            $movil = $this->provision_interior_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pp1":
                            $movil = $this->provision_puesto1_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "pp1v":
                            $movil = $this->provision_puesto1_vale_model->update_sincro($id_leg[$i][0]);
                            break;
                        case "no":
                            $no++;
                            break;                                                
                        
                    }

                    if($movil){
                        $p++;
                    }
                }
            }
            //return $p;
            return $p.'_'.$no;
        }else{
//                        echo "esta todo vacio ahora<br>";
//                        exit();
//            return NULL;
            return '0_0';
        }

    }

    function actualizar_tabla($ids){
        
        foreach ($ids as $obj) {
            
            $moviles = $this->movil_model->update_sincro($obj);

            echo '<br>'.$obj.'<br>';
        }        
    }
    

    function cargar_archivo() {
//    echo "Nombre: " . $_FILES['archivo']['name'] . "<br>";
//    var_dump($_FILES['archivo']);
//    $_FILES['archivo']['name'][0] = "Ahora el archivo se llama tal";
//    echo $_FILES['archivo']['name'][0];
//    var_dump($_FILES['archivo']);
        //$nombre = $this->input->post('nombre');
        //
//        $sueldo = $this->hidden->post('cadena');        
//        echo $sueldo;
        
//          $totalx = $this->input->post('cadena');
        $observa = $this->input->post('observa');
        var_dump($observa);
//        echo $observa;
        
            $x = $this->input->post('total');
            echo $x;
//          echo $totalx;
//          echo"holaaaaaaaaaa";
        exit();

//$mi_imagen = 'mi_imagen';
//        var_dump($_FILES['archivos']);
//        $config['upload_path'] = "uploads/";
//        $config['file_name'] = "nombre_archivo";
//        $config['allowed_types'] = "gif|jpg|jpeg|png";
//        $config['max_size'] = "50000";
//        $config['max_width'] = "2000";
//        $config['max_height'] = "2000";
        echo "holaaaa";
        exit();
	$this->load->library('upload', $config);

        if (!$this->upload->do_upload($mi_imagen)) {
            //*** ocurrio un error
            $data['uploadError'] = $this->upload->display_errors();
            echo $this->upload->display_errors();
            return;
        }

        $data['uploadSuccess'] = $this->upload->data();
	}    
    
    public function getValue($campo) {
        if(empty($campo)) {
            return 'null';
        }else {
            return "'$campo'";
        }
    }

    public function index22() {
        $body = 'seeeee probando';
        $remitente = array('username'=>'dap.policia.jujuy@gmail.com', 'password'=>'dap12345', 'name'=>'DAP', 'subject'=>'sincro datos logistica');
        $destinatario = 'danjor.mam@gmail.com';

        $serv = $_SERVER['DOCUMENT_ROOT'] . "/";
        $folder = $serv . "logistica/sincro/";
        $folder = $folder. 'prueba.sql';

        try {
            @$this->correo->send_email_planilla_por_gmail($body, $remitente, $destinatario, $folder);
        } catch (Exception $e) {
            echo "A ocurrido un error inesperado durante el envio.";
        }
    }

    public function enviar() {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.googlemail.com',
            'smtp_user' => 'dap.policia.jujuy@gmail.com', //Su Correo de Gmail Aqui
            'smtp_pass' => 'dap12345', // Su Password de Gmail aqui
            'smtp_port' => '465',
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'wordwrap' => TRUE,
            'charset' => 'utf-8'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('danjor.mam@gmail.com');
        $this->email->subject('Asunto del correo');
        $this->email->message('probando seeee 12345');
        $this->email->to('danjor.mam@gmail.com');
        if($this->email->send(FALSE)){
            echo "enviado<br/>";
            echo $this->email->print_debugger(array('headers'));
        }else {
            echo "fallo <br/>";
            echo "error: ".$this->email->print_debugger(array('headers'));
        }
    }
}