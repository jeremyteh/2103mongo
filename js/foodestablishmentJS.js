var pageNo = 12;
var currentPage = 1;
var totalPage;

function calculateTotalPage() {
  var totalRows = document.getElementById("feTotalResults").innerHTML;
  var totalPage = Math.ceil(totalRows/pageNo);
  document.getElementById("feTotalPageNo").innerHTML = totalPage;
}

function prevPage() {
  var endCount;
  var startCount;
  if (currentPage > 1) {
    currentPage--;
    endCount = currentPage * pageNo;
    startCount = endCount - pageNo;
  } else {
    startCount = 0;
    endCount = pageNo;
  }
  document.getElementById("feCurrentPageNo").innerHTML = currentPage;
  listResult(startCount, endCount);
}

function nextPage() {
  var totalPage = document.getElementById("feTotalPageNo").innerHTML;
  var startCount = currentPage * pageNo;
  var endCount;
  console.log(currentPage)
  console.log(totalPage)

  if (currentPage < totalPage) {
    currentPage++;
    endCount = currentPage * pageNo;
    while (endCount > feArray.length) {
      endCount--;
    }
    document.getElementById("feCurrentPageNo").innerHTML = currentPage;
    listResult(startCount, endCount);
  }
}

function pageJump() {
  var startCount = (currentPage - 1) * pageNo;
  var endCount = currentPage * pageNo;
  listResult(startCount, endCount);
}

function listResult(x, y) {
  document.getElementById("feListing").innerHTML = "<ul class='results-container'  id='feListingTable'>";
  for (var i = x; i < y; i++) {
    var spaceReplaced = feArray[i][1].split(" ").join("+");
    var symbolReplaced = spaceReplaced.split("&").join("and");
    //document.getElementById("feListingTable").innerHTML += "<div class='res-row-food'>" + feArray[i][0] + "</br>" + feArray[i][1] + "</br>" + feArray[i][2] + "</div>";
    document.getElementById("feListingTable").innerHTML += '<li class="res-row-food">'
    + '<a class="res-food-img" href="restaurant.php?foodEstablishmentId='+ feArray[i][0] +'">'
    + '<img src=http://ctjsctjs.com/'+ feArray[i][4] + ">"
    + '</a>'
    + "<div class='res-food'>"
    + '<a class="results-header hide-overflow" href="restaurant.php?foodEstablishmentId='+ feArray[i][0] +'">' + feArray[i][1] + '</a>'
    + '<span class="res-food-subheader">Address</span>'
    + '<span class="res-add-small">'  + feArray[i][2] + '</span>'
    + "<a class='res-more' href='restaurant.php?foodEstablishmentId="+ feArray[i][0] +"'>View more <i class='fa fa-caret-right' aria-hidden='true'></i></a>"
    + '</li>';
  }
  document.getElementById("feListing").innerHTML += "</ul>";
  $('.loader').hide();
  $('#feResults').show();
  $('#res-pageNo').show();
}

function initialLoad() {
  $('#feResults').load('getListing.php');
  return false;
}

function setSort() {
  var sortSelect = document.getElementById("sortDrop");
  var sortValue = sortSelect.options[sortSelect.selectedIndex].value;
  //clear the feResults div to replace with new results
  document.getElementById("feResults").innerHTML = "";
  $('#feResults').load('getListing.php?sort=' + sortValue);
  return false;
}
