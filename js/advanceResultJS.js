function initialLoad(){
    var resultCount = document.getElementById("resultsCount").innerHTML;
    if (resultCount > 0 && resultCount <= 24){
        var endResult = resultCount;
    } else if (resultCount > 24) {
        var endResult = 24;
    }
    for (var i = 0; i < endResult; i++){
        document.getElementById("res-food-cont").innerHTML += "<div class='res-row-food res-advanced'>"
                      + "<div class='res-food-img'>"
                      + "<img src=http://ctjsctjs.com/" + validArray[i].image + ">"
                      + "</div>"
                      + "<div class='res-food'>"
                      + "<a class='results-header hide-overflow' href='restaurant.php?foodEstablishmentId=" + validArray[i].foodEstablishmentId + "'>" + validArray[i].name + "</a>"
                      + "<span class='res-food-subheader'>Advanced Results</span>"
                      + "<div class='res-blocks'>"
                      + "<span class='res-lots'>" + validArray[i].lotCount + "</span>"
                      + "<span class='res-name hide-overflow'>Total Available Lots</span>"
                      + "<span class='res-dist'>" + validArray[i].validCarparks + " Valid Carparks</span>"
                      + "</div></div>"
                      + "<a class='res-more' href='restaurant.php?foodEstablishmentId=" + validArray[i].foodEstablishmentId + "'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a>"
                      + "</div>";
    }
}

function listResult(x,y){
    var startIndex = x * 24;
    var endIndex = y * 24;
    var totalResults = document.getElementById("resultsCount").innerHTML;
    if (endIndex > totalResults){
        endIndex = totalResults;
    }
    document.getElementById("res-food-cont").innerHTML = "";
    for (var i = startIndex; i < endIndex; i++){
      document.getElementById("res-food-cont").innerHTML += "<div class='res-row-food res-advanced'>"
                    + "<div class='res-food-img'>"
                    + "<img src=http://ctjsctjs.com/" + validArray[i].image + ">"
                    + "</div>"
                    + "<div class='res-food'>"
                    + "<a class='results-header hide-overflow' href='restaurant.php?foodEstablishmentId=" + validArray[i].foodEstablishmentId + "'>" + validArray[i].name + "</a>"
                    + "<span class='res-food-subheader'>Advanced Results</span>"
                    + "<div class='res-blocks'>"
                    + "<span class='res-lots'>" + validArray[i].lotCount + "</span>"
                    + "<span class='res-name hide-overflow'>Total Available Lots</span>"
                    + "<span class='res-dist'>" + validArray[i].validCarparks + " Valid Carparks</span>"
                    + "</div></div>"
                    + "<a class='res-more' href='restaurant.php?foodEstablishmentId=" + validArray[i].foodEstablishmentId + "'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a>"
                    + "</div>";
    }

}

function nextPage(){
    var currentPage = document.getElementById("resultsCurrentPage").innerHTML;
    var maxPage = document.getElementById("resultsMaxPage").innerHTML;
    var startResult = currentPage;
    if (currentPage < maxPage){
        currentPage++;
        document.getElementById("resultsCurrentPage").innerHTML = currentPage;
        var endResult = currentPage;
        listResult(startResult,endResult);
    }

}

function prevPage(){
    var currentPage = document.getElementById("resultsCurrentPage").innerHTML;
    if (currentPage > 1){
        currentPage--;
        var endResult = currentPage;
        document.getElementById("resultsCurrentPage").innerHTML = currentPage;
        var startResult = currentPage - 1;
        listResult(startResult,endResult);
    }
}
