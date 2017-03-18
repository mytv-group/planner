<div id="channels-list">
    <?php foreach ($channelTypes as $type => $name) {
        $channelPlaylists = [];
        foreach ($playlistToPoint as $item) {
                if (isset($item->playlist)
                  && isset($item->playlist->type)
                  && (intval($item->playlist->type) === intval($type))
                ) {
                    $channelPlaylists[] = $item->playlist;
                }
        }

        $this->render('channel', [
            'channelName' => $name,
            'channelType' => $type,
            'playlists' => $channelPlaylists,
            'editable' => $editable
        ]);
    } ?>
</div>
