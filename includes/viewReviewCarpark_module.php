<div class="res-left-mod review-wrapper" id="viewReviews">
  <?php$query = new \MongoDB\Driver\Query(['carparkId' => (string)$_GET['carparkId']]);

  $rows = $mongodbManager->executeQuery('foodfinderapp.feedback', $query);
  $numofReview = count($rows->toArray());
  echo "<span class='res-food-subheader'>".$numofReview." Reviews</span>";
  ?>
  <?php
   if ($_SERVER["REQUEST_METHOD"] == "POST"){
      if (isset($_POST['deleteReview'])) {
          $orderID = $_POST['deleteReview'];
          $delRec = new MongoDB\Driver\BulkWrite;
          $delRec->delete(['_id' => new MongoDB\BSON\ObjectID($orderID)]);
          $result = $mongodbManager->executeBulkWrite('foodfinderapp.feedback', $delRec);
          echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";
      }
      echo "<meta http-equiv='refresh' content='0;url=carpark.php?CarparkId=".$_GET['carparkId']."'>";
  }
  $query = new \MongoDB\Driver\Query(['carparkId' => (string)$_GET['CarparkId']]);

  $rows = $mongodbManager->executeQuery('foodfinderapp.feedback', $query);

    foreach($rows as $rowReview){
      echo "<div class='demo-table review-row'>"
            ."<span class='review-name'>".$rowReview->'firstName'." ".$rowReview->'lastName'."</span>";

            echo "<ul class='star-row'>";
            for($i=1;$i<=5;$i++) {
              echo '<input type="hidden" name="rating" id="rating"/>';
              $selected = "";
              if(!empty($rowReview->'AvgRating') && $i<=$rowReview->'AvgRating') {
                $selected = "selected";
              }
              echo '<li class="'.$selected.'">&#9733;</li>';
            }
            echo "</ul>";

            echo '<div class="review-text">'.$rowReview->'reviewResponse'.'</div>';
            if(isset($_SESSION['ID'])) {
              if($_SESSION["IsAdmin"] > 0){
              echo '<form role="form" method="POST" action="carpark.php?CarparkId='.$_GET['CarparkId'].'"><input type="hidden" name="deleteReview" value='.$rowReview->'feedbackId'.'><button class="button button-red">Delete</button></form>';

              }
            }
            echo "</div>";

    }

  ?>
</div>
