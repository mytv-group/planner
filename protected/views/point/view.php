<?php
/* @var $this PointController */
/* @var $model Point */

// $this->breadcrumbs=array(
//   'Points'=>array('index'),
//   $model->name,
// );

$this->menu=array(
  array('label'=>'List', 'url'=>array('index')),
  array('label'=>'Create', 'url'=>array('create')),
  array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete', 'url'=>array('delete', 'id'=>$model->id), 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Point: <?php echo $model->name; ?></h1>

<?php


function getPointSpaceInfo ($ip) {
  $spaceInfo = Yii::app()->pointInfo->getSpaceInfo($ip);
  return isset($spaceInfo['free']) ? $spaceInfo['free'] : '';
}

$this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'name',
    'volume',
    'sync',
    'sync_time',
    'update_time',
    array(
      'name' => 'free_space',
      'value' => getPointSpaceInfo($model->ip),
      'type'  => 'raw',
    ),
  ),
)); ?>


<div class="PointPlaylistsInView">
<?php
  printf("<div id='channelsList'>");
  $playlistsToPoint = $model->playlistToPoint;

  for ($ii = 1; $ii <= 3; $ii++)
  {
      printf("<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>",
          $ii);

      printf("<div class='btn-group' role='group' aria-label=''>" .
          "<button type='button' class='btn btn-default ChannelButt'>".
              "Channel %s </button>".
          "</div>", $ii, $ii);

      $channelPlaylists = [];
      foreach ($playlistsToPoint as $pl) {
          if ($pl->channel_type == $ii) {
              $channelPlaylists[] = Playlists::model()->findByPk($pl->id_playlist);;
          }
      }

      if (count($playlistsToPoint) > 0) {
          echo "<div class='btn-group' role='group' aria-label=''>";
      }
      foreach ($channelPlaylists as $pl) {
              printf("<button type='button' class='PlaylistLinks btn btn-default' ".
                      "data-plid='%s'>%s</button>",
                  $pl['id'], CHtml::link($pl['name'], array('playlists/' . $pl['id'])));
      }

      if (count($playlistsToPoint) > 0) {
          echo "</div>";
      }

      echo "</div>";
  }

  printf("</div>");
?>

</div>
