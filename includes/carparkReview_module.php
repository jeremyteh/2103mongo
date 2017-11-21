<div class="res-right-mod" id="userReview">
  <?php
  if(isset($_GET['carparkId'])) {

    // Editted SQL statement (Nizam)
    $foodID = $_GET['carparkId'];
    $checkReview = "SELECT COUNT(*) FROM feedback WHERE feedback.carparkId = ".$_GET['carparkId']." AND feedback.userId = ".$_SESSION['ID'];
    
    $checkresult = mysqli_query($conn, $checkReview) or die(mysqli_connect_error());
    $checkrow = mysqli_fetch_array($checkresult);
    $check = $checkrow[0];
    
    $selectedFoodEstablishment = "SELECT carpark.carparkId,CAST(AVG(feedback.AvgRating) as decimal(18,1)), COUNT(feedback.AvgRating) FROM carpark INNER JOIN feedback ON carpark.carparkId = feedback.carparkId WHERE carpark.carparkId = '".$_GET['carparkId']."'";
    $result = mysqli_query($conn, $selectedFoodEstablishment) or die(mysqli_connect_error());
    $row = mysqli_fetch_array($result);
    $rating = $row[1];
    $numofreview = $row[2];
    //$restaurantID = $row[5];

  }
  if($check == 0){
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
        $insert = "INSERT INTO feedback(accessibility,clean,parkRate,space,userFriendly,AvgRating, reviewResponse,userId,carparkId)
        VALUES  (".$queryTest.",".$avgrating.",'".$response."',".$_SESSION['ID'].",".$_GET['carparkId'].")";
        
        if ($conn->query($insert) === TRUE) {

          echo "Added to new review";
          
          
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        
          
        }
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
  else{
    echo "<span class='res-empty'><i class='fa fa-check' aria-hidden='true'></i> You have made a review for this establishment</span>";
  }
  ?>
</div>
