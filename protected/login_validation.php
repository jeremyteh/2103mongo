<?php

// include database connection
include_once 'databaseconnection.php';

// declare variables to get the value from input

// set a boolean variable to check if the fields have errors and retrun true if no error was detected
$valid = True;
$url = '../index.php?';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  //=====================  email validation ==========================
  // if the email field is empty
  if (empty($_POST["email"])){
    $url .= '&loginEmail=empty';
    $_POST['email'] = "";
    $valid = False;
  }

  //=====================  password validation ==========================
  // if the password field is empty
  if (empty($_POST["password"])){
    $url .= '&loginPw=empty';
    $_POST['password'] = "";
    $valid = False;
  }

  if($valid == True){

    $filter = ['email'=>$_POST['email']];

    $query = new \MongoDB\Driver\Query($filter);
    $rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

    $userRecord = current($rows->toArray());

    if($userRecord == null) {
      $url .= '&loginEmail=invalid';
      $_POST['email'] = "";
      $_POST['password'] = "";
    }
    else if($userRecord->accountActivated == false) {
      $url .= '&loginEmail=notActivated';
      $_POST['email'] = "";
      $_POST['password'] = "";
    }
    else {
      $hashedPwdCheck = password_verify($_POST['password'], $userRecord->password);
      if($hashedPwdCheck == false) {
          $url .= '&loginPw=invalid';
          $_POST['email'] = "";
          $_POST['password'] = "";
      }
      else {
          session_start();
          $_SESSION['FIRSTNAME'] = $userRecord->firstName;
          $_SESSION['LASTNAME'] = $userRecord->lastName;
          $_SESSION['EMAIL'] = $userRecord->email;
          $_SESSION['PASSWORD'] = $userRecord->password;
          $_SESSION['ID'] = $userRecord->_id;
          if ($userRecord->type == "AD")
            $_SESSION['IsAdmin'] = 1;
          else
            $_SESSION['IsAdmin'] = 0;
      }
    }
  }
  header("Location: $url");
}
