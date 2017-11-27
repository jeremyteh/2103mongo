<?php

include_once 'protected/databaseconnection.php';

//$filter = ['email'=>'abced@email.com'];
$options = [];

/*********************************/
// for loop print everything
/*
$filter = [
'$or' => [
['area' => new MongoDB\BSON\Regex(".*marina.*","i")],
['development' => new MongoDB\BSON\Regex(".*marina.*","i")]
]
];*/



$filter = [];
$query = new MongoDB\Driver\Query($filter);
$searchFoodEstablishments = $mongodbManager->executeQuery('foodfinderapp.review', $query)->toArray();

$foodArray = array();
$topThree = array();
foreach ($searchFoodEstablishments as $document) {
  $floatRating = (float)$document->AvgRating;
  $intId = (int)$document->foodEstablishmentId;

  if (array_key_exists($intId, $foodArray))
  {
    $foodArray[$intId]['rating'] += $floatRating;
    $foodArray[$intId]['count'] += 1;
    $foodArray[$intId]['average'] = $foodArray[$intId]['rating']/$foodArray[$intId]['count'];
  }
  else
  {
    $foodEst = array($intId => array('id'=>$intId, 'rating'=>$floatRating, 'count'=>1, 'average'=>$floatRating));
    $foodArray+=$foodEst;
  }
}

usort($foodArray, function ($item1, $item2) {
  if ($item1['average'] == $item2['average']) return 0;
  return $item1['average'] > $item2['average'] ? -1 : 1;
});


$filter = ['$or'=> array(
['foodEstablishmentId'=>(string)$foodArray[0]['id']],
['foodEstablishmentId'=>(string)$foodArray[1]['id']],
['foodEstablishmentId'=>(string)$foodArray[2]['id']]
)];

$query = new MongoDB\Driver\Query($filter);
$featuredArray = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();

$featured = array();
foreach ($featuredArray as $featuredPlace) {
  echo $featuredPlace->name . '<br>';
}


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
