<?php 
namespace App\Controllers;

// use App\Models\Central\PruebaCentralModel;
use App\Libraries\EmailSendgrid;

class CronTramite extends BaseController {
    
//     public function prueba() {
//         $pruebaModel = new PruebaCentralModel();
//         $prueba['id'] = 1;
//         $prueba['prueba'] = 'jaja';
//         $idPrueba = $pruebaModel->insert($prueba, true);
//         echo $idPrueba;
//     }
    

    public function mail() {
        $emailSendgrid = new EmailSendgrid();
        $emailSendgrid->pruebaEmail();
    }

}?>