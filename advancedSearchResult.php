
<?php
include_once 'includes/header.php';

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

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($search == ""){
      header("Location: advancedSearch.php?message=search_empty");
    } else {

      $sql = "SELECT name, foodEstablishmentId, image, RIGHT(address, 6)
      as postalcode
      FROM foodestablishment
      WHERE name
      LIKE '%" . $search . "%'";

      $result = mysqli_query($conn, $sql);
      if ($result) {
        if (mysqli_num_rows($result) > 0) {
          echo '<div class="results-container" id="res-food-cont">';
          $storedResult = array();
          while($row = mysqli_fetch_assoc($result)){
            //reset counter for valid carpark and lot;
            $validCarparks = 0;
            $lotCount = 0;
            $locationVector = getLocation($row['postalcode'], $googleKey); //Get Coords

            //Select carparks within radius
            $locateSQL = "SELECT *, ( 6371 *
                acos(
                cos( radians(". $locationVector[0] .")) * cos( radians( latitude )) *
                cos( radians( longitude ) - radians(". $locationVector[1] .")) +
                sin(radians(". $locationVector[0] .")) * sin(radians(latitude))
                ))
                as distance FROM carpark HAVING distance < ". $input_radius ." ORDER BY distance";
                $locateResult = mysqli_query($conn, $locateSQL);

                if (mysqli_num_rows($locateResult) >= $input_carpark) { //check carpark meets carpark_lots requirement
                  while($locateRow = mysqli_fetch_assoc($locateResult)) { //for each carpark
                    $lots = getLots($locateRow, $datamallKey); //Get number of lots available
                    if ($lots >= $input_lots){
                      $validCarparks += 1; //check lots meets input_lots requirement
                      $lotCount += $lots;
                    }
                  }
                }
                //if number of carpark with enough lots meet carpark input
                if ($validCarparks >= $input_carpark){
                  $row['lotCount'] = $lotCount;
                  $row['validCarparks'] = $validCarparks;
                  array_push($storedResult,$row);
                }
              }
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
