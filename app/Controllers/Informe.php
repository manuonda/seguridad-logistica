<?php

namespace App\Controllers;

use App\Models\CargaEspecialModel;
use App\Models\CargaModel;
use App\Models\CargaParticularModel;
use App\Models\CargaValeModel;
use App\Models\DependenciaModel;
use App\Models\EstacionModel;
use App\Models\MovilModel;
use App\Models\PersonalModel;
use App\Models\ProvisionInteriorModel;
use App\Models\ProvisionPuestoOneModel;
use App\Models\SituacionModel;
use App\Models\TipoCombustibleModel;
use App\Models\TipoMovilModel;
use App\Models\UnidadPolicialModel;

use App\Libraries\Pdf;
use MercadoPago\Card;
use Movil;

class Informe extends BaseController
{

    protected $cargaModel;
    protected $movilModel;
    protected $cargaEspecialModel;
    protected $cargaParticulaModel;
    protected $provisionInteriorModel;
    protected $provisionPuesto1Mode;
    protected $tipoMovilModel;
    protected $situacionModel;
    protected $unidadPolicialModel;
    protected $dependenciaModel;
    protected $tipoCombustibleModel;
    protected $cargaValeModel;
    protected $personalModel;
    protected $estacionModel;
    protected $provisionPuestoOneModel;

    public function __construct()
    {
        $cargaModel = new CargaModel();
        $movilModel = new MovilModel();
        $cargaEspecialModel = new CargaEspecialModel();
        $cargaParticulaModel = new CargaParticularModel();
        $provisionInteriorModel = new ProvisionInteriorModel();
        $tipoMovilModel = new TipoMovilModel();
        $situacionModel = new SituacionModel();
        $unidadPolicialModel = new UnidadPolicialModel();
        $dependenciaModel = new DependenciaModel();
        $tipoCombustibleModel = new TipoCombustibleModel();
        $cargaValeModel = new CargaValeModel();
        $personalModel = new PersonalModel();
        $estacionModel = new EstacionModel();
        $provisionPuestoOneModel = new ProvisionPuestoOneModel();
    }

    public function index($fecha_alta = null)
    {

        if (empty($fecha_alta)) {
            $data['fecha_desde'] = date('Y-m-d');
            $data['fecha_hasta'] = date('Y-m-d');
        } else {
            $data['fecha_desde'] = $fecha_alta;
            $data['fecha_hasta'] = $fecha_alta;
        }
        $data['contenido'] = "informe_buscar";
        echo view('frontend', $data);
    }

    public function carga_diaria()
    {
        $data['fecha_desde'] = '2017-06-06';
        $data['fecha_hasta'] = '2017-06-06';

        $data['titulo'] = null;
        $fecha_desde = $this->request->getVar('fecha_desde');

        if (!empty($fecha_desde)) {
            list($dia_desde, $mes_desde, $anio_desde) = explode("/", $fecha_desde);
            $fecha_desde = $anio_desde . "-" . $mes_desde . "-" . $dia_desde;
            $data['fecha_desde'] = date('Y-m-d', strtotime($fecha_desde));
            $data['fecha_hasta'] = date('Y-m-d', strtotime($fecha_desde));
            //$this->data['titulo'] = 'Movil Policial';                
        } else {
            echo "Se produsco un Error";
            return;
        }
        $data_header = null;
        $html = $this->getHeader($data_header, $data);
        $data['titulo'] = 'Movil Policial';
        $html = $this->get_title($html, $data);
        $html = $this->carga_moviles_policiales($html,$data);
        $data['titulo'] = 'Cargas Especiales';
        $html = $this->get_title($html, $data);
        $html = $this->carga_especiales($html , $data);
        $data['titulo'] = 'Cargas Particulares';
        $html = $this->get_title($html, $data);
        $html = $this->cargaMovilesParticulares($html ,$dat);
        $data['titulo'] = 'PROVISION al Interior';
        $html = $this->get_title($html, $data);
        $html = $this->provisionAlInterior($html , $data);
        $data['titulo'] = 'PROVISION al Puesto 1';
        $html = $this->get_title($html, $data);
        $html = $this->provision_al_puesto1($html , $data);

        ob_end_clean();
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetPageOrientation("L");
        $pdf->SetTitle('Reporte');
        $pdf->setPrintFooter(true);
        $pdf->setFooterMargin(20);
        $pdf->SetFont('times', '', 11);
        $pdf->AddPage();
        $pdf->writeHTML($html);
        $pdf->Output('reporte.pdf', 'I');
    }

