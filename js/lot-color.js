$( document ).ready(function() {
  applyLotColor();
});

function applyLotColor (){
  $('.res-lots').each(function () {
    if ($(this).text() >= 30) {
      $(this).addClass("res-lots-green");
      $(this).parent().addClass("res-block-green");
    }else if ($(this).text() > 0) {
      $(this).addClass("res-lots-orange");
      $(this).parent().addClass("res-block-orange");
    } else {
      $(this).addClass("res-lots-red");
      $(this).parent().addClass("res-block-red");
    }
  });
}
