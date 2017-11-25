<div class="res-right-mod" id="userReview">
  <?php
  if(isset($_GET['carparkId'])) {

    // Editted SQL statement (Nizam)
    $foodID = $_GET['carparkId'];

    $filter = ['userId'=>(string)$_SESSION['ID'], 'carparkId'=>(string)$_GET['carparkId']];
    $query = new \MongoDB\Driver\Query($filter);
    $rows = $mongodbManager->executeQuery('foodfinderapp.review', $query);
    $userRecord = $rows->toArray();

    if(count($userRecord) > 0) {
    echo "<span class='res-empty'><i class='fa fa-check' aria-hidden='true'></i> You have made a review for this establishment</span>";
    }

    else{
      $property = array("Accessibility","Cleaniness","Parking Rate","Space","User Friendly");

      echo "<div>"
      . "<span class='res-food-subheader'>Review</span>";

      if (isset($_POST['rate'])){
        $store = array();
        for($q =0;$q<5;$q++){
          if(isset($_POST['p-'.$q])){
            array_push($store, $_POST['p-'.$q]);
          }
        }
        if(in_array(-1, $store) || $_POST['reviewText'] == ""){
          echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Please check that all fields are entered</span>";
        }
        else{
          $response = $_POST['reviewText'];
          $queryTest = $store[0].','.$store[1].','.$store[2].','.$store[3].','.$store[4].'';
          $avgrating = array_sum($store)/5;

          $bulk = new MongoDB\Driver\BulkWrite();
          $bulk->insert(['accessibility'=>$store[0], 'clean'=>$store[1], 'parkRate'=>$store[2], 'space'=>$store[3], 'userFriendly'=>$store[4],'AvgRating'=>$avgrating,'reviewResponse'=>$response,'firstName'=>(string)$_SESSION['FIRSTNAME'],'lastName'=>(string)$_SESSION['LASTNAME'],'userId'=>(string)$_SESSION['ID'],'carparkId'=>$_GET['carparkId']]);
          $result = $mongodbManager->executeBulkWrite('foodfinderapp.feedback', $bulk);
           echo "Added to new review";

        }
        echo "<meta http-equiv='refresh' content='0;url=carpark.php?carparkId=".$_GET['carparkId']."'>";
      }
      echo "<form class='view-delete-form' role='form' method='POST' action='carpark.php?carparkId=".$_GET['carparkId']."'>";

      for($i =0;$i<5;$i++){
        echo "<select class='button button-red-outer select-button' name='p-".$i."' id='p-".$property[$i]."' style='width:100%'>";
        echo "<option value='-1'>".$property[$i]."</option>";
        for($y =1;$y<6;$y++){
          echo "<option value='".$y."'>".$y."</option>";
        }
        echo "</select>";
      }
      echo "<textarea name='reviewText' class='review-textarea select-button' placeholder='Leave a review here!'></textarea>";

      echo "<input type='hidden' name='rate'>";
      echo "<button class='button button-red button-wide'>Submit Review</button>";
      echo "</form></div>";
    }

  }

  ?>
</div>
