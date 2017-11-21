<div class="res-left-mod review-wrapper" id="viewReviews">
  <span class='res-food-subheader'><?php echo $numofreview?> Reviews</span>
  <?php
   if ($_SERVER["REQUEST_METHOD"] == "POST"){
      if (isset($_POST['deleteReview'])) {
          $orderID = $_POST['deleteReview'];
          $deleteFoodQuery = "DELETE from review WHERE reviewId = " . $orderID;

          if ($conn->query($deleteFoodQuery) === TRUE) {

              echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";
          } else {
              echo "Error deleting record: " . $conn->error;
          }
      }
  }

  $showReview = "SELECT firstName, lastName, AvgRating, reviewResponse, reviewId FROM user INNER JOIN review ON user.userId = review.userId WHERE review.foodEstablishmentId=".$_GET['foodEstablishmentId'];
  if ($result1 = mysqli_query($conn, $showReview) or die(mysqli_connect_error)) {
    $rowcount1 = mysqli_num_rows($result1);
    if ($rowcount1 > 0) {
      //for ($i = 0; $i < $rowcount1; $i++) {
      while($rowReview = mysqli_fetch_assoc($result1)){
        //$rowReview = mysqli_fetch_array($result1, MYSQLI_NUM);
        echo "<div class='demo-table review-row'>"
        ."<span class='review-name'>".$rowReview['firstName']." ".$rowReview['lastName']."</span>";

        echo "<ul class='star-row'>";
        for($i=1;$i<=5;$i++) {
          echo '<input type="hidden" name="rating" id="rating"/>';
          $selected = "";
          if(!empty($rowReview['AvgRating']) && $i<=$rowReview['AvgRating']) {
            $selected = "selected";
          }
          echo '<li class="'.$selected.'">&#9733;</li>';
        }
        echo "</ul>";

        echo '<div class="review-text">'.$rowReview['reviewResponse'].'</div>';
        
        if(isset($_SESSION['ID'])) {
          if($_SESSION["IsAdmin"] > 0){
            echo '<form role="form" method="POST" action="restaurant.php?foodEstablishmentId='.$_GET['foodEstablishmentId'].'"><input type="hidden" name="deleteReview" value='.$rowReview['reviewId'].'><button class="delete-review"><i class="fa fa-times" aria-hidden="true"></i></button></form>';
          }
        }
        echo "</div>";
      }
    }
    else{
      echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> There are no reviews yet.</span>";
    }
  }
  ?>
</div>
