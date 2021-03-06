<?php
include_once 'protected/databaseconnection.php';

if (isset($_GET['sort'])) {
  $sortValue = $_GET['sort'];
  if ($sortValue == 0) {
    $filter = [];
    $options = ['sort' => ['name' => 1]];
    $query = new \MongoDB\Driver\Query($filter, $options);
  } elseif ($sortValue == 1) {
    $filter = [];
    $options = ['sort' => ['name' => -1]];
    $query = new \MongoDB\Driver\Query($filter, $options);
  }
} else {
  $filter = [];
  $options = [];
  $query = new \MongoDB\Driver\Query($filter,$options);
}
//$mongodbManager->timeout(-1);
set_time_limit(0);
$rows = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();
//$rows = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();

$storedResult = array();

if(!empty($rows)) { ?>

  <span class= "results-total">Total results found: <span class="inline-text" id='feTotalResults'><?php echo count($rows); ?></span></span>
  <div id = 'feListing'>
  </div>
  <?php
  foreach ($rows as $indivFoodEstablishment) {
    array_push($storedResult, $indivFoodEstablishment);
  }
} else {
  ?>
  <span id='feTotalResults'>0</span>
  <?php

}
?>

<script>
var feArray = <?php echo json_encode($storedResult);?>;
calculateTotalPage();
listResult(0,12);
</script>
