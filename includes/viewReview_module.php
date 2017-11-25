<div class="res-left-mod review-wrapper" id="viewReviews">
  <?php
  $query = new \MongoDB\Driver\Query(['foodEstablishmentId' => (string)$_GET['foodEstablishmentId']]);

  $rows = $mongodbManager->executeQuery('foodfinderapp.review', $query);
  $numofReview = count($rows->toArray());
  echo "<span class='res-food-subheader'>".$numofReview." Reviews</span>";
  ?>

  <?php
   if ($_SERVER["REQUEST_METHOD"] == "POST"){
      if (isset($_POST['deleteReview'])) {
          $orderID = $_POST['deleteReview'];
          $delRec = new MongoDB\Driver\BulkWrite;
					$delRec->delete(['_id' => new MongoDB\BSON\ObjectID($orderID)]);
					$result = $mongodbManager->executeBulkWrite('foodfinderapp.review', $delRec);
          echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";
      }
        echo "<meta http-equiv='refresh' content='0;url=restaurant.php?foodEstablishmentId=".$_GET['foodEstablishmentId']."'>";
  }
 /* REMEMBER TO CHANGE GET foodestablishmentId to foodEstablishmentId */
  $query = new \MongoDB\Driver\Query(['foodEstablishmentId' => (string)$_GET['foodEstablishmentId']]);

  $rows = $mongodbManager->executeQuery('foodfinderapp.review', $query);

    foreach($rows as $review){

      echo "<div class='demo-table review-row'>"
            ."<span class='review-name'>".$review->firstName." ".$review->lastName."</span>";

            echo "<ul class='star-row'>";
            for($i=1;$i<=5;$i++) {
              echo '<input type="hidden" name="rating" id="rating"/>';
              $selected = "";
              if(!empty($review->AvgRating) && $i<=$review->AvgRating) {
                $selected = "selected";
              }
              echo '<li class="'.$selected.'">&#9733;</li>';
            }
            echo "</ul>";

            echo '<div class="review-text">'.$review->reviewResponse.'</div>';

            if(isset($_SESSION['ID'])) {
              if($_SESSION["IsAdmin"] > 0){
                echo '<form role="form" method="POST" action="restaurant.php?foodEstablishmentId='.$_GET['foodEstablishmentId'].'"><input type="hidden" name="deleteReview" value='.$review->_id.'><button class="delete-review"><i class="fa fa-times" aria-hidden="true"></i></button></form>';
              }
            }
            echo "</div>";
          }
  ?>
</div>
