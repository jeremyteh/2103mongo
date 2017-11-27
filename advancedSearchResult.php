
<?php
include_once 'includes/header.php';
include_once 'nearbyCarpark.php';

if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
};
?>
<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Advanced Search results</span>
    <form role="form" autocomplete="off" action="resultsPage.php" method="POST">
      <div class="search-row">
        <input type="text" class="search-form" placeholder="Enter a food establishment or carpark" name="search">
        <button type ="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>
  </div>
</section>
<div class="container-results">
  <div class="container-responsive">
    <?php
    include_once 'protected/databaseconnection.php';
    include_once 'protected/functions.php';

    //Declare variables
    $search = $_POST['search'];
    $input_radius = $_POST['radius']/1000;
    $input_lots = $_POST['minLots'];
    $input_carpark = $_POST['minCarpark'];
    $advanced_search = false;
    $resultList = array();
    $locationVector = array();
    $hasResult = false;

    if ($search == ""){
      header("Location: advancedSearch.php?message=search_empty");
    } else {

      $filter = ['name' => new MongoDB\BSON\Regex(".*".$_POST["search"].".*","i")];

      $query = new MongoDB\Driver\Query($filter);
      $searchFoodEstablishments = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();

      function cmp($nearByCarparks, $b)
      {
        if ($nearByCarparks->distance == $b->distance) {
          return 0;
        }
        return ($nearByCarparks->distance < $b->distance) ? -1 : 1;
      }

      if(!empty($searchFoodEstablishments)) {
        echo '<div class="results-container" id="res-food-cont">';
        $storedResult = array();
        foreach ($searchFoodEstablishments as $indivFoodEstablishment) {
          //reset counter for valid carpark and lot;
          $validCarparks = 0;
          $lotCount = 0;
          $oneFoodEstablishmentDisplay = array();

          $oneFoodEstablishmentDisplay['foodEstablishmentId'] = $indivFoodEstablishment->foodEstablishmentId;
          $oneFoodEstablishmentDisplay['name'] = $indivFoodEstablishment->name;
          $oneFoodEstablishmentDisplay['image'] = $indivFoodEstablishment->image;

          $locationVector = getLocation(substr($indivFoodEstablishment->address, -6), $googleKey);

          set_time_limit(0);
          $query = new MongoDB\Driver\Query([]);
          $allCarparks = $mongodbManager->executeQuery('foodfinderapp.carpark', $query)->toArray();

          $nearByCarparks = array();
          foreach ($allCarparks as $carpark) {

            //RAD
            $foodestablishmentLat = ($locationVector[0]/180)*M_PI;
            $carparkLat = (($carpark->latitude)/180)*M_PI;
            $foodestablishmentlong = ($locationVector[1]/180)*M_PI;
            $carparkLong = (($carpark->longitude)/180)*M_PI;

            //equatorial radius
            $r = 6378.137;
            // Formel
            $e = acos( sin($foodestablishmentLat)*sin($carparkLat) + cos($foodestablishmentLat)*cos($carparkLat)*cos($carparkLong-$foodestablishmentlong) );
            $distance = round($r*$e, 4);


            if($distance < $input_radius) {

              $newCarpark = new carparkNearBy();
              $newCarpark->set_carparkId($carpark->carparkId);
              $newCarpark->set_carparkName($carpark->development);
              $newCarpark->set_distance($distance);

              array_push($nearByCarparks, $newCarpark);
            }
          }

          if(count($nearByCarparks) >= $input_carpark) {
            usort($nearByCarparks, "cmp");
            $oneFoodEstablishmentDisplay['cpStatus'] = true;
            foreach($nearByCarparks as $relatedCarpark) {
              $lots = getSortCarparkLots($relatedCarpark, $datamallKey); //Get number of lots available
              if ($lots >= $input_lots){
                $validCarparks += 1; //check lots meets input_lots requirement
                $lotCount += $lots;
              }
              /*EACH BLOCK OF CARPARK*/
              $oneFoodEstablishmentDisplay['carparkId'] = $relatedCarpark->get_carparkId();
              $oneFoodEstablishmentDisplay['lotCount'] = $lotCount;
              $oneFoodEstablishmentDisplay['validCarparks'] = $validCarparks;
              $oneFoodEstablishmentDisplay['development'] = $relatedCarpark->get_carparkName();
              $oneFoodEstablishmentDisplay['distance'] = $relatedCarpark->get_distance();
              /*END OF CARPARK BLOCK*/
              break;
            }
            if ($validCarparks > 0){
              $hasResult = true;
              array_push($storedResult,$oneFoodEstablishmentDisplay);
            }
          }
          else {
            $oneFoodEstablishmentDisplay['cpStatus'] = false;
          }
        }
        if ($hasResult == true){
          $currentPage = 1;
          $pageCount = ceil(count($storedResult) / 24);
          echo "</div>";
          echo "<div class='page-row'>";
          echo "<a onclick='prevPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
          echo "<span class='inline-text' id='resultsCurrentPage'>" . $currentPage . "</span>";
          echo "<span class='inline-text'>&nbsp of &nbsp</span>";
          echo "<span class='inline-text' id='resultsMaxPage'>" . $pageCount . "</span>";
          echo "<a onclick='nextPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
          echo "</div>";
          echo "<p hidden id='resultsCount'>" . count($storedResult) . "</p>";
        }
        echo "</div>";
      }
      if ($hasResult == false){
        echo "<span class='empty-result'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Results are found. Please try another keyword.</span>";
      }
    }

    ?>

  </div>
</div>
<?php include_once 'includes/footer_main.php' ?>
<script>var validArray = <?php echo json_encode($storedResult);?>;</script>
<script src='js/advanceResultJS.js'></script>
<script type="text/javascript" src="js/lot-color.js"></script>

<script>
initialLoad();
</script>
