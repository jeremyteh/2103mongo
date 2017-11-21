<div class="res-right-mod-wrap" id="saveFav">
  <?php
  $userID = $_SESSION['ID'];
  if (isset($_POST['saveFood']) == 'save'.$foodID){
    $insert = "INSERT INTO favouritefood(foodestablishmentid, userid, status)
    VALUES  ($foodID,$userID , '1')";
    if ($conn->query($insert) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
  $checkFav = "SELECT COUNT(*) FROM favouritefood WHERE foodEstablishmentId = ".$foodID." AND userId = ".$_SESSION['ID'];

  $result = mysqli_query($conn, $checkFav) or die(mysqli_connect_error());
  $row = mysqli_fetch_array($result);
  if($row[0] == 0)  {
  echo "<form method='post' action='restaurant.php?foodEstablishmentId=".$foodID."' id='form' name='form'>"
  . "<input type='hidden' name='saveFood' value='save".$foodID."'>"
  . "<button class='button button-red button-wide' id='btn-save'>Save</button>"
  . "</form>";
  }
  else{
      echo "<span class='res-saved'><i class='fa fa-check' aria-hidden='true'></i> Added to favourites</span>";
  }
  ?>
</div>
