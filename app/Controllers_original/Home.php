<?php namespace App\Controllers;

use Exception;
use App\Libraries\EmailSendgrid;

class Home extends BaseController {
    
    protected $session;
    
    public function __construct() {
        $this->session = session();
    }
	
	public function index() {
	    $filter = $this->session->get('filter');
	    if ($filter != null) {
	        session()->set('filter', null);
	    }
	    
	    if(empty($this->session->get('user'))) {
	        return redirect()->to(base_url().'/tramite');
	    }else {
	        $data['contenido'] = "home";
	    }
	    
		echo view("frontend", $data);
	}
	
	public function error($codigo) {
	    $mensaje = null;
	    switch ($codigo) {
	        case 1:
	            $mensaje = "El extracto de caja ya fue generado.";
	            break;
	        case 2:
	            $mensaje = "Debe subir la foto de la persona para poder ver la planilla.";
	            break;
	        default:
	            $mensaje = "Disculpe, ah ocurrido un error inesperado.";
	    }

	    $data['mensaje'] = $mensaje;
	    $data['ua'] = $this->request->getUserAgent();
	    $data['contenido'] = "error_generico";
	    echo view("frontend", $data);
	}
	
	private function email() {
	    $email = new \SendGrid\Mail\Mail();
	    $email->setFrom("uad.jujuy@gmail.com", "PRUEBA JAJA");
	    $email->setSubject("Sending with Twilio SendGrid is Fun, jeje prueba");
// 	    $email->addTo("jorgefacu@hotmail.com", "Example User");
// 	    $email->addTo("manuonda@gmail.com", "Example User");
	    $email->addTo("danjor.mam@gmail.com", "Example User");
	    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
	    $email->addContent(
	        "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
	        );
// 	    $sendgrid = new \SendGrid('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');
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
	
	private function emailPdf() {
	    $email = new \SendGrid\Mail\Mail();
	    $email->setFrom("uad.jujuy@gmail.com", "PRUEBA JAJA");
	    $email->setSubject("Sending with Twilio SendGrid is Fun, jeje prueba");
	    $email->addTo("danjor.mam@gmail.com", "Example User");
	    // 	    $email->addTo("manuonda@gmail.com", "Example User");
// 	    $email->addTo("soria.cristian.r@gmail.com", "Example User");//danjor.mam
	    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
	    $email->addContent(
	        "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
	        );
	    $file_encoded = base64_encode(file_get_contents('../../cria-tramites-online/public/test.pdf'));
	    $email->addAttachment(
	        $file_encoded,
	        "application/pdf",
	        "planilla.pdf",
	        "attachment"
	        );
	    // 	    $sendgrid = new \SendGrid('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');
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
	
	private function sendGridDapPlanillaPdfParaDependenciasPoliciales() {
	    $body = $this->request->getVar('body');
	    $cuil = $this->request->getVar('cuil');
	    $file_encoded = $this->request->getVar('file');//base64_encode(file_get_contents($folder));
	    $destinatario = $this->request->getVar('destinatario');
	    log_message('info','email para: '.$destinatario.', del cuil: '.$cuil.', body: '.$body);
	    
	    if(empty($body) || empty($cuil) || empty($file_encoded) || empty($destinatario)) {
	        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	    }else {
	        $emailSendgrid = new EmailSendgrid();
	        $emailSendgrid->sendGridDap($body, $cuil, $file_encoded, $destinatario);
	    }	    
	}

}
