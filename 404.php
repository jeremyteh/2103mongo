<?php include_once 'includes/header.php' ?>
<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';
?>

<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Oops!</span>
    <form  role="form" autocomplete="off" action="resultsPage.php" method="POST">
      <div class="search-row">
        <input type="text" class="search-form" placeholder="Enter a food establishment or carpark" name="search">
        <button type ="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>
  </div>
</section>
<div class="container-default">
  <div class="container-responsive">
    <div class="error-page">
      <img src="images/milk.svg">
      <h1>The page you are looking for is not found.</h1>
      <div class="error-return">Click <a class="inline-text" href="index.php">here</a> to return home.</div>
    </div>
  </div>
</div>
<?php include_once 'includes/footer_main.php' ?>
