<?php

use App\Controllers\BaseController;


class Login extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
//        $this->load->library('MY_Form_validation');
//        $this->load->library('pagination');
        $this->load->model('carga_model');
        $this->load->model('movil_model');
        $this->load->model('tipo_movil_model');
        $this->load->model('situacion_model');
        $this->load->model('unidad_policial_model');
        $this->load->model('dependencia_model');
        $this->load->model('tipo_combustible_model');

        //Cargamos el Helper para el uso del BASE_URL()
        $this->load->helper('url');
    }

    public function index() {
//        if (!$this->tank_auth->is_logged_in()) {
//            redirect('/auth/login/');
//        } else {

//        $this->data['contenido'] = "login_view";
        $this->load->view('login');
//        }
    }
}