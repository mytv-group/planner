$(document).ready(function(e) {
    $('.add-playlists-btn').click(function(event) {
        var channelType = $(this).data('channel-type');
        var pointId = $(this).data('point-id');

        $(document).trigger('choose-playlist-dialog:show', {
            channelType: channelType,
            pointId: pointId
        });
    });

    $('.channels-list').on('click', '.remove-playlist', function(event) {
      event.stopPropagation();
      var $this = $(this);
      $this.parent('.channel-playlist-item').remove();
    });

    $(document).on('choose-playlist-dialog:playlist-attached', function(event, args) {
        var $channelContainer;

        if ((args.pointId !== null) && (args.pointId !== '')) {
            $channelContainer = $('.channel-container'
               + '[data-channel-type='+args.channelType+']'
               + '[data-point-id='+args.pointId+']'
               + ':first'
            );
        } else {
            $channelContainer = $('.channel-container[data-channel-type='+args.channelType+']:first');
        }
        var $pl = $channelContainer.find('.channel-playlist-item[data-playlist-id='+args.playlistId+']');

        if ($pl.length > 0) {
            return false;
        }

        var $plTpl;
        if ((args.pointId !== null) && (args.pointId !== '')) {
           $plTpl = $('.channel-playlist-item-template'
            + '[data-point-id='+args.pointId+']'
            + ':first').clone();
        } else {
            $plTpl = $('.channel-playlist-item-template:first').clone();
        }
        $plTpl.removeClass('channel-playlist-item-template');
        $plTpl.find('input').removeAttr('disabled');

        var plTplHtml = $plTpl.get(0).outerHTML.replace(/%playlistId%/g, args.playlistId);
        plTplHtml = plTplHtml.replace(/%playlistName%/g, args.playlistName);
        plTplHtml = plTplHtml.replace(/%channelType%/g, args.channelType);
        $channelContainer.append(plTplHtml);
    });

});