    private function showValue($value)
    {
        $html = '<td align="center">';
        $html = $html . '<font size="-3">' . $value . '</font>';
        $html = $html . '</td>';
        return $html;
    }

    private function getHeader($data, $par)
    {
        //$parametros = 'FECHA: 10/10/2017';
        //$parametros = $par['titulo'] .'&nbsp;&nbsp;&nbsp;' . $par['fecha_desde'];
        $parametros = date_format(date_create($par['fecha_desde']), 'd/m/Y');
        //date_format(date_create($par['fecha_desde']), 'd/m/Y H:i');
        $html =  $data . '<br><table border="0" style="border:1px solid #000000;" cellpadding="5">
				<tr>
                    <td width="15%"><img src="assets/img/escudo.png" width="580" height="315" /></td>
                    <td width="70%">
						<div align="center">
                      		Policia de Jujuy - Departamento de Logística<br/>
                      		<b>INFORME DE CARGAS DIARIA</b><br/><br/>
                      			' . $parametros . '
                      	</div>
                    </td>
                    <td width="15%"><img src="assets/img/logo.jpg" width="580" height="315" /></td>
                  </tr>
                </table><br/>';
        return $html;
    }
    //get_title
    private function get_title($data, $par)
    {

        $parametros = $par['titulo'];
        $html =  $data . '
                      		<h4 align="center"><u>' . strtoupper($parametros) . '</u></h4>
                      	<br/>';
        return $html;
    }



    public function carga_moviles_policiales($html, $data)
    {
        $lista_cargas = $this->cargaModel->buscar($data);
        //echo count($lista_cargas);
        //        if(count($lista_cargas) > 0){
        if (!empty(count($lista_cargas))) {
            $html = $html . '<br/><table border="1">
						     <thead>';
            $html = $html . '<tr>
						<th align="center" style="background-color: #F3F3F3"><b>Legajo Movil</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Dependencia</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Unidad Policial</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Tipo/Marca/ Modelo</b></th>
                                                <th align="center" style="background-color: #F3F3F3"><b>Dominio</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Kilometraje</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Combustible</b></th>
    						<th align="center" style="background-color: #F3F3F3"><b>Cantidad de litros</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Vales</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Legajo Policial</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Jerarquía</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Apellido y nombre</b></th>
						</tr>';

            $html = $html . '</thead><tbody>';

            foreach ($lista_cargas as $carga) :
                $html = $html . '<tr>';
                $html = $html . $this->showValue($carga->legajo_movil);
                $html = $html . $this->showValue($carga->dependencia);
                $html = $html . $this->showValue($this->unidadPolicialModel->get($carga->id_unidad_policial)->nombre);
                $html = $html . $this->showValue($carga->tipo . ' ' . $carga->marca . ' ' . $carga->modelo);
                $html = $html . $this->showValue($carga->dominio);
                $html = $html . $this->showValue($carga->kilometraje);
                $html = $html . $this->showValue($carga->tipo_combustible);
                $html = $html . $this->showValue($carga->cantidad_litros);
                $html = $html . $this->showValue($this->cargaValeModel->findValesByIdCarga($carga->id));
                $html = $html . $this->showValue($carga->legajo_personal);
                $html = $html . $this->showValue($carga->jerarquia);
                $html = $html . $this->showValue($carga->apellido . ' ' . $carga->nombre);
                $html = $html . '</tr>';
            endforeach;

            $html = $html . '</tbody></table><br/>';
        } else {
            $html = $html . '<table border="0">		
                            <tr> <td align="center" style="background-color: #F3F3F3"><b>No Registra carga para la feche ingresada</b></td>
                             </tr>
                             </table>
                             ';
        }
        return $html;
    }

