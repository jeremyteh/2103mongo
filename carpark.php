<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLgOEetVt0oeA8HdyUmOAdW8O1e0qpB7Q"></script>
<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';

if(isset($_GET['carparkId'])) {
	$carparkID = $_GET['carparkId'];
	$selectedCarpark = "SELECT latitude,longitude,development,CAST(AVG(feedback.AvgRating) as decimal(18,1)), COUNT(feedback.AvgRating), image FROM carpark INNER JOIN feedback ON carpark.carparkId = feedback.carparkId WHERE carpark.carparkId = '".$_GET['carparkId']."'";
	$result = mysqli_query($conn, $selectedCarpark) or die(mysqli_connect_error());
	$row = mysqli_fetch_array($result);
	$rating = $row[3];
	$numofreview = $row[4];
	$latitude = $row[0];
	$longtitude = $row[1];
}

$json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?&latlng='.$row["latitude"].','.$row["longitude"].'&key='. $googleKey);
$json1 = json_decode($json);

/*GET LOTS*/
$carparkLotsJson = "http://datamall2.mytransport.sg/ltaodataservice/CarParkAvailability";
$ch = curl_init($carparkLotsJson );
$options = array(CURLOPT_HTTPHEADER=>array("AccountKey: SFHPvNC5RP+jFTzftMxxFQ==, Accept: application/json" ),);
curl_setopt_array( $ch, $options );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$carparkJsonResult = curl_exec( $ch );
$carparkJsonResult = json_decode($carparkJsonResult);
$lots = $carparkJsonResult->{'value'}[$carparkID-1]->{'Lots'};
?>

<section class="container-searchbar">
	<div class="container-responsive">
		<span class="page-title">Carpark</span>
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
			<div class="res-left-mod">
				<div class="res-wrapper">
					<div class="res-wrapper-header">
						<h><?php echo $row["development"]; ?></h>
					</div>
					<div class="carpark-img" style="background-image: url(http://ctjsctjs.com/<?php echo $row['image'] ?>)"></div>
				</div>
				<div class="res-body">
					<span class="res-add"><?php echo $json1->{'results'}[0]->{'formatted_address'}; ?></span>
					<table class="demo-table">
						<tbody>
							<div id="tutorial-<?php echo $_GET['carparkId']; ?>">
								<?php $property=array("Accessiblity","Cleaniness","Parking Rate","Space","User Friendly"); ?>
								<?php
								$reviewquery = "SELECT ROUND(AVG(accessibility)) AS accessibility, ROUND(AVG(clean)) AS clean,ROUND(AVG(parkRate)) AS parkRate,ROUND(AVG(space)) AS space,ROUND(AVG(userFriendly)) AS userFriendly FROM feedback WHERE carparkId = '".$_GET['carparkId']."'";
								$listreview = mysqli_query($conn, $reviewquery);

								if ($listreview) {

									while ($row = mysqli_fetch_row($listreview)) {
										$count = 0;

										for($p = 0; $p < 5;$p++ ){
											echo '<tr><td>'.$property[$p].'</td>';
											echo '<td><input type="hidden" name="rating" id="rating" value="'.$rating.'"/>';
											echo '<ul>';

											for($i=1;$i<=5;$i++) {
												$selected = "";
												if(!empty($row[$p]) && $i<=$row[$p]) {
													$selected = "selected";
												}
												echo '<li class="'.$selected.'">&#9733;</li>';
											}
											echo '</ul>';
											echo '</td></tr>';
										}
									}
								}
								?>
							</div>
						</tbody>
					</table>
					<span class="res-no-review"><?php echo $numofreview?> reviews</span>
				</div>
			</div>
			<?php   include_once 'includes/viewReviewCarpark_module.php'; ?>
		</div>

		<div class="res-right-col">

			<?php if(isset($_SESSION['ID'])) { include_once 'includes/saveCarpark_module.php';} ?>

			<?php include_once 'includes/carparkLots_module.php'; ?>

			<div class="res-right-mod"><div id="carparkMap"></div></div>

			<?php if(isset($_SESSION['ID'])) {include_once 'includes/carparkReview_module.php';} ?>
		</div>
	</div>
</div>

<?php include_once 'includes/footer_main.php' ?>
<script type="text/javascript" src="js/lot-color.js"></script>

<script>

function CarparkMap() {

	maps = new google.maps.Map(document.getElementById('carparkMap'), {
		zoom: 16,
		center: {lat: <?php echo $latitude ?>, lng: <?php echo $longtitude ?>}
	});

	addCarparkMarker({lat: <?php echo $latitude ?>, lng: <?php echo $longtitude ?>}, 'restaurant Name');

	//Add carpark marker function
	function addCarparkMarker(coords, carparkDetails) {
		var marker = new google.maps.Marker({
			position:coords,
			map:maps,
			icon: "images/carpark.png"
		});
	}

	//Add restaurant marker function
	function addRestaurantMarker(coords, restuarantDetails) {
		var marker = new google.maps.Marker({
			position:coords,
			map:maps,
			icon: "images/restaurant.png"
		});
	}
}

google.maps.event.addDomListener(window, 'load', CarparkMap);
</script>
