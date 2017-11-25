<div class="res-right-mod" id="userReview">
  <?php
  if(isset($_GET['foodEstablishmentId'])) {

    $foodID = $_GET['foodEstablishmentId'];

    $filter = ['userId'=>(string)$_SESSION['ID'], 'foodEstablishmentId'=>(string)$_GET['foodEstablishmentId']];
    $query = new \MongoDB\Driver\Query($filter);
    $rows = $mongodbManager->executeQuery('foodfinderapp.review', $query);
    $userRecord = $rows->toArray();

    if(count($userRecord) > 0) {
    echo "<span class='res-empty'><i class='fa fa-check' aria-hidden='true'></i> You have made a review for this establishment</span>";
    }

    else{
      $property = array("Quality","Cleaniness","Comfort","Ambience","Service");
        echo "<div>"
        . "<span class='res-food-subheader'>Review</span>";

        if (isset($_POST['rate'])){
          $store = array();
          for($q =0;$q<5;$q++){
            if(isset($_POST['p-'.$property[$q]])){
              array_push($store, $_POST['p-'.$property[$q]]);
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
            $bulk->insert(['quality'=>$store[0], 'clean'=>$store[1], 'comfort'=>$store[2], 'ambience'=>$store[3], 'service'=>$store[4],'AvgRating'=>$avgrating,'reviewResponse'=>$response,'firstName'=>(string)$_SESSION['FIRSTNAME'],'lastName'=>(string)$_SESSION['LASTNAME'],'userId'=>(string)$_SESSION['ID'],'foodEstablishmentId'=>$_GET['foodEstablishmentId']]);
            $result = $mongodbManager->executeBulkWrite('foodfinderapp.review', $bulk);
             echo "Added to new review";
            // if ($conn->query($insert) === TRUE) {
            //   echo "Added to new review";
            // }
            // else {
            //   echo "Error: " . $sql . "<br>" . $conn->error;
            // }
          }
          echo "<meta http-equiv='refresh' content='0;url=restaurant.php?foodEstablishmentId=".$_GET['foodEstablishmentId']."'>";
        }
        echo "<form class='view-delete-form' role='form' method='POST' action='restaurant.php?foodEstablishmentId=".$_GET['foodEstablishmentId']."'>";

        for($i =0;$i<5;$i++){
          echo "<select class='button button-red-outer select-button' name='p-".$property[$i]."' id='p-".$property[$i]."' style='width:100%'>";
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
