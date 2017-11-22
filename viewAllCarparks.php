<?php
include_once 'includes/header.php';
include_once 'protected/databaseconnection.php';
include_once 'protected/functions.php';

if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
}
?>

<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Carparks</span>
    <form role="form" autocomplete="off" action="resultsPage.php" method="POST">
      <div class="search-row">
        <input type="text" class="search-form" placeholder="Enter a food establishment or carpark" name="search">
        <button type ="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>
  </div>
</section>
<div class="container-carpark">
  <div class="container-responsive">
    <div class="container-results">
      <div class="loader"></div>
      <?php

        $query = new \MongoDB\Driver\Query([]);
        $rows = $mongodbManager->executeQuery('foodfinderapp.carpark', $query)->toArray();

        echo "<p hidden id='carparkCounts'>" . count($rows) . "</p>";
        $pageCount = ceil(count($rows) / 24);
        $currentPage = 1;

        if (count($rows) > 0) {

          echo "<ul class='results-container' id='res-carpark-cont'>";
          $storedResult = array();
          $carparkJsonResult = array();

          foreach($rows as $indivCarpark) {
              array_push($storedResult, $indivCarpark);
              $tempLot = getCarparkLots($indivCarpark, $datamallKey);
              array_push($carparkJsonResult,$tempLot);
          }
        }

        echo "</ul>";
        echo "<div class='page-row load'>";
        echo "<a onclick='prevPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
        echo "<span class='inline-text' id='carparksCurrentPage'>" . $currentPage . "</span>";
        echo "<span class='inline-text'>&nbsp of &nbsp</span>";
        echo "<span class='inline-text' id='carparksMaxPage'>" . $pageCount . "</span>";
        echo "<a onclick='nextPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
        echo "</div>";
      ?>
    </div>
  </div>
</div>
<p id="demo"></p>

<?php include_once 'includes/footer_main.php' ?>
<script>
var cpArray = <?php echo json_encode($storedResult);?>;
var cpJson = <?php echo  json_encode($carparkJsonResult);?>;
</script>
<script type="text/javascript" src="js/carparkJS.js"></script>
<script type="text/javascript" src="js/lot-color.js"></script>
<script type="text/javascript" src="js/loader.js"></script>

<script>initialLoad();
updateLots();</script>
