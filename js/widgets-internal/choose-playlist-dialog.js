$(document).ready(function() {
    var channelType = null;
    var pointId = null;
    $(document).on('choose-playlist-dialog:show', function(event, args) {
        channelType = args.channelType;
        pointId = args.pointId;

        var $dialog = $('.choose-playlist-dialog[data-channel-type='+channelType+']:first');
        if ($dialog.length > 0) {
            $dialog.modal('show');
        }
    });

    var $rows = $('.choose-playlist-dialog .row-data');
    $rows.click(function() {
        $rows.removeClass('is-selected');
        $(this).addClass('is-selected');

        var $attachPlaylistBtn = $('.attach-playlist-btn[data-channel-type='+channelType+']:first');
        $attachPlaylistBtn.removeAttr('disabled');
    });

    $('.attach-playlist-btn').click(function(event) {
        var $selected = $($rows.filter('.is-selected').get(0));
        var id = $selected.data('id') || null;
        var playlistName = $selected.children('.modal-playlist-name').text().trim() || null;

        if ((channelType !== null) && (id !== null)) {
            $(document).trigger('choose-playlist-dialog:playlist-attached', {
                channelType: channelType,
                playlistName: playlistName,
                playlistId: id,
                pointId: pointId
            });
        }

        var $dialog = $('.choose-playlist-dialog[data-channel-type='+channelType+']:first');
        $dialog.modal('hide');
        $rows.removeClass('is-selected');
          var $attachPlaylistBtn = $('.attach-playlist-btn[data-channel-type='+channelType+']:first');
        $attachPlaylistBtn.attr('disabled', 'disabled');
    });
});
