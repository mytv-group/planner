<?php $buildPlaylistModalRow = function ($rowClass, $attr) { ?>
  <div class="row <?= $rowClass; ?>" data-id="<?= $attr['id']; ?>">
    <div class="col-sm-1">
      <?= $attr['counter']; ?>
    </div>
    <div class="col-sm-3 modal-playlist-name">
      <?= $attr['name']; ?>
    </div>
    <div class="col-sm-2">
      <?= $attr['fromDatetime']; ?>
    </div>
    <div class="col-sm-2">
      <?= $attr['toDatetime']; ?>
    </div>
    <? if($attr['type'] == 0): ?>
        <div class="col-sm-1">
          <?= $attr['fromTime']; ?>
        </div>
        <div class="col-sm-1">
          <?= $attr['toTime']; ?>
        </div>
    <? elseif($attr['type'] == 1): ?>
        <div class="col-sm-2">
          <?= $attr['every']; ?>
        </div>
    <? endif; ?>
    <div class="col-sm-2">
      <?= $attr['weekDays']; ?>
    </div>
  </div>
<? } ?>

<?php
$allWeekDays = [
  'sun'=> 'Sun ',
  'mon'=> 'Mon ',
  'tue'=> 'Tue ',
  'wed'=> 'Wed ',
  'thu'=> 'Thu ',
  'fri'=> 'Fri ',
  'sat'=> 'Sat '
];
$getWeekDays = function ($pl) use ($allWeekDays) {
    $res = '';
    foreach ($allWeekDays as $key => $value) {
        if($pl[$key]) { $res .= $value . ' '; }
    }
    return $res;
} ?>

<div class="choose-playlist-dialog modal fade" tabindex="-1" role="dialog"
    data-channel-type="<?= $channelType; ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= $channelName; ?> playlists</h4>
      </div>
      <div class="modal-body">
          <?php
             $attributeLabels = Playlists::model()->attributeLabels();
             $buildPlaylistModalRow('row-header', array_merge(
               $attributeLabels,
               ['id' => '', 'counter' => '#', 'type' => $channelType]));
             $counter = 1;

             foreach ($playlists as $playlist) {
                 $weekDays = $getWeekDays($playlist);
                 $buildPlaylistModalRow('row-data', [
                     'id' => $playlist->id,
                     'counter' => $counter,
                     'name' => $playlist->name,
                     'type' => $playlist->type,
                     'fromDatetime' => $playlist->fromDatetime,
                     'toDatetime' => $playlist->toDatetime,
                     'fromTime' => $playlist->fromTime,
                     'toTime' => $playlist->toTime,
                     'every' => $playlist->every,
                     'weekDays' => $weekDays
                 ]);
                 $counter++;
             }
          ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="attach-playlist-btn btn btn-primary"
          disabled="disabled" data-channel-type="<?= $channelType; ?>">Attach</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
