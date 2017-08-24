$(document).ready(function(e){
  $('.delete-point').on('submit', function() {
      return confirm('Do you really want to delete point?');
  });

  $('.delete-playlist').on('submit', function() {
      return confirm('Do you really want to delete playlist?');
  });
});
