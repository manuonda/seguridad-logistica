<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\PagoBancoMacro;
use App\Models\BloqueoUsuarioModel;
use DateTime;

class Users extends BaseController {
	protected $pagoBancoMacro;
	

	public function __construct() {
	}
	
	public function index() {
	    date_default_timezone_set("America/Argentina/Jujuy");
	    $inicio= 6;
	    $fin= 23;
	    $horaActual = intval(date("H")); // Hora actual
// 	    echo $HoraActual; return ;
	    if (!($horaActual >= $inicio && $horaActual < $fin)) {
	        $username = $this->request->getVar('username');
	        $userModel = new UserModel();
	        $usuario = $userModel->select('public.users.id, public.users.username, public.users.firstname, public.users.lastname')
                                            ->join('public.users_roles', 'public.users_roles.id_user = public.users.id')
                                            ->join('personal.personal', 'personal.personal.legajo = public.users.legajo')
                                            ->join('personal.dependencias', 'personal.dependencias.id_dependencia = personal.personal.id_dependencia')
                                            ->where('public.users.username', $username)
                                            ->whereIn('personal.dependencias.id_dependencia', [201,900]) // DIVISION ANTECEDENTES PERSONALES (D-5)
                                            ->first();
            if(empty($usuario)) {
                log_message('info', 'INTENTO DE INGRESO NOCTURNO, username=' . $username);
	            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }else {
                log_message('info', 'INGRESO NOCTURNO, id=' . $usuario['id'] .', username=' . $usuario['username'] . ', lastname=' . $usuario['lastname'] . ', firstname=' . $usuario['firstname']);
            }
	    }

		$data = [];
		$usuarioBloqueadoModel = new BloqueoUsuarioModel();
		helper(['form']);

		$data['bloqueado']  ="";

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
             
            $ipRemote = $this->request->getVar('ip');
			$userName = $this->request->getVar('username');
			$usuario_bloqueado = $usuarioBloqueadoModel->where('usuario_session', $userName)
			                          ->where('ip',$ipRemote)
									  ->whereIn('estado',[ 'INTENTO_ACCESO', 'BLOQUEADO'])
									  ->first();
			$data['bloqueado'] = "";
			if (!$this->validate($rules, $errors)) {
				$data['validation'] = $this->validator; 
				 // verifico la primera vez el acceso
				if ($usuario_bloqueado == null) {
                    $dataInsert = [
						'ip' => $this->request->getVar('ip'),
						'ip_information' => $this->request->getVar('ip_information'),
						 'usuario_session' => $userName,
						 'intento_fallos'  => 1,
						 'estado' => 'INTENTO_ACCESO',
						 'tiempo' =>  date('Y-m-d H:i:s')
					];
				  $usuarioBloqueadoModel->insert($dataInsert);
				  $data['error2'] = "Usuario de Password Incorrecto. Intento de Session número 1/3";
				 } else {
					if ($usuario_bloqueado['estado'] == "INTENTO_ACCESO") {
					   $nro_intentos = $usuario_bloqueado['intento_fallos'];
                       $nro_intentos = $nro_intentos + 1 ;
					   if ($nro_intentos == 3 ) {
                           // cerrar el registro ponerlo en estado : INTETO_ACESSO_FALLIDO
					       $usuario_bloqueado['intento_fallos'] = $nro_intentos;
					       $usuario_bloqueado['estado']  = "INTENTO_ACCESO_FALLIDO";  
					       $usuarioBloqueadoModel->update($usuario_bloqueado['id'] , $usuario_bloqueado);  
					      // crear un nuevo registro de intento de esa persona pero bloqueado
						  $dataInsert = [
						   	'ip' => $this->request->getVar('ip'),
						     'ip_information' => $this->request->getVar('ip_information'),
							 'usuario_session' => $userName,
							 'intento_fallos'  => 1,
							 'estado' => 'BLOQUEADO',
							 'tiempo' =>  date('Y-m-d H:i:s')
						];
					      $usuarioBloqueadoModel->insert($dataInsert);
						  $data['error2'] = "Debe esperar 30 segundos para loguearse el usuario :".$userName;
						  $data['bloqueado'] = true;

					   } else {
                           // cerrar el registro ponerlo en estado : INTETO_ACESSO_FALLIDO
					       $usuario_bloqueado['intento_fallos'] = $nro_intentos;
					       $usuarioBloqueadoModel->update($usuario_bloqueado['id'] , $usuario_bloqueado);  
						   $data['error2'] = "Usuario de Password Incorrecto. Intento de Session número ".$nro_intentos."/3";
					   }
					  	
					  // en el caso de usuario bloqueado : tiene un time de 30 seconds
					} else if ( $usuario_bloqueado['estado'] == "BLOQUEADO") {
						$date2 = date('Y-m-d H:i:s');
						$date1 = $usuario_bloqueado['tiempo'];
						$data['bloqueado'] = '';
						
						$seconds = $this->get_difference_seconds($date1,$date2); 
						
						if ($seconds < 30) {
							$data['error2'] = "Debe esperar 30 segundos para loguearse el usuario: ".$userName;
							$data['bloqueado'] = true;
						} else {
							// CIERRO EL BLQQUEO
							$usuario_bloqueado['intento_fallos'] = 0;
							$usuario_bloqueado['estado']  = "BLOQUEO_CERRADO";  
							$usuarioBloqueadoModel->update($usuario_bloqueado['id'] , $usuario_bloqueado); 
							//CREO UN NUEVO REGISTRO PARA EL DESBLOQUEO Y NO ES CORRECTO
							$dataInsert = [
								 'ip' => $this->request->getVar('ip'),
								 'ip_information' => $this->request->getVar('ip_information'),
								 'usuario_session' => $userName,
								 'intento_fallos'  => 1,
								 'estado' => 'INTENTO_ACCESO',
								 'tiempo' =>  date('Y-m-d H:i:s')
							];
						   $usuarioBloqueadoModel->insert($dataInsert); 
						   $data['error2'] = "Usuario de Password Incorrecto. Intento de Session numero 1/3";
                           $data['bloqueado'] = "";
						}
					}
	
				}
					
			} else {
                   if ($usuario_bloqueado != null ) {
					   $usuario_bloqueado['estado'] ="INTENTO_ACCESO_CORRECTO";
					   $usuario_bloqueado['ip'] = $this->request->getVar('ip');
					   $usuario_bloqueado['ip_information'] =  $this->request->getVar('ip_information');
					   $usuarioBloqueadoModel->update($usuario_bloqueado['id'], $usuario_bloqueado);
				   }
				   
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
    			       
    			       //$session->setFlashdata('success', 'Successful Registration');
    			       $id_rol = session()->get('id_rol');
    			       if($id_rol==ROL_COMISARIA_SECCIONAL) {
    			           return redirect()->to('/');
    			       }else if($id_rol==ROL_DAF) {
    			           return redirect()->to('dafVentanilla');
    			       }else if($id_rol==ROL_CIAC) {
//     			           return redirect()->to('ciacDenuncia');
    			           return redirect()->to('/');
    			       }else if($id_rol==ROL_UNIDAD_ADMINISTRATIVA || $id_rol==ROL_JEFE_UNIDAD_ADMINISTRATIVA || $id_rol==ROL_JEFE_DAP || $id_rol==ROL_UAD_REBA_CENTRAL) {
    			           return redirect()->to('dashboard');
    			       }else if($id_rol==DAP_RENDICION) {
                           return redirect()->to('daf');
    			       }else if($id_rol==ROL_UAD_UNIDAD_REGIONAL) {
    			           return redirect()->to('tramiteReba/buscar');
    			       }else if($id_rol==ROL_UAD_UNIDAD_REGIONAL_UR5) {
    			           return redirect()->to('/');
					   }else if($id_rol==ROL_DEPARTAMENTO_CONTRAVENCION) {
							return redirect()->to('pagoContravencion/cargarOrdenPago');
					   }else if($id_rol==ROL_ANTECEDENTE) {
							return redirect()->to('/dap');	   
					   }else {
    			           $data['error'] = "El usuario no tiene rol asignado.";
    			       }
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

// 	public function register()
// 	{
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
// 		echo view('auth/register');
// 		echo view('templates/auth/footer');
// 	}

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
