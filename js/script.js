
$(document).ready(function () {

  //hide main menu jumbo buttons when user is logged in
  $('#nav-hamburger').click(function(){
    $(".nav-center").slideToggle(100, "linear");
  });

  //Show Navigation upon resize to laptop scren
  $( window ).resize(function() {
    if ($(window).width() > 1024) {
      $('.nav-center').show();
    }
  });

  //status of modal
  var status = "";

  //If there is error, display modal immediately
  if (window.location.href.indexOf("login") > -1){
    status ="login";
    $(".modal").show();
    $("#modal-login").show();
  } else if (window.location.href.indexOf("reg") > -1){
    status ="register";
    $(".modal").show();
    $("#modal-register").show();
  } else if (window.location.href.indexOf("resetEmail") > -1){
    status ="login";
    $(".modal").show();
    $("#modal-forgotPw").show();
  }

  //login modal button
  $(".modal-loginBtn").click(function(){
    status = "login";
    $(".modal").fadeIn(100);
    $('#modal-login')
    .stop(true, true)
    .animate({
      marginTop: "+=30px",
      opacity: "show"
    },200);
  });

  //register modal button
  $(".modal-registerBtn").click(function(){
    status = "register";
    $(".modal").fadeIn(100);
    $('#modal-register')
    .stop(true, true)
    .animate({
      marginTop: "+=30px",
      opacity:"show"
    },200);
  });

  //Modal background click to exit button
  $(".modal").click(function(){
    $(".modal").fadeOut(100);
    if (status == "login"){
      $('#modal-login')
      .stop(true, true)
      .animate({
        marginTop: "-=30px",
        opacity: "hide"
      },200);
      $('#modal-forgotPw').css({
        marginTop: "-=30px",
        display:"none",
      });
    }

    else if (status == "register"){
      $('#modal-register')
      .stop(true, true)
      .animate({
        marginTop: "-=30px",
        opacity: "hide"
      },200);
    }
  });

  //Internal modal link
  $("#modal-registerlink").click(function(){
    status = "register";
    $('#modal-login').css({
      marginTop: "-=30px",
      display:"none",
    });
    $('#modal-register').css({
      marginTop: "+=30px",
      display: "block",
    });
  });

  $("#modal-loginlink").click(function(){
    status = "login";
    $('#modal-register').css({
      marginTop: "-=30px",
      display:"none",
    });
    $('#modal-login').css({
      marginTop: "+=30px",
      display: "block",
    });
  });

  $("#modal-forgotPwlink").click(function(){
    $('#modal-login').css({
      display:"none",
    });
    $('#modal-forgotPw').css({
      marginTop: "+=30px",
      display: "block",
    });
  });

  $("#modal-forgotPwBack").click(function(){
    $('#modal-login').css({
      display:"block",
    });
    $('#modal-forgotPw').css({
      marginTop: "-=30px",
      display: "none",
    });
  });

  //Advance Search modal button
  $(".modal-advSearchBtn").click(function(){
    $(".adv-search-cont").slideToggle(200, "swing");
  });

  //Prevent background clikc exit from interfering with child div
  $(this).children(".children").toggle();
  $(".modal-container *").click(function(e) {
    e.stopPropagation();
  });
});
