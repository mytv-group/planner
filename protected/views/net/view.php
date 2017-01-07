<?php
/* @var $this NetController */
/* @var $model Net */

$this->breadcrumbs=array(
	'Nets'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Net', 'url'=>array('index')),
	array('label'=>'Create Net', 'url'=>array('create')),
	array('label'=>'Update Net', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Net', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Net', 'url'=>array('admin')),
);
?>

<h1>View Net #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'id_user',
		'dt_created',
	),
)); ?>
