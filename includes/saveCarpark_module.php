<div class="res-right-mod-wrap">
  <?php
  $userID = $_SESSION['ID'];
  if (isset($_POST['saveFood']) == 'save'.$carparkID){

    $bulk = new MongoDB\Driver\BulkWrite();

    $bulk->insert(['carparkId'=>$_GET['carparkId'], 'userID'=>(string)$_SESSION['ID'], 'status'=>'1']);

    $result = $mongodbManager->executeBulkWrite('foodfinderapp.favouritecarpark', $bulk);
  }

  // Mongo
  $filter = ['userID'=>(string)$_SESSION['ID'], 'carparkId'=>$_GET['carparkId']];
  $query = new MongoDB\Driver\Query($filter);
  $rows = $mongodbManager->executeQuery('foodfinderapp.favouritecarpark', $query)->toArray();
  
  if(count($rows) > 0) {
    echo "<span class='res-saved'><i class='fa fa-check' aria-hidden='true'></i> Added to favourites</span>";
  }
  else{
     echo "<form method='post' action='carpark.php?carparkId=".$_GET['carparkId']."' id='form' name='form'>"
     . "<input type='hidden' name='saveFood' value='save".$_GET['carparkId']."'>"
     . "<button class='button button-red button-wide' id='btn-save'>Save</button>"
     . "</form>";
   }
  ?>
</div>
