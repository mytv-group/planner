<?php foreach ($playlists as $playlist): ?>
    <div class="btn-group channel-playlist-item" role="group" data-playlist-id="<?= $playlist->id; ?>">
        <button type="button" class="playlist-link btn btn-default">
            <a href="/playlists/<?= $playlist->id; ?>"><?= $playlist->name; ?></a>
        </button>
        <input class="channel-playlist"
           name="Point[channels][<?= $channelType; ?>][playlists][]" value="<?= $playlist->id; ?>" />
        <?php if ($editable): ?>
          <button type="button" class="remove-playlist btn btn-danger"
              data-playlist-id="<?= $playlist->id; ?>">x</button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
