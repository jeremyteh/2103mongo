<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<?php include_once 'nearbyCarpark.php' ?>

<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
header('Location: 404.php');
?>
<section class="container-searchbar">
	<div class="container-responsive">
		<span class="page-title">Favourites</span>
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

			<?php
			include_once 'protected/databaseconnection.php';
			include_once 'protected/functions.php';

			function cmp($nearByCarparks, $b)
	        {
	            if ($nearByCarparks->distance == $b->distance) {
	                return 0;
	            }
	            return ($nearByCarparks->distance < $b->distance) ? -1 : 1;
	        }

			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if (isset($_POST['deleteFavorite']))
				{
					$orderID = $_POST['deleteFavorite'];

					$delRec = new MongoDB\Driver\BulkWrite;
					$delRec->delete(['_id' => new MongoDB\BSON\ObjectID($orderID)]);
					$result = $mongodbManager->executeBulkWrite('foodfinderapp.favouritefood', $delRec);

					echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";

				}
        if (isset($_POST['deleteCarpark']))
				{
					$carparkID = $_POST['deleteCarpark'];
					$delRec = new MongoDB\Driver\BulkWrite;
					$delRec->delete(['_id' => new MongoDB\BSON\ObjectID($carparkID)]);
					$result = $mongodbManager->executeBulkWrite('foodfinderapp.favouritecarpark', $delRec);

						echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";

				}

			}

			$userID = $_SESSION['ID'];
			$filterByIDFood = ['userID' => (string)$userID];
			$query = new MongoDB\Driver\Query($filterByIDFood);
			$rows = $mongodbManager->executeQuery('foodfinderapp.favouritefood', $query);

			echo '<ul class="results-container load" id="res-food-cont">';
			if(count($rows) >0){
			foreach ($rows as $what){
				$favFoodID = $what->_id;
				$foodID = $what->foodestablishmentId;

				//$filterFood = ['foodEstablishmentId' => $foodID];
				$foodquery = new MongoDB\Driver\Query(['foodEstablishmentId' => $foodID]);
				$foodrows = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $foodquery)->toArray();

				$userRecord = current($foodrows);

				echo '<li class="res-row-food">';
				echo '<a class="res-food-img" href="restaurant.php?foodEstablishmentId='.$foodID.'">';
				echo '<img src=http://ctjsctjs.com/'.$userRecord->image.'>';
				echo '</a>';
				echo "<form class='view-delete-form' role='form' method='POST' action='favourites.php'>"
				. "<input type='hidden' name='deleteFavorite' value='".$favFoodID."'>"
				. "<button class='delete-fav'><i class='fa fa-times' aria-hidden='true'></i></button>"
				. "</form>";
				echo "<div class='res-food'>";
				echo '<a class="results-header hide-overflow" href="restaurant.php?foodEstablishmentId='.$foodID.'">' .$userRecord->name. '</a>';

				$locationVector = getLocation(substr($foodrows[0]->address, -6), $googleKey);

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
					foreach($nearByCarparks as $relatedCarpark) {
						                $lots = getSortCarparkLots($relatedCarpark, $datamallKey); //Get number of lots available
						                /*EACH BLOCK OF CARPARK*/

						                echo '<a href=carpark.php?carparkId='.$relatedCarpark->get_carparkId().' class="res-blocks">'
						                ."<span class='res-lots'>". $lots ."</span>"
						                ."<span class='res-name hide-overflow'>" . $relatedCarpark->get_carparkName(). "</span>"
						                ."<span class='res-dist'>" . sprintf(' %0.2f', $relatedCarpark->get_distance())*1000 . "m</span>"
						                ."</a>";
						                /*END OF CARPARK BLOCK*/
						                break;
						            }
						        }
						        else {
						        	echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Carparks Nearby</span>";
						        }
						        echo "<a class='res-more' href='restaurant.php?foodEstablishmentId=".$foodID."'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>";

						        echo "</li>";
						    }
						}
								
				else {
					echo "<span class='empty-result' id='label-food'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Favourites are found. Start browsing today.</span>";
				}


			
			echo '</ul>';


			?>

<?php
$filterByID   = ['userID' => (string)$_SESSION['ID']];
echo '<ul id="res-carpark-cont" style="display:none;">';
$queryfavCarpark = new MongoDB\Driver\Query($filterByID);
$rowsCarpark = $mongodbManager->executeQuery('foodfinderapp.favouritecarpark', $queryfavCarpark);
foreach ($rowsCarpark as $dc){

	$favCarparkID = $dc->_id;
	$carparkID = $dc->carparkId;
	$filterCarpark = ['carparkId' => $carparkID];
	$carparkquery = new MongoDB\Driver\Query($filterCarpark);
	$carparkrows = $mongodbManager->executeQuery('foodfinderapp.carpark', $carparkquery);

	$carparkRecord = current($carparkrows->toArray());


		echo '<li class="res-row-food">'
          .'<a class="res-food-img" href=carpark.php?carparkId='.$carparkID.'>'
         .'<img src=http://ctjsctjs.com/'.$carparkRecord->image.'>'
         .'</a>'
		 			."<form class='view-delete-form' role='form' method='POST' action='favourites.php'>"
				. "<input type='hidden' name='deleteCarpark' value='".$favCarparkID."'>"
				. "<button class='delete-fav'><i class='fa fa-times' aria-hidden='true'></i></button>"
		 			. "</form>"
           ."<div class='res-food'>"
           .'<a class="results-header hide-overflow" href=carpark.php?carparkId='.$carparkID.'>' .$carparkRecord->development. '</a>'
           ."<span class='res-food-subheader'>Lots Available</span>"
           .'<a href=carpark.php?carparkId='.$carparkID.' class="res-blocks">'
           ."<span class='res-lots'>".$carparkID."</span>"
           ."<span class='res-name res-single hide-overflow'>".$carparkRecord->development."</span>"
           ."</a>"
           . "<a class='res-more' href=carpark.php?carparkId=".$carparkID.">View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>"
           ."</li>";





}
echo "</ul>";


?>


	</div>

</div>


<?php include_once 'includes/footer_main.php' ?>
<script type="text/javascript" src="js/loader.js"></script>
<script type="text/javascript" src="js/resultsPage.js"></script>
<script type="text/javascript" src="js/lot-color.js"></script>