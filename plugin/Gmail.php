<?php
	include "/plugin/PHPMailer/class.phpmailer.php";
	include "/config/gmail.inc.php";

	function GMailFactory(){
		include "/config/gmail.inc.php";
		$mail= new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = "localhost";
		$mail->Port = 25;
		$mail->CharSet = "UTF8";
		$mail->Username = "administrator@ntut.cc";
		$mail->Password = "GtgYSyrMx8ZcWhcE";
		$mail->From = "administrator@ntut.cc";
		$mail->FromName = "NTUT.CC";
		$mail->IsHTML(true);
		return $mail;
	}
?>