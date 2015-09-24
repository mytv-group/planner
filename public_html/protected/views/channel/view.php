<?php
/* @var $this PointController */
/* @var $model Point */
/*
$this->menu=array(
	array('label'=>'Update Point', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Point', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Add Channel', 'url'=>array('/channel/create', 'id'=>$model->id))
);*/
?>

<h1>View Channel #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id'
	),
)); ?>
