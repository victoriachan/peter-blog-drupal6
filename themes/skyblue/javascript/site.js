/* Sitewide JS */

/* Top bar nav drop down */
$(document).ready(function() {
  
  $('#top-bar .expanded').hover( function(){
    $('#top-bar .expanded ul.menu').show();
  },function(){
    $('#top-bar .expanded ul.menu').hide();
  });
  
});