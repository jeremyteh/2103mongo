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

		$filter = ['email'=>$_POST["email"]];
		$query = new \MongoDB\Driver\Query($filter);
		$rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

		if(current($rows->toArray())) {
			$url .=  "&regEmail=exist";
			$_POST["email"] = "";
			$valid = False;
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
				
		// hash the password
		$hashedPassword = password_hash($_POST['passwordConfirm'], PASSWORD_DEFAULT);
		$hash = md5(rand(0,1000));
		
		$bulk = new MongoDB\Driver\BulkWrite();

		// Just some random code for website admin
		if($_POST['refCode'] == 2103) {
			$bulk->insert(['firstName'=>$_POST['firstName'], 'lastName'=>$_POST['lastName'], 'email'=>$_POST['email'], 'password'=>$hashedPassword, 'hash'=>$hash, 'accountActivated'=>'false', 'role'=>'website admin', 'type'=>'AD']);
			try {
			    $result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
			    include_once("../phpAdminAccountActivationMailer.php");
			} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
			    $result = var_dump($e->getWriteResult());
			}		
		}
		// Just some random code for food blogger
		else if($_POST['refCode'] == 3012) {
			$bulk->insert(['firstName'=>$_POST['firstName'], 'lastName'=>$_POST['lastName'], 'email'=>$_POST['email'], 'password'=>$hashedPassword, 'hash'=>$hash, 'accountActivated'=>'false', 'role'=>'food blogger', 'type'=>'AD']);
			try {
			    $result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
			    include_once("../phpAdminAccountActivationMailer.php");
			} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
			    $result = var_dump($e->getWriteResult());
			}			
		}
		else {
			$bulk->insert(['firstName'=>$_POST['firstName'], 'lastName'=>$_POST['lastName'], 'email'=>$_POST['email'], 'password'=>$hashedPassword, 'hash'=>$hash, 'accountActivated'=>'false', 'type'=>'NAD']);
			try {
			    $result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
			    include_once("../phpNonAdminAccountActivationMailer.php");
			} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
			    $result = var_dump($e->getWriteResult());
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
