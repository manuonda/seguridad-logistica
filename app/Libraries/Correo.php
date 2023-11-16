<?php namespace App\Libraries;
class Correo {
    
    public function email_v1($html, $remitente, $destinatario){

        $mail = new PHPMailer(); //Crea la clase
        $mail->From = $remitente['correo'];//"ap1@ppj.org.ar"; //Email pop3 que envía el email
        $mail->FromName = $remitente['nombre'];//"DAP"; //De parte de quien se ve el correo
        $mail->Host = "192.168.0.154"; //El nombre de tu servidor de salida SMTP
        $mail->Port = 25; //El puerto a utilizar para envíos. En México usualmente Infinitum bloquea el puerto 25, el alterno es el 587
        $mail->Mailer = "smtp"; //El protocolo para envíos a usar
        $mail->AddAddress($destinatario); //La dirección de email a la cual envías
        $mail->Subject = $remitente['asunto'];//"Mail Subject"; //Asunto del email
        //$mail->Body = "Write your mail here"; //Cuerpo del mensaje de email
        $mail->MsgHTML($html);//Cuerpo del mensaje HTML

        $mail->SMTPAuth = "true";
        $mail->Username = $remitente['usuario'];//"dipol0030"; //Un usuario válido de correo en el servidor SMTP
        $mail->Password = $remitente['pass'];//"ap1"; //Un password válido del usuario en el servidor SMTP
        $mail->CharSet = 'UTF-8';

        if(!$mail->Send()){ //Revisa el resultado del envío
            echo "ERROR!!! Al enviar el Correo a ".$destinatario; //Escribe el error en caso que no se haya enviado bien el correo
            exit; //Sale del s_cript sin ejecutar el resto del código siguiente
        }else{
            echo "Correo enviado satisfactoriamente";
        }
    }

    public function email($html, $remitente, $destinatario) {
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
    	require($serv . "antecedentes/PHPMailer-master/PHPMailerAutoload.php");
    	
    	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    	$mail->IsSMTP(); // telling the class to use SMTP
    	try {
    		$mail->Host       = "192.168.0.154"; 
//     		$mail->SMTPDebug  = 2;               
    		$mail->SMTPAuth   = false;           
    		$mail->Port       = 25;              
    		$mail->Username   = $remitente['usuario'];  
    		$mail->Password   = $remitente['pass'];        
    		$mail->AddAddress($destinatario);
    		$mail->SetFrom($remitente['correo']);
    		$mail->Subject = $remitente['asunto'];
    		$mail->MsgHTML($html);
    		
    		$mail->Send();
    		echo "Se ha enviado el Informe.";
    	
    	} catch (phpmailerException $e) {
//     		echo $e->errorMessage(); //Pretty error messages from PHPMailer
    		echo "No se pudo establecer la conexion al Servidor de Correo:  "+ $e->errorMessage();
    	} catch (Exception $e) {
//     		echo $e->getMessage(); //Boring error messages from anything else!
    		echo "No se pudo establecer la conexion al Servidor de Correo:  "+ $e->getMessage();
    	}
    }
    
    public function email_2($html, $remitente, $destinatario) {
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
    	require($serv . "antecedentes/PHPMailer-master/PHPMailerAutoload.php");
    	
    	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    	$mail->IsSMTP(); // telling the class to use SMTP
    	try {
    		$mail->Host       = "192.168.0.154"; 
//     		$mail->SMTPDebug  = 2;               
    		$mail->SMTPAuth   = false;           
    		$mail->Port       = 25;              
    		$mail->Username   = $remitente['usuario'];  
    		$mail->Password   = $remitente['pass'];        
    		$mail->AddAddress($destinatario);
    		$mail->SetFrom($remitente['correo']);
    		$mail->Subject = $remitente['asunto'];
    		$mail->MsgHTML($html);
    		
    		$mail->Send();
    		echo "<script>alert('Se ha enviado el Informe.');</script>";
    	
    	} catch (phpmailerException $e) {
//     		echo $e->errorMessage(); //Pretty error messages from PHPMailer
    		echo "<script>alert('No se pudo establecer la conexion al Servidor de Correo:');</script>";
    	} catch (Exception $e) {
//     		echo $e->getMessage(); //Boring error messages from anything else!
    		echo "<script>alert('No se pudo establecer la conexion al Servidor de Correo:');</script>";
    	}
    }    
    
