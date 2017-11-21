<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Welcome back, <?php echo $_SESSION['FIRSTNAME'] ?> </span>
    <form  role="form" autocomplete="off" action="resultsPage.php" method="POST">
      <div class="search-row">
        <input type="text" class="search-form" placeholder="Enter a food establishment or carpark" name="search">
        <button type ="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>
  </div>
</section>

    <?php
    $getTermSearches = "SELECT termSearch FROM foodsearch WHERE userId = ".$_SESSION['ID']." ORDER BY dateTimeSearch DESC";
    $result = mysqli_query($conn,  $getTermSearches) or die(mysqli_connect_error());

    $count = 0;
    $recentSearches = "";

    if (mysqli_num_rows($result) > 0) {
      echo '<section class="container-recentSearch">'
      .'<div class=" container-responsive">';
      echo "<span class='recent-search'>Recent searches: </span>";
      while(($row = mysqli_fetch_assoc($result)) and ($count != 3)) {
        if($recentSearches == "") {
          echo "<form class='recent-form' action='resultsPage.php' method='POST'><input type='hidden' name='search' class='form-control' value='".$row['termSearch']."'><button class='recentSearchesButton' type='submit'>".$row['termSearch']."</button></form>";
          $recentSearches = $row['termSearch'];
          $count++;
        }else if($recentSearches != $row['termSearch']) {
          echo "<form class='recent-form' action='resultsPage.php' method='POST'><input type='hidden' name='search' class='form-control' value='".$row['termSearch']."'><button class='recentSearchesButton' type='submit'>".$row['termSearch']."</button></form>";
          $recentSearches = $row['termSearch'];
          $count++;
        }
      }
      echo '</div></section>';
    }
    ?>
