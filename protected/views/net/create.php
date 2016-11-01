<?php
/* @var $this NetController */
/* @var $model Net */

$this->breadcrumbs=array(
	'Nets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Net', 'url'=>array('index'))
);
?>

<h1>Create Net</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>