    // primera version de envio de email ppj
    // funciona localmente, pero no funca en el servidor
    public function send_email_planilla_por_ppj_v1($html, $remitente, $destinatario, $cuil) {
    
    	$mail = new PHPMailer(); //Crea la clase
    	$mail->From = $remitente['correo'];//"ap1@ppj.org.ar"; //Email pop3 que envía el email
    	$mail->FromName = $remitente['nombre'];//"DAP"; //De parte de quien se ve el correo
    	$mail->Host = "192.168.0.154"; //El nombre de tu servidor de salida SMTP
    	$mail->Port = 25; //El puerto a utilizar para envíos. En México usualmente Infinitum bloquea el puerto 25, el alterno es el 587
    	$mail->Mailer = "smtp"; //El protocolo para envíos a usar
    	$mail->AddAddress($destinatario); //La dirección de email a la cual envías
    	$mail->Subject = $remitente['asunto'];//"Mail Subject"; //Asunto del email
    	//$mail->Body = "Write your mail here"; //Cuerpo del mensaje de email
//     	$mail->MsgHTML($html);//Cuerpo del mensaje HTML
    	$mail->Body = $html;
    
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
    	$folder = $serv . "antecedentes/cuil/temp/";
    	$mail->AddAttachment($folder . "planilla_" .$cuil. ".pdf");
//     	$mail->AddAttachment("C:/xampp/htdocs/antecedentes/planilla_$cuil.pdf");
    	
    	$mail->SMTPAuth = "true";
    	$mail->Username = $remitente['usuario'];//"dipol0030"; //Un usuario válido de correo en el servidor SMTP
    	$mail->Password = $remitente['pass'];//"ap1"; //Un password válido del usuario en el servidor SMTP
    	$mail->CharSet = 'UTF-8';
    
    	if(!$mail->Send()){ //Revisa el resultado del envío
    		//echo "ERROR!!! Al enviar el Correo a ".$destinatario; //Escribe el error en caso que no se haya enviado bien el correo
    		//exit; //Sale del s_cript sin ejecutar el resto del código siguiente
    		echo "No se ha podido establecer la conexion al Servidor de Correo.";
    	}else{
    		echo "Se ha enviado la Planilla prontuarial.";
    	}
    }
    
    public function send_email_planilla_por_ppj($html, $remitente, $destinatario, $cuil, $folder='') {
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
    	require($serv . "antecedentes/PHPMailer-master/PHPMailerAutoload.php");
    	
    	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    	$mail->IsSMTP(); // telling the class to use SMTP
    	try {
    	//	$mail->Host       = "192.168.0.154"; 
		$mail->Host       = "192.168.0.95"; 
//     		$mail->SMTPDebug  = 2;               
    		$mail->SMTPAuth   = false;           
    		$mail->Port       = 25;              
    		$mail->Username   = $remitente['usuario'];  
    		$mail->Password   = $remitente['pass'];        
    		$mail->AddAddress($destinatario);
    		$mail->SetFrom($remitente['correo']);
    		$mail->Subject = $remitente['asunto'];
    		$mail->Body = $html;
    		
    		//$folder = $serv . "antecedentes/cuil/temp/";
    		//$mail->AddAttachment($folder . "planilla_" .$cuil. ".pdf");
                $mail->AddAttachment($folder);
    		
    		$mail->Send();

    	
    	} catch (phpmailerException $e) {
//     		echo $e->errorMessage(); //Pretty error messages from PHPMailer
    		echo "No se pudo establecer la conexion al Servidor de Correo:  "+ $e->errorMessage();
    	} catch (Exception $e) {
//     		echo $e->getMessage(); //Boring error messages from anything else!
    		echo "No se pudo establecer la conexion al Servidor de Correo:  "+ $e->getMessage();
    	}
    }
    
