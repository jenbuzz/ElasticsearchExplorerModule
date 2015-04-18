$(document).ready(function() {
  var doRedirectSearch = function(value) {
    var arrUrl = $(location).attr('href').split('/');
    if (value!=-1 && value!=arrUrl[arrUrl.length-2]) {
      document.location+= value;
    }
  };

  $('#search-select-index').on('change', function() {
    doRedirectSearch($(this).val());
  });

  $('#search-select-type').on('change', function() {
    doRedirectSearch($(this).val());
  });
});
