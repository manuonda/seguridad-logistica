<?php namespace App\Controllers;

use App\Models\TramiteModel;

class Home extends BaseController
{

	protected $tramite;
	
	public function index(){
		$data['contenido'] = "home";
		echo view("frontend", $data);
	}

}
