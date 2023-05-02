<?php

require_once('phpmailer/PHPMailerAutoload.php');

$toemails = array();

$toemails[] = array(
				'email' => 'contacto@mithos.cl', // Your Email Address
				'name' => 'Contacto Mithos' // Your Name
			);

// Form Processing Messages
$message_success = 'Su mensaje se ha enviado <strong>correctamente.</strong> Le responderemos a la brevedad.';

// Add this only if you use reCaptcha with your Contact Forms
$recaptcha_secret = ''; // Your reCaptcha Secret

$mail = new PHPMailer();

// If you intend you use SMTP, add your SMTP Code after this Line


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_POST['template-contactform-email'] != '' ) {

		$name = isset( $_POST['template-contactform-name'] ) ? $_POST['template-contactform-name'] : '';
		$email = isset( $_POST['template-contactform-email'] ) ? $_POST['template-contactform-email'] : '';
		$phone = isset( $_POST['template-contactform-phone'] ) ? $_POST['template-contactform-phone'] : '';
		$service = isset( $_POST['template-contactform-service'] ) ? $_POST['template-contactform-service'] : '';
		$subject = isset( $_POST['template-contactform-subject'] ) ? $_POST['template-contactform-subject'] : '';
		$message = isset( $_POST['template-contactform-message'] ) ? $_POST['template-contactform-message'] : '';

		$subject = isset($subject) ? $subject : 'Correo recibido desde la pagina web.';

		$botcheck = $_POST['template-contactform-botcheck'];

		if( $botcheck == '' ) {

			$mail->SetFrom( $email , $name );
			$mail->AddReplyTo( $email , $name );
			foreach( $toemails as $toemail ) {
				$mail->AddAddress( $toemail['email'] , $toemail['name'] );
			}
			$mail->Subject = $subject;

			$name = isset($name) ? "Nombre: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Telefono: $phone<br><br>" : '';
			$service = isset($service) ? "Servicio: $service<br><br>" : '';
			$message = isset($message) ? "Mensaje: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Este formulario fue enviado por: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $service $message $referrer";


			// Uncomment the following Lines of Code if you want to Force reCaptcha Validation

			// if( !isset( $_POST['g-recaptcha-response'] ) ) {
			// 	echo '{ "alert": "error", "message": "Captcha not Submitted! Please Try Again." }';
			// 	die;
			// }

			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

			if( $sendEmail == true ):
				echo '{ "alert": "success", "message": "' . $message_success . '" }';
			else:
				echo '{ "alert": "error", "message": "El mensaje <strong>no se ha podido</strong> enviar correctamente. Porfavor intentelo nuevamente.<br /><br /><strong>Razon:</strong><br />' . $mail->ErrorInfo . '" }';
			endif;
		} else {
			echo '{ "alert": "error", "message": "Hemos detectado el uso de un <strong>Bot</strong>.! Ten Cuidado.!" }';
		}
	} else {
		echo '{ "alert": "error", "message": "Porfavor <strong>rellene </strong> los campos e intentelo nuevamente." }';
	}
} else {
	echo '{ "alert": "error", "message": "Ha <strong>ocurrido un error</strong> inesperado. Porfavor intentelo más tarde." }';
}

?>