<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<?php include_once 'nearbyCarparkForIndivFood.php' ?>

<?php
if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
}
if(isset($_GET['foodEstablishmentId'])) {

  function cmp($nearByCarparks, $b)
  {
    if ($nearByCarparks->distance == $b->distance) {
      return 0;
    }
    return ($nearByCarparks->distance < $b->distance) ? -1 : 1;
  }

  // create arrays to store carpark name and distance
  $carparkIdsArray = [];
  $carparkNameArray = [];
  $carparkLatArray = [];
  $carparkLongArray = [];
  $carparkDistanceArray = [];

  // Editted SQL statement (Nizam)

  $filter = ['foodEstablishmentId'=>$_GET['foodEstablishmentId']];

  $query = new MongoDB\Driver\Query($filter);
  $selectedFoodEstablishment = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $query)->toArray();

  $query = new MongoDB\Driver\Query($filter);
  $allReviews = $mongodbManager->executeQuery('foodfinderapp.review', $query)->toArray();

  $TotalAvgRating = 0;

  foreach($allReviews as $indivReview) {
    $TotalAvgRating += $indivReview->AvgRating;
  }

  if($TotalAvgRating != 0) {
    $rating = $TotalAvgRating/count($allReviews);
  }
  else{
    $rating = 0;
  } 

  $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=.'.substr($selectedFoodEstablishment[0]->address, -6).'&key=AIzaSyDbEqIHfTZwLD9cgm9-elubEhOCm7_C3VE');
  $json = json_decode($json);

  $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
  $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

  set_time_limit(0);
  $query = new MongoDB\Driver\Query([]);
  $allCarparks = $mongodbManager->executeQuery('foodfinderapp.carpark', $query)->toArray();

  $nearByCarparks = array();
  foreach ($allCarparks as $carpark) {

    //RAD
    $foodestablishmentLat = ($lat/180)*M_PI;
    $carparkLat = (($carpark->latitude)/180)*M_PI;
    $foodestablishmentlong = ($long/180)*M_PI;
    $carparkLong = (($carpark->longitude)/180)*M_PI;

    //equatorial radius
    $r = 6378.137;
    // Formula
    $e = acos( sin($foodestablishmentLat)*sin($carparkLat) + cos($foodestablishmentLat)*cos($carparkLat)*cos($carparkLong-$foodestablishmentlong) );
    $distance = round($r*$e, 4);


    if($distance < 0.5) {

      $newCarpark = new carparkNearBy();
      $newCarpark->set_carparkId($carpark->carparkId);
      $newCarpark->set_carparkName($carpark->development);
      $newCarpark->set_latitude($carpark->latitude);
      $newCarpark->set_longitude($carpark->longitude);
      $newCarpark->set_distance($distance);

      array_push($nearByCarparks, $newCarpark);
    }
  }

  if(count($nearByCarparks) != 0) {
    usort($nearByCarparks, "cmp");

    foreach($nearByCarparks as $relatedCarpark) {
      array_push($carparkIdsArray, $relatedCarpark->get_carparkId());
      array_push($carparkNameArray, $relatedCarpark->get_carparkname());
      array_push($carparkLatArray, $relatedCarpark->get_latitude());
      array_push($carparkLongArray, $relatedCarpark->get_longitude());
      array_push($carparkDistanceArray, sprintf('%0.2f', $relatedCarpark->get_distance())*1000);
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
