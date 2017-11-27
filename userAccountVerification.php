<?php include_once 'includes/header.php' ?>
<?php include_once 'includes/nav_index.php' ?>
<?php include_once 'protected/databaseconnection.php' ?>

<section class="container-searchbar">
	<div class="container-responsive">
		<span class="page-title">Activate Account</span>
		<form role="form" autocomplete="off" action="resultsPage.php" method="POST">
			<div class="search-row">
				<input type="text" class="search-form" placeholder="Enter a food establishment or carpark" name="search">
				<button type ="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>
				</button>
			</div>
		</form>
	</div>
</section>
<div class="container-carpark">
	<div class="container-responsive">
		<div class="container-activate">
			<img src="images/noodles.svg">

			<?php
			error_reporting(E_ERROR | E_PARSE);
			if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {

				$filter = ['email'=>$_GET['email'], 'hash'=>$_GET['hash'], 'accountActivated'=>false];

			    $query = new \MongoDB\Driver\Query($filter);
			    $rows = $mongodbManager->executeQuery('foodfinderapp.user', $query)->toArray();
			    
			    if(current($rows)) {
			    	
			    	$bulk = new MongoDB\Driver\BulkWrite();

			    	$bulk->update(['_id' => $rows[0]->_id], ['$set' => ['accountActivated'=>true]], ['multi' => false, 'upsert' => false]);

			    	try {
					    $result = $mongodbManager->executeBulkWrite('foodfinderapp.user', $bulk);
					} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
					    $result = var_dump($e->getWriteResult());
					}
					echo "<h1 align='center'>Your account has been activated! Happy parking and eating!</h1>";
					echo '<div class="error-return">Click <a class="inline-text" href="index.php">here</a> to return home.</div>';	
			    }			
				else {
					// No match -> invalid url or account has already been activated.
					//header("Location:../404.php");
					echo "<script>location.href='404.php';</script>";
					exit();
				}
			}
			else {
				// Invalid approach
				//header("Location:../404.php");
				echo "<script>location.href='404.php';</script>";
				exit();
			}
			?>
		</div>
	</div>
</div>

<?php include_once 'includes/footer_main.php' ?>
