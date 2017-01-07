<?php
/* @var $this NetController */
/* @var $model Net */

$this->breadcrumbs=array(
	'Nets'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Net', 'url'=>array('index')),
	array('label'=>'Create Net', 'url'=>array('create')),
	array('label'=>'View Net', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Net', 'url'=>array('admin')),
);
?>

<h1>Update Net <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>