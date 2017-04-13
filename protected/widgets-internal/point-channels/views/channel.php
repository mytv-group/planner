<div class="channel-container btn-toolbar" role="toolbar" aria-label=""
    data-channel-type="<?= $channelType; ?>"
    data-point-id="<?= $pointId; ?>"
>
  <div class="btn-group" role="group" aria-label="">
      <button type="button" class="btn btn-default channel-btn"><?= $channelName; ?> channel</button>
      <?php if ($editable): ?>
        <button type="button" class="add-playlists-btn btn btn-info"
            data-channel-type="<?= $channelType; ?>"
            data-point-id="<?= $pointId; ?>"
        >
            <span class="glyphicon glyphicon-plus"></span> Add playlist
        </button>
      <?php endif; ?>
  </div>

  <?php foreach ($playlists as $playlist) {
    $this->render('playlist', [
      'pointId' => $pointId,
      'playlist' => $playlist,
      'channelType' => $channelType,
      'playlistGroupClass' => '',
      'editable' => $editable,
      'postName' => $postName
    ]);
  } ?>
</div>
