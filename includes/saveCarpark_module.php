<div class="res-right-mod-wrap">
  <?php
  $userID = $_SESSION['ID'];
  if (isset($_POST['saveFood']) == 'save'.$carparkID){
    bulk = new MongoDB\Driver\BulkWrite();

    $bulk->insert(['carparkId'=>(string)$_GET['CarparkId'], 'userID'=>(string)$_SESSION['ID'], 'status'=>'1']);

    $result = $mongodbManager->executeBulkWrite('foodfinderapp.favouritecarpark', $bulk);
  }

  // Mongo
  $filter = ['userID'=>(string)$_SESSION['ID'], 'carparkId'=>(string)$_GET['CarparkId']];
  $query = new \MongoDB\Driver\Query($filter);
  $rows = $mongodbManager->executeQuery('foodfinderapp.favouritecarpark', $query);
  $userRecord = $rows->toArray();

  if(count($userRecord) > 0) {
    echo "<span class='res-saved'><i class='fa fa-check' aria-hidden='true'></i> Added to favourites</span>";
  }
  else{
     echo "<form method='post' action='restaurant.php?foodestablishmentId=".$_GET['carparkId']."' id='form' name='form'>"
     . "<input type='hidden' name='saveFood' value='save".$_GET['carparkId']."'>"
     . "<button class='button button-red button-wide' id='btn-save'>Save</button>"
     . "</form>";
   }
  ?>
</div>
