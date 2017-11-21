<?php
error_reporting(E_ALL);
require("PHPMailer_5.2.4/class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
//$mail->SMTPDebug  = 0;
//$mail->Debugoutput = 'html';
//$mail->From = "quizgroup24@gmail.com";
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
        
Your request to reset your password for your account has been received.<br><br>

Please click this link below to reset your account password:<br>
http://localhost/foodfinderapp/resetPasswordVerification.php?email='.$email.'<br><br>

Regards,<br><br>

    fooddfinderapp Admin.

';

$mail->Body = $message;

if($mail->Send()) {echo " ";}
else {echo "";}
?>