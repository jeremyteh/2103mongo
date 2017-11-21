<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<?php
if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
}
if(isset($_GET['foodEstablishmentId'])) {
  ?>
  <?php

  // Editted SQL statement (Nizam)
  $foodID = $_GET['foodEstablishmentId'];
  $selectedFoodEstablishment = "SELECT name, address,image, RIGHT(address, 6) as postalcode,CAST(AVG(review.AvgRating) as decimal(18,1)), COUNT(review.AvgRating) FROM foodestablishment INNER JOIN review ON foodestablishment.foodestablishmentId = review.foodEstablishmentId WHERE foodestablishment.foodEstablishmentId = '".$_GET['foodEstablishmentId']."'";
  $result = mysqli_query($conn, $selectedFoodEstablishment) or die(mysqli_connect_error());
  $row = mysqli_fetch_array($result);
  $rating = $row[3];
  $numofreview = $row[5];

  $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=.' . $row['postalcode']. '&key=AIzaSyDbEqIHfTZwLD9cgm9-elubEhOCm7_C3VE');
  $json = json_decode($json);

  $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
  $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

  #SQL statement to find all carpark within 500m
  $locateSQL = "SELECT *, ( 6371 *
    acos(
      cos( radians(". $lat .")) * cos( radians( latitude )) *
      cos( radians( longitude ) - radians(". $long .")) +
      sin(radians(". $lat .")) * sin(radians(latitude))
      ))
      as distance FROM carpark HAVING distance < 0.5 ORDER BY distance";

      $locateResult = mysqli_query($conn, $locateSQL) or die(mysqli_connect_error());

      // create arrays to store carpark name and distance
      $carparkIdsArray = [];
      $carparkNameArray = [];
      $carparkLatArray = [];
      $carparkLongArray = [];
      $carparkDistanceArray = [];

      if ($locateResult) {
        if (mysqli_num_rows($locateResult) > 0) {
          while($locateRow = mysqli_fetch_assoc($locateResult)) {
            array_push($carparkIdsArray, $locateRow["carparkId"]);
            array_push($carparkNameArray, $locateRow["development"]);
            array_push($carparkLatArray, $locateRow["latitude"]);
            array_push($carparkLongArray, $locateRow["longitude"]);
            array_push($carparkDistanceArray, sprintf('%0.2f', $locateRow["distance"])*1000);

          }
        }
      }
      $carparkLotsJson = "http://datamall2.mytransport.sg/ltaodataservice/CarParkAvailability";

      $ch      = curl_init( $carparkLotsJson );
      $options = array(
        CURLOPT_HTTPHEADER     => array( "AccountKey: SFHPvNC5RP+jFTzftMxxFQ==, Accept: application/json" ),
      );
      curl_setopt_array( $ch, $options );
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

      $carparkJsonResult = curl_exec( $ch );
      $carparkJsonResult = json_decode($carparkJsonResult);
    }
    ?>
    <section class="container-searchbar">
      <div class="container-responsive">
        <span class="page-title">Food Establishment</span>
        <form  role="form" autocomplete="off" action="resultsPage.php" method="POST">
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
        <div class="res-left-col">
          <?php  include_once 'includes/mainRestaurant_module.php';?>
          <?php  include_once 'includes/viewReview_module.php';?>
        </div>

        <div class="res-right-col">
          <?php   if(isset($_SESSION['ID'])) {
            include_once 'includes/saveRestaurant_module.php';
          }
          ?>
          <div class="res-right-mod" id="viewMap">
            <div id="foodCarparkMap"></div>
          </div>
          <?php   include_once 'includes/restaurantLots_module.php'; ?>
          <?php   if(isset($_SESSION['ID'])) {
            include_once 'includes/restaurantReview_module.php';
          }?>
        </div>
      </div>
    </div>

    <?php include_once 'includes/footer_main.php' ?>
    <script type="text/javascript" src="js/lot-color.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLgOEetVt0oeA8HdyUmOAdW8O1e0qpB7Q"></script>
    <?php include_once 'includes/restaurantMap_script.php' ?>
