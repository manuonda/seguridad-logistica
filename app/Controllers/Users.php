<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\PagoBancoMacro;
use App\Models\BloqueoUsuarioModel;
use DateTime;

class Users extends BaseController {
	protected $pagoBancoMacro;
	


	
	public function index() {
	   
            $username = $this->request->getVar('username');
	        $userModel = new UserModel();
	        $usuario = $userModel->select('public.users.id, public.users.username, public.users.firstname, '
			                              .'public.users.lastname')
                                            ->join('public.users_roles', 'public.users_roles.id_user = public.users.id')
                                            ->join('personal.personal', 'personal.personal.legajo = public.users.legajo')
                                            ->join('personal.dependencias', 'personal.dependencias.id_dependencia = personal.personal.id_dependencia')
                                            ->where('public.users.username', $username)
                                            ->first();

		    var_dump($usuario);
			var_dump("informatcion ");

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'username' => 'required|min_length[6]|max_length[50]',
				'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Usuario o Password Incorrecto'
				]
			];
             
          	if (!$this->validate($rules, $errors)) {
				$data['validation'] = $this->validator; 
				
							
						   $data['error2'] = "Usuario o Password Incorrecto. Intento de Session numero 1/3";
                           $data['bloqueado'] = "";
		    } else {
			
    				$model = new UserModel();
    				$user = $model->select('public.users.id, public.users.username, public.users.firstname, public.users.lastname, public.users.email, 
                                            public.users_roles.id_rol, personal.personal.id_dependencia, personal.dependencias.dependencia, personal.personal.cuil')
    				              ->join('public.users_roles', 'public.users_roles.id_user = public.users.id')
    				              ->join('personal.personal', 'personal.personal.legajo = public.users.legajo')
    				              ->join('personal.dependencias', 'personal.dependencias.id_dependencia = personal.personal.id_dependencia')
    				              ->where('public.users.username', $this->request->getVar('username'))
    				              ->first();
    			   if($user != null) {
    			       $user["token"] = null;
                       $session = session();
					   $this->setUserSession($user);
					   return redirect()->to('/');
    			   }else {
    			       $data['error'] = "El usuario no tiene rol asignado.";
    			   }
			}
		}else {
		    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		echo view('templates/auth/header', $data);
		echo view('auth/login');
		echo view('templates/auth/footer');
	}

	public function unidadAdmin987Gestion2021() {
	    date_default_timezone_set("America/Argentina/Jujuy");
// 	    $inicio= 6;
// 	    $fin= 23;
// 	    $horaActual = intval(date("H")); // Hora actual
// 	    echo $HoraActual; return ;
// 	    if (!($horaActual >= $inicio && $horaActual < $fin)) {
// 	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
// 	    }

	    $data = [];
	    helper(['form']);
	    $data['bloqueado']  ="";
	    
	    echo view('templates/auth/header', $data);
	    echo view('auth/login');
	    echo view('templates/auth/footer');
	}

	private function get_difference_seconds($date1,$date2) {
			$diff =  strtotime($date2) -  strtotime($date1); 
		    $years = floor($diff / (365*60*60*24)); 
            $months = floor(($diff - $years * 365*60*60*24)
					   / (30*60*60*24)); 
            $days = floor(($diff - $years * 365*60*60*24 - 
	        $months*30*60*60*24)/ (60*60*24));
            $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
            $minutes = floor(($diff - $years * 365*60*60*24 
 - $months*30*60*60*24 - $days*60*60*24 
				  - $hours*60*60)/ 60); 
            $seconds = floor(($diff - $years * 365*60*60*24 
 - $months*30*60*60*24 - $days*60*60*24
		- $hours*60*60 - $minutes*60)); 
		   return $seconds;

	}

	private function setUserSession($user)
	{
		$data = [
			'id' => $user['id'],
		    'cuil' => $user['cuil'],
			'username' => $user['username'],
			'firstname' => $user['firstname'],
			'lastname' => $user['lastname'],
			'email' => $user['email'],
		    'id_rol' => $user['id_rol'],
		    'id_dependencia' => $user['id_dependencia'],
		    'dependencia' => $user['dependencia'],
			'isLoggedIn' => true,
			'token' => $user['token']
		];
		session()->markAsTempdata($data, 100);

		session()->set($data);
		session()->set("user",$data);
		return true;
	}



	public function profile()
	{
	    if (session()->get('isLoggedIn') == NULL) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }

		$data = [];
		helper(['form']);
		$model = new UserModel();

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here
			$rules = [
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
			];

			if ($this->request->getPost('password') != '') {
				$rules['password'] = 'required|min_length[8]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
			}


			if (!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			} else {

				$newData = [
					'id' => session()->get('id'),
					'firstname' => $this->request->getPost('firstname'),
					'lastname' => $this->request->getPost('lastname'),
				];
				if ($this->request->getPost('password') != '') {
					$newData['password'] = $this->request->getPost('password');
				}
				$model->save($newData);

				session()->setFlashdata('success', 'Successfuly Updated');
				return redirect()->to('/profile');
			}
		}

		$data['usuario'] = $model->where('id', session()->get('id'))->first();
		
		//cho view("frontend", $data);
		$data['titulo']='Datos Personales';
        $data['subtitulo']="Editar Usuario";
        ///$data['usuario'] = $user;
        $data['contenido'] = 'usuarios/profile.php';  
		// echo view('templates/auth/header', $data);
		// echo view('profile');
		// echo view('templates/auth/footer');
		echo view("frontend", $data);
	}

	public function logoutUnidadAdmin987Gestion2021()
	{
		session()->destroy();
		return redirect()->to('/unidadAdmin987Gestion-2021');
	}


