
<div class="res-left-mod" id="mainCarpark">
  <div class="res-wrapper">
    <div class="res-wrapper-header">
      <h><?php echo $row["name"]; ?></h>
    </div>
    <div class="food-img" style="background-image: url(http://ctjsctjs.com/<?php echo $row['image'] ?>)"></div>
  </div>
  <div class="res-body">
    <span class="res-add"><?php echo $row["address"]; ?></span>
    <table class="demo-table">
      <div id="tutorial-<?php echo $_GET['foodEstablishmentId']; ?>">
        <?php $property=array("Quality","Cleaniness","Comfort","Ambience","Service"); ?>
        <?php
        $reviewquery = "SELECT ROUND(AVG(quality)) AS quality, ROUND(AVG(clean)) AS clean,ROUND(AVG(comfort)) AS comfort,ROUND(AVG(ambience)) AS ambience,ROUND(AVG(service)) AS service FROM review WHERE foodestablishmentID = '".$_GET['foodEstablishmentId']."'";
        $listreview = mysqli_query($conn, $reviewquery);
        $property=array("Quality","Cleaniness","Comfort","Ambience","Service");
        if ($listreview) {
          while ($row = mysqli_fetch_row($listreview)) {
            $count = 0;

            for($p = 0; $p < 5;$p++ ){
              echo '<tr><td>'.$property[$p].'</td>';
              echo '<td><input type="hidden" name="rating" id="rating" value="'.$rating.'"/>';
              echo '<ul>';
              for($i=1;$i<=5;$i++) {
                $selected = "";
                if(!empty($row[$p]) && $i<=$row[$p]) {
                  $selected = "selected";
                }
                echo '<li class="'.$selected.'">&#9733;</li>';
              }
              echo '</ul>';
              echo '</td></tr>';
            }
          }
        }
        ?>
      </div>
    </table>
  </div>
</div>
