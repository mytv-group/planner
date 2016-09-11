<?php
/* @var $this PlaylistsController */
/* @var $model Playlists */

$this->breadcrumbs=array(
	'Playlists'=>array('index'),
	'Manage',
);

$this->menu=array(
	/*array('label'=>'List Playlists', 'url'=>array('index')),*/
	array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#playlists-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Playlists</h1>

<!-- <p> -->
<!-- You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> -->
<!-- or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done. -->
<!-- </p> -->

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'playlists-grid',
	'dataProvider'=>$model->search(),
	/*'filter'=>$model,*/
	'columns'=>array(
		/*'id',*/
		'name',
		/*'files',*/
		'fromDatetime',
		'toDatetime',
		array(
				'name' => 'fromTime',
				'value' => function($data,$row){
					if(($data->type == 0) || ($data->type == 2)) //0 - bg, 1- adv
						return $data->fromTime;
					else
						return ''; 
				},
				'type'  => 'raw',
		),
		array(
				'name' => 'toTime',
				'value' => function($data,$row){
					if(($data->type == 0) || ($data->type == 2))
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
		/*'sun',
		'mon',
		'tue',
		'wed',
		'thu',
		'fri',
		'sat',
		'author',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
