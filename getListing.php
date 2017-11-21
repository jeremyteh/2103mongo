<?php
include_once 'protected/databaseconnection.php';
if (isset($_GET['sort'])) {
  $sortValue = $_GET['sort'];
  if ($sortValue == 0) {
    $query = "SELECT * FROM foodestablishment ORDER BY name ASC";
  } elseif ($sortValue == 1) {
    $query = "SELECT * FROM foodestablishment ORDER BY name DESC";
  }
} else {
  $query = "SELECT * FROM foodestablishment";
}

$storedResult = array();

if ($result = mysqli_query($conn, $query) or die(mysqli_connect_error())) {
  $rowcount = mysqli_num_rows($result); ?>

<span class= "results-total">Total results found: <span class="inline-text" id='feTotalResults'><?php echo $rowcount; ?></span></span>
<div id = 'feListing'>
</div>
<?php
for ($i = 0; $i < $rowcount; $i++) {
  $row = mysqli_fetch_array($result, MYSQLI_NUM);
  array_push($storedResult, $row);
}
} else {
  ?>
  <span id='feTotalResults'>0</span>
  <?php

}
?>

<script>
var feArray = <?php echo json_encode($storedResult);?>;
calculateTotalPage();
listResult(0,12);
</script>
