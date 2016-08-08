<?php
$this->menu=array(
	array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#point-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Points</h1>

<?php echo CHtml::link('Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'point-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
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
			'class'=>'CButtonColumn',
		),
	),
)); ?>