    public function cargaMovilesParticulares($html,$data)
    {
        $lista_cargas = $this->cargaParticulaModel->buscar($data);

        if (!empty(count($lista_cargas))) {
            $html = $html . '<br/><table border="1"><thead>';
            $html = $html . '<tr>
						<th align="center" style="background-color: #F3F3F3"><b>Dominio</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Dependencia</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Unidad Policial</b></th>
                                                <th align="center" style="background-color: #F3F3F3"><b>Modelo</b></th>
                                                <th align="center" style="background-color: #F3F3F3"><b>Kilometraje</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Combustible</b></th>
    						<th align="center" style="background-color: #F3F3F3"><b>Cantidad de litros</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Vales</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Legajo/Dni</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Jerarquía</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Apellido y nombre</b></th>
                                                <th align="center" style="background-color: #F3F3F3"><b>Lugar de Trabajo</b></th>
						</tr>';

            $html = $html . '</thead>
				         <tbody>';

            foreach ($lista_cargas as $carga) :
                $html = $html . '<tr>';
                $html = $html . $this->showValue($carga->dominio);
                //if(!empty($carga->id_dependencia)) echo $this->dependencia_model->get($carga->id_dependencia)->dependencia;
                $x = null;
                if (!empty($carga->id_dependencia)) {
                    $x = $this->dependenciaModel->get($carga->id_dependencia)->dependencia;
                }
                $html = $html . $this->showValue($x);
                $html = $html . $this->showValue($this->unidadPolicialModel->get($carga->id_unidad_policial)->nombre);
                $html = $html . $this->showValue($this->tipoMovilModel->get($carga->id_tipo_movil)->descripcion . ' ' . $carga->marca . ' ' . $carga->modelo);
                $html = $html . $this->showValue($carga->kilometraje);
                $html = $html . $this->showValue($carga->tipo_combustible);
                $html = $html . $this->showValue($carga->cantidad_litros);
                $html = $html . $this->showValue($this->cargaValeModel->findValesByIdCargaParticular($carga->id));
                //$this->cargaValeModel->findValesByIdCargaParticular($item->id);
                $x = null;
                if (!empty($carga->legajo) && !empty($carga->dni)) {
                    $x = $carga->legajo . ' / ' . $carga->dni;
                } else {
                    if (!empty($carga->legajo)) {
                        $x = $carga->legajo;
                    }
                    if (!empty($carga->dni)) {
                        $x = $carga->dni;
                    }
                }
                $html = $html . $this->showValue($x);
                $html = $html . $this->showValue($carga->cargo_funcion);
                $html = $html . $this->showValue($carga->apellido . ' ' . $carga->nombre);
                $html = $html . $this->showValue($carga->lugar_de_trabajo);
                //$item->lugar_de_trabajo
                $html = $html . '</tr>';
            endforeach;

            $html = $html . '</tbody>
				         </table>';
        } else {
            $html = $html . ' <table border="0">		
                            <tr>                                              
				<td align="center" style="background-color: #F3F3F3"><b>No Registra carga para la feche ingresada</b></td>
                             </tr>
                             </table>
                             ';
        }
        return $html;
        //return $html;
    }

    public function carga_especiales($html,$data)
    {
        //$lista_cargas = $this->cargaModel->buscar($this->data);
        $lista_cargas = $this->cargaEspecialModel->buscar($data);
        if (!empty(count($lista_cargas))) {
            $html = $html . '<br/><table border="1">
						<thead>';
            $html = $html . '<tr>
						<th align="center" style="background-color: #F3F3F3"><b>Descripcion</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Destino</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Unidad Policial</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Combustible</b></th>
    						<th align="center" style="background-color: #F3F3F3"><b>Cantidad de litros</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Vales</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Legajo Policial</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Jerarquía</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Apellido y nombre</b></th>
						</tr>';

            $html = $html . '</thead>
				         <tbody>';

            foreach ($lista_cargas as $carga) :
                $html = $html . '<tr>';
                $html = $html . $this->showValue($carga->descripcion);
                $html = $html . $this->showValue($carga->dependencia);
                $html = $html . $this->showValue($this->unidadPolicialModel->get($carga->id_unidad_policial)->nombre);
                $html = $html . $this->showValue($carga->tipo_combustible);
                $html = $html . $this->showValue($carga->cantidad_litros);
                $html = $html . $this->showValue($this->cargaValeModel->findValesByIdCargaEspecial($carga->id));
                $html = $html . $this->showValue($carga->legajo_personal);
                $html = $html . $this->showValue($carga->jerarquia);
                $html = $html . $this->showValue($carga->apellido . ' ' . $carga->nombre);
                $html = $html . '</tr>';
            endforeach;

            $html = $html . '</tbody>
				         </table><br/>';
        } else {
            $html = $html . ' <table border="0">		
                            <tr>                                              
				<td align="center" style="background-color: #F3F3F3"><b>No Registra carga para la feche ingresada</b></td>
                             </tr>
                             </table>
                             ';
        }
        return $html;
    }

