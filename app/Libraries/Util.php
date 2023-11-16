<?php namespace App\Libraries;

use App\Models\LocalidadModel;
use App\Models\DepartamentoModel;

class Util {
    
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function fechaCastellano ($fecha) {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
    }
    

    /**
     * Funcion que permite obtener la url base de Banco Macro
     * en function del API 
     */
    public function getApiBaseURLBancoMacro(){
        $entorno = getenv("ENTORNO");
        $baseUrl = "";
        if ($entorno == "DEV") {
          $baseUrl =getenv("API_BANCO_MACRO_DEV");
        } else if($entorno == "PROD") {
          $baseUrl = getenv("API_BANCO_MACRO_PROD");  
        }
        return $baseUrl;
    }

    public function getDireccion($titular_tramite) {
        $localidadModel = new LocalidadModel();
        $localidad = $localidadModel->where('id_localidad', $titular_tramite['id_localidad'])->first();
        $localidad_nombre = $localidad != null ? $localidad['localidad']: "";

        $direccion = $titular_tramite['calle'];
        if(!empty($titular_tramite['numero'])) {
            if($titular_tramite['numero']!=SIN_NUMERO) {
                $direccion .= ' N° '.$titular_tramite['numero'];
            }else {
                $direccion .= ' '.$titular_tramite['numero'];
            }
        }
        if ($titular_tramite['manzana'] != '') {
            $direccion .= ', Mza. ' . $titular_tramite['manzana'];
        }
        if ($titular_tramite['lote'] != '') {
            $direccion .= ', Lote ' . $titular_tramite['lote'];
        }
//         if ($titular_tramite['sector'] != '') {
//             $direccion .= ', Sector ' . $titular_tramite['sector'];
//         }
        if ($titular_tramite['dpto'] != '') {
            $direccion .= ', Dpto. ' . $titular_tramite['dpto'];
        }
        if ($titular_tramite['piso'] != '') {
            $direccion .= ', Piso ' . $titular_tramite['piso'];
        }
        if(!empty($titular_tramite['barrio'])) {
            if($titular_tramite['barrio']!=SIN_BARRIO) {
                $direccion .= ', B° '.$titular_tramite['barrio'];
            }else {
                $direccion .= ', '.$titular_tramite['barrio'];
            }
        }
        if(!empty($localidad_nombre)) {
            $direccion .= ', '.$localidad_nombre;
        }

        return $direccion;
    }
    
    public function getDireccion2($titular_tramite) {
        $localidadModel = new LocalidadModel();
        $departamentoModel = new DepartamentoModel();
        $localidad = $localidadModel->where('id_localidad', $titular_tramite['id_localidad'])->first();
        $localidad_nombre = $localidad != null ? $localidad['localidad']: "";
        $departamento = $departamentoModel->where('id_departamento', $titular_tramite['id_departamento'])->first();
        $departamento_nombre = $departamento != null ? $departamento['depto']: "";
        
        $direccion = $titular_tramite['calle'];
        if(!empty($titular_tramite['numero'])) {
            if($titular_tramite['numero']!=SIN_NUMERO) {
                $direccion .= ' N° '.$titular_tramite['numero'];
            }else {
                $direccion .= ' '.$titular_tramite['numero'];
            }
        }
        if ($titular_tramite['manzana'] != '') {
            $direccion .= ', Mza. ' . $titular_tramite['manzana'];
        }
        if ($titular_tramite['lote'] != '') {
            $direccion .= ', Lote ' . $titular_tramite['lote'];
        }
        if ($titular_tramite['dpto'] != '') {
            $direccion .= ', Dpto. ' . $titular_tramite['dpto'];
        }
        if ($titular_tramite['piso'] != '') {
            $direccion .= ', Piso ' . $titular_tramite['piso'];
        }
        if(!empty($titular_tramite['barrio'])) {
            if($titular_tramite['barrio']!=SIN_BARRIO) {
                $direccion .= ', B° '.$titular_tramite['barrio'];
            }else {
                $direccion .= ', '.$titular_tramite['barrio'];
            }
        }
        if(!empty($localidad_nombre)) {
            $direccion .= ', '.$localidad_nombre;
        }
        if(!empty($departamento_nombre)) {
            $direccion .= ', '.$departamento_nombre;
        }
        
        return $direccion;
    }

