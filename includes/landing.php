<?php
if(isset($_GET['message'])){
  if ($_GET['message']=="success"){
    echo '<div class="message">&#xf06a; Register successful! Check your email for verification</div>';
  }  else if ($_GET['message']=="resetSent"){
    echo '<div class="message">&#xf06a; A password reset link has been sent to your email. </div>';
  } else if ($_GET['message']=="resetsuccess"){
    echo '<div class="message">&#xf06a; Reset successful! Log in now to start browsing!</div>';
  }
}
?>
<section class="container-main">
  <div class="container-responsive">
    <a href="index.php">
      <img class="main-logo ease public"src="images/logo-white.svg">
    </a>
    <span class="button button-white modal-registerBtn top-btn">Register</span>
    <span class="button button-white modal-loginBtn top-btn">Log in</span>
    <p class="main-header">Bringing to you a dining and<br>parking experience like never before</p>
    <form role="form" autocomplete="off" action="resultsPage.php" method="POST">
      <div class="main-row">
        <input type="text" class="main-form" placeholder="Enter a food establishment or carpark" name="search">
        <button type ="submit" class="main-button"><i class="fa fa-search" aria-hidden="true"></i></button>
      </div>
    </form>
  </div>
</section>
<section class="container-news">
  <p>The fastest growing startup in based in Singapore!</p>
</section>
