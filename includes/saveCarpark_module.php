<div class="res-right-mod-wrap">
  <?php
  $userID = $_SESSION['ID'];
  if (isset($_POST['saveFood']) == 'save'.$carparkID){
    $insert = "INSERT INTO favouriteCarpark(carparkId, userId, status)
    VALUES  ($carparkID,$userID , '1')";
    if ($conn->query($insert) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }

  $checkFav = "SELECT COUNT(*) FROM favouritecarpark WHERE carparkId = ".$carparkID." AND userId = ".$_SESSION['ID'];

  $result = mysqli_query($conn, $checkFav) or die(mysqli_connect_error());
  $row = mysqli_fetch_array($result);
  if($row[0] == 0)  {
  echo "<form method='post' action='carpark.php?carparkId=".$carparkID."' id='form' name='form'>"
  . "<input type='hidden' name='saveFood' value='save".$carparkID."'>"
  . "<button class='button button-red button-wide' id='btn-save'>Save</button>"
  . "</form>";}

  else{
       echo "<span class='res-saved'><i class='fa fa-check' aria-hidden='true'></i> Added to favourites</span>";
  }
  ?>
</div>
