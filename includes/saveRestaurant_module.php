<div class="res-right-mod-wrap" id="saveFav">
  <?php
  if (isset($_POST['saveFood']) == 'save'.$_GET['foodestablishmentId']){

    $bulk = new MongoDB\Driver\BulkWrite();

    $bulk->insert(['foodestablishmentId'=>(string)$_GET['foodestablishmentId'], 'userID'=>(string)$_SESSION['ID'], 'status'=>'1']);

    $result = $mongodbManager->executeBulkWrite('foodfinderapp.favouritefood', $bulk);

}

  $filter = ['userID'=>(string)$_SESSION['ID'], 'foodestablishmentId'=>(string)$_GET['foodestablishmentId']];
  $query = new \MongoDB\Driver\Query($filter);
  $rows = $mongodbManager->executeQuery('foodfinderapp.favouritefood', $query);
  $userRecord = $rows->toArray();

  if(count($userRecord) > 0) {
    echo "<span class='res-saved'><i class='fa fa-check' aria-hidden='true'></i> Added to favourites</span>";
  }
  else{
     echo "<form method='post' action='restaurant.php?foodestablishmentId=".$_GET['foodestablishmentId']."' id='form' name='form'>"
     . "<input type='hidden' name='saveFood' value='save".$_GET['foodestablishmentId']."'>"
     . "<button class='button button-red button-wide' id='btn-save'>Save</button>"
     . "</form>";
   }

  ?>
</div>
