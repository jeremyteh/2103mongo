<?php 

include_once 'protected/databaseconnection.php';


$options = ['sort' => ['dateTimeSearch' => -1]];


$query = new MongoDB\Driver\Query([], $options);
$allSearches = $mongodbManager->executeQuery('foodfinderapp.foodsearch', $query)->toArray();
$count = 0;
$recentSearches = "";
$recent3Searches = array();
/*
for ($i = 0; $i < count($allSearches); $i++){
	if (($i + 1) < count($allSearches)){
		if ($allSearches[$i]->termSearch == $allSearches[$i+1]->termSearch){
			continue;
		} else {
			echo $allSearches[$i]->termSearch;
			echo $allSearches[$i]->dateTimeSearch;
		}
	} else {
		echo $allSearches[$i]->termSearch;
		echo $allSearches[$i]->dateTimeSearch;
	}
}
*/
if (isset($tempArray)){
	unset($tempArray);
}
if (isset($tempCount)){
	$tempCount = 0;
}
$tempArray = array();
$tempCount = 0;
for ($i = 0; $i < count($allSearches); $i++){
	if ($tempCount == 3){
		break;
	}
	if (empty($tempArray)){
		array_push($tempArray,$allSearches[$i]);
		$tempCount++;	
	} else {
		$tempFlag = 0;
		for ($x = 0; $x < count($tempArray); $x++){
			if ($allSearches[$i]->termSearch == $tempArray[$x]->termSearch){
				$tempFlag = 1;
				break;
			} else {
				continue;
			}
		}
		if ($tempFlag == 0){
			array_push($tempArray,$allSearches[$i]);
			$tempCount++;
		}
	}
	
}

foreach ($tempArray as $indivSearch){
	echo $indivSearch->termSearch;
}


if(!empty($getTermSearches)) {
	echo "<p>You've recently searched for: </p>";
	foreach($getTermSearches as $searchTerm) {
		if($count != 3) {
			if(empty($recent3Searches)) {
				array_push($recent3Searches, $searchTerm['termSearch']);
				$count++;
			}else{
				for($i=0; $i < count($recent3Searches); $i++) {
					if($searchTerm['termSearch'] == $recent3Searches[$i]) {
						break;
					}

					array_push($recent3Searches, $searchTerm['termSearch']);
					$count++;

				}
			}
		}
	}

	for($i=0; $i < count($recent3Searches); $i++) {
		echo $recent3Searches[$i];
	}
}

//$filter = ['email'=>'abced@email.com'];


/*********************************/
// for loop print everything
/*
$filter = [
	'$or' => [
		['area' => new MongoDB\BSON\Regex(".*marina.*","i")],
		['development' => new MongoDB\BSON\Regex(".*marina.*","i")]
	]
];*/

/*

$filter = ['name' => new MongoDB\BSON\Regex(".*marina.*","i")];

$query = new MongoDB\Driver\Query($filter);
$searchFoodEstablishments = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();

echo count($searchFoodEstablishments)."<br>";

foreach ($searchFoodEstablishments as $document) {
    echo $document->name."<br>";
}

*/
//$query = new \MongoDB\Driver\Query([]);
//$rows = $mongodbManager->executeQuery('foodfinderapp.carpark', $query);
/*
foreach ($rows as $dc){
	echo $dc->email;
}
*/
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
/*
$c = current($rows->toArray());

if($c = null) {
	echo $c->email;
}else {
	echo 'jhdsjdhf';
}
*/
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