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

    $query = new MongoDB\Driver\Query([]);
    $allSearches = $mongodbManager->executeQuery('foodfinderapp.foodsearch', $query)->toArray();

    $tempArray = array();
    $tempCount = 0;

    if(count($allSearches) > 0) {

      echo '<section class="container-recentSearch">'
      .'<div class=" container-responsive">';
      echo "<span class='recent-search'>Recent searches: </span>";
      for ($i = 0; $i < count($allSearches); $i++){
        if ($tempCount == 3){
          break;
        }
        if (empty($tempArray)){
          array_push($tempArray,$allSearches[$i]);
          $tempCount++; 
        } else {
          $tempFlag = 0;
          for ($x = 0; $x < count($tempArray); $x++){
            if ($allSearches[$i]->termSearch == $tempArray[$x]->termSearch){
              $tempFlag = 1;
              break;
            } else {
              continue;
            }
          }
          if ($tempFlag == 0){
            array_push($tempArray,$allSearches[$i]);
            $tempCount++;
          }
        }      
      }
    }

    foreach ($tempArray as $indivSearch){
      
      echo "<form class='recent-form' action='resultsPage.php' method='POST'><input type='hidden' name='search' class='form-control' value='".$indivSearch->termSearch."'><button class='recentSearchesButton' type='submit'>".$indivSearch->termSearch."</button></form>";
    }

    echo '</div></section>';
    /*
    $getTermSearches = $database->foodsearch->find(array('userId' => $_SESSION['ID']))->sort(array('dateTimeSearch'=>-1));
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
    }*/
    ?>
