<?php

use App\Controllers\BaseController;

class Index extends BaseController {

    public function __construct() {
        parent::__construct();
//        $this ->load->library('Mobile_Detect');
    }

    public function index() {
//        $detect = new Mobile_Detect();
//        if ($detect->isMobile()) {
//            echo 'seeee';
//        }

        $this->data['title'] = "mi proyecto";
        $this->data['contenido'] = "index/index";
        $this->load->view('frontend', $this->data);
//        $this->load->view('login', $this->data);
    }
}