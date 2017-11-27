<?php include_once 'includes/header.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>
<?php include_once 'protected/functions.php' ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLgOEetVt0oeA8HdyUmOAdW8O1e0qpB7Q"></script>
<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';

if(isset($_GET['carparkId'])) {
	$carparkID = $_GET['carparkId'];

	$filter = ['carparkId'=>$_GET['carparkId']];

	$query = new MongoDB\Driver\Query($filter);
	$selectedCarpark = $mongodbManager->executeQuery('foodfinderapp.carpark', $query)->toArray();

	$filter = ['carparkId'=>$_GET['carparkId']];

	$query = new MongoDB\Driver\Query($filter);
	$allFeedbacks = $mongodbManager->executeQuery('foodfinderapp.feedback', $query)->toArray();

	$TotalAvgRating = 0;

	foreach($allFeedbacks as $indivFeedback) {
		$TotalAvgRating += $indivFeedback->AvgRating;
	}

	if($TotalAvgRating != 0) {
    	$rating = $TotalAvgRating/count($allFeedbacks);
	}
	else{
		$rating = 0;
	} 

	$numofreview = count($allFeedbacks);
	$latitude = $selectedCarpark[0]->latitude;
	$longtitude = $selectedCarpark[0]->longitude;
}

$json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?&latlng='.$latitude.','.$longtitude.'&key='. $googleKey);
$json1 = json_decode($json);
/*

$carparkLotsJson = "http://datamall2.mytransport.sg/ltaodataservice/CarParkAvailability";
$ch = curl_init($carparkLotsJson );
$options = array(CURLOPT_HTTPHEADER=>array("AccountKey: SFHPvNC5RP+jFTzftMxxFQ==, Accept: application/json" ),);
curl_setopt_array( $ch, $options );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$carparkJsonResult = curl_exec( $ch );
$carparkJsonResult = json_decode($carparkJsonResult);
$lots = $carparkJsonResult->{'value'}[$carparkID-1]->{'Lots'};
*/
$lots = getLots($selectedCarpark[0], $datamallKey);

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
						<h><?php echo $selectedCarpark[0]->development ?></h>
					</div>
					<div class="carpark-img" style="background-image: url(http://ctjsctjs.com/<?php echo $selectedCarpark[0]->image ?>)"></div>
				</div>
				<div class="res-body">
					<span class="res-add"><?php echo $json1->{'results'}[0]->{'formatted_address'}; ?></span>
					<table class="demo-table">
						<tbody>
							<div id="tutorial-<?php echo $_GET['carparkId']; ?>">
								<?php $property=array("Accessiblity","Cleaniness","Parking Rate","Space","User Friendly"); ?>
								<?php

									$filter = ['carparkId'=>$_GET['carparkId']];

									$query = new MongoDB\Driver\Query($filter);
									$allFeedbacks = $mongodbManager->executeQuery('foodfinderapp.feedback', $query)->toArray();
									
									$TotalAccessRating = 0;
							        $TotalCleanRating = 0;
							        $TotalParkRating = 0;
							        $TotalSpaceRating = 0;
							        $TotalUserFriendlyRating = 0;

							        foreach($allFeedbacks as $indivFeedback) {
							          $TotalAccessRating += $indivFeedback->accessibility;
							          $TotalCleanRating += $indivFeedback->clean;
							          $TotalParkRating += $indivFeedback->parkRate;
							          $TotalSpaceRating += $indivFeedback->space;
							          $TotalUserFriendlyRating += $indivFeedback->userFriendly;
							        }

							        $allRatings = array();

							        if(($TotalAccessRating != 0) or ($TotalCleanRating != 0) or ($TotalParkRating != 0) or ($TotalSpaceRating = 0) or($TotalUserFriendlyRating = 0)) {

							            $AvgAccessRating = round($TotalAccessRating/count($allFeedbacks), 2);
							            array_push($allRatings, $AvgAccessRating);
							            $AvgCleanRating = round($TotalCleanRating/count($allFeedbacks), 2);
							            array_push($allRatings, $AvgCleanRating);
							            $AvgParkRating = round($TotalParkRating/count($allFeedbacks), 2);
							            array_push($allRatings, $AvgParkRating);
							            $AvgSpaceRating = round($TotalSpaceRating/count($allFeedbacks), 2);
							            array_push($allRatings, $AvgSpaceRating);
							            $AvgUserFriendlyRating = round($TotalUserFriendlyRating/count($allFeedbacks), 2);
							            array_push($allRatings, $AvgUserFriendlyRating);
							        }


										for($p = 0; $p < 5;$p++ ){
											echo '<tr><td>'.$property[$p].'</td>';
											echo '<td><input type="hidden" name="rating" id="rating" value="'.$rating.'"/>';
											echo '<ul>';

											for($i=1;$i<=5;$i++) {
												$selected = "";
												if(!empty($allRatings[$p]) && $i<=$allRatings[$p]) {
													$selected = "selected";
												}
												echo '<li class="'.$selected.'">&#9733;</li>';
											}
											echo '</ul>';
											echo '</td></tr>';
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
