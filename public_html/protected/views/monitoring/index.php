<?php
/* @var $this MonitoringController */
/* @var $model Monitoring */

// $this->menu=array(
// 	array('label'=>'List Monitoring', 'url'=>array('index')),
// 	array('label'=>'Create Monitoring', 'url'=>array('create')),
// );

// Yii::app()->clientScript->registerScript('search', "
// $('.search-button').click(function(){
// 	$('.search-form').toggle();
// 	return false;
// });
// $('.search-form form').submit(function(){
// 	$('#monitoring-grid').yiiGridView('update', {
// 		data: $(this).serialize()
// 	});
// 	return false;
// });
// ");
?>

<h1>Monitorings</h1>

<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display: none">
<?php //$this->renderPartial('_search',array(
	//'model'=>$model,
//)); ?>
</div>
<!-- search-form -->

<?php 

function isPointOnline ($ip){
	$monitoringModel = new Monitoring();
	return $monitoringModel->checkIpOnline($ip);
}

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'point-grid',
		'dataProvider'=>$pointModel,
		'columns'=>array(
				'id',
				'name',
				'ip',
				'volume',
				'sync_time',
				'update_time',
				array(
						'name' => 'sync',
						'value' => function($data,$row){
							if($data->sync)
							{
								return '<input name="syncCheckBox" type="checkbox" checked>';
							}
							else
							{
								return '<input name="syncCheckBox" type="checkbox">';
							}
						},
						'type'  => 'raw',
				),
				array(
						'name' => 'status',
						'value' => function($data, $row){
							$isOnline = isPointOnline($data->ip);
							$glyphicon = $isOnline ? 'glyphicon-globe' : 'glyphicon-eye-close';
							$btnType = $isOnline ? 'btn-success' : 'btn-danger';
							
							return '<button type="button" class="btn '.$btnType.' btn-sm" disabled>
								  <span class="glyphicon '.$glyphicon.'" aria-hidden="true"></span>
								</button>';
						},
						'type'  => 'raw',
				),
				array(
					'name' => 'screen',
					'value' => function($data, $row){
						return '<button type="button" class="btn btn-default btn-sm ShowPointScreenBut" ' .
									'data-id="'.$data->id.'" data-ip="'.$data->ip.'">
								  <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> 
								</button>';
					},
					'type'  => 'raw',
				),
				array(
					'name' => 'preview',
					'value' => function($data, $row){
						return '<form action="/preview/' . $data->id . '" type="GET" class="btn-group" target="_blank">' .
							'<button type="submit" class="btn btn-default btn-sm">' .
								'<span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>' .
							'</button>' .
						'</form>';
					},
					'type'  => 'raw',
				),
				array(
						'name' => 'ctrl',
						'value' => function($data, $row){
							$viewBut = '<form action="/point/'.$data->id.'" type="post" class="btn-group">'.
											'<button type="submit" class="btn btn-default btn-sm">'.
											  '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>'.
											'</button>'.
										'</form>';
							
							$editBut = '<form action="/point/update/' . $data->id . '" type="post" class="btn-group">' . 
											'<button type="submit" class="btn btn-default btn-sm">' . 
												'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' . 
											'</button>' . 
										'</form>';
							
							$delBut = '<form action="/point/delete/' . $data->id . '" type="post" class="btn-group">' . 
											'<button type="submit" class="btn btn-danger btn-sm">' . 
												'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' . 
											'</button>' . 
										'</form>';
							
							return $viewBut . $editBut . $delBut;
						},
						'type'  => 'raw',
				),
		),
)); 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'playlists-grid',
		'dataProvider'=>$playlistModel,
		'columns'=>array(
				'name',
				'fromDatetime',
				'toDatetime',
				array(
					'name' => 'fromTime',
					'value' => function($data,$row){
						if($data->type == 0) //0 - bg, 1- adv
							return $data->fromTime;
						else
							return '';
					},
					'type'  => 'raw',
				),
				array(
					'name' => 'toTime',
					'value' => function($data,$row){
						if($data->type == 0)
							return $data->toTime;
						else
							return '';
					},
					'type'  => 'raw',
				),
				array(
					'name' => 'every',
					'value' => function($data,$row){
						if($data->type == 1) //0 - bg, 1- adv
							return $data->every;
						else
							return '';
					},
					'type'  => 'raw',
				),
				array(
						'name' => 'ctrl',
						'value' => function($data, $row){
							$viewBut = '<form action="/playlists/'.$data->id.'" type="post" class="btn-group">'.
											'<button type="submit" class="btn btn-default btn-sm">'.
											  '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>'.
											'</button>'.
										'</form>';
							
							$editBut = '<form action="/playlists/update/' . $data->id . '" type="post" class="btn-group">' . 
										'<button type="submit" class="btn btn-default btn-sm">' . 
										'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' . 
										'</button>' . 
										'</form>';
							
							$delBut = '<form action="/playlists/delete/' . $data->id . '" type="post" class="btn-group">' . 
										'<button type="submit" class="btn btn-danger btn-sm">' . 
										'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' . 
										'</button>' . 
										'</form>';
							
							return $viewBut . $editBut . $delBut;
						},
						'type'  => 'raw',
				),
		),
));

// $this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'playlistsAdv-grid',
// 	'dataProvider'=>$playlistAdvModel,
// 	'columns'=>array(
// 			'name',
// 			'fromDatetime',
// 			'toDatetime',
// 			'every',
// 		array(
// 			'class'=>'CButtonColumn',
// 		),
// 	),
// )); 

// $this->widget('zii.widgets.grid.CGridView', array(
// 		'id'=>'playlistsBg-grid',
// 		'dataProvider'=>$playlistBgModel,
// 		'columns'=>array(
// 			'name',
// 			'fromDatetime',
// 			'toDatetime',
// 			'fromTime',
// 			'toTime',
// 			array(
// 					'class'=>'CButtonColumn',
// 			),
// 		),
// ));

?>
