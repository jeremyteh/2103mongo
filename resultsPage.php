<?php include_once 'includes/header.php' ?>
<?php include_once 'nearbyCarpark.php'; ?>

<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';

date_default_timezone_set("Asia/Singapore");
$datetime = date('Y-m-d H:i:s');
?>
<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Search Results</span>
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
    <div class="results-btn-row">
      <a class="button-link active" id="toggle-res-food">Food Establishment</a>
      <a class="button-link" id="toggle-res-carpark">Carpark</a>
    </div>
    <hr class="divider" id="result-divider">
    <div class="loader"></div>
    <div class="results-container">

      <?php

      if ($_POST["search"] == ""){
        echo "<span class='empty-result'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> Please enter a valid search input</span>";
      } else {

        include_once 'protected/databaseconnection.php';
        include_once 'protected/functions.php';
        //FOOD ESTABLISHMENT SEARCH ALGO

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

        $foodResults = array();
        if(!empty($searchFoodEstablishments)) {
          echo "<p hidden id='foodCounts'>" . count($searchFoodEstablishments) . "</p>";
          $currentFoodPage = 1;
          $maxFoodPage = ceil(count($searchFoodEstablishments) / 24);

          $bulk = new MongoDB\Driver\BulkWrite();

          echo '<ul class="load" id="res-food-cont">';
          foreach ($searchFoodEstablishments as $indivFoodEstablishment) {
            $oneFoodEstablishmentDisplay = array();
            if(isset($_SESSION['ID'])) {
              $bulk->insert(['dateTimeSearch'=>$datetime, 'termSearch'=>$_POST['search'], 'userId'=>$_SESSION['ID'], 'foodEstablishmentId'=>($indivFoodEstablishment->_id)]);
              try {
                  $result = $mongodbManager->executeBulkWrite('foodfinderapp.foodsearch', $bulk);
              } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
                  $result = var_dump($e->getWriteResult());
              }
            }

            $oneFoodEstablishmentDisplay['foodEstablishmentId'] = $indivFoodEstablishment->foodEstablishmentId;
            $oneFoodEstablishmentDisplay['name'] = $indivFoodEstablishment->name;
            $oneFoodEstablishmentDisplay['image'] = $indivFoodEstablishment->image;

            /*EACH FOOD INSTANCE*/
            #SQL statement to find all carpark within 500m
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


                if($distance < 0.5) {
                    
                    $newCarpark = new carparkNearBy();
                    $newCarpark->set_carparkId($carpark->carparkId);
                    $newCarpark->set_carparkName($carpark->development);
                    $newCarpark->set_distance($distance);

                    array_push($nearByCarparks, $newCarpark);           
                }
            }

            if(count($nearByCarparks) != 0) {
              usort($nearByCarparks, "cmp");
              $row['cpStatus'] = true;
              foreach($nearByCarparks as $relatedCarpark) {
                $lots = getLots($relatedCarpark, $datamallKey); //Get number of lots available
                /*EACH BLOCK OF CARPARK*/
                $oneFoodEstablishmentDisplay['carparkId'] = $relatedCarpark->get_carparkId();
                $oneFoodEstablishmentDisplay['lots'] = $lots;
                $oneFoodEstablishmentDisplay['development'] = $relatedCarpark->get_carparkName();
                $oneFoodEstablishmentDisplay['distance'] = $relatedCarpark->get_distance();
                /*END OF CARPARK BLOCK*/
                break;
              }
            }
            else {
              $oneFoodEstablishmentDisplay['cpStatus'] = false;
            }
            array_push($foodResults,$oneFoodEstablishmentDisplay);   
          }

          echo "</li>";
          echo '</ul>';
          echo "<div class='page-row label-food load'>";
          echo "<a onclick='prevFoodPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
          echo "<span class='inline-text' id='foodCurrentPage'>" . $currentFoodPage . "</span>";
          echo "<span class='inline-text'>&nbsp of &nbsp</span>";
          echo "<span class='inline-text' id='foodMaxPage'>" . $maxFoodPage . "</span>";
          echo "<a onclick='nextFoodPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
          echo "</div>";
        }

        /*
        $sql = "SELECT foodEstablishmentId, image, name, RIGHT(address, 6) as postalcode FROM foodestablishment WHERE name LIKE '%" . $_POST["search"] . "%'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
          if (mysqli_num_rows($result) > 0) {
            echo "<p hidden id='foodCounts'>" . mysqli_num_rows($result) . "</p>";
            $currentFoodPage = 1;
            $maxFoodPage = ceil(mysqli_num_rows($result) / 24);

            echo '<ul class="load" id="res-food-cont">';
            while($row = mysqli_fetch_assoc($result)) {

              $foodId = $row['foodEstablishmentId'];
              $term = $_POST['search'];
              date_default_timezone_set("Asia/Singapore");
              $datetime = date('Y-m-d H:i:s');

              if(isset($_SESSION['ID'])) {
                $userId = $_SESSION['ID'];

                $insertFoodSearch = "INSERT INTO foodsearch(userId, foodEstablishmentId, termSearch, datetimeSearch)VALUES('$userId', '$foodId', '$term', '$datetime')";

                mysqli_query($conn, $insertFoodSearch) or die(mysqli_connect_error());
              }
              */
              /*EACH FOOD INSTANCE*/
              #SQL statement to find all carpark within 500m
              /*
              $locationVector = getLocation($row['postalcode'], $googleKey); //Get Coords
              $dist = "( 6371 * acos( cos( radians(". $locationVector[0] .")) * cos( radians( latitude )) * cos( radians( longitude ) - radians(". $locationVector[1] .")) + sin(radians(". $locationVector[0] .")) * sin(radians(latitude))))";
              $locateSQL = "SELECT *, ".$dist." as distance FROM carpark HAVING distance < 0.5 ORDER BY distance ASC LIMIT 1 ";
              $locateResult = mysqli_query($conn, $locateSQL) or die(mysqli_connect_error());

              if ($locateResult) {
                if (mysqli_num_rows($locateResult) > 0) {
                  $row['cpStatus'] = true;
                  while($locateRow = mysqli_fetch_assoc($locateResult)) {
                    $lots = getLots($locateRow, $datamallKey); //Get number of lots available
                    */
                    /*EACH BLOCK OF CARPARK*/
                    /*
                    $row['carparkId'] = $locateRow['carparkId'];
                    $row['lots'] = $lots;
                    $row['development'] = $locateRow['development'];
                    $row['distance'] = $locateRow['distance'];
                    */
                    /*END OF CARPARK BLOCK*/
                    /*
                  }
                }
                else {
                  $row['cpStatus'] = false;
                }
                array_push($foodResults,$row);
              }
              echo "</li>";
            }
            echo '</ul>';
            echo "<div class='page-row label-food load'>";
            echo "<a onclick='prevFoodPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
            echo "<span class='inline-text' id='foodCurrentPage'>" . $currentFoodPage . "</span>";
            echo "<span class='inline-text'>&nbsp of &nbsp</span>";
            echo "<span class='inline-text' id='foodMaxPage'>" . $maxFoodPage . "</span>";
            echo "<a onclick='nextFoodPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
            echo "</div>";
          }
        }
        */
        //CARPARK SEARCH ALGO

        $filter = [
          '$or' => [
            ['area' => new MongoDB\BSON\Regex(".*marina.*","i")],
            ['development' => new MongoDB\BSON\Regex(".*marina.*","i")]
          ]
        ];

        $query = new MongoDB\Driver\Query($filter);
        $searchCarparks = $mongodbManager->executeQuery('foodfinderapp.carpark', $query)->toArray();

        if(!empty($searchCarparks)) {

          // output data of each row
            echo "<p hidden id='carparkCount'>" .count($searchCarparks). "</p>";
            $currentCarparkPage = 1;
            $maxCarparkPage = ceil(count($searchCarparks) / 3);

            $bulk = new MongoDB\Driver\BulkWrite();

            echo '<ul id="res-carpark-cont" style="display:none;">';
            foreach($searchCarparks as $indivCarpark) {
              $oneCarparkDisplay = array();
              if(isset($_SESSION['ID'])) {
                $bulk->insert(['dateTimeSearch'=>$datetime, 'termSearch'=>$_POST['search'], 'userId'=>$_SESSION['ID'], 'carparkId'=>($indivCarpark->_id)]);
                try {
                    $result = $mongodbManager->executeBulkWrite('foodfinderapp.carparksearch', $bulk);
                } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
                    $result = var_dump($e->getWriteResult());
                }
              }

              $oneCarparkDisplay['carparkId'] = $indivCarpark->carparkId;
              $oneCarparkDisplay['longitude'] = $indivCarpark->longitude;
              $oneCarparkDisplay['latitude'] = $indivCarpark->latitude;
              $oneCarparkDisplay['area'] = $indivCarpark->area;
              $oneCarparkDisplay['development'] = $indivCarpark->development;
              $oneCarparkDisplay['image'] = $indivCarpark->image;

              $lots = getLots($indivCarpark, $datamallKey); //Get number of lots available
              $oneCarparkDisplay['lots'] = $lots;
              array_push($cpResults,$oneCarparkDisplay);
            }
            echo '</ul>';
            echo "<div class='page-row label-carpark' style='none'>";
            echo "<a onclick='prevCarparkPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
            echo "<span class='inline-text' id='carparkCurrentPage'>" . $currentCarparkPage . "</span>";
            echo "<span class='inline-text'>&nbsp of &nbsp</span>";
            echo "<span class='inline-text' id='carparkMaxPage'>" . $maxCarparkPage . "</span>";
            echo "<a onclick='nextCarparkPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
            echo "</div>";
        }
        else {
            echo "<span class='empty-result label-carpark'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Results are found. Please try another keyword.</span>";
        }
        /*
        $sql1 = "SELECT * FROM carpark WHERE area LIKE '%" . $_POST["search"] . "%' OR development LIKE '%" . $_POST["search"] . "%'";
        $result1 = mysqli_query($conn, $sql1) or die(mysqli_connect_error());
        $cpResults = array();
        if ($result1) {
          if (mysqli_num_rows($result1) > 0) {
            // output data of each row
            echo "<p hidden id='carparkCount'>" . mysqli_num_rows($result1) . "</p>";
            $currentCarparkPage = 1;
            $maxCarparkPage = ceil(mysqli_num_rows($result1) / 3);

            echo '<ul id="res-carpark-cont" style="display:none;">';
            while($row1 = mysqli_fetch_assoc($result1)) {

              $userId = $_SESSION['ID'];
              $carparkId = $row1['carparkId'];
              $term = $_POST['search'];
              date_default_timezone_set("Asia/Singapore");
              $datetime = date('Y-m-d H:i:s');
              if(isset($_SESSION['ID'])) {

                $insertFoodSearch = "INSERT INTO carparksearch(userId, carparkId, termSearch, datetimeSearch)VALUES('$userId', '$carparkId', '$term', '$datetime')";

                mysqli_query($conn, $insertFoodSearch) or die(mysqli_connect_error());
              }

              $lots = getLots($row1, $datamallKey); //Get number of lots available
              $row1['lots'] = $lots;
              array_push($cpResults,$row1);

            }
            echo '</ul>';
            echo "<div class='page-row label-carpark' style='none'>";
            echo "<a onclick='prevCarparkPage()' class='page-arrow'><i class='fa fa-caret-left' aria-hidden='true'></i></a>";
            echo "<span class='inline-text' id='carparkCurrentPage'>" . $currentCarparkPage . "</span>";
            echo "<span class='inline-text'>&nbsp of &nbsp</span>";
            echo "<span class='inline-text' id='carparkMaxPage'>" . $maxCarparkPage . "</span>";
            echo "<a onclick='nextCarparkPage()' class='page-arrow'><i class='fa fa-caret-right' aria-hidden='true'></i></a>";
            echo "</div>";
          }
          else {
            echo "<span class='empty-result label-carpark'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Results are found. Please try another keyword.</span>";
          }
        }*/
      }
      ?>
    </div>
  </div>
</div>

<?php include_once 'includes/footer_main.php' ?>
<script>
var foodArray = <?php echo json_encode($foodResults);?>;
var cpArray = <?php echo json_encode($cpResults);?>;
</script>
<script type="text/javascript" src="js/lot-color.js"></script>
<script type="text/javascript" src="js/resultsPage.js"></script>
<script type="text/javascript" src="js/loader.js"></script>

<script>initialFoodLoad();</script>
<script>initialCarparkLoad();</script>
