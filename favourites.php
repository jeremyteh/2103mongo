<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>

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


			$userID = (string)$_SESSION['ID'];
			$filterFavFood      = ['userID' => $userID];
			$query = new \MongoDB\Driver\Query(['userID' => $userID]);
			$rows = $mongodbManager->executeQuery('foodfinderapp.favouritefood', $query);
	echo '<ul class="results-container load" id="res-food-cont">';
			foreach ($rows as $what){
				$favFoodID = $what->_id;
				$foodID = $what->foodestablishmentId;
				//$filterFood = ['foodEstablishmentId' => $foodID];
				$foodquery = new \MongoDB\Driver\Query(['foodEstablishmentId' => (string)$foodID]);
				$foodrows = $mongodbManager->executeQuery('foodfinderapp.foodestablishment', $foodquery);

				$userRecord = current($foodrows->toArray());



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
								//echo "<span class='res-food-subheader'>Nearest Carpark</span>";
								#SQL statement to find all carpark within 500m
								// NEED TO ADD CARPARK
				// 		$postalcode = substr($row[5], -6);
				// 		$locationVector = getLocation($postalcode, $googleKey); //Get Coords
				// 		$dist = "( 6371 * acos( cos( radians(". $locationVector[0] .")) * cos( radians( latitude )) * cos( radians( longitude ) - radians(". $locationVector[1] .")) + sin(radians(". $locationVector[0] .")) * sin(radians(latitude))))";
				// 		$locateSQL = "SELECT *, ".$dist." as distance FROM carpark HAVING distance < 0.5 ORDER BY distance ASC LIMIT 1 ";
				// 		$locateResult = mysqli_query($conn, $locateSQL) or die(mysqli_connect_error());
				// 		if ($locateResult) {
				// 			if (mysqli_num_rows($locateResult) > 0) {
				// 				while($locateRow = mysqli_fetch_assoc($locateResult)) {
				// 					$lots = getLots($locateRow, $datamallKey); //Get number of lots available
				// 					/*EACH BLOCK OF CARPARK*/
				// 					echo '<a href=carpark.php?carparkId='.$locateRow["carparkId"].' class="res-blocks">'
				// 					."<span class='res-lots'>". $lots ."</span>"
				// 					."<span class='res-name hide-overflow'>" . $locateRow["development"]. "</span>"
				// 					."<span class='res-dist'>" . sprintf(' %0.2f', $locateRow["distance"])*1000 . "m</span>"
				// 					."</a>";
				// 					/*END OF CARPARK BLOCK*/
				// 				}
				// 			}
				// 			else {
				// 				echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Carparks Nearby</span>";
				// 			}
				// 		}
				// 		echo "<a class='res-more' href='restaurant.php?foodEstablishmentId=".$row[4]."'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>";
				// 		echo "</li>";
				// 	}
				// }
				// else {
				// 	echo "<span class='empty-result' id='label-food'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Favourites are found. Start browsing today.</span>";
				// }


			}
			echo '</ul>';
			?>

<?php
$filterfavCarpark     = ['userId' => (string)$_SESSION['ID']];

$queryfavCarpark = new \MongoDB\Driver\Query($filterfavCarpark);
$rowsCarpark = $mongodbManager->executeQuery('foodfinderapp.favouritecarpark', $queryfavCarpark);
echo '<ul id="res-carpark-cont" style="display:none;">';
foreach ($rowsCarpark as $dc){
	$favCarparkID = $dc->_id;
	$carparkID = $dc->carparkId;
	$filterCarpark      = ['carparkId' => (string)$carparkID];
	$carparkquery = new \MongoDB\Driver\Query($filterCarpark);
	$carparkrows = $mongodbManager->executeQuery('foodfinderapp.carpark', $carparkquery);

	$carparkRecord = current($carparkrows->toArray());

		echo '<li class="res-row-food">'
          .'<a class="res-food-img" href=carpark.php?carparkId='.$carparkID.'>'
         .'<img src=http://ctjsctjs.com/'.$carparkRecord->image.'>'
         .'</a>'
		 			."<form class='view-delete-form' role='form' method='POST' action='favourites.php'>"
		 			. "<input type='hidden' name='deleteCarpark' value='".$favCarparkID."'>"
		 			. "<button class='delete-carpark'><i class='fa fa-times' aria-hidden='true'></i></button>"
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
