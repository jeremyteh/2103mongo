<?php
error_reporting(E_ALL);
require("PHPMailer_5.2.4/class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
//$mail->SMTPDebug  = 0;
//$mail->Debugoutput = 'html';
$mail->From = "Foodpark";
$mail->FromName = "Foodpark";
$mail->Host = "smtp.gmail.com"; // specif smtp server
$mail->SMTPSecure= "ssl"; // Used instead of TLS when only POP mail is selected
$mail->Port = 465; // 465 Used instead of 587 when only POP mail is selected
$mail->SMTPAuth = true;

$mail->Username = "foodparkco@gmail.com"; // SMTP username
$mail->Password = "foodpark123"; // SMTP password
$mail->setFrom("foodparkco@gmail.com");  //add sender email address.
$mail->AddAddress($_POST['email']);
$mail->WordWrap = 50; // set word wrap


$mail->IsHTML(true); // set email format to HTML
$mail->Subject = 'Foodpark Account Email Verification';

$message = 'Dear '.$_POST["firstName"].',<br><br>

Thank you for signing up with Foodpark!<br><br>

Your account has been created, you can login with the following credentials after you have activated your account by pressing on the url below.<br><br>

-------------------------<br>
Email: '.$_POST["email"].'<br>
Password: '.$_POST["passwordConfirm"].'<br>
-------------------------<br><br>

Please click this link to activate your account:<br>

<a href="http://localhost/2103mongo/userAccountVerification.php?email='.$_POST["email"].'&hash='.$hash.'">http://localhost/2103mongo/userAccountVerification.php?email='.$_POST["email"].'&hash='.$hash.'</a><br><br>

';

$mail->Body = $message;

if($mail->Send()) {echo " ";}
else {echo "";}
?>