    public function isTramiteOnline($idTipoTramite) {
        $isTramiteOnline = false;
        if($idTipoTramite==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA || $idTipoTramite==TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA || $idTipoTramite==TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA
            || $idTipoTramite==TIPO_TRAMITE_CONSTANCIA_DENUNCIA || $idTipoTramite==TIPO_TRAMITE_CONSTANCIA_EXTRAVIO || $idTipoTramite==TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE
            || $idTipoTramite==TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION) {
            return true;
        }
        return $isTramiteOnline;
    }

  

    public function getTemplateTurno($requisitos = null){
        $template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'>".
        "<html xmlns='http://www.w3.org/1999/xhtml'>".
        "<head>".
        "<meta name='viewport' content='width=device-width' />".
        "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />".
        "<title>Alta de Usuario</title>".
        "<style>".
        "* {
          margin: 0;
          padding: 0;
          font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
          box-sizing: border-box;
          font-size: 14px;
         }
        img {
            max-width: 100%;
        }
 
    body {
     -webkit-font-smoothing: antialiased;
     -webkit-text-size-adjust: none;
     width: 100% !important;
     height: 100%;
     line-height: 1.6;
 }
 
 /* Let's make sure all tables have defaults */
 table td {
     vertical-align: top;
 }
 
 /* -------------------------------------
     BODY & CONTAINER
 ------------------------------------- */
 body {
     background-color: #f6f6f6;
 }
 
 .body-wrap {
     background-color: #f6f6f6;
     width: 100%;
 }
 
 .container {
     display: block !important;
     max-width: 600px !important;
     margin: 0 auto !important;
     /* makes it centered */
     clear: both !important;
 }
 
 .content {
     max-width: 600px;
     margin: 0 auto;
     display: block;
     padding: 20px;
 }
 
 /* -------------------------------------
     HEADER, FOOTER, MAIN
 ------------------------------------- */
 .main {
     background: #fff;
     border: 1px solid #e9e9e9;
     border-radius: 3px;
 }
 
 .content-wrap {
     padding: 20px;
 }
 
 .content-block {
     padding: 0 0 20px;
 }
 
 .header {
     width: 100%;
     margin-bottom: 20px;
 }
 
 .footer {
     width: 100%;
     clear: both;
     color: #999;
     padding: 20px;
 }
 .footer a {
     color: #999;
 }
 .footer p, .footer a, .footer unsubscribe, .footer td {
     font-size: 12px;
 }
 
 /* -------------------------------------
     TYPOGRAPHY
 ------------------------------------- */
 h1, h2, h3 {
     font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
     color: #000;
     margin: 40px 0 0;
     line-height: 1.2;
     font-weight: 400;
 }
 
 h1 {
     font-size: 32px;
     font-weight: 500;
 }
 
 h2 {
     font-size: 24px;
 }
 
 h3 {
     font-size: 18px;
 }
 
 h4 {
     font-size: 14px;
     font-weight: 600;
 }
 
 p, ul, ol {
     margin-bottom: 10px;
     font-weight: normal;
 }
 p li, ul li, ol li {
     margin-left: 5px;
     list-style-position: inside;
 }
 
 /* -------------------------------------
     LINKS & BUTTONS
 ------------------------------------- */
 a {
     color: #1ab394;
     text-decoration: underline;
 }
 
 .btn-primary {
     text-decoration: none;
     color: #FFF;
     background-color: #1ab394;
     border: solid #1ab394;
     border-width: 5px 10px;
     line-height: 2;
     font-weight: bold;
     text-align: center;
     cursor: pointer;
     display: inline-block;
     border-radius: 5px;
     text-transform: capitalize;
 }
 
 /* -------------------------------------
     OTHER STYLES THAT MIGHT BE USEFUL
 ------------------------------------- */
 .last {
     margin-bottom: 0;
 }
 
