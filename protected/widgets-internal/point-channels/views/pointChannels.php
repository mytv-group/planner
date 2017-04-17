<div class="channels-list">
    <?php
        $pointPlaylists = isset($point->playlists) ? $point->playlists : [];
        $pointId = isset($point->id) ? $point->id : '';
    ?>
    <?php foreach ($channelTypes as $type => $name) {
        $channelPlaylists = [];
        foreach ($pointPlaylists as $playlist) {
            if (intval($playlist->type) === intval($type)) {
                $channelPlaylists[] = $playlist;
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
