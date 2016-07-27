<?php
	require('config.php');
	require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	// $mail->Host = 'borkhairuu.com';  // Specify main and backup SMTP servers
	$mail->Host = 'localhost';
	// $mail->SMTPAuth = true;                             // Enable SMTP authentication
	// $mail->Username = 'user@example.com';                 // SMTP username
	// $mail->Password = 'secret';                           // SMTP password
	// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 25;                                    // TCP port to connect to

	$mail->setFrom('admin@borkhairuu.com', 'บอกให้รู้');
	$mail->addAddress($userDetails->email, $userDetails->username);     // Add a recipient
	// $mail->addAddress('ellen@example.com');               // Name is optional
	// $mail->addReplyTo('info@example.com', 'Information');
	// $mail->addCC('cc@example.com');
	// $mail->addBCC('bcc@example.com');

	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'บอกให้รู้: รีเซ็ตรหัสผ่าน';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent';
	}

	// if(isset($_POST['email'])){
	// 	mail($_POST['email'], 'My Subject', 'test');
	// }

?>