 .first {
     margin-top: 0;
 }
 
 .aligncenter {
     text-align: center;
 }
 
 .alignright {
     text-align: right;
 }
 
 .alignleft {
     text-align: left;
 }
 
 .clear {
     clear: both;
 }
 
 /* -------------------------------------
     ALERTS
     Change the class depending on warning email, good email or bad email
 ------------------------------------- */
 .alert {
     font-size: 16px;
     color: #fff;
     font-weight: 500;
     padding: 20px;
     text-align: center;
     border-radius: 3px 3px 0 0;
 }
 .alert a {
     color: #fff;
     text-decoration: none;
     font-weight: 500;
     font-size: 16px;
 }
 .alert.alert-warning {
     background: #f8ac59;
 }
 .alert.alert-bad {
     background: #ed5565;
 }
 .alert.alert-good {
     background: #1ab394;
 }
 
 /* -------------------------------------
     INVOICE
     Styles for the billing table
 ------------------------------------- */
 .invoice {
     margin: 40px auto;
     text-align: left;
     width: 80%;
 }
 .invoice td {
     padding: 5px 0;
 }
 .invoice .invoice-items {
     width: 100%;
 }
 .invoice .invoice-items td {
     border-top: #eee 1px solid;
 }
 .invoice .invoice-items .total td {
     border-top: 2px solid #333;
     border-bottom: 2px solid #333;
     font-weight: 700;
 }
 
 /* -------------------------------------
     RESPONSIVE AND MOBILE FRIENDLY STYLES
 ------------------------------------- */
 @media only screen and (max-width: 640px) {
     h1, h2, h3, h4 {
         font-weight: 600 !important;
         margin: 20px 0 5px !important;
     }
 
     h1 {
         font-size: 22px !important;
     }
 
     h2 {
         font-size: 18px !important;
     }
 
     h3 {
         font-size: 16px !important;
     }
 
     .container {
         width: 100% !important;
     }
 
     .content, .content-wrap {
         padding: 10px !important;
     }
 
     .invoice {
         width: 100% !important;
     }
 }
 </style>
 
 </head>
 
 <body>
 
 <table class='body-wrap'>
     <tr>
         <td></td>
         <td class='container' width='600'>
             <div class='content'>
                 <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                     <tr>
                         <td class='content-wrap'>
                             <table  cellpadding='0' cellspacing='0'>
                                 <tr>
                                     <td>
                                     <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>
                                     <img class='img-fluid' src='https://tramites.seguridad.jujuy.gob.ar/assets/img/encabezado_2.jpeg'  alt='Unidad Administrativa Digial'/>
                                     </a>
                                     </td>
                                 </tr>
                                 
                                 <tr>
                                         <td class='content-block'>
                                           <br>
                                            Estimado/a: A continuación, se adjunta su comprobante de turno.
                                           </td>
                                       </tr>
                                       <tr>
                                           <td class='content-block'>
                                               <br>
                                                Recuerde presentarse a la comisaría con el mismo impreso o en su celular.
                                           </td>
                                       </tr>
                                 
                               </table>
                         </td>
                     </tr>
                 </table>
                 ";
                 if($requisitos != null ) {
                   $template = $template . $requisitos;   
                 }
                  $template = $template  ."
                 <div class='footer'>
                     <table width='100%'>
                         <tr>
                                 <td class='aligncenter content-block'>E-mail enviado desde: <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>UAD</a></td>
                         </tr>
                         <tr>
                           <td class='aligncenter content-block' style='color:red'>Email generado por el sistema. No responda este email.</td>
                        </tr>
                     </table>
                 </div></div>
         </td>
         <td></td>
     </tr>
 </table>
 
 </body>
 </html>";

