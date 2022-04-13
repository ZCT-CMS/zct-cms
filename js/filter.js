// Add "active" class to the current control button (highlight it)
var btnContainer = document.getElementById("filterBtnContainer");
var btns = btnContainer.getElementsByClassName("btn");

for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    
    if (allBtnsActive()) {
      //console.log('dorobit only pressed btn active');
      for (var j = 0; j < btns.length; j++) {
        btns[j].classList.toggle("active", false);
      }
      this.classList.toggle("active", true)
    } else {
      if (this.classList.contains("toggle-all")) {
        for (var j = 1; j < btns.length; j++) {
          btns[j].classList.toggle("active");
        }
      } else {
        this.classList.toggle("active");
      }
    }
  });
}




var articleCards = document.getElementsByClassName("article-card");
var alumniCards = document.getElementsByClassName("alumni-card");
//console.log(alumniCards);
//console.log(articleCards)

// filterSelection("all")
function filterSelection(position) {
  var tagList, i;
  tagList = document.getElementsByClassName("tag");
  if (position == "all") position = "";
  // Add the "filter-out" class to the filtered elements, and remove the "show" class from the elements that are not selected
  let isActive = allBtnsActive();
  if (allBtnsActive()) {
    for (i = 0; i < tagList.length; i++) {
      tagList[i].classList.toggle("filter-out", true);
      if (tagList[i].classList.contains(position)) {
        tagList[i].classList.toggle("filter-out", false);
        //console.log("filter out", position)
      }
    }
    //console.log('dorobit only chosen cards active')
  } else {
    for (i = 0; i < tagList.length; i++) {
      if (tagList[i].classList.contains(position)) {
        tagList[i].classList.toggle("filter-out");
        //console.log("filter out", position)
      }
    }
  }
  let cards;
  if(articleCards.length != 0){
    cards = articleCards;
  } else{
    cards = alumniCards;
  }

  for (i = 0; i < cards.length; i++) {
    tagList = cards[i].getElementsByClassName("tag");
    //console.log(tagList)
    var keep =  0
    for (j = 0; j < tagList.length; j++) {
      if (!tagList[j].classList.contains("filter-out")) {
        //console.log("keep", c);
        keep = 1;
        cards[i].classList.toggle("d-none", false);
        break;
      }
    }
    if (keep == 0) {
      cards[i].classList.toggle("d-none", true);
    }
  }
}

function allBtnsActive() {
  for (b = 0; b < btns.length; b++) {
    //console.log('all btn active?', btns[b]);
    if (!btns[b].classList.contains("active")) {
      return false
    }
  }
  return true
}