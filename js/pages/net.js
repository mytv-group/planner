$(document).ready(function() {
  $('option').mousedown(function(e) {
      e.preventDefault();
      $(this).prop('selected', $(this).prop('selected') ? false : true);
  });

    $('.delete-net').on('submit', function() {
        return confirm('Do you really want to delete network?');
    });
});
