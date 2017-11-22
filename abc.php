<?php 

include_once 'protected/databaseconnection.php';

//$filter = ['email'=>'abced@email.com'];
$options = [];

/*********************************/
// for loop print everything

$query = new \MongoDB\Driver\Query([]);
$rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

foreach ($rows as $dc){
	echo $dc->email;
}

/*********************************/

// check if one thing is in the collection
/*
$filter = ['email'=>$_POST["email"]];
$query = new \MongoDB\Driver\Query($filter);
$rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

if(current($rows->toArray())) {
	// means it exist
}
else {
	//it does not exist
}

$query = new \MongoDB\Driver\Query([]);
$rows = $mongodbManager->executeQuery('foodfinderapp.user', $query);

*/
/*********************************/
/*
// count

$query = new \MongoDB\Driver\Query([]);
$rows = $mongodbManager->executeQuery('foodfinderapp.carpark', $query);

echo count($rows->toArray());
*/
/*********************************/
/*
// insert

$bulk = new MongoDB\Driver\BulkWrite();

$bulk->insert(['firstName'=>'dsds', 'lastName'=>'sdsd', 'email'=>'sds', 'password'=>'sdsd', 'hash'=>'sdsd', 'accountActivated'=>'false', 'role'=>'website admin', 'type'=>'AD']);



$result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
*/
/*********************************/

/*$c = current($rows->toArray());

if($c = null) {
	echo $c->email;
}else {
	echo 'jhdsjdhf';
}*/

//echo $c->email;


// $bulk = new MongoDB\Driver\BulkWrite();

// $bulk->insert(['firstName'=>'dsds', 'lastName'=>'sdsd', 'email'=>'sds', 'password'=>'sdsd', 'hash'=>'sdsd', 'accountActivated'=>'false', 'role'=>'website admin', 'type'=>'AD']);



// $result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);


/*
$array = array();

foreach ($rows as $document) {
	echo $document->email;
  //array_push($array, $document);
}
*/
// $email = 'email';

// for($i=0; $i < count($array); $i++){

// 	echo $array[$i]->$email;
// }

?>