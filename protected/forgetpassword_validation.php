<?php

// include database connection
include_once 'databaseconnection.php';

// declare variables to get the value from input
$emailError = "";

// set a boolean variable to check if the fields have errors and retrun true if no error was detected
$valid = True;
$emailExist = False;
$url = '../index.php?';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//=====================  email validation ==========================
	// if the email field is empty
	if (empty($_POST["email"])){
		$url .= "&resetEmail=empty";
		$_POST["email"] = "";
		$valid = False;
	}
    // else if the email field is invalid
	else if (!preg_match("/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i" ,$_POST["email"])){
		$url .= "&resetEmail=invalid";
		$_POST["email"] = "";
		$valid = False;
	}
	// else if the email field is not empty check if the email is unique
	else if (!empty($_POST["email"])) {
		$filter = ['email'=>$_POST["email"]];
		$query = new \MongoDB\Driver\Query($filter);
		$rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

		$userRecord = current($rows->toArray());

		if($userRecord != null) {
			$emailExist = True;
		}

		if($emailExist == False) {
			$url .= "&resetEmail=notExist";
			$_POST["email"] = "";
			$valid = False;
		}
	}

	// if there are no errors in the forget password form, it will proceed to send the email to the user
	if(($valid==True) and ($emailExist == True)){

		$firstName = $userRecord->firstName;
		include_once("../phpForgetPasswordMailer.php");
		header("Location: ../index.php?message=resetSent");
	} else{
		header("Location: $url");
	}
}

?>
