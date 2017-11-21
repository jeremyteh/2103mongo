<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<style>
.demo-table ul{margin:0;padding:0;}
.demo-table li{cursor:pointer;list-style-type: none;display: inline-block;color: #F0F0F0;text-shadow: 0 0 1px #666666;font-size:20px;}
.demo-table .highlight, .demo-table .selected {color:#F4B30A;text-shadow: 0 0 1px #F48F0A;}

</style>
<?php
if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
}

if(isset($_GET['carparkId'])) {

  // Editted SQL statement (Nizam)
  $foodID = $_GET['carparkId'];
  $checkReview = "SELECT COUNT(*) FROM feedback WHERE feedback.carparkId = ".$_GET['carparkId']." AND feedback.userId = ".$_SESSION['ID'];

  $checkresult = mysqli_query($conn, $checkReview) or die(mysqli_connect_error());
  $checkrow = mysqli_fetch_array($checkresult);
  $check = $checkrow[0];

  $selectedFoodEstablishment = "SELECT development, area,CAST(AVG(feedback.AvgRating) as decimal(18,1)), COUNT(feedback.AvgRating) FROM carpark INNER JOIN feedback ON carpark.carparkId = feedback.carparkId WHERE carpark.carparkId = '".$_GET['carparkId']."'";
  $result = mysqli_query($conn, $selectedFoodEstablishment) or die(mysqli_connect_error());
  $row = mysqli_fetch_array($result);
  $rating = $row[2];
  $numofreview = $row[3];
  //$restaurantID = $row[5];


}

?>
<section class="jumbotron jumbotron-fluid bg-light ">
  <div class="container-responsive">
    <div class="row">
      <div class="col-lg-8 text-center">
        <h2><b><?php echo $row["development"]; ?></b></h2>
        <p class="lead"><?php echo $row["area"]; ?></p>
        <p style="text-align:left">
          <?php echo $numofreview.' people has reviewed this place';?>
        </p></div></div>


        <?php
        echo "<a href='carpark.php?carparkId=".$_GET['carparkId']."' class='button button-red'>Back</a>";

        if($check == 0){
          $property = array("Accesibility","Cleaniness","Park Rate","Space","User Friendly");

          echo "<table>";
          echo "<form class='view-delete-form' role='form' method='POST' action='carparkReview.php?carparkId=".$_GET['carparkId']."'>";


          for($i =0;$i<5;$i++){
            echo "<tr>";
            echo "<th>".$property[$i].": </th>";

            echo "<th><select name='p-".$i."' id='".$property[$i]."' style='width:200px'>";
            echo "<option value='-1'>--Select A Rating--</option>";
            for($y =1;$y<6;$y++){
              echo "<option value='".$y."'>".$y."</option>";
            }
            echo "</select></th></tr>";
          }
          echo "<tr><th>Review</th><th><input style='width: 453px;
          height: 124px;
          border: 1px solid #999999;
          text-align: left;
          padding: 0.6em;
          padding-bottom: 133px;
          overflow: auto;' type='text' name='reviewText' maxlength='1000' ></th></tr>";

          echo "<tr style='border-style: none;'><th style='border-style: none;'><input type='hidden' name='rate'></th>";
          echo "<th style='border-style: none;'><button class='button button-red'>Submit Rate</button></th></tr>";
          echo "</form></table>";

        }
        else{
          echo "</br>You have made a review for this restaurant";
        }

        ?>

        <?php
        if (isset($_POST['rate'])){
          $store = array();
          for($q =0;$q<5;$q++){
            if(isset($_POST['p-'.$q])){

              array_push($store, $_POST['p-'.$q]);

            }

          }
          if(in_array(-1, $store) || $_POST['reviewText'] == ""){
            echo "Please enter all field";

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
          echo "<meta http-equiv='refresh' content='0;url=carparkReview.php?carparkId=".$_GET['carparkId']."'>";
        }

        ?>
        <div class="jumbotron">

          <?php



          $showReview = "SELECT firstName, lastName, AvgRating, reviewResponse FROM user INNER JOIN feedback ON user.userId = feedback.userId WHERE feedback.carparkId=".$_GET['carparkId'];
          if ($result1 = mysqli_query($conn, $showReview) or die(mysqli_connect_error)) {
            $rowcount1 = mysqli_num_rows($result1);
            if ($rowcount1 > 0) {
              echo "</br><h2>Reviews</h2>";
              echo "<table class='demo-table'><tr><th>Name</th><th>Stars</th><th>Response</th></tr>";
              //for ($i = 0; $i < $rowcount1; $i++) {
              while($rowReview = mysqli_fetch_assoc($result1)){
                //$rowReview = mysqli_fetch_array($result1, MYSQLI_NUM);


                echo "";
                echo "<tr><th>".$rowReview['firstName']." ".$rowReview['lastName']."";
                echo "<th>";
                for($i=1;$i<=5;$i++) {
                  echo '<input type="hidden" name="rating" id="rating"/>';
                  $selected = "";
                  if(!empty($rowReview['AvgRating']) && $i<=$rowReview['AvgRating']) {
                    $selected = "selected";
                  }

                  echo '<li class="'.$selected.'">&#9733;</li>';
                }
                echo '</th>';
                echo '<th>'.$rowReview['reviewResponse'].'</th></tr>';
              }
              echo "</table>";
            }
            else{
              echo "No reviews";
            }
          }


          ?>
        </div>
      </div>
    </section>
