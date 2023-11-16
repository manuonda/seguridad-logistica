<?php

use App\Controllers\BaseController;


class Inicio extends BaseController {

    public function __construct() {
      
    }

    public function index() {
       
            if ($this->ion_auth->login($this->session->userdata('identity'), '12345678', false)) {
                redirect('admin/login/change_password', 'refresh');
            }else {
                $this->data['contenido'] = "inicio_view";
            }
            $this->load->view('frontend', $this->data);
        // }else {
        //     redirect('admin/login');
        // }
    }
}