    public function provisionAlInterior($html,$data)
    {
        $lista_cargas = $this->provisionInteriorModel->buscar($data);
        //$this->provisionInteriorModel->buscar($this->data)
        if (!empty(count($lista_cargas))) {
            $html = $html . '<br/><table border="1">
						<thead>';
            $html = $html . '<tr>

						<th align="center" style="background-color: #F3F3F3"><b>Destino</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Combustible</b></th>
    						<th align="center" style="background-color: #F3F3F3"><b>Cantidad de litros</b></th>                                                
						<th align="center" style="background-color: #F3F3F3"><b>Vales</b></th>                                                
						<th align="center" style="background-color: #F3F3F3"><b>Legajo Policial</b></th>                                                
						<th align="center" style="background-color: #F3F3F3"><b>Jerarquía</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Apellido y nombre</b></th>
						</tr>';

            $html = $html . '</thead>
				         <tbody>';

            foreach ($lista_cargas as $carga) :
                $html = $html . '<tr>';
                $x = null;
                if (!empty($carga->destino_1)) {
                    $x = $this->unidadPolicialModel->get($carga->destino_1)->nombre;
                }
                if (!empty($carga->destino_2)) {
                    $x = $x . ' y ' . $this->unidadPolicialModel->get($carga->destino_2)->nombre;
                }

                $html = $html . $this->showValue($x);
                $x = null;
                if (empty($carga->id_tipo_combustible_2)) {
                    $x = $this->tipoCombustibleModel->get($carga->id_tipo_combustible_1)->descripcion;
                } else {
                    $x = $this->tipoCombustibleModel->get($carga->id_tipo_combustible_1)->descripcion . " ($carga->cantidad_litros_1 Lt.), " . $this->tipoCombustibleModel->get($carga->id_tipo_combustible_2)->descripcion . " ($carga->cantidad_litros_2 Lt.)";
                }
                $html = $html . $this->showValue($x);
                $x = null;
                if (empty($carga->cantidad_litros_2)) {
                    $x = $carga->cantidad_litros_1;
                } else {
                    $x = $carga->cantidad_litros_1 + $carga->cantidad_litros_2;
                }
                $html = $html . $this->showValue($x);
                $html = $html . $this->showValue($this->cargaValeModel->findValesByIdProvisionInterior($carga->id));
                $html = $html . $this->showValue($carga->legajo_personal);
                $html = $html . $this->showValue($carga->jerarquia);
                $html = $html . $this->showValue($carga->apellido . ' ' . $carga->nombre);
                $html = $html . '</tr>';
            endforeach;

            $html = $html . '</tbody>
				         </table><br/>';
        } else {
            $html = $html . ' <table border="0">		
                            <tr>                                              
				<td align="center" style="background-color: #F3F3F3"><b>No Registra carga para la feche ingresada</b></td>
                             </tr>
                             </table>
                             ';
        }
        return $html;
    }

    public function provision_al_puesto1($html ,$data)
    {
        $lista_cargas = $this->provisionPuestoOneModel->buscar($data);
        //$this->provisionInteriorModel->buscar($this->data)
        if (!empty(count($lista_cargas))) {
            $html = $html . '<br/><table border="1">
						<thead>';
            $html = $html . '<tr>                                              
						<th align="center" style="background-color: #F3F3F3"><b>Vales</b></th>                                                
						<th align="center" style="background-color: #F3F3F3"><b>Legajo Policial</b></th>                                                
						<th align="center" style="background-color: #F3F3F3"><b>Jerarquía</b></th>
						<th align="center" style="background-color: #F3F3F3"><b>Apellido y nombre</b></th>
						</tr>';

            $html = $html . '</thead>
				         <tbody>';

            foreach ($lista_cargas as $carga) :
                $html = $html . '<tr>';
                $html = $html . $this->showValue($this->provisionPuestoOneModel->findValesByIdProvision($carga->id));
                $html = $html . $this->showValue($carga->legajo_personal);
                $html = $html . $this->showValue($carga->jerarquia);
                $html = $html . $this->showValue($carga->apellido . ' ' . $carga->nombre);
                $html = $html . '</tr>';
            endforeach;

            $html = $html . '</tbody>
				         </table><br/>';
        } else {
            //$html = $html.' <b> No Registra carga para la feche ingresada</b><br/>';
            $html = $html . '<table border="0">		
                            <tr>                                              
				<td align="center" style="background-color: #F3F3F3"><b>No Registra carga para la feche ingresada</b></td>
                             </tr>
                             </table>
                             ';
        }
        return $html;
    }

    public function pdf()
    {
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('My Title');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');

        $pdf->AddPage();

        $pdf->Write(5, 'Some sample text');
        $pdf->Output('My-File-Name.pdf', 'I');
    }
}
