//load the php page getListing without sort criteria
initialLoad();

//enable enter key for page jump
document.getElementById("feCurrentPageNo")
.addEventListener("keyup", function(event) {
  event.preventDefault();
  if (event.keyCode == 13) {
    document.getElementById("pageJump").click();
  }
});
