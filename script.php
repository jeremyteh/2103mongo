<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>

<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';
?>

<?php
/*
$query = "SELECT * FROM foodestablishment";
$count = 1;
$counter = 1;
set_time_limit(0);
if ($result = mysqli_query($conn, $query) or die(mysqli_connect_error)) {
  while($row = mysqli_fetch_assoc($result)) {
    $insert = "UPDATE foodEstablishment SET image ='img".$count.".jpg' WHERE foodEstablishmentId =".$row['foodEstablishmentId']."";
    if ($conn->query($insert) === TRUE) {
    echo $counter;
  } else {
    echo "fail";
  }
    $count ++;
    if($count == 31){
      $count = 1;
    }
    $counter++;
  }
}
*/

/*
$query = "SELECT * FROM carpark";
$count = 1;
$counter = 1;
set_time_limit(0);
if ($result = mysqli_query($conn, $query) or die(mysqli_connect_error)) {
  while($row = mysqli_fetch_assoc($result)) {
  $insert = "UPDATE carpark SET image ='carpark".$count.".jpg' WHERE carparkId =".$row['carparkId']."";
    if ($conn->query($insert) === TRUE) {
    echo $counter;
  } else {
    echo "fail";
  }
    $count ++;
    if($count == 16){
      $count = 1;
    }
    $counter++;
  }
}
*/


?>
