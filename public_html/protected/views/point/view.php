<?php
/* @var $this PointController */
/* @var $model Point */

// $this->breadcrumbs=array(
// 	'Points'=>array('index'),
// 	$model->name,
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

//$channelNames = $this->ChangePointPlaylistIdsToNames($model);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		/*'id',*/
		'name',
		/*'username',*/
		/*'password',*/
		'volume',
		'sync',
		'sync_time',
		'update_time',
		/*array(
				'name'  => 'channel1',
				'value' => $model->channel1 ? CHtml::link($channelNames[1], Yii::app()->
					createUrl("playlist/view",array("id"=>$model->channel1))) : '',
				'type'  => 'raw',
		),
		array(
				'name'  => 'channel2',
				'value' => $model->channel2 ? CHtml::link($channelNames[2], Yii::app()->
					createUrl("playlist/view",array("id"=>$model->channel2))) : '',
				'type'  => 'raw',
		),
		array(
				'name'  => 'channel3',
				'value' => $model->channel3 ? CHtml::link($channelNames[3], Yii::app()->
						createUrl("schedule/update",array("id"=>$model->channel3))) : '',
				'type'  => 'raw',
		),
		array(
				'name'  => 'channel3',
				'value' => $model->channel4 ? CHtml::link($channelNames[4], Yii::app()->
						createUrl("schedule/update",array("id"=>$model->channel4))) : '',
				'type'  => 'raw',
		),*/
	),
)); ?>


<div class="PointPlaylistsInView">
	<?php 
	
		$channels = $model->channels;
		foreach ($channels as $channel)
		{
			printf("<div class='ChannelsContainer btn-toolbar' data-channelid='%s' role='toolbar' aria-label=''>", 
				$channel['id']); 
			$channelM = Channel::model()->findByPk($channel['id']);
			$pls = $channelM->playlists;
			
			printf("<div class='btn-group' role='group' aria-label=''>
				<button class='ChannelId btn btn-default' disabled>Id: %s</button>".
				"</div>", $channel['internalId']);
			
			foreach ($pls as $pl)
			{
				
				echo "<div class='btn-group' role='group' aria-label=''>";
				printf("<button type='button' class='btn btn-default'>%s</button>",
					CHtml::link($pl['name'], array('playlists/' . $pl['id'])));
				echo "</div>";
			}
						
			echo "</div>";
		}
	?>

</div>
