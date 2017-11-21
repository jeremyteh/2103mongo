<section class="modal">
  <div class="modal-container" id="modal-login">
    <form role="form" autocomplete="off" action="protected/login_validation.php" method="POST">
      <span class="modal-login-h">Welcome back!</span>
      <span class="modal-register-text">Don't have an account?</span>
      <span class="modal-link" id="modal-registerlink">Register here.</span>
      <input type="text" class="modal-form" name="email" placeholder="Email" value="<?php echo (isset($_POST['email']) ? $_POST['email']:''); ?>">
      <span class="modal-error login-err" id="login-err">
        <?php
        if(isset($_GET['loginEmail'])){
          switch ($_GET['loginEmail']){
            case "empty":
            echo "&#xf06a; Please enter your email";
            break;
            case "invalid":
            echo "&#xf06a; The account is invalid.";
            break;
            case "notActivated":
            echo "&#xf06a; The account has not yet been activated.";
            break;
          }
        }
        ?>
      </span>
      <input type="password" class="modal-form" placeholder="Password" name="password" value="<?php echo (isset($_POST['password']) ? $_POST['password']:''); ?>">
      <span class="modal-error login-err">
        <?php
        if(isset($_GET['loginPw'])){
          switch ($_GET['loginPw']){
            case "empty":
            echo "&#xf06a; Please enter your password.";
            break;
            case "invalid":
            echo "&#xf06a; The password is invalid.";
            break;
          }
        }
        ?>
      </span>
      <button type="submit" class="modal-login-cfm button-red">Login</button>
      <a href="#" class="modal-link" id="modal-forgotPwlink">Forget Password?</a>
    </form>
  </div>
  <div class="modal-container" id="modal-forgotPw" style="display:none;">
    <form role="form" autocomplete="off" action="protected/forgetpassword_validation.php" method="POST">
      <span class="modal-login-h">Forgot Password</span>
      <span class="modal-register-text">Remember your password?</span>
      <span class="modal-link" id="modal-forgotPwBack">Login here.</span>
      <input type="text" class="modal-form" name="email" placeholder="Email" value="<?php echo (isset($_POST['email']) ? $_POST['email']:''); ?>">
      <span class="modal-error login-err" id="login-err">
        <?php
        if(isset($_GET['resetEmail'])){
          switch ($_GET['resetEmail']){
            case "empty":
            echo "&#xf06a; Please enter your registered email";
            break;
            case "invalid":
            echo "&#xf06a; Please enter a valid email account";
            break;
            case "notExist":
            echo "&#xf06a; The email does not exist";
            break;
          }
        }
        ?>
      </span>
      <button type="submit" class="modal-login-cfm button-red">Reset</button>
    </form>
  </div>
  <div class="modal-container" id="modal-register">
    <form role="form" autocomplete="off" action="protected/signup_validation.php" method="POST">
      <span class="modal-login-h">Register</span>
      <span class="modal-register-text">Already have an account?</span>
      <span class="modal-link" id="modal-loginlink">Login here.</span>
      <div class="form-left">
        <input type="text" class="modal-form" placeholder="First Name" name="firstName" value="<?php echo (isset($_POST['firstName']) ? $_POST['firstName']:''); ?>">
        <span class="modal-error reg-err">
          <?php
          if(isset($_GET['regFname'])){
            switch ($_GET['regFname']){
              case "empty":
              echo "&#xf06a; Please enter your First Name.";
              break;
              case "alphaNum":
              echo "&#xf06a; Please only enter alpha numeric characters.";
              break;
            }
          }
          ?>
        </span>
        <input type="text" class="modal-form" placeholder="Last Name" name="lastName" value="<?php echo (isset($_POST['lastName']) ? $_POST['lastName']:''); ?>">
        <span class="modal-error reg-err">
        <?php
        if(isset($_GET['regLname'])){
          switch ($_GET['regLname']){
            case "empty":
            echo "&#xf06a; Please enter your Last Name.";
            break;
            case "alphaNum":
            echo "&#xf06a; Please only enter alpha numeric characters.";
            break;
          }
        }
        ?>
      </span>
      <input type="text" class="modal-form" placeholder="Email" name="email"  value="<?php echo (isset($_POST['email']) ? $_POST['email']:''); ?>">
      <span class="modal-error reg-err">
        <?php
        if(isset($_GET['regEmail'])){
          switch ($_GET['regEmail']){
            case "empty":
            echo "&#xf06a; Please enter your email.";
            break;
            case "invalid":
            echo "&#xf06a; Please enter a valid email address.";
            break;
            case "exist":
            echo "&#xf06a; This email has been registered";
            break;
          }
        }
        ?>
      </span>
    </div>
    <div class="form-right">
      <input type="password" class="modal-form" placeholder="Password" name="password" value="<?php echo (isset($_POST['password']) ? $_POST['password']:''); ?>">
      <span class="modal-error reg-err">
        <?php
        if(isset($_GET['regPw'])){
          switch ($_GET['regPw']){
            case "empty":
            echo "&#xf06a; Please enter your password.";
            break;
            case "validErr":
            echo "&#xf06a; Please ensure your password is at least 8 characters and alpha-numeric.";
            break;
          }
        }
        ?>
      </span>
      <input type="password" class="modal-form" placeholder="Re-enter Password"  name="passwordConfirm" value="<?php echo (isset($_POST['passwordConfirm']) ? $_POST['passwordConfirm']:''); ?>">
      <span class="modal-error reg-err">
        <?php
        if(isset($_GET['regPwCfm'])){
          switch ($_GET['regPwCfm']){
            case "empty":
            echo "&#xf06a; Please re-enter your password.";
            break;
            case "diff":
            echo "&#xf06a; Please ensure your password entered is correct.";
            break;
          }
        }
        ?>
      </span>
      <input type="text" class="modal-form" placeholder="Reference Code for Admin" name="refCode" value="<?php echo (isset($_POST['refCode']) ? $_POST['refCode']:''); ?>">
      <span class="modal-error reg-err"></span>
      <button type="submit" class="modal-login-cfm button-red">Register</button>
    </div>
  </form>
</div>
</section>
