<div class="res-right-mod" id="userReview">
  <?php
  if(isset($_GET['foodEstablishmentId'])) {

    // Editted SQL statement (Nizam)
    $foodID = $_GET['foodEstablishmentId'];
    $checkReview = "SELECT COUNT(*) FROM review WHERE review.foodEstablishmentId = ".$_GET['foodEstablishmentId']." AND review.userId = ".$_SESSION['ID'];

    $checkresult = mysqli_query($conn, $checkReview) or die(mysqli_connect_error());
    $checkrow = mysqli_fetch_array($checkresult);
    $check = $checkrow[0];

    $selectedFoodEstablishment = "SELECT name, address, RIGHT(address, 6) as postalcode,CAST(AVG(review.AvgRating) as decimal(18,1)), COUNT(review.AvgRating),foodestablishment.foodEstablishmentId FROM foodestablishment INNER JOIN review ON foodestablishment.foodestablishmentId = review.foodEstablishmentId WHERE foodestablishment.foodEstablishmentId = '".$_GET['foodEstablishmentId']."'";
    $result = mysqli_query($conn, $selectedFoodEstablishment) or die(mysqli_connect_error());
    $row = mysqli_fetch_array($result);
    $rating = $row[3];
    $numofreview = $row[4];
    //$restaurantID = $row[5];

  }
  if($check == 0){
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
        $insert = "INSERT INTO review(quality,clean,comfort,ambience,service,AvgRating, reviewResponse,userId,foodEstablishmentId)
        VALUES  (".$queryTest.",".$avgrating.",'".$response."',".$_SESSION['ID'].",".$_GET['foodEstablishmentId'].")";

        if ($conn->query($insert) === TRUE) {

          echo "Added to new review";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
          
        }
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
  else{
    echo "<span class='res-empty'><i class='fa fa-check' aria-hidden='true'></i> You have made a review for this establishment</span>";
  }
  ?>
</div>
