<?php namespace App\Libraries;

use TCPDF;
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_header_tramite($nroTramite) {
        $length = 7;
        $html = '<html>
                 <body>
                 <table style="border-bottom:1px solid #000000;" border="0">
                    <tr>
                        <td width="40%" align="left">
                            <img src="assets/img/logo-unidad.png" />
                        </td>
                        <td width="20%" align="center"> 

                        </td>
                        <td width="40%" align="rigth">
                            <table border="0">
                                
                                <tr>
                                    
                                    <td width="60%" height="30%" align="center" valign="middle">
                                        <table border="1">
                                            <tr>
                                                <td width="100%" height="100%" align="center"  style="height: 60px; vertical-align: middle;">Nro de trámite<br/> <b>'.str_pad("$nroTramite",$length,"0", STR_PAD_LEFT).' </b></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="40%" align="left">
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        return $html;
    }
    
    public function getHeaderTramite($nroTramite) {
        $length = 7;
        $html = '<html>
                 <body>
                 <table style="border-bottom:1px solid #000000;" border="0">
                    <tr>
                        <td width="40%" align="left">
                            <img src="assets/img/logo-unidad.png" />
                        </td>
                        <td width="20%" align="center">
            
                        </td>
                        <td width="40%" align="rigth">
                            <table border="0">
            
                                <tr>
                                    <td width="40%" align="left">
                                                    
                                    </td>
                                    <td width="60%" height="30%" align="center" valign="middle">
                                        <table border="1">
                                            <tr>
                                                <td width="100%" height="100%" align="center"  style="height: 60px; vertical-align: middle;">Nro de trámite<br/> <b>'.str_pad("$nroTramite",$length,"0", STR_PAD_LEFT).' </b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        return $html;
    }

    public function get_footer_tramite($html) {
        $html =  $html.'
                 <table>
                    <tr>
                        <td width="100%" align="rigth"><br/><br/>
                            <img src="assets/img/nuevo_escudo_poli.png" width="80" height="100" />
                        </td>
                    </tr>    
                </table>';
                 
        return $html;
    }
    
    public function get_footer_tramite_MAL($html) {
        $html =  $html.'<html>
                 <body>
                 <table>
                 <tr>
                        <td width="100%" align="rigth"><br/><br/>
                            <img src="assets/img/nuevo_escudo_poli.png" width="80" height="100" />
                        </td>
                    </tr>    
                    <tr>
                        <td width="100%" align="left"><br/><br/>
                            NOTA: Por consultas, reclamos o sugerencias <a href="#">unidaddigital@jujuy.gob.ar</a> o al (388) 4311111
                        </td>
                    </tr>
                </table>
                </body>';
                 
        return $html;
    }
    
    public function get_header_tramite22($nroTramite) {
        $html = '<html>
                 <body>
                 <table style="border-bottom:1px solid #000000;" border="0">
                    <tr>
                        <td width="40%" align="left">
                            <img src="assets/img/logo-unidad.png" /><br/>
                        </td>
                        <td width="20%" align="center">

                        </td>
                        <td width="40%" align="rigth">
                            <table border="0">
                                <tr>
                                    <td width="20%" align="left"></td>
                                    <td width="80%" align="left"></td>
                                </tr>
                                <tr>
                                    <td width="20%" align="left"></td>
                                    <td width="80%" align="left"></td>
                                </tr>
                                <tr>
                                    <td width="20%" align="left"></td>
                                    <td width="80%" align="rigth">
                                        <table border="1" height="80">
                                            <tr>
                                                <td width="100%" align="rigth">N° de trámite: 00001</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
        return $html;
    }
    
    public function get_header() {
        $html = '<html>
            
                 <body>
            
                 <table style="border-bottom:1px solid #000000;" border="0">
            
                    <tr>
                        <td width="19%" align="left"></td>
            
                        <td width="61%"  align="center" valign="middle" >
            
                            <b><p>POLICIA DE LA PROVINCIA DE JUJUY</p></b>
                        </td>
            
                        <td width="20%" align="right">
                            <img src="assets/img/nuevo_escudo_poli.png" width="70" height="65" />
                        </td>
                    </tr>
                    <tr>
                         <td width="20%" align="left"></td>
                    </tr>
                </table>';
        return $html;
    }
    
    public function get_header_Gobierno() {
        $html = '<html>
                 <head>
                    <style>
                        .subrayado-punteado{text-decoration: none; border-bottom:1px dotted;}
                    </style>
                 </head>
                 <body>
                 <table style="border-bottom:1px solid #000000;">
                    <tr>
                        <td width="100%" align="center">
                            <img src="assets/img/logo.jpg" width="90" height="55" /><br/>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" align="center">
                            <b>
                            GOBIERNO DE LA PROVINCIA DE JUJUY<br/>
                            MINISTERIO DE SEGURIDAD
                            </b><br/>
                        </td>
                    </tr>
                </table>';
        return $html;
    }

    public function get_header_cupon_pago($html) {
        $html = $html . '<table border="0">
                    <tr>
                        <td width="100%">
                            <img src="assets/img/cupon-header2.png" />
                        </td>
                    </tr>
                </table>
                <br/>';
        return $html;
    }
    
    public function imagenConTextoYcoordenadas($texto, $x, $y) {
        $lineCount = 100;        
        $im = @imagecreate($x, $y);
        $color_fondo = imagecolorallocatealpha($im, 255, 255, 255, 127);
        $lineColor = imagecolorallocate($im, 128, 128, 128);
        $color_texto = imagecolorallocate($im, 128, 128, 128);
        $texto .= ' ';
        $vector = str_split($texto);
        $longitud = sizeof($vector);
        $indice = $longitud;
        for($j=0; $j<= $y; $j+=11){
            for ($i=5; $i<= $x; $i+=10){
                $indice = $indice % $longitud;
                imagestring($im, 2, $i, $j, $vector[$indice], $color_texto);
                $indice++;
            }
        }
        ob_start();
        imagepng($im);
        $contents =  ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        ob_clean();
        $imdata = base64_encode($contents);
        return $imdata;
    }
    
    public function imagenConTexto($texto) {
        $x = 540;
        $y = 663;
        return $this->imagenConTextoYcoordenadas($texto, $x, $y);
    }

    //cabecera para los pdf de las ORDEN DE PAGO para CONTRAVENSIONES
    public function get_header_contravensiones() {
        $html = '<html>
            
                 <body>
            
                 <table style="border-bottom:1px solid #000000;" border="0">
            
                    <tr>
                        <td width="19%" align="left"></td>
            
                        <td width="61%"  align="center" valign="middle" >
            
                            <b><p>JUZGADO CONTRAVENCIONAL</p></b>
                        </td>
            
                        <td width="20%" align="right">                            
                            <img src="assets/img/logo.jpg" width="90" height="55" /><br/>
                        </td>
                    </tr>
                    <tr>
                         <td width="20%" align="left"></td>
                    </tr>
                </table>';
        return $html;
    }
}

//https://www.uno-de-piera.com/creacion-de-pdf-con-codeigniter-la-libreria-tcpdf/
//https://github.com/bcit-ci/CodeIgniter/wiki/TCPDF-Integration