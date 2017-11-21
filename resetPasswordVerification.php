<?php include_once 'includes/header.php' ?>
<?php include_once 'includes/nav_index.php' ?>
<?php include_once 'protected/resetPasswordVerification_validation.php' ?>


<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Reset Password</span>
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
    <div class="container-resetPw">
      <img src="images/reset.svg">
      <form role="form" autocomplete="off" action="protected/resetPasswordVerification_validation.php" method="POST">
        <input type="password" class="form slider-form" placeholder="New Password" name="password">
        <span class="modal-error login-err">
          <input type="hidden" name="email" value="<?php echo (isset($_GET['email']) && !empty($_GET['email']) ? $_GET['email']:''); ?>">
          <?php
          if(isset($_GET['password'])){
            if ($_GET['password']=='empty'){
              echo "&#xf06a; Please enter your password";
            } else if ($_GET['password']=='alphaNum'){
              echo "&#xf06a; Please ensure your password is at least 8 characters and alpha-numeric.";
            }
          }
          ?>
        </span>
        <input type="password" class="form slider-form" placeholder="Confirm Password" name="passwordConfirm">
        <span class="modal-error login-err">
          <?php
          if(isset($_GET['cfmPassword'])){
            if ($_GET['cfmPassword']=='empty'){
              echo "&#xf06a; Please re-enter your password";
            } else if($_GET['cfmPassword']=='diff'){
              echo "&#xf06a; Your password does not match";
            }
          }
          ?>
        </span>
        <button class="button button-red" name="submit" type="submit" id="slider-btn">Submit</button>
      </form>
    </div>
  </div>
</div>

<?php include_once 'includes/footer_main.php' ?>