    public function send_email_planilla_por_gmail($html, $remitente, $destinatario, $folder='') {
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
        echo $serv;
    	require($serv . "logistica/PHPMailer-master/PHPMailerAutoload.php");
    	
    	//Crear una instancia de PHPMailer
    	$mail = new PHPMailer();
    	//Definir que vamos a usar SMTP
    	$mail->IsSMTP();
    	//Esto es para activar el modo depuraci�n. En entorno de pruebas lo mejor es 2, en producci�n siempre 0
    	// 0 = off (producci�n)
    	// 1 = client messages
    	// 2 = client and server messages
    	$mail->SMTPDebug  = 0;
    	//Ahora definimos gmail como servidor que aloja nuestro SMTP
    	$mail->Host       = 'smtp.gmail.com';
    	//El puerto ser� el 465 ya que usamos encriptaci�n SSL
    	$mail->Port       = 465;
    	//Definmos la seguridad como SSL
    	$mail->SMTPSecure = 'ssl';
    	//Tenemos que usar gmail autenticados, as� que esto a TRUE
    	$mail->SMTPAuth   = true;
    	//Definimos la cuenta que vamos a usar. Direcci�n completa de la misma
    	$mail->Username   = $remitente['username'];
    	//Introducimos nuestra contrase�a de gmail
    	$mail->Password   = $remitente['password'];
    	//Definimos el remitente (direcci�n y, opcionalmente, nombre)
    	$mail->SetFrom($remitente['username']);
    	//Esta l�nea es por si quer�is enviar copia a alguien (direcci�n y, opcionalmente, nombre)
    	//$mail->AddReplyTo('replyto@correoquesea.com','El de la r�plica');
    	//Y, ahora s�, definimos el destinatario (direcci�n y, opcionalmente, nombre)
    	$mail->AddAddress($destinatario);
    	//Definimos el tema del email
    	$mail->Subject = $remitente['subject'];
    	//Para enviar un correo formateado en HTML lo cargamos con la siguiente funci�n. Si no, puedes meterle directamente una cadena de texto.
    	//$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
    	$mail->Body = $html;
    	//Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versi�n alternativa en texto plano (tambi�n ser� v�lida para lectores de pantalla)
//     	$mail->AltBody = 'This is a plain-text message body';

        $mail->AddAttachment($folder);
    	
    	//Enviamos el correo
    	if(!$mail->Send()) {
     		echo "Error: " . $mail->ErrorInfo;
    		echo "No se ha podido establecer la conexion al Servidor de Correo.";
    	} 
//     	else {
//     		echo "Se ha enviado la Planilla prontuarial.";
//     	}
    }
    
    public function send_email_consulta($tipo_consulta, $destinatario, $html) {
    	$serv = $_SERVER['DOCUMENT_ROOT'] . "/";
    	require($serv . "antecedentes/PHPMailer-master/PHPMailerAutoload.php");
    	 
    	//Crear una instancia de PHPMailer
    	$mail = new PHPMailer();
    	//Definir que vamos a usar SMTP
    	$mail->IsSMTP();
    	//Esto es para activar el modo depuraci�n. En entorno de pruebas lo mejor es 2, en producci�n siempre 0
    	// 0 = off (producci�n)
    	// 1 = client messages
    	// 2 = client and server messages
    	$mail->SMTPDebug  = 0;
    	//Ahora definimos gmail como servidor que aloja nuestro SMTP
    	$mail->Host       = 'smtp.gmail.com';
    	//El puerto ser� el 465 ya que usamos encriptaci�n SSL
    	$mail->Port       = 465;
    	//Definmos la seguridad como SSL
    	$mail->SMTPSecure = 'ssl';
    	//Tenemos que usar gmail autenticados, as� que esto a TRUE
    	$mail->SMTPAuth   = true;
    	//Definimos la cuenta que vamos a usar. Direcci�n completa de la misma
    	$mail->Username   = 'dap.policia.jujuy@gmail.com';
    	//Introducimos nuestra contrase�a de gmail
    	$mail->Password   = 'dap12345';
    	//Definimos el remitente (direcci�n y, opcionalmente, nombre)
    	$mail->SetFrom('dap.policia.jujuy@gmail.com', 'DAP');
    	//Esta l�nea es por si quer�is enviar copia a alguien (direcci�n y, opcionalmente, nombre)
    	//$mail->AddReplyTo('replyto@correoquesea.com','El de la r�plica');
    	//Y, ahora s�, definimos el destinatario (direcci�n y, opcionalmente, nombre)
    	$mail->AddAddress($destinatario);
    	$mail->AddCC('soporte.dap.jujuy@gmail.com');
    	//Definimos el tema del email
    	$mail->Subject = $tipo_consulta;
    	//Para enviar un correo formateado en HTML lo cargamos con la siguiente funci�n. Si no, puedes meterle directamente una cadena de texto.
    	//$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
    	$mail->Body = $html;
    	//Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versi�n alternativa en texto plano (tambi�n ser� v�lida para lectores de pantalla)
    	//     	$mail->AltBody = 'This is a plain-text message body';
    
    	//Enviamos el correo
    	if(!$mail->Send()) {
    		echo "Error: " . $mail->ErrorInfo;
    		echo "No se ha podido establecer la conexion al Servidor de Correo.";
    	}
    	//     	else {
    	//     		echo "Se ha enviado la Planilla prontuarial.";
    	//     	}
    }
    
}
?>
