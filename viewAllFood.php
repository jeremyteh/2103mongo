<?php
include_once 'includes/header.php';

if (isset($_SESSION['FIRSTNAME'])) {
  include_once 'includes/nav_user.php';
} else {
  include_once 'includes/nav_index.php';
}
?>

<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Food Establishments</span>
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
    <div class="container-results">
      <!--set onchange of select input to reload the listing table, displaying the sorted result set-->
      <select class="button botton-red" id="sortDrop" onchange="setSort()">
        <option value="">Sort By</option>
        <option value="0">Ascending</option>
        <option value="1">Descending</option>
      </select>
      <hr class="divider" id="result-divider">
      <div class="loader"></div>
      <div id="feResults"></div>
      <div class="page-row" id="res-pageNo" style="display:none;">
        <a onclick="prevPage()" class="page-arrow"><i class="fa fa-caret-left" aria-hidden="true"></i></a>
        <span class="inline-text" id='feCurrentPageNo'>1</span>
        <span class="inline-text"> of </span>
        <span class="inline-text" id='feTotalPageNo'></span>
        <a onclick="nextPage()" class="page-arrow"><i class="fa fa-caret-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer_main.php' ?>
<script src='js\foodestablishmentJS.js'></script>
<script type="text/javascript" src="js/viewAllFood.js"></script>
