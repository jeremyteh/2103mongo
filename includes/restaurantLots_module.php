<div class="res-right-mod" id="availableLots">
  <span class='res-food-subheader'>Carparks nearby</span>
  <?php

  if (count($carparkNameArray) > 0) {

    for($i=0; $i < count($carparkNameArray); $i++) {
      echo '<a href=carpark.php?carparkId=1" class="res-blocks">';
      echo "<span class='res-lots'>".$carparkJsonResult->{'value'}[$carparkIdsArray[$i]-1]->{'Lots'}."</span>";
      echo '<div class="res-name" >' .$carparkNameArray[$i]. '</div>';
      echo '<div class="res-dist" >' .$carparkDistanceArray[$i]. 'm</div>';
      echo "</a>";
    }
  }
  else{
    echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Carparks Nearby</span>";
  }

  ?>
</div>
