<?php include_once 'includes/header.php' ?>
<?php
if(isset($_SESSION['FIRSTNAME']))
include_once 'includes/nav_user.php';
else
include_once 'includes/nav_index.php';
?>

<section class="container-searchbar">
  <div class="container-responsive">
    <span class="page-title">Privacy</span>
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
    <div class="body-copy">
      <h1>Information Collected</h1>

      We may collect any or all of the information that you give us depending on the type of transaction you enter into, including your name, address, telephone number, and email address, together with data about your use of the website. Other information that may be needed from time to time to process a request may also be collected as indicated on the website.

      <h1>Information Use</h1>

      We use the information collected primarily to process the task for which you visited the website. Data collected in the UK is held in accordance with the Data Protection Act. All reasonable precautions are taken to prevent unauthorised access to this information. This safeguard may require you to provide additional forms of identity should you wish to obtain information about your account details.

      <h1>Cookies</h1>

      Your Internet browser has the in-built facility for storing small files - "cookies" - that hold information which allows a website to recognise your account. Our website takes advantage of this facility to enhance your experience. You have the ability to prevent your computer from accepting cookies but, if you do, certain functionality on the website may be impaired.

      <h1>Disclosing Information</h1>

      We do not disclose any personal information obtained about you from this website to third parties unless you permit us to do so by ticking the relevant boxes in registration or competition forms. We may also use the information to keep in contact with you and inform you of developments associated with us. You will be given the opportunity to remove yourself from any mailing list or similar device. If at any time in the future we should wish to disclose information collected on this website to any third party, it would only be with your knowledge and consent.

      We may from time to time provide information of a general nature to third parties - for example, the number of individuals visiting our website or completing a registration form, but we will not use any information that could identify those individuals.

      In addition FoodPark may work with third parties for the purpose of delivering targeted behavioural advertising to the FoodPark website. Through the use of cookies, anonymous information about your use of our websites and other websites will be used to provide more relevant adverts about goods and services of interest to you. For more information on online behavioural advertising and about how to turn this feature off, please visit       foodpark@startup.com
.

      <h1>Changes to this Policy</h1>

      Any changes to our Privacy Policy will be placed here and will supersede this version of our policy. We will take reasonable steps to draw your attention to any changes in our policy. However, to be on the safe side, we suggest that you read this document each time you use the website to ensure that it still meets with your approval.

      <h1>Contacting Us</h1>

      If you have any questions about our Privacy Policy, or if you want to know what information we have collected about you, please email us at foodpark@startup.com. You can also correct any factual errors in that information or require us to remove your details form any list under our control.

    </div>
  </div>
</div>
<?php include_once 'includes/footer_main.php' ?>
