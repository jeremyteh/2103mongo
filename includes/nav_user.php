<nav class="nav" id="nav-user">
  <div class="nav-centerlinks">
    <ul class="nav-center">
      <li><a href="viewAllCarparks.php">Carpark</a></li>
      <li><a href="viewAllFood.php">Food Establishment</a></li>
      <li><a href="favourites.php">Favourites</a></li>
      <li><a href="advancedSearch.php">Advanced Search</a></li>
      <li id="nav-mobile-logout">
        <form action="protected/logout_validation.php" method="POST">
          <button class="nav-logout-link" name="submit" type="submit">Logout</button>
        </form>
      </li>
      </ul>
    </div>
    <div class="container-responsive">
      <a href="index.php"><img class="nav-logo ease"src="images/logo.svg"></a>
      <div class="nav-right">
        <i class="fa fa-bars" aria-hidden="true" id="nav-hamburger"></i>
        <form action="protected/logout_validation.php" method="POST">
          <button class="button button-red nav-logout" name="submit" type="submit">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <div class="nav-push"></div>
</header>
