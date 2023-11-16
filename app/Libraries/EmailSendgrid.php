<?php namespace App\Libraries;

require("sendgrid-php/sendgrid-php.php");
use Exception;

 class EmailSendgrid {
    
    public function pruebaEmail() {
        $email = new \SendGrid\Mail\Mail();
//          $email->setFrom("danjor.mam@gmail.com", "PRUEBA JAJA");
         $email->setFrom("dir.administrativa.digital@jujuy.gob.ar", "PRUEBA DE ENVIO");
         $email->setSubject("Enviado con Twilio SendGrid is Fun !!");
         // 	    $email->addTo("jorgefacu@hotmail.com", "Example User");
//          $email->addTo("manuonda@gmail.com", "Example User");
         $email->addTo("danjor.mam@gmail.com", "Usuario de ejemmplo");
         $email->addContent("text/plain", "Esto es una prueba de envio de email!!");
         $email->addContent(
             "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
             );
//          $sendgrid = new \SendGrid('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'); // mio prueba
         $sendgrid = new \SendGrid('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'); 
         try {
             echo '<pre>';
             $response = $sendgrid->send($email);
             print $response->statusCode() . "\n";
             print_r($response->headers());
             print $response->body() . "\n";
             echo '</pre>';
         } catch (Exception $e) {
             echo 'Caught exception: '. $e->getMessage() ."\n";
         }
    }

    public function sendEmail($remitente, $destino, $subject, $filePath, $body)
    {
        $email = new \SendGrid\Mail\Mail();
        $destino = trim($destino);
        log_message('info','Enviando Email a '.$destino. ' fecha : '.date('Y-m-d H:i:s'));
//          $key_value = getenv('KEY_SENGRID');
        try {
            $remitente = 'dir.administrativa.digital@jujuy.gob.ar';
            $email->setFrom($remitente,$subject);
            $email->setSubject($subject);
            $email->addTo($destino, ""); // FIXME: poner el nombre de la persona en las comillas dobles
            $email->addContent("text/html",$body);
//             $sendgrid = new \SendGrid($key_value);
            $sendgrid = new \SendGrid('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');
            $contenido = file_get_contents($filePath);
            if ($contenido) {
                $filename = pathinfo($filePath,PATHINFO_FILENAME);
                $base64 =  base64_encode($contenido);
                $attachment0 = new \SendGrid\Mail\Attachment();
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
//              var_dump($e);
            log_message('info','My log email: '.$email->erroInfo);
            log_message('error', $e->getMessage());
            return $email->ErrorInfo;
        }
    }
    
    public function sendGridDap($body, $cuil, $file_encoded, $destinatario) {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("dap_planilla_prontuarial@jujuy.gob.ar", "PLANILLA see");
        $email->setSubject("Planilla jeje ". $cuil);
        $email->addTo($destinatario, "Destinatario");
        $email->addContent(
            "text/html", $body
            );
        
        $email->addAttachment(
            $file_encoded,
            "application/pdf",
            "planilla.pdf",
            "attachment"
            );
        
//         $sendgrid = new \SendGrid('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');
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            return "ok";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>