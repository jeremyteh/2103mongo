<?php
error_reporting(E_ALL);
require("PHPMailer_5.2.4/class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
//$mail->SMTPDebug  = 0;
//$mail->Debugoutput = 'html';
$mail->From = "jeremyteh8@gmail.com";
$mail->FromName = "Foodpark";
$mail->Host = "smtp.gmail.com"; // specif smtp server
$mail->SMTPSecure= "ssl"; // Used instead of TLS when only POP mail is selected
$mail->Port = 465; // 465 Used instead of 587 when only POP mail is selected
$mail->SMTPAuth = true;

//$mail->Username = "";
//$mail->Password = ""; // SMTP password
$mail->Username = "jeremyteh8@gmail.com"; // SMTP username
$mail->Password = "jtys#2804"; // SMTP password
$mail->setFrom("jeremyteh8@gmail.com");  //add sender email address.
$mail->AddAddress("$email");
$mail->WordWrap = 50; // set word wrap



$mail->IsHTML(true); // set email format to HTML
$mail->Subject = 'Food Finder App Email Verification';

$message = 'Dear '.$firstName.',<br><br>

Thank you for signing up with Foodpark!<br><br>

Your account has been created, you can login with the following credentials after you have activated your account by pressing on the url below.<br><br>

-------------------------<br>
Email: '.$email.'<br>
Password: '.$passwordConfirm.'<br>
-------------------------<br><br>

Please click this link to activate your account:<br>
http://localhost/2103/foodfinderapp/userAccountVerification.php?email='.$email.'&hash='.$hash.'<br><br>

';

$mail->Body = $message;

if($mail->Send()) {echo " ";}
else {echo "";}
?>
