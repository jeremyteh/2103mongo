<?php

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

echo '<ul id="res-food-cont">';
foreach ($featuredArray as $featuredPlace) {
  for ($i=0; $i<3; $i++){
    if ($featuredPlace->foodEstablishmentId==(string)$foodArray[$i]['id']){
      $rating=(float)$foodArray[$i]['average'];
    }
  }
  /*EACH FOOD INSTANCE*/
  echo '<li class="res-row-food">';
  echo '<a class="res-food-img" href="restaurant.php?foodEstablishmentId='.$featuredPlace->foodEstablishmentId.'">';
  echo '<img src=http://ctjsctjs.com/'. $featuredPlace->image .'>';
  echo '</a>';
  echo "<div class='res-food'>";
  echo '<a class="results-header hide-overflow" href="restaurant.php?foodEstablishmentId='.$featuredPlace->foodEstablishmentId.'">' . $featuredPlace->name . '</a>';
  echo "<span class='res-food-subheader'>Average Rating</span>";
  echo "<table class='demo-table'><tbody>";
  echo '<td><input type="hidden" name="rating" id="rating" value="'.$rating.'"/>';
  echo '<ul class="featured-stars">';

  for($i=1;$i<=5;$i++) {
    $selected = "";
    if(!empty($rating) && $i<=$rating) {
      $selected = "selected";
    }
    echo '<li class="'.$selected.'">&#9733;</li>';
  }
  echo '</ul>';
  echo "</tbody></table>";
  echo "<a class='res-more' href='restaurant.php?foodEstablishmentId=".$featuredPlace->foodEstablishmentId."'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a>";
  echo "</div>";
}
echo "</li>";
echo '</ul>';


?>
