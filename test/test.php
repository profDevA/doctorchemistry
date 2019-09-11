<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

require("class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPDebug = true;
$mail->Port = '465';
$mail->SMTPSecure = "ssl";
$mail->Host = 'mail.doctorchemistry.com';
$mail->Username = 'support@doctorchemistry.com';
$mail->Password = 'Sk9@*6uj*2^@%3^DDG';

$mail->FromName = 'Magento Test';
$mail->From = 'support@doctorchemistry.com';
$mail->AddAddress("nasim@minervainfocom.com");
$mail->IsHTML(true);
$mail->Subject = "Test Msg";

$mail->Body = 'This is the test message from Magento';

if(!$mail->Send())
{
	echo "Mailer Error: ".$mail->ErrorInfo; ;
	die();
}
else
{
	echo"Mail successfully sent3";
}


?>

<h1>Mail Test1</h1>
