<?php include_once 'includes/header.php' ?>

<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';
?>
<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Advanced Search</span>
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
  <div class="container-responsive" id="container-narrow">
    <div class="adv-search-inner">
      <form role="form" autocomplete="off" action="advancedSearchResult.php" method="POST">
        <input type="text" class="form slider-form" placeholder="Enter a food establishment" name="search">
        <span class="modal-error login-err">
        <?php
        if(isset($_GET['message'])){
          if ($_GET['message']=='search_empty'){
            echo "&#xf06a; Please enter your search input";
          }
        }
        ?>
      </span>
        <div class="slider-wrappper">
          <div class="slidecontainer">
            <span class='res-slider-subheader' id="radius-output"></span>
            <input name="radius" type="range" min="1" max="500" value="1" class="slider" id="radius">
          </div>
          <div class="slidecontainer" id="slidecontainer2 minimum-carparks">
            <span class='res-slider-subheader' id="minCarpark-output"></span>
            <input name="minCarpark" type="range" min="1" max="10" value="1" class="slider" id="minCarpark">
          </div>
          <div class="slidecontainer" id="minimum-lots">
            <span class='res-slider-subheader' id="minLots-output"></span>
            <input name="minLots" type="range" min="1" max="100" value="1" class="slider" id="minLots">
          </div>
          <button class="button button-red" name="submit" type="submit" id="slider-btn">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once 'includes/footer_main.php' ?>
<script type="text/javascript" src="js/advancedSearch.js"></script>
