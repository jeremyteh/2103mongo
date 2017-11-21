//using availability update to retrieve json object
//updates the lots field accordingly

function updateLots() {
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var myObj = JSON.parse(this.responseText);
      for (var i = 0; i < myObj.value.length; i++) {
        //if lot is not displayed due to google api limitation reached
        //skip the lot
        if (document.getElementById("lot" + (i + 1)) === null) {
          continue;
        } else {
          document.getElementById("lot" + (i + 1)).innerHTML = myObj.value[i].Lots;
        }

      }
    }
  };
  xmlhttp.open("GET", "availabilityUpdate.php", true);
  xmlhttp.send();
}

//set to update the lots every 60 seconds
setInterval(function () {
  updateLots();
}, 60000);

function initialLoad(){
    if (cpArray.length > 0 && cpArray.length < 24){
        var endLength = cpArray.length;
    } else if (cpArray.length >= 24){
        var endLength = 24;
    }
    for (var i = 0; i < endLength; i++){
          var lat = cpArray[i]['latitude'];
          var lng = cpArray[i]['longitude'];
          var lots = cpJson[i];
          var location = cpArray[i]['development'];
          document.getElementById("res-carpark-cont").innerHTML += "<li class='res-row-food'>"
          + "<a class='res-food-img' href=carpark.php?carparkId=" + cpArray[i]['carparkId'] + ">"
          + "<img src=http://ctjsctjs.com/" + cpArray[i]['image'] + ">"
          + "</a>"
          + "<div class='res-food'>"
          + "<a class='results-header hide-overflow' href=carpark.php?carparkId=" + cpArray[i]['carparkId'] + ">" + location + "</a>"
          + "<span class='res-food-subheader'>Lots Available</span>"
          + "<a href='carpark.php?carparkId="  + cpArray[i]['carparkId'] +  "' class='res-blocks'>"
          + "<span class='res-lots'>" + lots + "</span>"
          + "<span class='res-name res-single hide-overflow'>" + location + "</span>"
          + "</a>"
          + "<a class='res-more' href=carpark.php?carparkId=" + cpArray[i]['carparkId'] + ">View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>"
          + "</li>";
    }
}

function nextPage(){
    var currentPage = document.getElementById("carparksCurrentPage").innerHTML;
    var maxPage = document.getElementById("carparksMaxPage").innerHTML;
    var startResult = currentPage;
    if (currentPage < maxPage){
        currentPage++;
        document.getElementById("carparksCurrentPage").innerHTML = currentPage;
        var endResult = currentPage;
        listResult(startResult,endResult);
    }
    applyLotColor();
}

function prevPage(){
    var currentPage = document.getElementById("carparksCurrentPage").innerHTML;
    if (currentPage > 1){
        currentPage--;
        var endResult = currentPage;
        document.getElementById("carparksCurrentPage").innerHTML = currentPage;
        var startResult = currentPage - 1;
        listResult(startResult,endResult);
    }
    applyLotColor();
}

function listResult(x,y){
    var startIndex = x * 24;
    var endIndex = y * 24;
    var totalCarparks = document.getElementById("carparkCounts").innerHTML;
    if (endIndex > totalCarparks){
        endIndex = totalCarparks;
    }
    document.getElementById("res-carpark-cont").innerHTML = "";
    for (var i = startIndex; i < endIndex; i++){
        var lat = cpArray[i][2];
        var lng = cpArray[i][1];
        var lots = cpJson[i];
        var location = cpArray[i][4];
        document.getElementById("res-carpark-cont").innerHTML += "<li class='res-row-food'>"
        + "<a class='res-food-img' href=carpark.php?carparkId=" + cpArray[i][0] + ">"
        + "<img src=http://ctjsctjs.com/" + cpArray[i][5] + ">"
        + "</a>"
        + "<div class='res-food'>"
        + "<a class='results-header hide-overflow' href=carpark.php?carparkId=" + cpArray[i][0] + ">" + location + "</a>"
        + "<span class='res-food-subheader'>Lots Available</span>"
        + "<a href='carpark.php?carparkId="  + cpArray[i][0] +  "' class='res-blocks'>"
        + "<span class='res-lots'>" + lots + "</span>"
        + "<span class='res-name res-single hide-overflow'>" + location + "</span>"
        + "</a>"
        + "<a class='res-more' href=carpark.php?carparkId=" + cpArray[i][0] + ">View more <i class='fa fa-caret-right' aria-hidden='true'></i></a></div>"
        + "</li>";
    }

}

$( document ).ready(function() {
  $('#res-carpark-cont').show();
  $('.loader').hide();
});
