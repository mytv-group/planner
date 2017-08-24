<div class="channels-list">
    <?php
        $playlistsToPoint = isset($point->playlistToPoint) ? $point->playlistToPoint : [];
        $pointId = isset($point->id) ? $point->id : '';
    ?>
    <?php foreach ($channelTypes as $type => $name) {
        $channelPlaylists = [];
        foreach ($playlistsToPoint as $playlistToPoint) {
            if (isset($playlistToPoint->playlist)
                && intval($playlistToPoint->channel_type) === intval($type)
            ) {
                $channelPlaylists[] = $playlistToPoint->playlist;
            }
        }

        $this->render('channel', [
            'pointId' => $pointId,
            'channelName' => $name,
            'channelType' => $type,
            'playlists' => $channelPlaylists,
            'editable' => $editable,
            'postName' => $postName
        ]);
    } ?>

    <?php /*playlist template for js*/
    $this->render('playlist', [
      'pointId' => $pointId,
      'playlist' => null,
      'channelType' => null,
      'playlistGroupClass' => 'channel-playlist-item-template',
      'editable' => true,
      'postName' => $postName
    ]); ?>
</div>
