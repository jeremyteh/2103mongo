
<div class="res-left-mod" id="mainCarpark">
  <div class="res-wrapper">
    <div class="res-wrapper-header">
      <h><?php echo $selectedFoodEstablishment[0]->name; ?></h>
    </div>
    <div class="food-img" style="background-image: url(http://ctjsctjs.com/<?php echo $selectedFoodEstablishment[0]->image ?>)"></div>
  </div>
  <div class="res-body">
    <span class="res-add"><?php echo $selectedFoodEstablishment[0]->address; ?></span>
    <table class="demo-table">
      <div id="tutorial-<?php echo $_GET['foodEstablishmentId']; ?>">
        <?php $property=array("Quality","Cleaniness","Comfort","Ambience","Service"); ?>
        <?php

        $filter = ['foodEstablishmentId'=>$_GET['foodEstablishmentId']];

        $query = new MongoDB\Driver\Query($filter);
        $allReviews = $mongodbManager->executeQuery('foodfinderapp.review', $query)->toArray();

        $TotalQualityRating = 0;
        $TotalCleanRating = 0;
        $TotalComfortRating = 0;
        $TotalAmbienceRating = 0;
        $TotalServiceRating = 0;

        foreach($allReviews as $indivReview) {
          $TotalQualityRating += $indivReview->quality;
          $TotalCleanRating += $indivReview->clean;
          $TotalComfortRating += $indivReview->comfort;
          $TotalAmbienceRating += $indivReview->ambience;
          $TotalServiceRating += $indivReview->service;
        }

        $allRatings = array();

        if(($TotalQualityRating != 0) or ($TotalCleanRating != 0) or ($TotalComfortRating != 0) or ($TotalComfortRating = 0) or($TotalServiceRating = 0)) {

            $AvgQualityRating = round($TotalQualityRating/count($allReviews), 2);
            array_push($allRatings, $AvgQualityRating);
            $AvgCleanRating = round($TotalCleanRating/count($allReviews), 2);
            array_push($allRatings, $AvgCleanRating);
            $AvgComfortRating = round($TotalComfortRating/count($allReviews), 2);
            array_push($allRatings, $AvgComfortRating);
            $AvgAmbienceRating = round($TotalAmbienceRating/count($allReviews), 2);
            array_push($allRatings, $AvgAmbienceRating);
            $AvgServiceRating = round($TotalServiceRating/count($allReviews), 2);
            array_push($allRatings, $AvgServiceRating);
        }
        $property=array("Quality","Cleaniness","Comfort","Ambience","Service");
        /*
        $reviewquery = "SELECT ROUND(AVG(quality)) AS quality, ROUND(AVG(clean)) AS clean,ROUND(AVG(comfort)) AS comfort,ROUND(AVG(ambience)) AS ambience,ROUND(AVG(service)) AS service FROM review WHERE foodestablishmentID = '".$_GET['foodEstablishmentId']."'";
        $listreview = mysqli_query($conn, $reviewquery);
        $property=array("Quality","Cleaniness","Comfort","Ambience","Service");
        if ($listreview) {
          while ($row = mysqli_fetch_row($listreview)) {
          */  

            for($p = 0; $p < 5;$p++ ){
              echo '<tr><td>'.$property[$p].'</td>';
              echo '<td><input type="hidden" name="rating" id="rating" value="'.$rating.'"/>';
              echo '<ul>';
              for($i=1;$i<=5;$i++) {
                $selected = "";
                if(!empty($allRatings[$p]) && $i<=$allRatings[$p]) {
                  $selected = "selected";
                }
                echo '<li class="'.$selected.'">&#9733;</li>';
              }
              echo '</ul>';
              echo '</td></tr>';
            }
          //}
        //}
        ?>
      </div>
    </table>
  </div>
</div>
