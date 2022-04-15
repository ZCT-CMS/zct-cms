// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("blogBar").style.width = scrolled + "%";
  if (winScroll < 100) {
    document.getElementById("progressContainer").style.visibility = "hidden";
  }
  else {
    document.getElementById("progressContainer").style.visibility = "visible";
  }
}