// 	public function caducado(){
// 		$data = [];
// 		helper(['form']);

// 		if ($this->request->getMethod() == 'post') {
// 			//let's do the validation here
// 			$rules = [
// 				'username' => 'required|min_length[3]|max_length[20]',
// 				'firstname' => 'required|min_length[3]|max_length[20]',
// 				'lastname' => 'required|min_length[3]|max_length[20]',
// 				'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
// 				'password' => 'required|min_length[8]|max_length[255]',
// 				'password_confirm' => 'matches[password]',
// 			];

// 			if (!$this->validate($rules)) {
// 				$data['validation'] = $this->validator;
// 			} else {
// 				$model = new UserModel();

// 				$newData = [
// 					'username' => $this->request->getVar('username'),
// 					'firstname' => $this->request->getVar('firstname'),
// 					'lastname' => $this->request->getVar('lastname'),
// 					'email' => $this->request->getVar('email'),
// 					'password' => $this->request->getVar('password'),
// 				];
// 				$model->save($newData);
// 				$session = session();
// 				$session->setFlashdata('success', 'Usuario Registrado');
// 				return redirect()->to('/dashboard');
// 			}
// 		}


// 		echo view('templates/auth/header', $data);
// 		echo view('auth/caducado');
// 		echo view('templates/auth/footer');
// 	}
	
	/**
      * Funcion que permite actualizar el password 
      * del usuario
    **/
    public function actualizarPassword(){
        if (session()->get('isLoggedIn') == NULL) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model = new UserModel();    
		$data = array();
		$id               = $this->request->getVar('id');
		$identity         = $this->request->getVar('identity');
		$password         = $this->request->getVar('password');
		$passwodActual    = $this->request->getVar('passwordactual');
		$repetirpassword  = $this->request->getVar('repetirpassword');
		$user = $model->find($id);
      
		if (trim($password)!= '') {
			$password = trim($this->request->getVar('password'));
		}

		$validate = password_verify($passwodActual,$user['password']);
	    if ($password != $repetirpassword) {
			$data['status'] = "ERROR";
			$data['message']  ="Las contraseñas no coinciden";
		} else if(!$validate){
            $data['status'] = "ERROR";
			$data['message']  ="El Password Actual no concuerda";
		} else {
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$user['password'] = $hashed_password;
			

			$model->set($user);
			$model->where('id', $id);
			$model->update() ;

			$user = $model->find($id);
			
			if ($user) {
				$data['status'] = "OK";
				$data['message'] = 'Se actualizaron los datos correctamente';
			} else{
				$data['mensage'] = 'No se pudo actualizar la clave del usuario';
				$data['tipo'] = 'warning';
			}
		}
		
		return $this->response->setJSON($data);
    }

}
