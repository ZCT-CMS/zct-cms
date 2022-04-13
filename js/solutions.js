$('.collapse').on('show.bs.collapse', function(e) {
  var $card = $(this).closest('.group');
  var $open = $($(this).data('parent')).find('.collapse.show');
  
  var additionalOffset = 75;
  if($card.prevAll().filter($open.closest('.group')).length !== 0)
  {
    additionalOffset =  $open.height() + 70;
  }
  
  
  $('html,body').animate({
    scrollTop: $card.offset().top - additionalOffset
  }, 500);
});