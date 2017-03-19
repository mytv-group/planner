<?php
  $playlistId = isset($playlist->id) ? $playlist->id : '%playlistId%';
  $playlistName = isset($playlist->name) ? $playlist->name : '%playlistName%';
  $channelType = isset($channelType) ? $channelType : '%channelType%';
  $disabled = isset($playlist->id) ? '' : 'disabled="disabled"';
?>

<div class="btn-group channel-playlist-item <?= $playlistGroupClass; ?>"
  role="group" data-playlist-id="<?= $playlistId; ?>">
    <button type="button" class="playlist-link btn btn-default">
        <a href="/playlists/<?= $playlistId; ?>">
          <?= $playlistName; ?>
        </a>
    </button>
    <input class="channel-playlist"
       name="Point[channels][<?= $channelType; ?>][]"
       value="<?= $playlistId; ?>"
       <?= $disabled; ?>/>
    <?php if ($editable): ?>
      <button type="button" class="remove-playlist btn btn-danger">x</button>
    <?php endif; ?>
</div>
