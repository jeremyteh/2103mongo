<?php
// include database connection
include_once 'databaseconnection.php';
// set a boolean variable to check if the fields have errors and retrun true if no error was detected
$valid = True;
$url = '../index.php?';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//=====================  first name validation ==========================
	// if the first name field is empty
	if (empty($_POST["firstName"])){
		$url .= '&regFname=empty';
		$_POST["firstName"] = "";
		$valid = False;
	}
	// else if the first name field contains numbers
	else if (!ctype_alpha($_POST["firstName"])){
		$url .= '&regFname=alphaNum';
		$_POST["firstName"] = "";
		$valid = False;
	}
	//=====================  last name validation ==========================
	// if the last name field is empty
	if (empty($_POST["lastName"])){
		$url .=  "&regLname=empty";
		$_POST["lastName"] = "";
		$valid = False;
	}
	// else if the last name field contains numbers
	else if (!ctype_alpha($_POST["lastName"])){
		$url .=  "&regLname=alphaNum";
		$_POST["lastName"] = "";
		$valid = False;
	}
	//=====================  email validation ==========================
	// if the email field is empty
	if (empty($_POST["email"])){
		$url .=  "&regEmail=empty";
		$_POST["email"] = "";
		$valid = False;
	}
	// else if the email field is invalid
	else if (!preg_match("/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i" ,$_POST["email"])){
		$url .=  "&regEmail=invalid";
		$_POST["email"] = "";
		$valid = False;
	}
	// else if the email field is not empty check if the email is unique
	else if (!empty($_POST["email"])) {
		$checkUniqueEmail = "SELECT * FROM user";
		$result = mysqli_query($conn, $checkUniqueEmail) or die(mysqli_connect_error());
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$row = mysqli_fetch_array($result);
			if (strtoupper($row['email']) == strtoupper($_POST["email"])) {
				$url .=  "&regEmail=exist";
				$_POST["email"] = "";
				$valid = False;
			}
		}
	}
	//=====================  password validation ==========================
	// if the password field is empty
	if (empty($_POST["password"])){
		$url .=  "&regPw=empty";
		$_POST["password"] = "";
		$valid = False;
	}
	// else if the password field is invalid
	else if ((strlen($_POST["password"]) < 8) || (!preg_match("/((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i",$_POST["password"])) || (strlen($_POST["password"]) > 16)){
		$url .=  "&regPw=validErr";
		$_POST["password"] = "";
		$valid = False;
	}
	//=====================  password confirm validation ==========================
	// if the confiemed password field is empty
	if (empty($_POST["passwordConfirm"])){
		$url .=  "&regPwCfm=empty";
		$_POST["passwordConfirm"] = "";
		$valid = False;
	}
	// else if the confirmed password is not the same as the password entered above
	else if (!($_POST["passwordConfirm"] === $_POST["password"])){
		$url .=  "&regPwCfm=diff";
		$_POST["passwordConfirm"] = "";
		$valid = False;
	}
	// if there are no errors in the sign up form, it will proceed to insert the user information into the database
	if($valid==True){
		$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
		$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$passwordConfirm = mysqli_real_escape_string($conn, $_POST['passwordConfirm']);
		// hash the password
		$hashedPassword = password_hash($passwordConfirm, PASSWORD_DEFAULT);
		$hash = md5(rand(0,1000));
		$insertUser = "INSERT INTO user(firstName, lastName, email, password, hash)VALUES('$firstName', '$lastName ', '$email', '$hashedPassword', '$hash')";
		mysqli_query($conn, $insertUser) or die(mysqli_connect_error());
		$selectUser = "SELECT userId FROM user WHERE firstName = '$firstName' AND lastName = '$lastName'";
		$result = mysqli_query($conn, $selectUser) or die(mysqli_connect_error());
		$resultCheck = mysqli_num_rows($result);

		// Just some random code for website admin
		if($_POST['refCode'] == 2103) {
			if($resultCheck == 1) {
				while($row = mysqli_fetch_assoc($result)) {
					$insertWebAdmin = "INSERT INTO admin(userId, role)VALUES('".$row['userId']."', 'website admin')";
					mysqli_query($conn, $insertWebAdmin) or die(mysqli_connect_error());
				}
				include_once("../phpAdminAccountActivationMailer.php");
			}
		}
		// Just some random code for food blogger
		else if($_POST['refCode'] == 3012) {
			if($resultCheck == 1) {
				while($row = mysqli_fetch_assoc($result)) {
					$insertBlogger = "INSERT INTO admin(userId, role)VALUES('".$row['userId']."', 'food blogger')";
					mysqli_query($conn, $insertBlogger) or die(mysqli_connect_error());
				}
				include_once("../phpAdminAccountActivationMailer.php");
			}
		}
		else {
			if($resultCheck == 1) {
				while($row = mysqli_fetch_assoc($result)) {
					$insertNonAdmin = "INSERT INTO nonadmin(userId)VALUES('".$row['userId']."')";
					mysqli_query($conn, $insertNonAdmin) or die(mysqli_connect_error());
				}
				include_once("../phpNonAdminAccountActivationMailer.php");
			}
		}
		$_POST['firstName'] = '';
		$_POST['lastName'] = '';
		$_POST['email'] = '';
		$_POST["password"] = '';
		$_POST['passwordConfirm'] = '';
		$url .=  "&message=success";
	}
	header("Location: $url");
}
?>
