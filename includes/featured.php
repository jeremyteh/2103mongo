<?php

$sql = "SELECT
foodestablishment.foodEstablishmentId,
foodestablishment.image,
foodestablishment.name,
AVG(review.AvgRating) AS rating
FROM
foodestablishment
INNER JOIN
review ON foodestablishment.foodEstablishmentId = review.foodEstablishmentId
GROUP BY
foodEstablishmentId
ORDER BY rating DESC
LIMIT 3";

$result = mysqli_query($conn, $sql);
if ($result) {
  if (mysqli_num_rows($result) > 0) {
    echo '<ul id="res-food-cont">';
    while($row = mysqli_fetch_assoc($result)) {

      /*EACH FOOD INSTANCE*/
      echo '<li class="res-row-food">';
      echo '<a class="res-food-img" href="restaurant.php?foodEstablishmentId='.$row["foodEstablishmentId"].'">';
      echo '<img src=http://ctjsctjs.com/'. $row['image'] .'>';
      echo '</a>';
      echo "<div class='res-food'>";
      echo '<a class="results-header hide-overflow" href="restaurant.php?foodEstablishmentId='.$row["foodEstablishmentId"].'">' . $row["name"] . '</a>';
      echo "<span class='res-food-subheader'>Average Rating</span>";
      echo "<table class='demo-table'><tbody>";
        echo '<td><input type="hidden" name="rating" id="rating" value="'.$row["rating"].'"/>';
        echo '<ul class="featured-stars">';

        for($i=1;$i<=5;$i++) {
          $selected = "";
          if(!empty($row["rating"]) && $i<=$row["rating"]) {
            $selected = "selected";
          }
          echo '<li class="'.$selected.'">&#9733;</li>';
        }
        echo '</ul>';
      echo "</tbody></table>";
      echo "<a class='res-more' href='restaurant.php?foodEstablishmentId=".$row['foodEstablishmentId']."'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a>";
      echo "</div>";
    }
    echo "</li>";
  }
  echo '</ul>';
}

?>