 return $template;
    }

    public function getComprobantePago(){
        $template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'>".
               "<html xmlns='http://www.w3.org/1999/xhtml'>".
               "<head>".
               "<meta name='viewport' content='width=device-width' />".
               "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />".
               "<title>Alta de Usuario</title>".
               "<style>".
               "* {
                 margin: 0;
                 padding: 0;
                 font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                 box-sizing: border-box;
                 font-size: 14px;
                }
               img {
                   max-width: 100%;
               }
        
           body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }
        
        /* Let's make sure all tables have defaults */
        table td {
            vertical-align: top;
        }
        
        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        body {
            background-color: #f6f6f6;
        }
        
        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }
        
        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }
        
        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }
        
        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }
        
        .content-wrap {
            padding: 20px;
        }
        
        .content-block {
            padding: 0 0 20px;
        }
        
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }
        .footer a {
            color: #999;
        }
        .footer p, .footer a, .footer unsubscribe, .footer td {
            font-size: 12px;
        }
        
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }
        
        h1 {
            font-size: 32px;
            font-weight: 500;
        }
        
        h2 {
            font-size: 24px;
        }
        
        h3 {
            font-size: 18px;
        }
        
        h4 {
            font-size: 14px;
            font-weight: 600;
        }
        
        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
        }
        p li, ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }
        
        /* -------------------------------------
            LINKS & BUTTONS
        ------------------------------------- */
        a {
            color: #1ab394;
            text-decoration: underline;
        }
        
        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #1ab394;
            border: solid #1ab394;
            border-width: 5px 10px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }
        
        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }
        
        .first {
            margin-top: 0;
        }
        
        .aligncenter {
            text-align: center;
        }
        
        .alignright {
            text-align: right;
        }
        
        .alignleft {
            text-align: left;
        }
        
        .clear {
            clear: both;
        }
        
        /* -------------------------------------
            ALERTS
            Change the class depending on warning email, good email or bad email
        ------------------------------------- */
        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }
        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .alert.alert-warning {
            background: #f8ac59;
        }
        .alert.alert-bad {
            background: #ed5565;
        }
        .alert.alert-good {
            background: #1ab394;
        }
        
        /* -------------------------------------
            INVOICE
            Styles for the billing table
        ------------------------------------- */
        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }
        .invoice td {
            padding: 5px 0;
        }
        .invoice .invoice-items {
            width: 100%;
        }
        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }
        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }
        
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 640px) {
            h1, h2, h3, h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }
        
            h1 {
                font-size: 22px !important;
            }
        
            h2 {
                font-size: 18px !important;
            }
        
            h3 {
                font-size: 16px !important;
            }
        
            .container {
                width: 100% !important;
            }
        
            .content, .content-wrap {
                padding: 10px !important;
            }
        
            .invoice {
                width: 100% !important;
            }
        }
        </style>
        
        </head>
        
        <body>
        
        <table class='body-wrap'>
            <tr>
                <td></td>
                <td class='container' width='600'>
                    <div class='content'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                            <tr>
                                <td class='content-wrap'>
                                    <table  cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td>
                                             <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>
                                                <img class='img-fluid' src='https://tramites.seguridad.jujuy.gob.ar/assets/img/encabezado_2.jpeg'  alt='Unidad Administrativa Digial'/>
                                             </a>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                          <td class='content-block'>
                                            <br>
                                            Estimado/a: A continuación, se adjunta su comprobante de pago.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='content-block'>
                                                <br>
                                                Consérvelo en un lugar seguro.
                                            </td>
                                        </tr>
                                        
                                      </table>
                                </td>
                            </tr>
                        </table>
                       
                        <div class='footer'>
                            <table width='100%'>
                                <tr>
                                        <td class='aligncenter content-block'>E-mail enviado desde: <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>UAD</a></td>
                                </tr>
                                <tr>
                                  <td class='aligncenter content-block' style='color:red'>Email generado por el sistema. No responda este email.</td>
                               </tr>
                            </table>
                        </div></div>
                </td>
                <td></td>
            </tr>
        </table>
        
        </body>
        </html>";
 
        return $template;
     }


     public function getComprobanteDigital(){
        $template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'>".
               "<html xmlns='http://www.w3.org/1999/xhtml'>".
               "<head>".
               "<meta name='viewport' content='width=device-width' />".
               "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />".
               "<title>Alta de Usuario</title>".
               "<style>".
               "* {
                 margin: 0;
                 padding: 0;
                 font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                 box-sizing: border-box;
                 font-size: 14px;
                }
               img {
                   max-width: 100%;
               }
        
           body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }
        
        /* Let's make sure all tables have defaults */
        table td {
            vertical-align: top;
        }
        
        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        body {
            background-color: #f6f6f6;
        }
        
        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }
        
        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }
        
        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }
        
        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }
        
        .content-wrap {
            padding: 20px;
        }
        
        .content-block {
            padding: 0 0 20px;
        }
        
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }
        .footer a {
            color: #999;
        }
        .footer p, .footer a, .footer unsubscribe, .footer td {
            font-size: 12px;
        }
        
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }
        
        h1 {
            font-size: 32px;
            font-weight: 500;
        }
        
        h2 {
            font-size: 24px;
        }
        
        h3 {
            font-size: 18px;
        }
        
        h4 {
            font-size: 14px;
            font-weight: 600;
        }
        
        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
        }
        p li, ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }
        
        /* -------------------------------------
            LINKS & BUTTONS
        ------------------------------------- */
        a {
            color: #1ab394;
            text-decoration: underline;
        }
        
        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #1ab394;
            border: solid #1ab394;
            border-width: 5px 10px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }
        
        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }
        
        .first {
            margin-top: 0;
        }
        
        .aligncenter {
            text-align: center;
        }
        
        .alignright {
            text-align: right;
        }
        
        .alignleft {
            text-align: left;
        }
        
        .clear {
            clear: both;
        }
        
        /* -------------------------------------
            ALERTS
            Change the class depending on warning email, good email or bad email
        ------------------------------------- */
        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }
        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .alert.alert-warning {
            background: #f8ac59;
        }
        .alert.alert-bad {
            background: #ed5565;
        }
        .alert.alert-good {
            background: #1ab394;
        }
        
        /* -------------------------------------
            INVOICE
            Styles for the billing table
        ------------------------------------- */
        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }
        .invoice td {
            padding: 5px 0;
        }
        .invoice .invoice-items {
            width: 100%;
        }
        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }
        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }
        
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 640px) {
            h1, h2, h3, h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }
        
            h1 {
                font-size: 22px !important;
            }
        
            h2 {
                font-size: 18px !important;
            }
        
            h3 {
                font-size: 16px !important;
            }
        
            .container {
                width: 100% !important;
            }
        
            .content, .content-wrap {
                padding: 10px !important;
            }
        
            .invoice {
                width: 100% !important;
            }
        }
        </style>
        
        </head>
        
        <body>
        
        <table class='body-wrap'>
            <tr>
                <td></td>
                <td class='container' width='600'>
                    <div class='content'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                            <tr>
                                <td class='content-wrap'>
                                    <table  cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td>
                                              <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>
                                                <img class='img-fluid' src='https://tramites.seguridad.jujuy.gob.ar/assets/img/encabezado_2.jpeg' alt='Unidad Administrativa Digial'/>
                                              </a>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                          <td class='content-block'>
                                            <br>
                                            Estimado/a: A continuación, se adjunta su certificado o constancia firmado digitalmente.
                                            </td>
                                        </tr>
                                      </table>
                                </td>
                            </tr>
                        </table>
                        <div class='footer'>
                            <table width='100%'>
                                <tr>
                                        <td class='aligncenter content-block'>E-mail enviado desde: <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>UAD</a></td>
                                </tr>
                                <tr>
                                  <td class='aligncenter content-block' style='color:red'>Email generado por el sistema. No responda este email.</td>
                               </tr>
                            </table>
                        </div></div>
                </td>
                <td></td>
            </tr>
        </table>
        
        </body>
        </html>";
 
        return $template;
     }

     public function getComprobanteDigitalSinFirmar($tipoTramite){
         $template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'>".
             "<html xmlns='http://www.w3.org/1999/xhtml'>".
             "<head>".
             "<meta name='viewport' content='width=device-width' />".
             "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />".
             "<title>Alta de Usuario</title>".
             "<style>".
             "* {
                 margin: 0;
                 padding: 0;
                 font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                 box-sizing: border-box;
                 font-size: 14px;
                }
               img {
                   max-width: 100%;
               }
                 
           body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }
                 
        /* Let's make sure all tables have defaults */
        table td {
            vertical-align: top;
        }
                 
        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        body {
            background-color: #f6f6f6;
        }
                 
        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }
                 
        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }
                 
        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }
                 
        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }
                 
        .content-wrap {
            padding: 20px;
        }
                 
        .content-block {
            padding: 0 0 20px;
        }
                 
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
                 
        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }
        .footer a {
            color: #999;
        }
        .footer p, .footer a, .footer unsubscribe, .footer td {
            font-size: 12px;
        }
                 
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }
                 
        h1 {
            font-size: 32px;
            font-weight: 500;
        }
                 
        h2 {
            font-size: 24px;
        }
                 
        h3 {
            font-size: 18px;
        }
                 
        h4 {
            font-size: 14px;
            font-weight: 600;
        }
                 
        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
        }
        p li, ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }
                 
        /* -------------------------------------
            LINKS & BUTTONS
        ------------------------------------- */
        a {
            color: #1ab394;
            text-decoration: underline;
        }
                 
        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #1ab394;
            border: solid #1ab394;
            border-width: 5px 10px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }
                 
        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }
                 
        .first {
            margin-top: 0;
        }
                 
        .aligncenter {
            text-align: center;
        }
                 
        .alignright {
            text-align: right;
        }
                 
        .alignleft {
            text-align: left;
        }
                 
        .clear {
            clear: both;
        }
                 
        /* -------------------------------------
            ALERTS
            Change the class depending on warning email, good email or bad email
        ------------------------------------- */
        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }
        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .alert.alert-warning {
            background: #f8ac59;
        }
        .alert.alert-bad {
            background: #ed5565;
        }
        .alert.alert-good {
            background: #1ab394;
        }
                 
        /* -------------------------------------
            INVOICE
            Styles for the billing table
        ------------------------------------- */
        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }
        .invoice td {
            padding: 5px 0;
        }
        .invoice .invoice-items {
            width: 100%;
        }
        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }
        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }
                 
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 640px) {
            h1, h2, h3, h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }
                 
            h1 {
                font-size: 22px !important;
            }
                 
            h2 {
                font-size: 18px !important;
            }
                 
            h3 {
                font-size: 16px !important;
            }
                 
            .container {
                width: 100% !important;
            }
                 
            .content, .content-wrap {
                padding: 10px !important;
            }
                 
            .invoice {
                width: 100% !important;
            }
        }
        </style>
                 
        </head>
                 
        <body>
                 
        <table class='body-wrap'>
            <tr>
                <td></td>
                <td class='container' width='600'>
                    <div class='content'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                            <tr>
                                <td class='content-wrap'>
                                    <table  cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td>
                                              <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>
                                                <img class='img-fluid' src='https://tramites.seguridad.jujuy.gob.ar/assets/img/encabezado_2.jpeg' alt='Unidad Administrativa Digial'/>
                                              </a>
                                            </td>
                                        </tr>
                 
                                        <tr>
                                          <td class='content-block'>
                                            <br>
                                            Estimado/a: a continuación, se adjunta su trámite de $tipoTramite.
                                            </td>
                                        </tr>
                                      </table>
                                </td>
                            </tr>
                        </table>
                        <div class='footer'>
                            <table width='100%'>
                                <tr>
                                        <td class='aligncenter content-block'>E-mail enviado desde: <a href='https://tramites.seguridad.jujuy.gob.ar/tramite'>UAD</a></td>
                                </tr>
                                <tr>
                                  <td class='aligncenter content-block' style='color:red'>Email generado por el sistema. No responda este email.</td>
                               </tr>
                            </table>
                        </div></div>
                </td>
                <td></td>
            </tr>
        </table>
                 
        </body>
        </html>";
         
         return $template;
     }

    }


     

?>
