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


			<!-- QUERY NEED TO CHANGE AT WHERE STATEMENT -->
			<!-- SESSION["ID"] can't retrieve, for now temporary put "1" -->
			<!-- NIZAM -->
			<?php
			include_once 'protected/databaseconnection.php';
			include_once 'protected/functions.php';

			$query = "SELECT favouritefood.favFoodID,foodestablishment.name,foodestablishment.address,foodestablishment.image,foodestablishment.foodestablishmentId, foodestablishment.address FROM `favouritefood` INNER JOIN foodestablishment on favouritefood.foodestablishmentId = foodestablishment.foodEstablishmentId WHERE favouritefood.userID = ".$_SESSION['ID'];
			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if (isset($_POST['deleteFavorite']))
				{
					$orderID = $_POST['deleteFavorite'];
					$deleteFoodQuery = "DELETE from favouritefood WHERE favFoodID = ".$orderID;

					if ($conn->query($deleteFoodQuery) === TRUE) {
						echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";
					} else {
						echo "Error deleting record: " . $conn->error;
					}
				}
                                if (isset($_POST['deleteCarpark']))
				{
					$carparkID = $_POST['deleteCarpark'];
					$deleteCarparkQuery = "DELETE from favouritecarpark WHERE favcarparkid = ".$carparkID;

					if ($conn->query($deleteCarparkQuery) === TRUE) {
						echo "<span class='res-deleted load label-food'><i class='fa fa-check' aria-hidden='true'></i> Record deleted successfully</span>";
					} else {
						echo "Error deleting record: " . $conn->error;
					}
				}

			}
			echo '<ul class="results-container load" id="res-food-cont">';
			if ($result = mysqli_query($conn, $query) or die(mysqli_connect_error)) {
				$rowcount = mysqli_num_rows($result);
				if ($rowcount > 0) {
					for ($i = 0; $i < $rowcount; $i++) {
						$row = mysqli_fetch_array($result, MYSQLI_NUM);
						echo '<li class="res-row-food">';
						echo '<a class="res-food-img" href="restaurant.php?foodEstablishmentId='.$row[4].'">';
						echo '<img src=http://ctjsctjs.com/'. $row[3] .'>';
						echo '</a>';
						echo "<form class='view-delete-form' role='form' method='POST' action='favourites.php'>"
						. "<input type='hidden' name='deleteFavorite' value='".$row[0]."'>"
						. "<button class='delete-fav'><i class='fa fa-times' aria-hidden='true'></i></button>"
						. "</form>";
						echo "<div class='res-food'>";
						echo '<a class="results-header hide-overflow" href="restaurant.php?foodEstablishmentId='.$row[4].'">' .$row[1]. '</a>';
						echo "<span class='res-food-subheader'>Nearest Carpark</span>";

						#SQL statement to find all carpark within 500m
						$postalcode = substr($row[5], -6);
						$locationVector = getLocation($postalcode, $googleKey); //Get Coords
						$dist = "( 6371 * acos( cos( radians(". $locationVector[0] .")) * cos( radians( latitude )) * cos( radians( longitude ) - radians(". $locationVector[1] .")) + sin(radians(". $locationVector[0] .")) * sin(radians(latitude))))";
						$locateSQL = "SELECT *, ".$dist." as distance FROM carpark HAVING distance < 0.5 ORDER BY distance ASC LIMIT 1 ";
						$locateResult = mysqli_query($conn, $locateSQL) or die(mysqli_connect_error());

						if ($locateResult) {
							if (mysqli_num_rows($locateResult) > 0) {
								while($locateRow = mysqli_fetch_assoc($locateResult)) {
									$lots = getLots($locateRow, $datamallKey); //Get number of lots available

									/*EACH BLOCK OF CARPARK*/
									echo '<a href=carpark.php?carparkId='.$locateRow["carparkId"].' class="res-blocks">'
									."<span class='res-lots'>". $lots ."</span>"
									."<span class='res-name hide-overflow'>" . $locateRow["development"]. "</span>"
									."<span class='res-dist'>" . sprintf(' %0.2f', $locateRow["distance"])*1000 . "m</span>"
									."</a>";
									/*END OF CARPARK BLOCK*/
								}
							}
							else {
								echo "<span class='res-empty'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Carparks Nearby</span>";
							}
						}
						echo "<a class='res-more' href='restaurant.php?foodEstablishmentId=".$row[4]."'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>";
						echo "</li>";
					}
				}
				else {
					echo "<span class='empty-result' id='label-food'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Favourites are found. Start browsing today.</span>";
				}
				echo "</ul>";
			}
			?>

		<?php
		$query = "SELECT favouriteCarpark.favCarparkID,favouriteCarpark.carparkId,carpark.carparkId,carpark.development,carpark.area, carpark.image FROM `favouriteCarpark` INNER JOIN carpark on favouriteCarpark.carparkId = carpark.carparkId WHERE favouriteCarpark.userID = ".$_SESSION['ID'];
		if ($result = mysqli_query($conn, $query) or die(mysqli_connect_error)) {
			echo '<ul id="res-carpark-cont" style="display:none;">';
			$rowcount = mysqli_num_rows($result);
			if ($rowcount > 0) {
				for ($i = 0; $i < $rowcount; $i++) {
					$row = mysqli_fetch_array($result, MYSQLI_NUM);


					echo '<li class="res-row-food">'
          .'<a class="res-food-img" href=carpark.php?carparkId='.$row[1].'>'
          .'<img src=http://ctjsctjs.com/'. $row[5] .'>'
          .'</a>'
					."<form class='view-delete-form' role='form' method='POST' action='favourites.php'>"
					. "<input type='hidden' name='deleteCarpark' value='".$row[0]."'>"
					. "<button class='delete-carpark'><i class='fa fa-times' aria-hidden='true'></i></button>"
					. "</form>"
          ."<div class='res-food'>"
          .'<a class="results-header hide-overflow" href=carpark.php?carparkId='.$row[1].'>' .$row[3]. '</a>'
          ."<span class='res-food-subheader'>Lots Available</span>"
          .'<a href=carpark.php?carparkId='.$row[1].' class="res-blocks">'
          ."<span class='res-lots'>".$row[1]."</span>"
          ."<span class='res-name res-single hide-overflow'>".$row[3]."</span>"
          ."</a>"
          . "<a class='res-more' href=carpark.php?carparkId=".$row[1].">View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>"
          ."</li>";
				}
			}
			else {
				echo "<span class='empty-result' id='label-carpark'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> No Favourites are found. Start browsing today.</span>";
			}
			echo "</ul>";
		}



		?>
	</div>

</div>
<!--<section class="container">
<div class="row">
<div class="col-md-12">
<ul class="section-header text-center favourites">
<li style="display: inline"><a href="#foodEstablishment"><b>Food Establishment</b></a></li>
<li style="display: inline"> | </li>
<li style="display: inline"><a href="#carpark"><b>Carpark</b></a></li>
<ul>
</div>
</div>
<section>-->

<?php include_once 'includes/footer_main.php' ?>
<script type="text/javascript" src="js/loader.js"></script>
<script type="text/javascript" src="js/resultsPage.js"></script>
<script type="text/javascript" src="js/lot-color.js"></script>
