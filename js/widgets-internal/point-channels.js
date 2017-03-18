$(document).ready(function(e) {
    $('.add-playlists-btn').click(function(event) {
        var channelType = $(this).data('channel-type');
        $(document).trigger('choose-playlist-dialog:show', {channelType: channelType});
    });

    $('#channels-list').on('click', '.remove-playlist', function(event) {
      event.stopPropagation();
      var $this = $(this);
      $this.parent('.channel-playlist-item').remove();
    });

    $(document).on('choose-playlist-dialog:playlist-attached', function(event, args) {
        console.log(args);
    });

});
