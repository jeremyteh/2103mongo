<?php

// include database connection
include_once 'databaseconnection.php';

// declare variables to get the value from input
$passwordError = $passwordConError = "";

// set a boolean variable to check if the fields have errors and retrun true if no error was detected
$valid = True;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$url = '../resetPasswordVerification.php?email='.$_POST["email"];

	//=====================  password validation ==========================
	// if the password field is empty
	if (empty($_POST["password"])){
		$url .= '&password=empty';
		$_POST["password"] = "";
		$valid = False;
	}
	// else if the password field is invalid
	else if ((strlen($_POST["password"]) < 8) || (!preg_match("/((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i",$_POST["password"])) || (strlen($_POST["password"]) > 16)){
		$url .= '&password=alphaNum';
		$_POST["password"] = "";
		$valid = False;
	}

	//=====================  password confirm validation ==========================
	// if the confiemed password field is empty
	if (empty($_POST["passwordConfirm"])){
		$url .= '&cfmPassword=empty';
		$_POST["passwordConfirm"] = "";
		$valid = False;
	}
	// else if the confirmed password is not the same as the password entered above
	else if (!($_POST["passwordConfirm"] === $_POST["password"])){
		$url .= '&cfmPassword=diff';
		$_POST["passwordConfirm"] = "";
		$valid = False;
	}

	// if there are no errors in the reset password  form, it will proceed to update the user password into the database
	if($valid==True){

		// hash the password
		$hashedPassword = password_hash($_POST["passwordConfirm"], PASSWORD_DEFAULT);

		$bulk = new MongoDB\Driver\BulkWrite();

		$bulk->update(['email' => $_POST['email']], ['$set' => ['password' => $hashedPassword]], ['multi' => false, 'upsert' => false]);

		try {
			$result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
		} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
			$result = var_dump($e->getWriteResult());
		}	

		$_POST["password"] = '';
		$_POST['passwordConfirm'] = '';
		header("Location: ../index.php?message=resetsuccess");
	} else {
		header("Location: $url");
	}
}
?>
