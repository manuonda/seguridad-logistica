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

use App\Models\ArchivoTramiteModel;
use App\Models\TramiteArchivoModel;
use Exception;

class RenderImage extends BaseController
{
    public function index($idArchivoTramiteModel)
    {
        $tramiteArchivoModel = new TramiteArchivoModel();
        $archivo =$tramiteArchivoModel->find($idArchivoTramiteModel);
//         var_dump($archivo);
        if ($archivo != null &&
            $archivo['ruta'] != null &&
            isset($archivo['ruta']) &&
            file_exists($archivo['ruta']."/".$archivo['nombre'])) {
            $image  =  file_get_contents($archivo['ruta']."/".$archivo['nombre']);
//             var_dump($image);
            $this->response->setStatusCode(200)
                ->setContentType($archivo['tipo'])
                ->setBody($image);
        }
    }


    public function deleteImage($idArchivoTramiteModel) {
        $tramiteArchivoModel = new TramiteArchivoModel();
        $archivo =$tramiteArchivoModel->find($idArchivoTramiteModel);
        $status ="";
        $message ="";          

        try{
           if ($archivo != null &&
               $archivo['ruta'] != null &&
               isset($archivo['ruta']) &&
               file_exists($archivo['ruta']."/".$archivo['nombre'])) {
                $tramiteArchivoModel->delete($idArchivoTramiteModel);
                unlink($archivo['ruta']."/".$archivo['nombre']);
                $status = "OK";
           } else {
               $status ="ERROR";
               $message ="El archivo no existe en la ruta";
           }
        }catch(Exception $ex) {
            $status ="ERROR";
            $message = $ex;
        }

        $data = [
			"status" => $status,
			"message" => $message
		];

		return $this->response->setJSON($data);
    }
}
