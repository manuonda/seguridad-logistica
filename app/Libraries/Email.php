<?php namespace App\Libraries;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\EmailCuentaModel;
use SendGrid\Mail\Attachment;
use SendGrid\Mail\To;
use SendGrid\Mail\Cc;
use SendGrid\Mail\Bcc;
use SendGrid\Mail\From;
use SendGrid\Mail\Content;
use SendGrid\Mail\Mail;

//Load Composer's autoloader
// require 'vendor/autoload.php';

 class Email{

    public function sendEmail_Old($remitente, $destino, $subject, $filePath, $body)
    {
        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $cuentaEmailModel = new EmailCuentaModel();  
        $cuentaActivaEmail = $cuentaEmailModel->findCuentaActiva();
        $cantidad = intval($cuentaActivaEmail->cantidad);
        $email = $cuentaActivaEmail->email;
        $password = $cuentaActivaEmail->password;

        // Cantidad Email 
        if ($cantidad == CANTIDAD_POR_EMAIL) {
            log_message('info','Completo la cantidad maxima por email  : '.$cuentaActivaEmail->email. ' fecha : '.date('Y-m-d H:i:s'));
            
          //Busco el siguiente ID de cuenta 
          $idActual = $cuentaActivaEmail->id;
          $idActual++;
          if ($idActual <= MAX_NUMERO_ID_CUENTA_EMAIL) {
            log_message('info','Completo el maximo numero id cuenta email  : '.$cuentaActivaEmail->email. ' fecha : '.date('Y-m-d H:i:s'));
  
             
              //Desactivo la cuenta del idAnterior
              $cuentaActivaEmail->activa = 0;
              $cuentaActivaEmail->cantidad = 0;
              $cuentaEmailModel->update($cuentaActivaEmail->id, $cuentaActivaEmail);
             
             
             $nuevaCuentaActiva  = $cuentaEmailModel->findById($idActual); 
             $nuevaCuentaActiva->activa = 1;
             $nuevaCuentaActiva->cantidad = 1;
             $cuentaEmailModel->update($idActual , $nuevaCuentaActiva);
             $email = $nuevaCuentaActiva->email;
             $password = $nuevaCuentaActiva->password;
          } else {
            
             log_message('info','Obtengo la primera cuenta para comenzar de nuevo  : '.$cuentaActivaEmail->email. ' fecha : '.date('Y-m-d H:i:s'));

             //Desactivo la cuenta del idAnterior
             $cuentaActivaEmail->activa = 0;
             $cuentaActivaEmail->cantidad = 0;
             $cuentaEmailModel->update($cuentaActivaEmail->id, $cuentaActivaEmail);
           
               
             //Obtengo la primera cuenta para comenzar de nuevo
             $cuentaActivaEmail  = $cuentaEmailModel->findById(CUENTA_EMAIL_1);
             $cuentaActivaEmail->activa = 1;
             $cuentaEmailModel->update(CUENTA_EMAIL_1, $cuentaActivaEmail);
             $email = $cuentaActivaEmail->email;
             $password = $cuentaActivaEmail->password;
          }
        } else {
            $cantidad++;
            log_message('info','Actualizo la cantidad por el momento  : '.$cuentaActivaEmail->email. ' Cantidad : '.$cantidad. ' Fecha :'.date('Y-m-d H:i:s'));

          
            //echo "<br> cuant activa email id : ".$cuentaActivaEmail->id;
            //Actualizo la cantidad 
            $cuentaActivaEmail->cantidad= $cantidad;
            $cuentaEmailModel->update($cuentaActivaEmail->id, $cuentaActivaEmail); 
            $email = $cuentaActivaEmail->email;
            $password = $cuentaActivaEmail->password;
        }


        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();           
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
//             $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth   = true;
            $mail->Port       = 587;
            $mail->Username   = $email ; // getenv('USERNAME_EMAIL');
	        $mail->Password   = $password ; // getenv('PASSWORD_EMAIL');
	        
	        $mail->SMTPOptions = array(
	            'ssl' => array(
	                'verify_peer' => false,
	                'verify_peer_name' => false,
	                'allow_self_signed' => true
	            )
	        );

                                  //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($remitente);
            $mail->addAddress($destino);     //Add a recipient
          
            //Attachments
            $mail->addAttachment($filePath);         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
         
            $mail->send();
            return "OK";
        } catch (Exception $e) {
            // var_dump($e);
            log_message('info','My log email: '.$mail->erroInfo);
            log_message('error', $e);
            return $mail->ErrorInfo;
        }
    }


    public function sendEmail($remitente, $destino, $subject, $filePath, $body)
    {
        $email = new \SendGrid\Mail\Mail();
	 
         log_message('info','Enviando Email a '.$destino. ' fecha : '.date('Y-m-d H:i:s'));
         $key_value = getenv('KEY_SENGRID');
       
         

        try {
  
            $email->setFrom($remitente,$subject);
            $email->setSubject($subject);
            $email->addTo($destino, "Example User");
            $email->addContent("text/html",$body);
            //var_dump($remitente);
            $sendgrid = new \SendGrid($key_value);
            $contenido = file_get_contents($filePath);
            if ($contenido) {
                $filename = pathinfo($filePath,PATHINFO_FILENAME);
                $base64 =  base64_encode($contenido);
                $attachment0 = new Attachment();
                $attachment0->setContent($base64);
                $attachment0->setFilename($filename.".pdf");
                $attachment0->setType("text/plain");
                $attachment0->setDisposition("attachment");
                $email->addAttachment($attachment0);
            }      
           

            //Content
            $response = $sendgrid->send($email);
            
            //var_dump($response);
	        log_message('info',$response->body());
            log_message('info',$response->statusCode());
            return "OK";
        } catch (Exception $e) {
            // var_dump($e);
            log_message('info','My log email: '.$email->erroInfo);
            log_message('error', $e->getMessage());
            return $email->ErrorInfo;
        }
    }
}

?>