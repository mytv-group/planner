<?php
/* @var $this WidgetController */
/* @var $model Widget */

$this->menu=array(
	array('label'=>'Create', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#widget-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Widgets</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'widget-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'id',
		'name',
		'content',
		'user_id',
		'created_dt',
		'updated_dt',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
