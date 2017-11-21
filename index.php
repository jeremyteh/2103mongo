<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>

<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';
?>

<?php
if(isset($_SESSION['ID'])) {
  include_once 'includes/dashboard.php';
} else {
  include_once 'includes/landing.php';
}
?>

<section class="container-featured">
  <div class=" container-responsive">
    <h1 class="header-verified">Verified by you</h1>
    <span class="text-verified"> Check out the highest rated food places rating by the community </span>
    <hr class="divider" id="result-divider">
    <?php include_once 'includes/featured.php' ?>
  </div>
</section>
<section class="container white wrapper">
  <div class="container-responsive">
    <h1>Worry Free Food Experience</h1>
    <p>Tired of waiting in the car instead of enjoying your delicious food?
      Fret not, FoodPark is here to eliminate your parking problems. FoodPark
      is a web application that tracks real-time data of available parking lots near
      various food establishments, allowing you to locate the nearest available
      parking location.</p>
      <a href="viewAllFood.php"><span class="button button-red" id="foodEst-link">Browse Food Places</span></a>
      <img class="container-img" src="images/car.svg">
    </div>
  </section>
  <section class="container pink wrapper">
    <div class="container-responsive">
      <h1>Search quickly with precision</h1>
      <p>Our search algorithm brings the best results to you within seconds,
        making sure you find a place to park and eat as quick as possible. Our
        Advanced Search allows you to make detailed searches, solving all your
        parking frustrations. </p>
        <a href="advancedSearch.php"><span class="button button-white" id="advSearch-link">Try Advanced Search</span></a>
        <img class="container-img-search" src="images/search.svg">
      </div>
    </section>

    <?php include_once 'includes/footer_main.php' ?>
